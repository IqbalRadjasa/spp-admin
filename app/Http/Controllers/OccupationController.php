<?php

namespace App\Http\Controllers;

use App\Models\Occupation;

use App\Services\ActivityLogService;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

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
    public function store(Request $request, ActivityLogService $activityLog)
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

            DB::transaction(function () use ($validated, $activityLog, $newCode) {
                $occupation = Occupation::create([
                    'name' => $validated['name'],
                    'code' => $newCode,
                    'is_active' => $validated['is_active'],
                ]);

                $activityLog->log(
                    'tambah pekerjaan',
                    'occupations',
                    $occupation->id,
                    'Pekerjaan ditambah: ' . $occupation->name
                );
            });

            return redirect()
                ->route('settings.occupations.index')
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
    public function update(Request $request, Occupation $occupation, ActivityLogService $activityLog)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('occupations', 'name')->ignore($occupation->id)],
            'is_active' => ['required', 'boolean'],
        ]);

        try {

            DB::transaction(function () use ($validated, $activityLog, $occupation) {
                $occupation->update([
                    'name' => $validated['name'],
                    'is_active' => $validated['is_active'],
                ]);

                $activityLog->log(
                    'update pekerjaan',
                    'occupations',
                    $occupation->id,
                    'Pekerjaan diperbarui: ' . $occupation->name
                );
            });

            return redirect()
                ->route('settings.occupations.index')
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
    public function destroy(Occupation $occupation, ActivityLogService $activityLog)
    {
        try {
            DB::transaction(function () use ($occupation, $activityLog) {
                $activityLog->log(
                    'hapus pekerjaan',
                    'occupations',
                    $occupation->id,
                    'Pekerjaan dihapus: ' . $occupation->name
                );

                $occupation->delete();
            });

            return redirect()
                ->route('settings.occupations.index')
                ->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
