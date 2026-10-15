<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

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
    public function store(Request $request)
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
            if ($validated['is_active'] == 1) {
                AcademicYear::where('is_active', 1)->update(['is_active' => 0]);
            }

            AcademicYear::create([
                'name'      => $formattedYear,
                'is_active' => $validated['is_active']
            ]);

            return redirect()
                ->back()
                ->with('success', 'Data berhasil ditambahkan!');
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
    public function update(Request $request, AcademicYear $academicYear)
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
            if ($validated['is_active'] == 1) {
                AcademicYear::where('is_active', 1)->update(['is_active' => 0]);
            }

            $academicYear->update([
                'name'      => $formattedYear,
                'is_active' => $validated['is_active']
            ]);

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
    public function destroy(AcademicYear $academicYear)
    {
        $activeYear = AcademicYear::select('is_active')->where('id', $academicYear->id)->first();
        if ($activeYear->is_active) {
            return redirect()
                ->back()
                ->with('error', 'Tahun akademik sedang aktif.');
        }

        $academicYear->delete();

        try {
            return redirect()
                ->back()
                ->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function activateAcademicYear(AcademicYear $academicYear)
    {
        $anotherActiveYear = AcademicYear::query()
            ->where('is_active', true)
            ->where('id', '!=', $academicYear->id)
            ->exists();

        if ($anotherActiveYear) {
            return back()->with(
                'error',
                'Another academic year is still active.'
            );
        }

        try {

            $academicYear->update([
                'is_active' => true
            ]);

            return redirect()
                ->route('settings.academic-years.index')
                ->with(
                    'success',
                    'Academic year activated successfully!'
                );
        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Failed to activate academic year.'
            );
        }
    }

    public function getAcademicYear($id)
    {
        $academicYear = AcademicYear::find($id);

        if (!$academicYear) {
            return response()->json([
                'success' => false,
                'message' => 'Academic year not found.'
            ], 404);
        }

        [$from, $to] = array_map(
            'trim',
            explode('/', $academicYear->name)
        );

        return response()->json([
            'success' => true,
            'message' => 'Data found.',
            'data' => [
                'from' => $from,
                'to' => $to,
            ]
        ]);
    }
}
