<?php

namespace App\Http\Controllers;

use App\Models\Student;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::latest()->get();

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'nis' => 'required|unique:students',
            'class' => 'required',
            'parent_phone' => 'required|string|max:20',
        ]);

        try {
            Student::create([
                'name' => $validated['name'],
                'nis' => $validated['nis'],
                'class' => $validated['class'],
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
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required',
            'nis' => 'required|unique:students,nis,' . $student->id,
            'class' => 'required',
            'parent_phone' => 'required|string|max:20',
        ]);

        try {
            $student->update([
                'name' => $validated['name'],
                'nis' => $validated['nis'],
                'class' => $validated['class'],
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
