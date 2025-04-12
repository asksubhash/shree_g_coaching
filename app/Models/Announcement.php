<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function getCurrentAnnouncements()
    {
        return Announcement::where('from_date', '<=', date('Y-m-d'))
            ->where('to_date', '>=', date('Y-m-d'))
            ->where('record_status', 1)
            ->get();
    }
}
