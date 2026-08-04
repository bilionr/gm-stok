<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockEntry extends Model
{
    protected $fillable = ['item_id', 'lokasi', 'isi', 'tapel', 'tinggi', 'col0', 'cttn'];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function getTotalAttribute(): int
    {
        return $this->tapel * $this->tinggi + $this->col0;
    }
}
