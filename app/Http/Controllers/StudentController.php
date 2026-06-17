<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Major;
use App\Models\Student;

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

        $classrooms = Classroom::query()
            ->with('major')
            ->when(
                $request->major,
                function ($query) use ($request) {
                    $query->where(
                        'major_id',
                        $request->major
                    );
                }
            )
            ->orderBy('level')
            ->orderBy('name')
            ->get();

        // dd($classrooms);

        return view(
            'students.index',
            compact('students', 'majors', 'classrooms')
        );
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classrooms = Classroom::all();

        return view('students.create', compact('classrooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'nis' => 'required|unique:students',
            'classroom_id' => 'required',
            'parent_phone' => 'required|string|max:20',
        ]);


        try {
            Student::create([
                'name' => $validated['name'],
                'nis' => $validated['nis'],
                'classroom_id' => $validated['classroom_id'],
                'parent_phone' => normalizePhone($validated['parent_phone']),
            ]);

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
        $classrooms = Classroom::all();

        return view('students.edit', compact('student', 'classrooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
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
    public function destroy(Student $student)
    {
        $student->delete();

        try {
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
