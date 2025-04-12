<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenCodeGroup extends Model
{
    use HasFactory;
    protected $table = 'gen_code_groups';
    protected $guarded = [];
}
