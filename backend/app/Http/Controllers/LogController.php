<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;

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
        //
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
    public function destroy(string $id)
    {
        //
    }
}
