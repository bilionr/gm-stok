<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockEntriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // =========================================================================
        // PART 1: Seed Unique Items (`items` table)
        // =========================================================================
        $itemsData = [
            ['barang' => 'DLSIP50',    'omega' => 61],
            ['barang' => 'DLPG50',     'omega' => 45],
            ['barang' => 'TLM25',      'omega' => 0],
            ['barang' => 'DLBOLA50',   'omega' => 84],
            ['barang' => 'TC25',       'omega' => 0],
            ['barang' => 'GLP50',      'omega' => 0],
            ['barang' => 'KJRS25',     'omega' => 0],
            ['barang' => 'KJGR25',     'omega' => 0],
            ['barang' => 'TPYG25',     'omega' => 0],
            ['barang' => 'TTULIP25',   'omega' => 0],
            ['barang' => 'KJRS1/2',    'omega' => 0],
            ['barang' => 'KJGB25',     'omega' => 0],
            ['barang' => 'MIELOS',     'omega' => 0],
            ['barang' => 'TS05',       'omega' => 0],
            ['barang' => 'TB1/2',      'omega' => 0],
            ['barang' => 'TLM1',       'omega' => 0],
            ['barang' => 'TSEG25',     'omega' => 0],
            ['barang' => 'DLSINTA50',  'omega' => 55],
            ['barang' => 'TC1',        'omega' => 0],
            ['barang' => 'MINFORTPLW', 'omega' => 0],
            ['barang' => 'TS1',        'omega' => 0],
            ['barang' => 'MINTW1',     'omega' => 0],
            ['barang' => 'B5P',        'omega' => 108],
            ['barang' => 'BOB25P',     'omega' => 0],
            ['barang' => 'B10P',       'omega' => 0],
            ['barang' => 'RG',         'omega' => 0],
            ['barang' => 'G5',         'omega' => 20],
            ['barang' => 'MTGAMD15',   'omega' => 0],
            ['barang' => 'HW',         'omega' => 0],
            ['barang' => 'MTGAMD5',    'omega' => 0],
            ['barang' => 'KRN',        'omega' => 0],
            ['barang' => 'KJGL1/2',    'omega' => 0],
            ['barang' => 'GRMD',       'omega' => 0],
            ['barang' => 'MINCUR',     'omega' => 0],
        ];

        foreach ($itemsData as $item) {
            DB::table('items')->updateOrInsert(
                ['barang' => $item['barang']],
                [
                    'omega'      => $item['omega'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Map item codes (barang) to their generated IDs from DB
        $itemMap = DB::table('items')->pluck('id', 'barang');


        // =========================================================================
        // PART 2: Seed Stock Entries (`stock_entries` table)
        // =========================================================================
        $stockEntriesRaw = [
            ['barang' => 'DLSIP50',    'lokasi' => 11,  'isi' => 1,  'tapel' => 7,  'tinggi' => 8,   'sisa' => 0,  'cttn' => ''],
            ['barang' => 'DLPG50',     'lokasi' => 11,  'isi' => 1,  'tapel' => 5,  'tinggi' => 7,   'sisa' => 4,  'cttn' => ''],
            ['barang' => 'TLM25',      'lokasi' => 11,  'isi' => 1,  'tapel' => 5,  'tinggi' => 8,   'sisa' => 0,  'cttn' => ''],
            ['barang' => 'DLBOLA50',   'lokasi' => 11,  'isi' => 1,  'tapel' => 5,  'tinggi' => 13,  'sisa' => 0,  'cttn' => ''],
            ['barang' => 'TC25',       'lokasi' => 11,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 9,  'cttn' => ''],
            ['barang' => 'GLP50',      'lokasi' => 12,  'isi' => 1,  'tapel' => 7,  'tinggi' => 7,   'sisa' => 6,  'cttn' => ''],
            ['barang' => 'KJRS25',     'lokasi' => 13,  'isi' => 1,  'tapel' => 7,  'tinggi' => 9,   'sisa' => 3,  'cttn' => ''],
            ['barang' => 'KJGR25',     'lokasi' => 13,  'isi' => 1,  'tapel' => 11, 'tinggi' => 7,   'sisa' => 10, 'cttn' => ''],
            ['barang' => 'TPYG25',     'lokasi' => 21,  'isi' => 1,  'tapel' => 5,  'tinggi' => 4,   'sisa' => 2,  'cttn' => ''],
            ['barang' => 'TTULIP25',   'lokasi' => 21,  'isi' => 1,  'tapel' => 7,  'tinggi' => 8,   'sisa' => 5,  'cttn' => ''],
            ['barang' => 'TTULIP25',   'lokasi' => 22,  'isi' => 1,  'tapel' => 5,  'tinggi' => 20,  'sisa' => 0,  'cttn' => ''],
            ['barang' => 'KJRS25',     'lokasi' => 22,  'isi' => 1,  'tapel' => 5,  'tinggi' => 10,  'sisa' => 1,  'cttn' => ''],
            ['barang' => 'KJRS1/2',    'lokasi' => 22,  'isi' => 1,  'tapel' => 5,  'tinggi' => 0,   'sisa' => 0,  'cttn' => ''],
            ['barang' => 'KJRS1/2',    'lokasi' => 31,  'isi' => 1,  'tapel' => 5,  'tinggi' => 2,   'sisa' => 4,  'cttn' => ''],
            ['barang' => 'KJGB25',     'lokasi' => 31,  'isi' => 1,  'tapel' => 5,  'tinggi' => 10,  'sisa' => 0,  'cttn' => ''],
            ['barang' => 'MIELOS',     'lokasi' => 33,  'isi' => 1,  'tapel' => 5,  'tinggi' => 4,   'sisa' => 3,  'cttn' => ''],
            ['barang' => 'TS05',       'lokasi' => 33,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 40, 'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 33,  'isi' => 1,  'tapel' => 10, 'tinggi' => 10,  'sisa' => 5,  'cttn' => ''],
            ['barang' => 'TLM1',       'lokasi' => 33,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 6,  'cttn' => ''],
            ['barang' => 'TSEG25',     'lokasi' => 41,  'isi' => 1,  'tapel' => 7,  'tinggi' => 7,   'sisa' => 4,  'cttn' => ''],
            ['barang' => 'DLSINTA50',  'lokasi' => 41,  'isi' => 1,  'tapel' => 5,  'tinggi' => 9,   'sisa' => 2,  'cttn' => ''],
            ['barang' => 'TC1',        'lokasi' => 42,  'isi' => 1,  'tapel' => 4,  'tinggi' => 5,   'sisa' => 2,  'cttn' => ''],
            ['barang' => 'TS05',       'lokasi' => 42,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 1,  'cttn' => ''],
            ['barang' => 'MINFORTPLW', 'lokasi' => 51,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 14, 'cttn' => ''],
            ['barang' => 'TS1',        'lokasi' => 51,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 21, 'cttn' => ''],
            ['barang' => 'DLBOLA50',   'lokasi' => 52,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 6,  'cttn' => ''],
            ['barang' => 'GLP50',      'lokasi' => 52,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 6,  'cttn' => ''],
            ['barang' => 'DLSINTA50',  'lokasi' => 52,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 8,  'cttn' => ''],
            ['barang' => 'DLPG50',     'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 6,  'cttn' => ''],
            ['barang' => 'MINTW1',     'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 10, 'cttn' => ''],
            ['barang' => 'DLBOLA50',   'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 13, 'cttn' => ''],
            ['barang' => 'B5P',        'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 68, 'cttn' => ''],
            ['barang' => 'DLSIP50',    'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 7,  'cttn' => ''],
            ['barang' => 'B5P',        'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 39, 'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 0,  'cttn' => ''],
            ['barang' => 'TLM1',       'lokasi' => 72,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 14, 'cttn' => ''],
            ['barang' => 'KJRS1/2',    'lokasi' => 72,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 4,  'cttn' => ''],
            ['barang' => 'BOB25P',     'lokasi' => 81,  'isi' => 1,  'tapel' => 5,  'tinggi' => 4,   'sisa' => 0,  'cttn' => ''],
            ['barang' => 'B10P',       'lokasi' => 81,  'isi' => 1,  'tapel' => 7,  'tinggi' => 7,   'sisa' => 5,  'cttn' => ''],
            ['barang' => 'RG',         'lokasi' => 81,  'isi' => 1,  'tapel' => 8,  'tinggi' => 5,   'sisa' => 4,  'cttn' => ''],
            ['barang' => 'RG',         'lokasi' => 81,  'isi' => 50, 'tapel' => 0,  'tinggi' => 0,   'sisa' => 3,  'cttn' => ''],
            ['barang' => 'MIELOS',     'lokasi' => 81,  'isi' => 1,  'tapel' => 4,  'tinggi' => 7,   'sisa' => 2,  'cttn' => ''],
            ['barang' => 'MIELOS',     'lokasi' => 82,  'isi' => 1,  'tapel' => 4,  'tinggi' => 7,   'sisa' => 0,  'cttn' => ''],
            ['barang' => 'G5',         'lokasi' => 82,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 9,  'cttn' => ''],
            ['barang' => 'G5',         'lokasi' => 82,  'isi' => 2,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 5,  'cttn' => ''],
            ['barang' => 'RG',         'lokasi' => 84,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 4,  'cttn' => ''],
            ['barang' => 'MTGAMD15',   'lokasi' => 110, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 1,  'cttn' => ''],
            ['barang' => 'TS1',        'lokasi' => 110, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 7,  'cttn' => ''],
            ['barang' => 'HW',         'lokasi' => 110, 'isi' => 1,  'tapel' => 5,  'tinggi' => 4,   'sisa' => 3,  'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 110, 'isi' => 1,  'tapel' => 10, 'tinggi' => 10,  'sisa' => 0,  'cttn' => ''],
            ['barang' => 'MTGAMD5',    'lokasi' => 120, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 15, 'cttn' => ''],
            ['barang' => 'TLM1',       'lokasi' => 120, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 9,  'cttn' => ''],
            ['barang' => 'KRN',        'lokasi' => 120, 'isi' => 10, 'tapel' => 5,  'tinggi' => 5,   'sisa' => 4,  'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 130, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 3,  'cttn' => ''],
            ['barang' => 'KJGL1/2',    'lokasi' => 130, 'isi' => 1,  'tapel' => 5,  'tinggi' => 0,   'sisa' => 3,  'cttn' => ''],
            ['barang' => 'KJGL1/2',    'lokasi' => 130, 'isi' => 1,  'tapel' => 5,  'tinggi' => 30,  'sisa' => 0,  'cttn' => ''],
            ['barang' => 'KJGL1/2',    'lokasi' => 130, 'isi' => 1,  'tapel' => 5,  'tinggi' => 19,  'sisa' => 1,  'cttn' => ''],
            ['barang' => 'KJGL1/2',    'lokasi' => 130, 'isi' => 1,  'tapel' => 5,  'tinggi' => 0,   'sisa' => 0,  'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 140, 'isi' => 1,  'tapel' => 6,  'tinggi' => 3,   'sisa' => 0,  'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 140, 'isi' => 1,  'tapel' => 6,  'tinggi' => 3,   'sisa' => 2,  'cttn' => ''],
            ['barang' => 'GRMD',       'lokasi' => 140, 'isi' => 1,  'tapel' => 10, 'tinggi' => 20,  'sisa' => 0,  'cttn' => ''],
            ['barang' => 'GRMD',       'lokasi' => 140, 'isi' => 1,  'tapel' => 10, 'tinggi' => 20,  'sisa' => 0,  'cttn' => ''],
            ['barang' => 'GRMD',       'lokasi' => 140, 'isi' => 1,  'tapel' => 7,  'tinggi' => 11,  'sisa' => 6,  'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 150, 'isi' => 4,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 13, 'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 150, 'isi' => 10, 'tapel' => 0,  'tinggi' => 0,   'sisa' => 3,  'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 150, 'isi' => 15, 'tapel' => 0,  'tinggi' => 0,   'sisa' => 11, 'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 150, 'isi' => 20, 'tapel' => 0,  'tinggi' => 0,   'sisa' => 12, 'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 150, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 0,  'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 160, 'isi' => 0,  'tapel' => 0,  'tinggi' => 735, 'sisa' => 0,  'cttn' => '4053'],
        ];

        $stockEntries = [];

        foreach ($stockEntriesRaw as $entry) {
            $stockEntries[] = [
                'item_id'    => $itemMap[$entry['barang']],
                'lokasi'     => $entry['lokasi'],
                'isi'        => $entry['isi'],
                'tapel'      => $entry['tapel'],
                'tinggi'     => $entry['tinggi'],
                'sisa'       => $entry['sisa'],
                'cttn'       => $entry['cttn'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('stock_entries')->insert($stockEntries);

        $stockEntriesRawNew = [
            ['barang' => 'DLSIP50',    'lokasi' => 11,  'isi' => 1,  'tapel' => 7,  'tinggi' => 10, 'sisa' => 2,  'date' => '2026-08-01', 'cttn' => ''],
            ['barang' => 'DLPG50',     'lokasi' => 11,  'isi' => 1,  'tapel' => 5,  'tinggi' => 6,  'sisa' => 0,  'date' => '2026-08-01', 'cttn' => ''],
            ['barang' => 'TLM25',      'lokasi' => 11,  'isi' => 1,  'tapel' => 5,  'tinggi' => 9,  'sisa' => 3,  'date' => '2026-08-01', 'cttn' => ''],
            ['barang' => 'DLBOLA50',   'lokasi' => 11,  'isi' => 1,  'tapel' => 5,  'tinggi' => 11, 'sisa' => 1,  'date' => '2026-08-01', 'cttn' => ''],
            ['barang' => 'TC25',       'lokasi' => 11,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 5,  'date' => '2026-08-02', 'cttn' => ''],
            ['barang' => 'GLP50',      'lokasi' => 12,  'isi' => 1,  'tapel' => 7,  'tinggi' => 8,  'sisa' => 0,  'date' => '2026-08-02', 'cttn' => ''],
            ['barang' => 'KJRS25',     'lokasi' => 13,  'isi' => 1,  'tapel' => 7,  'tinggi' => 12, 'sisa' => 5,  'date' => '2026-08-02', 'cttn' => ''],
            ['barang' => 'KJGR25',     'lokasi' => 13,  'isi' => 1,  'tapel' => 11, 'tinggi' => 5,  'sisa' => 2,  'date' => '2026-08-02', 'cttn' => ''],
            ['barang' => 'TPYG25',     'lokasi' => 21,  'isi' => 1,  'tapel' => 5,  'tinggi' => 6,  'sisa' => 0,  'date' => '2026-08-03', 'cttn' => ''],
            ['barang' => 'TTULIP25',   'lokasi' => 21,  'isi' => 1,  'tapel' => 7,  'tinggi' => 10, 'sisa' => 1,  'date' => '2026-08-03', 'cttn' => ''],
            ['barang' => 'TTULIP25',   'lokasi' => 22,  'isi' => 1,  'tapel' => 5,  'tinggi' => 15, 'sisa' => 3,  'date' => '2026-08-03', 'cttn' => ''],
            ['barang' => 'KJRS25',     'lokasi' => 22,  'isi' => 1,  'tapel' => 5,  'tinggi' => 8,  'sisa' => 0,  'date' => '2026-08-03', 'cttn' => ''],
            ['barang' => 'KJRS1/2',    'lokasi' => 22,  'isi' => 1,  'tapel' => 5,  'tinggi' => 3,  'sisa' => 2,  'date' => '2026-08-03', 'cttn' => ''],
            ['barang' => 'KJRS1/2',    'lokasi' => 31,  'isi' => 1,  'tapel' => 5,  'tinggi' => 0,  'sisa' => 0,  'date' => '2026-08-04', 'cttn' => ''],
            ['barang' => 'KJGB25',     'lokasi' => 31,  'isi' => 1,  'tapel' => 5,  'tinggi' => 12, 'sisa' => 4,  'date' => '2026-08-04', 'cttn' => ''],
            ['barang' => 'MIELOS',     'lokasi' => 33,  'isi' => 1,  'tapel' => 5,  'tinggi' => 6,  'sisa' => 0,  'date' => '2026-08-04', 'cttn' => ''],
            ['barang' => 'TS05',       'lokasi' => 33,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 25, 'date' => '2026-08-04', 'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 33,  'isi' => 1,  'tapel' => 10, 'tinggi' => 8,  'sisa' => 2,  'date' => '2026-08-04', 'cttn' => ''],
            ['barang' => 'TLM1',       'lokasi' => 33,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 12, 'date' => '2026-08-04', 'cttn' => ''],
            ['barang' => 'TSEG25',     'lokasi' => 41,  'isi' => 1,  'tapel' => 7,  'tinggi' => 5,  'sisa' => 0,  'date' => '2026-08-04', 'cttn' => ''],
            ['barang' => 'DLSINTA50',  'lokasi' => 41,  'isi' => 1,  'tapel' => 5,  'tinggi' => 11, 'sisa' => 4,  'date' => '2026-08-04', 'cttn' => ''],
            ['barang' => 'TC1',        'lokasi' => 42,  'isi' => 1,  'tapel' => 4,  'tinggi' => 8,  'sisa' => 0,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'TS05',       'lokasi' => 42,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 15, 'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'MINFORTPLW', 'lokasi' => 51,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 8,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'TS1',        'lokasi' => 51,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 10, 'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'DLBOLA50',   'lokasi' => 52,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 2,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'GLP50',      'lokasi' => 52,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 0,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'DLSINTA50',  'lokasi' => 52,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 14, 'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'DLPG50',     'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 1,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'MINTW1',     'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 5,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'DLBOLA50',   'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 8,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'B5P',        'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 42, 'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'DLSIP50',    'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 0,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'B5P',        'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 15, 'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 9,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'TLM1',       'lokasi' => 72,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 20, 'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'KJRS1/2',    'lokasi' => 72,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 0,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'BOB25P',     'lokasi' => 81,  'isi' => 1,  'tapel' => 5,  'tinggi' => 6,  'sisa' => 2,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'B10P',       'lokasi' => 81,  'isi' => 1,  'tapel' => 7,  'tinggi' => 4,  'sisa' => 0,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'RG',         'lokasi' => 81,  'isi' => 1,  'tapel' => 8,  'tinggi' => 10, 'sisa' => 1,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'RG',         'lokasi' => 81,  'isi' => 50, 'tapel' => 0,  'tinggi' => 0,  'sisa' => 8,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'MIELOS',     'lokasi' => 81,  'isi' => 1,  'tapel' => 4,  'tinggi' => 5,  'sisa' => 0,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'MIELOS',     'lokasi' => 82,  'isi' => 1,  'tapel' => 4,  'tinggi' => 9,  'sisa' => 4,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'G5',         'lokasi' => 82,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 3,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'G5',         'lokasi' => 82,  'isi' => 2,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 12, 'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'RG',         'lokasi' => 84,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 1,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'MTGAMD15',   'lokasi' => 110, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 0,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'TS1',        'lokasi' => 110, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 18, 'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'HW',         'lokasi' => 110, 'isi' => 1,  'tapel' => 5,  'tinggi' => 8,  'sisa' => 0,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 110, 'isi' => 1,  'tapel' => 10, 'tinggi' => 6,  'sisa' => 3,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'MTGAMD5',    'lokasi' => 120, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 7,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'TLM1',       'lokasi' => 120, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 2,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'KRN',        'lokasi' => 120, 'isi' => 10, 'tapel' => 5,  'tinggi' => 8,  'sisa' => 1,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 130, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 11, 'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'KJGL1/2',    'lokasi' => 130, 'isi' => 1,  'tapel' => 5,  'tinggi' => 4,  'sisa' => 0,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'KJGL1/2',    'lokasi' => 130, 'isi' => 1,  'tapel' => 5,  'tinggi' => 22, 'sisa' => 5,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'KJGL1/2',    'lokasi' => 130, 'isi' => 1,  'tapel' => 5,  'tinggi' => 15, 'sisa' => 0,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'KJGL1/2',    'lokasi' => 130, 'isi' => 1,  'tapel' => 5,  'tinggi' => 2,  'sisa' => 1,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 140, 'isi' => 1,  'tapel' => 6,  'tinggi' => 5,  'sisa' => 2,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 140, 'isi' => 1,  'tapel' => 6,  'tinggi' => 2,  'sisa' => 0,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'GRMD',       'lokasi' => 140, 'isi' => 1,  'tapel' => 10, 'tinggi' => 15, 'sisa' => 3,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'GRMD',       'lokasi' => 140, 'isi' => 1,  'tapel' => 10, 'tinggi' => 18, 'sisa' => 1,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'GRMD',       'lokasi' => 140, 'isi' => 1,  'tapel' => 7,  'tinggi' => 8,  'sisa' => 0,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 150, 'isi' => 4,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 5,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 150, 'isi' => 10, 'tapel' => 0,  'tinggi' => 0,  'sisa' => 0,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 150, 'isi' => 15, 'tapel' => 0,  'tinggi' => 0,  'sisa' => 6,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 150, 'isi' => 20, 'tapel' => 0,  'tinggi' => 0,  'sisa' => 4,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 150, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,  'sisa' => 2,  'date' => '2026-08-05', 'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 160, 'isi' => 0,  'tapel' => 0,  'tinggi' => 680,'sisa' => 0,  'date' => '2026-08-05', 'cttn' => '4053'],
        ];
        $stockEntriesNew = [];

        foreach ($stockEntriesNew as $entry) {
            $stockEntriesNew[] = [
                'item_id'    => $itemMap[$entry['barang']],
                'lokasi'     => $entry['lokasi'],
                'isi'        => $entry['isi'],
                'tapel'      => $entry['tapel'],
                'tinggi'     => $entry['tinggi'],
                'sisa'       => $entry['sisa'],
                'cttn'       => $entry['cttn'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('stock_entries')->insert($stockEntriesNew);
    }
}
