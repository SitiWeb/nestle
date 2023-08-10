<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'img'
    ];
    // Define the relationship with the Unit model
    public function units()
    {
        return $this->belongsToMany(Unit::class, 'unit_brand');
    }
}
