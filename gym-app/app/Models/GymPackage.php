<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'duration',
        'description',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
