<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = ['recorded_on'];
    protected $casts = ['recorded_on' => 'date:Y-m-d'];
    
    public function entries()
    {
        return $this->hasMany(Entry::class);
    }
}
