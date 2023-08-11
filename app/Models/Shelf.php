<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    use HasFactory;
    protected $table = 'shelves';

    protected $fillable = [
        'name',
        'unit_id',
        'width',
        'length',
        'height',
        'comment',
        'type',
    ];
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
