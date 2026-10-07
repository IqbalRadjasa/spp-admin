<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Major;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Occupation;
use App\Models\SchoolSetting;
use App\Models\StudentParent;
use App\Services\ActivityLogService;

use App\Notifications\StudentLinkedNotification;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $schoolSetting = SchoolSetting::first();
        $enrollmentYears = Student::select('enrollment_year')
            ->whereNotNull('enrollment_year')
            ->distinct()
            ->orderBy('enrollment_year', 'desc')
            ->pluck('enrollment_year');

        $query = Student::with(['classroom.major'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('fullname', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('major'), function ($query) use ($request) {
                $query->whereHas(
                    'classroom.major',
                    function ($q) use ($request) {
                        $q->where(
                            'id',
                            $request->major
                        );
                    }
                );
            })
            ->when($request->filled('level'), function ($query) use ($request) {
                $query->whereHas(
                    'classroom',
                    function ($q) use ($request) {
                        $q->where(
                            'level',
                            $request->level
                        );
                    }
                );
            })
            ->when($request->filled('enrollment_year'), function ($query) use ($request) {
                $query->where('enrollment_year', $request->enrollment_year);
            });

        // SUMMARY
        $totalStudents = (clone $query)->count();
        $majorSummaries = Major::withCount(['students'])->get();


        $students = $query
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $majors = Major::all();


        return view(
            'students.index',
            compact(
                'students',
                'majors',
                'enrollmentYears',
                'majorSummaries',
                'totalStudents',
                'schoolSetting'
            )
        );
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schoolSetting = SchoolSetting::first();
        $majors = Major::select('id', 'name')->where('is_active', true)->orderBy('id', 'asc')->get();
        $classrooms = Classroom::select('id')->get();
        $occupations = Occupation::select('id', 'name')->where('is_active', true)->orderBy('code', 'asc')->get();

        return view('students.create', compact(
            'classrooms',
            'majors',
            'schoolSetting',
            'occupations'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ActivityLogService $activityLog)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:100',
            'nis' => [
                'required',
                'string',
                'max:50',
                Rule::unique('students', 'nis')->whereNull('deleted_at'), // Handles soft-deletes correctly
            ],
            'nisn' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('students', 'nisn')->whereNull('deleted_at'),
            ],
            'gender' => 'required|in:L,P',
            'place_of_birth' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'religion' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'enrollment_year' => 'nullable|numeric',
            'status' => 'required',
            'classroom_id' => 'required|exists:classrooms,id',
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'parent_id' => 'nullable|exists:users,id', // Single ID check
        ]);

        DB::beginTransaction();

        // $parent = User::find($validated['parent_id']);
        // dd($parent);
        try {
            if ($request->hasFile('avatar')) {
                $validated['avatar'] = $request->file('avatar')->store('students/avatars', 'public');
            }

            // 2. Create Student Record
            $student = Student::create([
                'fullname' => $validated['fullname'],
                'nickname' => $validated['nickname'] ?? null,
                'nis' => $validated['nis'],
                'nisn' => $validated['nisn'] ?? null,
                'gender' => $validated['gender'],
                'place_of_birth' => $validated['place_of_birth'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'religion' => $validated['religion'] ?? null,
                'phone' => !empty($validated['phone']) ? normalizePhone($validated['phone']) : null,
                'enrollment_year' => $validated['enrollment_year'] ?? date('Y'),
                'status' => $validated['status'],
                'classroom_id' => $validated['classroom_id'],
                'address' => $validated['address'] ?? null,
                'avatar' => $validated['avatar'] ?? null,
            ]);

            if (!empty($validated['parent_id'])) {
                $student->parents()->sync([
                    $validated['parent_id'] => [
                        'relationship' => $validated['relationship'] ?? 'father', // Or $request->relationship
                    ],
                ]);

                // Find parent and send notification
                $parent = User::find($validated['parent_id']);
                if ($parent) {
                    $parent->notify(new StudentLinkedNotification($student));
                }
            }

            // 4. Log Activity
            $activityLog->log(
                'created',
                'student',
                $student->id,
                'Created Student ' . $student->fullname
            );

            DB::commit();

            return redirect()
                ->route('students.index')
                ->with('success', 'Data siswa berhasil ditambahkan dan orang tua telah diberi notifikasi!');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);

            if (isset($validated['avatar'])) {
                Storage::disk('public')->delete($validated['avatar']);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data siswa: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        // Eager load necessary relationships, including parents and pivot data
        $student->load([
            'classroom.major',
            'parents' => function ($query) {
                $query->withPivot('relationship');
            },
            'parents.studentParent.occupation',
        ]);

        // Retrieve the primary linked parent (since single parent association is used)
        $parent = $student->parents->first();

        $majors = Major::all();
        $classrooms = Classroom::all();
        $schoolSetting = SchoolSetting::first();

        return view('students.show', compact(
            'student',
            'parent',
            'classrooms',
            'schoolSetting',
            'majors'
        ));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $majors = Major::all();
        $classrooms = Classroom::all();
        $schoolSetting = SchoolSetting::first();

        return view('students.edit', compact('student', 'classrooms', 'schoolSetting', 'majors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student, ActivityLogService $activityLog)
    {
        $validated = $request->validate([
            'name' => 'required',
            'nis' => 'required|unique:students,nis,' . $student->id,
            'classroom_id' => 'required',
            'parent_phone' => 'required|string|max:20',
        ]);

        try {
            $student->update([
                'name' => $validated['name'],
                'nis' => $validated['nis'],
                'classroom_id' => $validated['classroom_id'],
                'parent_phone' => normalizePhone($validated['parent_phone']),
            ]);

            $activityLog->log(
                'updated',
                'student',
                $student->id,
                'Updated Student ' .
                    $student->name
            );


            return redirect()
                ->route('students.index')
                ->with('success', 'Data updated successfully!');
        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update data!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student, ActivityLogService $activityLog): RedirectResponse
    {
        DB::transaction(function () use ($student, $activityLog) {
            $timestamp = time();
            $studentName = $student->fullname;
            $avatarPath = $student->avatar;

            // 1. Append timestamp suffix to unique columns to free up NIS/NISN
            $student->update([
                'nis' => $student->nis . '_deleted_' . $timestamp,
                'nisn' => $student->nisn ? $student->nisn . '_deleted_' . $timestamp : null,
            ]);

            // 2. Detach parent relationship from pivot table
            $student->parents()->detach();

            // 3. Delete avatar file from storage if present
            if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
                $student->update(['avatar' => null]);
            }

            // 4. Soft delete (or force delete) student record
            $student->delete();

            // 5. Log activity
            $activityLog->log(
                'deleted',
                'student',
                $student->id,
                "Deleted Student {$studentName}"
            );
        });

        return redirect()
            ->route('students.index')
            ->with('success', "Data siswa {$student->fullname} berhasil dihapus.");
    }

    public function searchParent(Request $request)
    {
        $query = $request->input('q');

        if (blank($query)) {
            return response()->json([]);
        }

        $parents = StudentParent::query()
            ->with('user:id,email')
            ->where('fullname', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('email', 'LIKE', "%{$query}%");
            })
            ->select('id', 'user_id', 'fullname', 'phone')
            ->limit(10)
            ->get()
            ->map(function ($parent) {
                return [
                    'id' => $parent->user_id,
                    'fullname' => $parent->fullname,
                    'phone' => $parent->phone,
                    'email' => $parent->user?->email ?? '-',
                ];
            });

        return response()->json($parents);
    }
}
