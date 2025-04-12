<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getAcademicYears()
    {
        return AcademicYear::where([
            'record_status' => 1
        ])->get();
    }

    public static function getActiveAcademicYear()
    {
        return AcademicYear::where([
            'record_status' => 1,
            'active_status' => 1
        ])->get();
    }
}
