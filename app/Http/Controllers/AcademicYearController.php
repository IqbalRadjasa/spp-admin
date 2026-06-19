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
            'from' => 'required|integer|min:2000|max:2100',
            'to' => 'required|integer|min:2000|max:2100|gt:from',
        ]);

        $academicYear = $validated['from'] . ' / ' . $validated['to'];

        $exists = AcademicYear::where('name', $academicYear)->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Academic year already exists.'
            ], 422);
        }

        AcademicYear::create([
            'name' => $academicYear,
        ]);

        try {
            return response()->json([
                'success' => true,
                'message' => 'Data created successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ]);
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
            'from' => 'required|integer|min:2000|max:2100',
            'to' => 'required|integer|min:2000|max:2100|gt:from',
        ]);

        $new_academicYear = $validated['from'] . ' / ' . $validated['to'];

        $exists = AcademicYear::where('name', $new_academicYear)->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Academic year already exists.'
            ], 422);
        }

        try {
            $academicYear->update([
                'name' => $new_academicYear,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data updated successfully!'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();

        try {
            return redirect()
                ->route('settings.academic-years.index')
                ->with('success', 'Data deleted successfully!');
        } catch (\Exception $th) {
            return redirect()
                ->back()
                ->with('error', 'Failed to detele data!');
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
