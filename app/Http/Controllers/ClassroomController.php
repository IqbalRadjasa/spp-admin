<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\Classroom;
use App\Models\SchoolSetting;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $schoolSetting = SchoolSetting::first();
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
            'schoolSetting',
            'majors',
            'summary'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schoolSetting = SchoolSetting::first();
        $majors = Major::all();

        return view('settings.classrooms.create', compact('schoolSetting', 'majors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'level' => ['required', 'integer', 'min:1', 'max:12'],
            'major_id' => ['required', 'integer', Rule::exists('majors', 'id')],
            'class_number' => [
                'required',
                'integer',
                'min:1',
                // Composite Unique Rule: level + major_id + class_number must be unique
                Rule::unique('classrooms')->where(function ($query) use ($request) {
                    return $query->where('level', $request->level)
                        ->where('major_id', $request->major_id);
                }),
            ],
            'is_active' => ['required', 'boolean'],
        ], [
            // Custom Indonesian error message for the composite unique check
            'class_number.unique' => 'Kombinasi Tingkat, Jurusan, dan Nomor Kelas ini sudah ada.',
            'major_id.exists' => 'Jurusan yang dipilih tidak valid.',
        ]);

        try {
            Classroom::create($validated);

            return redirect()
                ->route('settings.classrooms.index')
                ->with('success', 'Data berhasil dibuat!');
        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat data!');
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
        $schoolSetting = SchoolSetting::first();
        $majors = Major::all();

        return view('settings.classrooms.edit', compact('classroom', 'schoolSetting', 'majors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
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
            $classroom->update([
                'level' => $validated['level'],
                'major_id' => $validated['major_id'],
                'class_number' => $validated['class_number'],
                'is_active' => $validated['is_active'],
            ]);

            return redirect()
                ->route('settings.classrooms.index')
                ->with('success', 'Data telah berhasil diperbarui!');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        $classroom->delete();

        try {
            return redirect()
                ->route('settings.classrooms.index')
                ->with('success', 'Data deleted successfully!');
        } catch (\Exception $th) {
            return redirect()
                ->back()
                ->with('error', 'Failed to detele data!');
        }
    }

    public function byMajor(Major $major)
    {
        return response()->json(
            $major->classrooms()
                ->orderBy('level')
                ->orderBy('name')
                ->get()
        );
    }
}
