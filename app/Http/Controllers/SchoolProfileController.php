<?php

namespace App\Http\Controllers;

use App\Models\SchoolProfile;

use App\Services\ActivityLogService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SchoolProfileController extends Controller
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
        $setting = SchoolProfile::first();

        if ($setting && filled($setting->school_name) && filled($setting->education_level)) {
            return redirect()->route('dashboard');
        }

        return view('onboarding-school');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ActivityLogService $activityLog)
    {
        $validated = $request->validate([
            'school_name'     => 'required|string|max:255',
            'education_level' => 'required|in:SD,SMP,SMA,SMK',
            'phone'           => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string|max:1000',
            'logo'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $setting = SchoolProfile::first() ?? new SchoolProfile();

        // Lock education_level once already set on an existing profile
        if ($setting->exists && filled($setting->education_level)) {
            $validated['education_level'] = $setting->education_level;
        }

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('school-logos', 'public');
            $validated['logo'] = $logoPath;
        } else {
            unset($validated['logo']);
        }

        try {
            DB::transaction(function () use ($setting, $validated, $activityLog) {
                $setting->fill($validated);
                $setting->save();

                $activityLog->log(
                    'pengaturan awal profil sekolah',
                    'school_profile',
                    $setting->id,
                    'Informasi profil sekolah telah ditambahkan: ' . $setting->school_name
                );
            });

            return redirect()
                ->route('dashboard')
                ->with('success', 'Profil sekolah berhasil disimpan!');
        } catch (\Throwable $e) {
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan profil: ' . $e->getMessage());
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
    public function edit()
    {

        $setting = SchoolProfile::first();

        return view('settings.school', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ActivityLogService $activityLog)
    {
        $validated = $request->validate([
            'school_name'     => 'required|string|max:255',
            'education_level' => 'required|in:SD,SMP,SMA,SMK',
            'phone'           => 'nullable|string|max:50',
            'email'           => 'nullable|email|max:255',
            'address'         => 'nullable|string|max:1000',
            'logo'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $setting = SchoolProfile::first() ?? new SchoolProfile();

        if ($request->hasFile('logo')) {
            if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                Storage::disk('public')->delete($setting->logo);
            }

            $validated['logo'] = $request->file('logo')->store('school-logos', 'public');
        } else {
            unset($validated['logo']);
        }

        try {
            DB::transaction(function () use ($setting, $validated, $activityLog) {
                $setting->fill($validated);
                $setting->save();

                $activityLog->log(
                    'update profil sekolah',
                    'school_profile',
                    $setting->id,
                    'Informasi profil sekolah telah diperbarui: ' . $setting->school_name
                );
            });

            return back()->with('success', 'Profil sekolah berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
