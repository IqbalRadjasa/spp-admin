<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $academicYears = AcademicYear::query()
            ->latest()
            ->paginate(5)
            ->withQueryString();;

        return view('settings.academic-years.index', compact('academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.academic-years.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ActivityLogService $activityLog)
    {
        $validated = $request->validate([
            'year' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (preg_match('/^\d{4}$/', $value)) {
                        $yearInt = (int) $value;
                        if ($yearInt < 2000 || $yearInt > 2100) {
                            $fail('Tahun harus berada di antara 2000 dan 2100.');
                        }
                        return;
                    }

                    if (preg_match('/^\d{4}\s\/\s\d{4}$/', $value)) {
                        [$start, $end] = explode(' / ', $value);
                        if ((int)$end !== (int)$start + 1) {
                            $fail('Tahun akhir harus tepat 1 tahun setelah tahun mulai (contoh: 2026 / 2027).');
                        }
                        return;
                    }

                    $fail('Format tahun tidak valid. Gunakan format tahun (contoh: 2026) atau rentang (contoh: 2026 / 2027).');
                },
            ],
            'is_active' => 'required|in:0,1',
        ]);

        if (preg_match('/^\d{4}$/', $validated['year'])) {
            $formattedYear = $validated['year'] . ' / ' . ($validated['year'] + 1);
        } else {
            $formattedYear = trim($validated['year']);
        }

        $exists = AcademicYear::where('name', $formattedYear)->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Tahun akademik sudah ada.');
        }

        try {

            DB::transaction(function () use ($formattedYear, $validated, $activityLog) {
                if ($validated['is_active'] == 1) {
                    AcademicYear::where('is_active', 1)->update(['is_active' => 0]);
                }

                $academicYear = AcademicYear::create([
                    'name'      => $formattedYear,
                    'is_active' => $validated['is_active']
                ]);

                $activityLog->log(
                    'tambah tahun akademik',
                    'academic_years',
                    $academicYear->id,
                    'Tahun akademik ditambah: ' . $academicYear->name
                );
            });

            return redirect()
                ->back()
                ->with('success', 'Data berhasil ditambah!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicYear $academicYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academicYear)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicYear $academicYear, ActivityLogService $activityLog)
    {
        $validated = $request->validate([
            'year' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (preg_match('/^\d{4}$/', $value)) {
                        $yearInt = (int) $value;
                        if ($yearInt < 2000 || $yearInt > 2100) {
                            $fail('Tahun harus berada di antara 2000 dan 2100.');
                        }
                        return;
                    }

                    if (preg_match('/^\d{4}\s\/\s\d{4}$/', $value)) {
                        [$start, $end] = explode(' / ', $value);
                        if ((int)$end !== (int)$start + 1) {
                            $fail('Tahun akhir harus tepat 1 tahun setelah tahun mulai (contoh: 2026 / 2027).');
                        }
                        return;
                    }

                    $fail('Format tahun tidak valid. Gunakan format tahun (contoh: 2026) atau rentang (contoh: 2026 / 2027).');
                },
            ],
            'is_active' => 'required|in:0,1',
        ]);

        if (preg_match('/^\d{4}$/', $validated['year'])) {
            $formattedYear = $validated['year'] . ' / ' . ($validated['year'] + 1);
        } else {
            $formattedYear = trim($validated['year']);
        }

        $exists = AcademicYear::where('name', $formattedYear)
            ->where('id', '!=', $academicYear->id)
            ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Tahun akademik sudah ada.');
        }

        try {
            DB::transaction(function () use ($formattedYear, $validated, $activityLog, $academicYear) {
                if ($validated['is_active'] == 1) {
                    AcademicYear::where('is_active', 1)->update(['is_active' => 0]);
                }

                $academicYear->update([
                    'name'      => $formattedYear,
                    'is_active' => $validated['is_active']
                ]);

                $activityLog->log(
                    'update tahun akademik',
                    'academic_years',
                    $academicYear->id,
                    'Tahun akademik diperbarui: ' . $academicYear->name
                );
            });

            return redirect()
                ->back()
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
    public function destroy(AcademicYear $academicYear, ActivityLogService $activityLog)
    {
        $activeYear = AcademicYear::select('is_active')->where('id', $academicYear->id)->first();
        if ($activeYear->is_active) {
            return redirect()
                ->back()
                ->with('error', 'Tahun akademik sedang aktif.');
        }

        try {
            DB::transaction(
                function () use ($academicYear, $activityLog) {
                    $activityLog->log(
                        'hapus tahun akademik',
                        'academic_years',
                        $academicYear->id,
                        'Tahun akademik dihapus: ' . $academicYear->name
                    );

                    $academicYear->delete();
                }
            );

            return redirect()
                ->back()
                ->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
