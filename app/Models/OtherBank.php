<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherBank extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'user_data' => 'object'
    ];
    public function scopeActive()
    {
        return $this->where('status', 1);
    }
}
