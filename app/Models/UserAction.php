<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAction extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
        'send_at' => 'datetime',
        'expired_at' => 'datetime',
        'used_at' => 'datetime'
    ];
    public $timestamps = false;
}
