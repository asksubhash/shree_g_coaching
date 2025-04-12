<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OldStudentData extends Model
{
    use HasFactory;
    protected $table = 'old_student_data';
    protected $guarded = [];
    public $timestamps = false;
}
