<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAcademicDetail extends Model
{
    use HasFactory;
    public $timestamps = false; // disables created_at & updated_at
    protected $guarded = [];    // allows mass assignment on all fields
}
