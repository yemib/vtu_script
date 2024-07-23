<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static function loanInstallments($id)
    {
        return Self::where('type', 'LOAN')->where('f_id', $id)->get();
    }

    public static function dpsInstallments($id)
    {
        return Self::where('type', 'DPS')->where('f_id', $id)->get();
    }

    public static function fdrInstallments($id)
    {
        return Self::where('type', 'FDR')->where('f_id', $id)->get();
    }
}
