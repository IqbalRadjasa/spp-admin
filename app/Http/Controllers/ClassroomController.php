<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\Classroom;
use App\Models\SchoolProfile;

use App\Services\ActivityLogService;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $schoolProfile = SchoolProfile::first();
        $educationLevel = $schoolProfile?->education_level;
        $hasMajor = in_array($educationLevel, ['SMA', 'SMK'], true);

        $majors = Major::select('id', 'name', 'code')->where('is_active', true)->orderBy('id', 'asc')->get();

        // Global summary metrics
        $summary = [
            'total'    => Classroom::count(),
            'active'   => Classroom::where('is_active', true)->count(),
            'inactive' => Classroom::where('is_active', false)->count(),
        ];

        $classrooms = Classroom::with('major')
            // 1. Filter by Level
            ->when($request->filled('level'), function ($query) use ($request) {
                $query->where('level', $request->level);
            })
            // 2. Filter by Major ID
            ->when($request->filled('major_id'), function ($query) use ($request) {
                $query->where('major_id', $request->major_id);
            })
            // 3. Filter by Active Status (Strict boolean check)
            ->when($request->filled('is_active'), function ($query) use ($request) {
                $query->where('is_active', $request->boolean('is_active'));
            })
            // 4. Sorting logic with fallback
            ->when($request->filled('sort'), function ($query) use ($request) {
                match ($request->sort) {
                    'oldest' => $query->oldest(),
                    'level'  => $query->orderBy('level', 'asc')->orderBy('class_number', 'asc'),
                    default  => $query->latest(),
                };
            }, function ($query) {
                $query->latest();
            })
            ->paginate(5)
            ->withQueryString();

        return view('settings.classrooms.index', compact(
            'classrooms',
            'schoolProfile',
            'majors',
            'summary',
            'hasMajor'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schoolProfile = SchoolProfile::first();
        $majors = Major::all();

        return view('settings.classrooms.create', compact('schoolProfile', 'majors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ActivityLogService $activityLog)
    {
        $schoolProfile = SchoolProfile::first();
        $educationLevel = $schoolProfile?->education_level;
        $hasMajor = in_array($educationLevel, ['SMA', 'SMK'], true);
        $usesParallelLetter = in_array($educationLevel, ['SD', 'SMP'], true);

        $classNumberRules = $usesParallelLetter
            ? ['required', 'string', 'regex:/^[A-Za-z]{1,2}$/']
            : ['required', 'integer', 'min:1'];

        $validated = $request->validate([
            'level' => ['required', 'integer', 'min:1', 'max:12'],
            'major_id' => [
                $hasMajor ? 'required' : 'nullable',
                'integer',
                Rule::exists('majors', 'id'),
            ],
            'class_number' => [
                ...$classNumberRules,
                Rule::unique('classrooms')->where(function ($query) use ($request, $hasMajor, $usesParallelLetter) {
                    $normalizedClassNumber = $usesParallelLetter
                        ? strtoupper(trim((string) $request->class_number))
                        : $request->class_number;

                    $query->where('level', $request->level)
                        ->where('class_number', $normalizedClassNumber);

                    if ($hasMajor) {
                        $query->where('major_id', $request->major_id);
                    } else {
                        $query->whereNull('major_id');
                    }

                    return $query;
                }),
            ],
            'is_active' => ['required', 'boolean'],
        ], [
            'class_number.regex' => 'Huruf rombel hanya boleh berisi 1 sampai 2 huruf (contoh: A, B, C).',
            'class_number.unique' => $usesParallelLetter
                ? 'Kombinasi Tingkat dan Huruf Rombel ini sudah ada.'
                : 'Kombinasi Tingkat, Jurusan, dan Nomor Kelas ini sudah ada.',
            'major_id.exists' => 'Jurusan yang dipilih tidak valid.',
        ]);

        $validated['major_id'] = $hasMajor ? $validated['major_id'] : null;
        $validated['class_number'] = $usesParallelLetter
            ? strtoupper(trim((string) $validated['class_number']))
            : (int) $validated['class_number'];

        try {
            DB::transaction(function () use ($validated, $activityLog) {
                $classroom = Classroom::create($validated);

                $activityLog->log(
                    'tambah kelas',
                    'classrooms',
                    $classroom->id,
                    'Kelas ditambah: ' . $classroom->display_name
                );
            });

            return redirect()
                ->route('settings.classrooms.index')
                ->with('success', 'Data berhasil ditambah!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom)
    {
        $schoolProfile = SchoolProfile::first();
        $majors = Major::all();

        return view('settings.classrooms.edit', compact('classroom', 'schoolProfile', 'majors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom, ActivityLogService $activityLog)
    {
        $validated = $request->validate([
            'level' => ['required', 'integer'],
            'major_id' => ['nullable', 'integer'],
            'class_number' => ['required', 'integer'],
            'is_active' => ['required', 'boolean']
        ]);

        $exists = Classroom::query()
            ->where('id', '!=', $classroom->id)
            ->where('level', $validated['level'])
            ->where('major_id', $validated['major_id'] ?? null)
            ->where('class_number', $validated['class_number'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['class_number' => 'Kelas dengan kombinasi ini sudah ada.'])
                ->withInput();
        }

        try {
            DB::transaction(function () use ($validated, $activityLog, $classroom) {
                $classroom->update([
                    'level' => $validated['level'],
                    'major_id' => $validated['major_id'],
                    'class_number' => $validated['class_number'],
                    'is_active' => $validated['is_active'],
                ]);

                $activityLog->log(
                    'update kelas',
                    'classrooms',
                    $classroom->id,
                    'Kelas diperbarui: ' . $classroom->display_name
                );
            });

            return redirect()
                ->route('settings.classrooms.index')
                ->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom, ActivityLogService $activityLog)
    {

        try {
            DB::transaction(function () use ($activityLog, $classroom) {
                $activityLog->log(
                    'hapus kelas',
                    'classrooms',
                    $classroom->id,
                    'Kelas dihapus: ' . $classroom->display_name
                );

                $classroom->delete();
            });

            return redirect()
                ->route('settings.classrooms.index')
                ->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function getAllClassrooms()
    {
        $classrooms = Classroom::all();
        return response()->json($classrooms);
    }

    public function byMajor(Major $major)
    {
        return response()->json(
            $major->classrooms()
                ->orderBy('level')
                ->orderBy('class_number')
                ->get()
        );
    }
}
