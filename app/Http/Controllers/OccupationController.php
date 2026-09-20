<?php

namespace App\Http\Controllers;

use App\Models\Occupation;
use Illuminate\Http\Request;

class OccupationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $summary = [
            'total'    => Occupation::count(),
            'active'   => Occupation::where('is_active', true)->count(),
            'inactive' => Occupation::where('is_active', false)->count(),
        ];

        $occupations = Occupation::select('id', 'name', 'code', 'is_active')
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

        return view('settings.occupations.index', compact('occupations', 'summary'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.occupations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:occupations,name'],
            'is_active' => ['required', 'boolean'],
        ]);

        $lastCode = Occupation::where('code', 'LIKE', 'OC-%')
            ->where('code', '!=', 'OC-99')
            ->orderByRaw('CAST(SUBSTRING(code, 4) AS UNSIGNED) DESC')
            ->value('code');

        if ($lastCode) {
            $lastNumber = (int) preg_replace('/[^0-9]/', '', $lastCode);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $newCode = 'OC-' . $nextNumber;

        try {
            Occupation::create([
                'name' => $validated['name'],
                'code' => $newCode,
                'is_active' => $validated['is_active'],
            ]);

            return redirect()
                ->route('settings.occupations.index')
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
    public function edit(Occupation $occupation)
    {
        return view('settings.occupations.edit', compact('occupation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Occupation $occupation)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ]);

        try {
            $occupation->update([
                'name' => $validated['name'],
                'is_active' => $validated['is_active'],
            ]);

            return redirect()
                ->route('settings.occupations.index')
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
    public function destroy(Occupation $occupation)
    {
        try {
            $occupation->delete();

            return redirect()
                ->route('settings.occupations.index')
                ->with('success', 'Data deleted successfully!');
        } catch (\Exception $th) {
            return redirect()
                ->back()
                ->with('error', 'Failed to detele data!');
        }
    }
}
