<?php

namespace App\Models;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;

class UnitMeta extends Model
{
    protected $table = 'unit_meta';

    protected $fillable = [
        'unit_id',
        'meta_key',
        'meta_value',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

}