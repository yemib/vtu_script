<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDps extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'matured_at'            => 'datetime',
        'next_installment_date' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(DpsPlan::class, 'plan_id', 'id');
    }

    public function scopeRunning()
    {
        return $this->where('status', 1);
    }

    public function scopeMatured()
    {
        return $this->where('status', 2);
    }

    public function scopeClosed()
    {
        return $this->where('status', 0);
    }

    public function scopeDue()
    {
        return $this->where('status', 1)->whereDate('next_installment_date', '<', Carbon::now());
    }


}
