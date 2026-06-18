<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\SchoolSetting;

use App\Services\ActivityLogService;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with(['classroom.major']);

        if ($request->search) {

            $query->where(
                'name',
                'like',
                '%' . $request->search . '%'
            );
        }

        if ($request->major) {
            $query->whereHas(
                'classroom.major',
                function ($q) use ($request) {
                    $q->where(
                        'id',
                        $request->major
                    );
                }
            );
        }

        if ($request->classroom) {
            $query->where('classroom_id', $request->classroom);
        }

        $students = $query
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $majors = Major::all();

        return view(
            'students.index',
            compact('students', 'majors')
        );
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $majors = Major::all();
        $classrooms = Classroom::all();
        $schoolSetting = SchoolSetting::first();

        return view('students.create', compact('classrooms', 'majors', 'schoolSetting'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ActivityLogService $activityLog)
    {
        $validated = $request->validate([
            'name' => 'required',
            'nis' => 'required|unique:students',
            'classroom_id' => 'required',
            'parent_phone' => 'required|string|max:20',
        ]);

        try {
            $student = Student::create([
                'name' => $validated['name'],
                'nis' => $validated['nis'],
                'classroom_id' => $validated['classroom_id'],
                'parent_phone' => normalizePhone($validated['parent_phone']),
            ]);

            $activityLog->log(
                'created',
                'student',
                $student->id,
                'Created Student ' .
                    $student->name
            );

            return redirect()
                ->route('students.index')
                ->with('success', 'Data created successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create data!');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(Student $student, ActivityLogService $activityLog)
    {
        try {
            $student->delete();

            $activityLog->log(
                'deleted',
                'student',
                $student->id,
                'Deleted Student ' .
                    $student->name
            );

            return redirect()
                ->route('students.index')
                ->with('success', 'Data deleted successfully!');
        } catch (\Exception $th) {
            return redirect()
                ->back()
                ->with('error', 'Failed to detele data!');
        }
    }
}
