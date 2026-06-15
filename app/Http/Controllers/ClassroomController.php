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
    public function index()
    {
        $query = Classroom::with('major');

        $classrooms = $query
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('settings.classrooms.index', compact('classrooms'));
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
            'level' => ['required'],
            'major_id' => ['required'],
            'name' => ['required']
        ]);

        $exists = Classroom::query()
            ->where(
                'level',
                $validated['level']
            )
            ->where(
                'major_id',
                $validated['major_id']
            )
            ->where(
                'name',
                $validated['name']
            )
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['name' => 'This classroom already exists.'])
                ->withInput();
        }

        try {
            Classroom::create([
                'level' => $validated['level'],
                'major_id' => $validated['major_id'],
                'name' => $validated['name'],
            ]);

            return redirect()
                ->route('settings.classrooms.index')
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
            'level' => ['required'],
            'major_id' => ['required'],
            'name' => ['required']
        ]);

        $exists = Classroom::query()
            ->where(
                'level',
                $validated['level']
            )
            ->where(
                'major_id',
                $validated['major_id']
            )
            ->where(
                'name',
                $validated['name']
            )
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['name' => 'This classroom already exists.'])
                ->withInput();
        }

        try {
            $classroom->update([
                'level' => $validated['level'],
                'major_id' => $validated['major_id'],
                'name' => $validated['name'],
            ]);

            return redirect()
                ->route('settings.classrooms.index')
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
}
