<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'trainer_id',
        'date',
        'time',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
}
