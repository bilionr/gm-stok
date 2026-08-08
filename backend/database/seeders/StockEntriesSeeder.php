<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Omega;
use App\Models\Log;
use App\Models\Entry;


class StockEntriesSeeder extends Seeder
{
    public function run(): void
    {
        $itemsWithOmega = [
            ['kode' => 'DLSIP50',    'omega' => 61],
            ['kode' => 'DLPG50',     'omega' => 45],
            ['kode' => 'TLM25',      'omega' => 0],
            ['kode' => 'DLBOLA50',   'omega' => 84],
            ['kode' => 'TC25',       'omega' => 0],
            ['kode' => 'GLP50',      'omega' => 0],
            ['kode' => 'KJRS25',     'omega' => 0],
            ['kode' => 'KJGR25',     'omega' => 0],
            ['kode' => 'TPYG25',     'omega' => 0],
            ['kode' => 'TTULIP25',   'omega' => 0],
            ['kode' => 'KJRS1/2',    'omega' => 0],
            ['kode' => 'KJGB25',     'omega' => 0],
            ['kode' => 'MIELOS',     'omega' => 0],
            ['kode' => 'TS05',       'omega' => 0],
            ['kode' => 'TB1/2',      'omega' => 0],
            ['kode' => 'TLM1',       'omega' => 0],
            ['kode' => 'TSEG25',     'omega' => 0],
            ['kode' => 'DLSINTA50',  'omega' => 55],
            ['kode' => 'TC1',        'omega' => 0],
            ['kode' => 'MINFORTPLW', 'omega' => 0],
            ['kode' => 'TS1',        'omega' => 0],
            ['kode' => 'MINTW1',     'omega' => 0],
            ['kode' => 'B5P',        'omega' => 108],
            ['kode' => 'BOB25P',     'omega' => 0],
            ['kode' => 'B10P',       'omega' => 0],
            ['kode' => 'RG',         'omega' => 0],
            ['kode' => 'G5',         'omega' => 20],
            ['kode' => 'MTGAMD15',   'omega' => 0],
            ['kode' => 'HW',         'omega' => 0],
            ['kode' => 'MTGAMD5',    'omega' => 0],
            ['kode' => 'KRN',        'omega' => 0],
            ['kode' => 'KJGL1/2',    'omega' => 0],
            ['kode' => 'GRMD',       'omega' => 0],
            ['kode' => 'MINCUR',     'omega' => 0],
        ];

        // 3. Raw Initial Log Dataset
        $stockEntriesRawInitial = [
            ['barang' => 'DLSIP50',    'lokasi' => 11,  'isi' => 1,  'tapel' => 7,  'tinggi' => 8,   'sisa' => 0,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'DLPG50',     'lokasi' => 11,  'isi' => 1,  'tapel' => 5,  'tinggi' => 7,   'sisa' => 4,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TLM25',      'lokasi' => 11,  'isi' => 1,  'tapel' => 5,  'tinggi' => 8,   'sisa' => 0,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'DLBOLA50',   'lokasi' => 11,  'isi' => 1,  'tapel' => 5,  'tinggi' => 13,  'sisa' => 0,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TC25',       'lokasi' => 11,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 9,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'GLP50',      'lokasi' => 12,  'isi' => 1,  'tapel' => 7,  'tinggi' => 7,   'sisa' => 6,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'KJRS25',     'lokasi' => 13,  'isi' => 1,  'tapel' => 7,  'tinggi' => 9,   'sisa' => 3,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'KJGR25',     'lokasi' => 13,  'isi' => 1,  'tapel' => 11, 'tinggi' => 7,   'sisa' => 10, 'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TPYG25',     'lokasi' => 21,  'isi' => 1,  'tapel' => 5,  'tinggi' => 4,   'sisa' => 2,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TTULIP25',   'lokasi' => 21,  'isi' => 1,  'tapel' => 7,  'tinggi' => 8,   'sisa' => 5,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TTULIP25',   'lokasi' => 22,  'isi' => 1,  'tapel' => 5,  'tinggi' => 20,  'sisa' => 0,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'KJRS25',     'lokasi' => 22,  'isi' => 1,  'tapel' => 5,  'tinggi' => 10,  'sisa' => 1,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'KJRS1/2',    'lokasi' => 22,  'isi' => 1,  'tapel' => 5,  'tinggi' => 0,   'sisa' => 0,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'KJRS1/2',    'lokasi' => 31,  'isi' => 1,  'tapel' => 5,  'tinggi' => 2,   'sisa' => 4,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'KJGB25',     'lokasi' => 31,  'isi' => 1,  'tapel' => 5,  'tinggi' => 10,  'sisa' => 0,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'MIELOS',     'lokasi' => 33,  'isi' => 1,  'tapel' => 5,  'tinggi' => 4,   'sisa' => 3,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TS05',       'lokasi' => 33,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 40, 'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 33,  'isi' => 1,  'tapel' => 10, 'tinggi' => 10,  'sisa' => 5,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TLM1',       'lokasi' => 33,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 6,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TSEG25',     'lokasi' => 41,  'isi' => 1,  'tapel' => 7,  'tinggi' => 7,   'sisa' => 4,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'DLSINTA50',  'lokasi' => 41,  'isi' => 1,  'tapel' => 5,  'tinggi' => 9,   'sisa' => 2,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TC1',        'lokasi' => 42,  'isi' => 1,  'tapel' => 4,  'tinggi' => 5,   'sisa' => 2,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TS05',       'lokasi' => 42,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 1,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'MINFORTPLW', 'lokasi' => 51,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 14, 'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TS1',        'lokasi' => 51,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 21, 'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'DLBOLA50',   'lokasi' => 52,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 6,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'GLP50',      'lokasi' => 52,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 6,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'DLSINTA50',  'lokasi' => 52,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 8,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'DLPG50',     'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 6,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'MINTW1',     'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 10, 'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'DLBOLA50',   'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 13, 'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'B5P',        'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 68, 'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'DLSIP50',    'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 7,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'B5P',        'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 39, 'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 71,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 0,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TLM1',       'lokasi' => 72,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 14, 'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'KJRS1/2',    'lokasi' => 72,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 4,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'BOB25P',     'lokasi' => 81,  'isi' => 1,  'tapel' => 5,  'tinggi' => 4,   'sisa' => 0,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'B10P',       'lokasi' => 81,  'isi' => 1,  'tapel' => 7,  'tinggi' => 7,   'sisa' => 5,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'RG',         'lokasi' => 81,  'isi' => 1,  'tapel' => 8,  'tinggi' => 5,   'sisa' => 4,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'RG',         'lokasi' => 81,  'isi' => 50, 'tapel' => 0,  'tinggi' => 0,   'sisa' => 3,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'MIELOS',     'lokasi' => 81,  'isi' => 1,  'tapel' => 4,  'tinggi' => 7,   'sisa' => 2,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'MIELOS',     'lokasi' => 82,  'isi' => 1,  'tapel' => 4,  'tinggi' => 7,   'sisa' => 0,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'G5',         'lokasi' => 82,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 9,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'G5',         'lokasi' => 82,  'isi' => 2,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 5,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'RG',         'lokasi' => 84,  'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 4,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'MTGAMD15',   'lokasi' => 110, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 1,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TS1',        'lokasi' => 110, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 7,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'HW',         'lokasi' => 110, 'isi' => 1,  'tapel' => 5,  'tinggi' => 4,   'sisa' => 3,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 110, 'isi' => 1,  'tapel' => 10, 'tinggi' => 10,  'sisa' => 0,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'MTGAMD5',    'lokasi' => 120, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 15, 'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TLM1',       'lokasi' => 120, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 9,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'KRN',        'lokasi' => 120, 'isi' => 10, 'tapel' => 5,  'tinggi' => 5,   'sisa' => 4,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 130, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 3,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'KJGL1/2',    'lokasi' => 130, 'isi' => 1,  'tapel' => 5,  'tinggi' => 0,   'sisa' => 3,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'KJGL1/2',    'lokasi' => 130, 'isi' => 1,  'tapel' => 5,  'tinggi' => 30,  'sisa' => 0,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'KJGL1/2',    'lokasi' => 130, 'isi' => 1,  'tapel' => 5,  'tinggi' => 19,  'sisa' => 1,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'KJGL1/2',    'lokasi' => 130, 'isi' => 1,  'tapel' => 5,  'tinggi' => 0,   'sisa' => 0,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 140, 'isi' => 1,  'tapel' => 6,  'tinggi' => 3,   'sisa' => 0,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'TB1/2',      'lokasi' => 140, 'isi' => 1,  'tapel' => 6,  'tinggi' => 3,   'sisa' => 2,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'GRMD',       'lokasi' => 140, 'isi' => 1,  'tapel' => 10, 'tinggi' => 20,  'sisa' => 0,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'GRMD',       'lokasi' => 140, 'isi' => 1,  'tapel' => 10, 'tinggi' => 20,  'sisa' => 0,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'GRMD',       'lokasi' => 140, 'isi' => 1,  'tapel' => 7,  'tinggi' => 11,  'sisa' => 6,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 150, 'isi' => 4,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 13, 'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 150, 'isi' => 10, 'tapel' => 0,  'tinggi' => 0,   'sisa' => 3,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 150, 'isi' => 15, 'tapel' => 0,  'tinggi' => 0,   'sisa' => 11, 'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 150, 'isi' => 20, 'tapel' => 0,  'tinggi' => 0,   'sisa' => 12, 'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 150, 'isi' => 1,  'tapel' => 0,  'tinggi' => 0,   'sisa' => 0,  'date' => '2026-07-31', 'cttn' => ''],
            ['barang' => 'MINCUR',     'lokasi' => 160, 'isi' => 0,  'tapel' => 0,  'tinggi' => 735, 'sisa' => 0,  'date' => '2026-07-31', 'cttn' => '4053'],
        ];

        // 4. Raw Second Log Dataset
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

        // 5. Seed Barangs and Omegas
        $barangMap = [];
        $now = now();

        foreach ($itemsWithOmega as $item) {
            // Create Barang
            $barang = Barang::create(['kode' => $item['kode']]);
            $barangMap[$item['kode']] = $barang->id;

            // Create Omega record
            Omega::create([
                'barang_id' => $barang->id,
                'qty'       => $item['omega'],
                'recorded_at' => $now,
            ]);
        }

        // Combine both log datasets
        $allEntries = array_merge($stockEntriesRawInitial, $stockEntriesRawNew);

        // 6. Seed Logs table (grouped by unique audit date)
        $uniqueDates = array_unique(array_column($allEntries, 'date'));
        sort($uniqueDates);
        $logMap = [];

        foreach ($uniqueDates as $date) {
            $log = Log::create(['recorded_on' => $date]);
            $logMap[$date] = $log->id;
        }

        // 7. Seed Entries table
        $entriesToInsert = [];

        foreach ($allEntries as $entry) {
            $barangId = $barangMap[$entry['barang']] ?? null;
            $logId = $logMap[$entry['date']] ?? null;

            if (!$barangId || !$logId) continue;

            $tapel = (int) $entry['tapel'];
            $tinggi = (int) $entry['tinggi'];
            $sisa = (int) $entry['sisa'];

            $physicalStock = ($tapel * $tinggi) + $sisa;

            // Fetch latest Omega value for this item from database
            $omegaStock = Omega::where('barang_id', $barangId)
                ->latest('recorded_at')
                ->value('qty') ?? 0;

            $difference = $physicalStock - $omegaStock;

            $entriesToInsert[] = [
                'log_id'         => $logId,
                'barang_id'      => $barangId,
                'location'       => (int) $entry['lokasi'],
                'isi'            => (int) $entry['isi'],
                'tapel'          => $tapel,
                'tinggi'         => $tinggi,
                'sisa'           => $sisa,
                'physical_stock' => $physicalStock,
                'omega_stock'    => $omegaStock,
                'difference'     => $difference,
                'notes'          => !empty($entry['cttn']) ? $entry['cttn'] : null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ];
        }

        // Mass insert entries in chunks
        foreach (array_chunk($entriesToInsert, 100) as $chunk) {
            Entry::insert($chunk);
        }
    }
}