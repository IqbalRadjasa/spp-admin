<?php

namespace App\Http\Controllers;

use App\Models\SchoolSetting;

use Illuminate\Http\Request;

class SchoolSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit()
    {

        $setting = SchoolSetting::first();
        // dd($setting->education_level == 'SMK');

        return view('settings.school', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated =
            $request->validate([
                'school_name' => 'required',
                'education_level' => 'required',
                'phone' => 'nullable',
                'email' => 'nullable|email',
                'address' => 'nullable',
                'academic_year' => 'nullable',
                'logo' => 'nullable|image'
            ]);

        $setting = SchoolSetting::first();

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store(
                'school-logos',
                'public'
            );
        }

        $setting->update($validated);

        return back()->with(
            'success',
            'School settings updated.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
