<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $summary = [
            'total'    => Major::count(),
            'active'   => Major::where('is_active', true)->count(),
            'inactive' => Major::where('is_active', false)->count(),
        ];

        $majors = Major::select('id', 'name', 'code', 'is_active')
            ->when($request->filled('is_active'), function ($query) use ($request) {
                $query->where('is_active', $request->boolean('is_active'));
            })
            ->when($request->filled('sort'), function ($query) use ($request) {
                match ($request->sort) {
                    'oldest' => $query->oldest(),
                    'name'   => $query->orderBy('name', 'asc'),
                    default  => $query->latest(),
                };
            }, function ($query) {
                $query->latest();
            })
            ->paginate(5)
            ->withQueryString();

        return view('settings.majors.index', compact('majors', 'summary'));
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
            'name' => ['required', 'string',],
            'code' => ['required', 'unique:majors,code'],
            'is_active' => ['required', 'boolean'],
        ]);

        try {
            Major::create([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'is_active' => $validated['is_active'],
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
            'name' => ['required', 'string',],
            'code' => ['required', 'unique:majors,code'],
            'is_active' => ['required', 'boolean'],
        ]);

        try {
            $major->update([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'is_active' => $validated['is_active'],
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
