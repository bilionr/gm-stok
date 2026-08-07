<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Log $log)
    {
        $entries = $log->entries()
            ->with('barang')
            ->orderBy('location')
            ->get();

        return response()->json([
            'log' => $log,
            'entries' => $entries,
        ]);
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Log $log)
    {
        $validated = $request->validate([
            'entries' => 'required|array',
            'entries.*.id' => 'required|integer|exists:entries,id',
            'entries.*.isi' => 'nullable|integer',
            'entries.*.tapel' => 'nullable|integer',
            'entries.*.tinggi' => 'nullable|integer',
            'entries.*.sisa' => 'nullable|integer',
            'entries.*.physical_stock' => 'nullable|integer',
            'entries.*.notes' => 'nullable|string',
        ]);

        foreach ($validated['entries'] as $entryData) {
            Entry::where('id', $entryData['id'])
                ->where('log_id', $log->id) // guards against updating another log's entries
                ->update([
                    'isi' => $entryData['isi'] ?? 0,
                    'tapel' => $entryData['tapel'] ?? 0,
                    'tinggi' => $entryData['tinggi'] ?? 0,
                    'sisa' => $entryData['sisa'] ?? 0,
                    'physical_stock' => $entryData['physical_stock'] ?? 0,
                    'notes' => $entryData['notes'] ?? null,
                ]);
        }

        return response()->json(['message' => 'Entries updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
