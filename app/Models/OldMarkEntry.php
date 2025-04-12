<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OldMarkEntry extends Model
{
    use HasFactory;
    protected $table = 'old_marks_entry';

    public static function getExamTypes()
    {
        return OldMarkEntry::select('exam_type')->distinct()->get();
    }
}
