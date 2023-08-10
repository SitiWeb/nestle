<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'airport_store_name',
        'airport_code',
        'terminal',
        'retailer',
        'country'
    ];
    // Define the relationship with the Unit model
    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
