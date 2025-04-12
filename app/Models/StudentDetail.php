<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class StudentDetail extends Model
{
    use HasFactory;
    public $timestamps = false;

    // public function admissionSession()
    // {
    //     return $this->hasOne(GenCode::class, "gen_code", "adm_sesh")->withDefault([
    //         'description' => 'N/A',
    //     ]);
    // }
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
