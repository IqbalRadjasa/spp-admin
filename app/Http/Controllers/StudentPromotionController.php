<?php

namespace App\Http\Controllers;

use App\Models\Major;

use App\Services\StudentPromotionService;

use Illuminate\Http\Request;

class StudentPromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, StudentPromotionService $service)
    {
        $promotions = $service->getPromotionPreview($request?->search, $request?->major, $request?->classroom);
        $majors = Major::all();

        return view('student-promotion.index', compact('promotions', 'majors'));
    }

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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
