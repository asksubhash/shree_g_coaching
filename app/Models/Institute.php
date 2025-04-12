<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institute extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public static function getInstitutes()
    {
        return Institute::where('record_status', 1)->get();
    }
}
