<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceTransfer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function beneficiary()
    {
        return $this->belongsTo(UserBeneficiary::class, 'beneficiary_id', 'id');
    }

    public function bank()
    {
        return $this->belongsTo(OtherBank::class, 'bank_id', 'id');
    }

    public function scopeCompleted()
    {
        return $this->where('status', 1);
    }

}
