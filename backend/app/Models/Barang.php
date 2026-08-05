<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    public function locations()
    {
        return $this->hasMany(BarangLocation::class);
    }

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }
}
