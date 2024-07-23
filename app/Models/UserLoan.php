<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'next_installment_date' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(LoanPlan::class, 'plan_id', 'id');
    }

    public function scopePending()
    {
        return $this->where('status', 0);
    }

    public function scopeRunning()
    {
        return $this->where('status', 1);
    }

    public function scopePaid()
    {
        return $this->where('status', 2);
    }

    public function scopeRejected()
    {
        return $this->where('status', 3);
    }

    public function scopeApproved()
    {
        return $this->where('status', '!=', 3);
    }

    public function scopeDue()
    {
        return $this->where('status', 1)
        ->whereDate('next_installment_date', '<', Carbon::now());
    }

}
