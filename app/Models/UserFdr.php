<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFdr extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'locked_date'       => 'datetime',
        'next_return_date'  => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(FdrPlan::class, 'plan_id', 'id');
    }

    public function scopeRunning()
    {
        return $this->where('status', 1);
    }
    public function scopeClosed()
    {
        return $this->where('status', 2);
    }

    public function scopedue()
    {
        return $this->where('status', 1)->whereDate('next_return_date', '<', Carbon::now());
    }
}
