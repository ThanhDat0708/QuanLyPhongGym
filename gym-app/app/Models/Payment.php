<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'amount',
        'method',
        'status',
        'payment_date',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
        ];
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
