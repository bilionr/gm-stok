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
        return response()->json(
            Log::orderByDesc('recorded_on')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $log = Log::create([
            'recorded_on' => $request->input('recorded_on', now()->toDateString()),
        ]);

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
