<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'gym_package_id',
        'preferred_trainer_id',
        'start_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function gymPackage()
    {
        return $this->belongsTo(GymPackage::class);
    }

    public function preferredTrainer()
    {
        return $this->belongsTo(Trainer::class, 'preferred_trainer_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
