<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'country';
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    // public function project()
    // {
    //     return $this->belongsTo(Project::class);
    // }
}
