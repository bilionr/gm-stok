<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Entry;
use App\Models\BarangLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logs = Log::withCount([
            'entries',
            'entries as discrepancies_count' => function ($query) {
                $query->where('difference', '!=', 0);
            },
        ])
        ->orderByDesc('recorded_on')
        ->get();

        return response()->json($logs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recorded_on' => 'nullable|date',
        ]);

        $recordedOn = $validated['recorded_on'] ?? now()->toDateString();

        $log = DB::transaction(function () use ($recordedOn) {
            $log = Log::create(['recorded_on' => $recordedOn]);

            // Seed one entry per barang+location combo currently on record
            $barangLocations = BarangLocation::select('barang_id', 'location')->get();

            $rows = $barangLocations->map(fn ($bl) => [
                'log_id'         => $log->id,
                'barang_id'      => $bl->barang_id,
                'location'       => $bl->location,
                'isi'            => 0,
                'tapel'          => 0,
                'tinggi'         => 0,
                'sisa'           => 0,
                'physical_stock' => 0,
                'omega_stock'    => 0,
                'difference'     => 0,
                'notes'          => null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ])->toArray();

            if (!empty($rows)) {
                Entry::insert($rows); // bulk insert, avoids N queries
            }

            return $log;
        });

        return response()->json($log, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Log $log)
    {
        $entries = $log->entries()->with('barang')->orderBy('location')->get();

        return view('logs.show', compact('log', 'entries'));
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
    public function destroy(Log $log): JsonResponse
    {
        // Deletes associated entries automatically if cascade on delete is set in DB migration,
        // or delete manually if needed: $log->entries()->delete();
        $log->delete();

        return response()->json([
            'message' => 'Log deleted successfully',
        ], 200); // Or 204 No Content
    }
}
