<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = [
        'log_id', 'barang_id', 'location',
        'physical_stock', 'omega_stock', 'difference', 'notes',
    ];
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function log()
    {
        return $this->belongsTo(Log::class);
    }
}
