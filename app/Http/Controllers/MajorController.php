<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Major::query();

        $majors = $query
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('settings.majors.index', compact('majors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.majors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required|unique:majors,code'
        ]);

        try {
            Major::create([
                'name' => $validated['name'],
                'code' => $validated['code'],
            ]);

            return redirect()
                ->route('settings.majors.index')
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
    public function show(Major $major)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Major $major)
    {
        return view('settings.majors.edit', compact('major'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Major $major)
    {
        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required|unique:majors,code'
        ]);

        try {
            $major->update([
                'name' => $validated['name'],
                'code' => $validated['code'],
            ]);

            return redirect()
                ->route('settings.majors.index')
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
    public function destroy(Major $major)
    {
        $major->delete();

        try {
            return redirect()
                ->route('settings.majors.index')
                ->with('success', 'Data deleted successfully!');
        } catch (\Exception $th) {
            return redirect()
                ->back()
                ->with('error', 'Failed to detele data!');
        }
    }
}
