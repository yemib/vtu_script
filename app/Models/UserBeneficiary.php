<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBeneficiary extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'details' => 'object',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function beneficiary()
    {
        return $this->belongsTo(User::class, 'beneficiary_id');
    }

    public function bank()
    {
        return $this->belongsTo(OtherBank::class);
    }
}
