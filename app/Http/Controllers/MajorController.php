<?php

namespace App\Http\Controllers;

use App\Models\Major;

use App\Services\ActivityLogService;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

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
    public function store(Request $request, ActivityLogService $activityLog)
    {
        $validated = $request->validate([
            'name' => ['required', 'string',],
            'code' => ['required', Rule::unique('majors', 'code')],
            'is_active' => ['required', 'boolean'],
        ]);

        try {

            DB::transaction(function () use ($validated, $activityLog) {
                $major = Major::create([
                    'name' => $validated['name'],
                    'code' => $validated['code'],
                    'is_active' => $validated['is_active'],
                ]);

                $activityLog->log(
                    'tambah jurusan',
                    'majors',
                    $major->id,
                    'Jurusan ditambah: ' . $major->name
                );
            });

            return redirect()
                ->route('settings.majors.index')
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
    public function update(Request $request, Major $major, ActivityLogService $activityLog)
    {
        $validated = $request->validate([
            'name' => ['required', 'string',],
            'code' => ['required', 'string', Rule::unique('majors', 'code')->ignore($major->id)],
            'is_active' => ['required', 'boolean'],
        ]);

        try {

            DB::transaction(function () use ($validated, $activityLog, $major) {
                $major->update([
                    'name' => $validated['name'],
                    'code' => $validated['code'],
                    'is_active' => $validated['is_active'],
                ]);

                $activityLog->log(
                    'update jurusan',
                    'majors',
                    $major->id,
                    'Jurusan diperbarui: ' . $major->name
                );
            });

            return redirect()
                ->route('settings.majors.index')
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
    public function destroy(Major $major, ActivityLogService $activityLog)
    {
        try {
            DB::transaction(function () use ($major, $activityLog) {
                $activityLog->log(
                    'hapus jurusan',
                    'majors',
                    $major->id,
                    'Jurusan dihapus: ' . $major->name
                );

                $major->delete();
            });

            return redirect()
                ->route('settings.majors.index')
                ->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
