<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DistrictMaster;
use App\Models\StateMaster;

class EducationDetail extends Model
{
    use HasFactory;

    public function admissionSession()
    {
        return $this->hasOne(GenCode::class, "gen_code", "adm_sesh")->withDefault([
            'description' => 'N/A',
        ]);
    }

    public function admissionType()
    {
        return $this->hasOne(GenCode::class, "gen_code", "adm_type")->withDefault([
            'description' => 'N/A',
        ]);
    }

    public function Specialization()
    {
        return $this->hasOne(GenCode::class, "gen_code", "specialization")->withDefault([
            'description' => 'N/A',
        ]);
    }
    public function Year()
    {
        return $this->hasOne(GenCode::class, "gen_code", "year")->withDefault([
            'description' => 'N/A',
        ]);
    }

    public function Gender()
    {
        return $this->hasOne(GenCode::class, "gen_code", "gender")->withDefault([
            'description' => 'N/A',
        ]);
    }
    public function postOffice()
    {
        return $this->hasOne(GenCode::class, "gen_code", "post_office")->withDefault([
            'description' => 'N/A',
        ]);
    }

    public function Nationality()
    {
        return $this->hasOne(GenCode::class, "gen_code", "nationality")->withDefault([
            'description' => 'N/A',
        ]);
    }

    public function Category()
    {
        return $this->hasOne(GenCode::class, "gen_code", "category")->withDefault([
            'description' => 'N/A',
        ]);
    }

    public function empStatus()
    {
        return $this->hasOne(GenCode::class, "gen_code", "emp_status")->withDefault([
            'description' => 'N/A',
        ]);
    }
    public function tenYear()
    {
        return $this->hasOne(GenCode::class, "gen_code", "ac_ten_year")->withDefault([
            'description' => 'N/A',
        ]);
    }

    public function tenSubject()
    {
        return $this->hasOne(GenCode::class, "gen_code", "ac_ten_subj")->withDefault([
            'description' => 'N/A',
        ]);
    }


    public function tenBoard()
    {
        return $this->hasOne(GenCode::class, "gen_code", "ac_ten_board_name")->withDefault([
            'description' => 'N/A',
        ]);
    }




    public function twelveYear()
    {
        return $this->hasOne(GenCode::class, "gen_code", "ac_twelve_year")->withDefault([
            'description' => 'N/A',
        ]);
    }

    public function twelveSubject()
    {
        return $this->hasOne(GenCode::class, "gen_code", "ac_twelve_subj")->withDefault([
            'description' => 'N/A',
        ]);
    }


    public function twelveBoard()
    {
        return $this->hasOne(GenCode::class, "gen_code", "ac_twelve_board_name")->withDefault([
            'description' => 'N/A',
        ]);
    }




    public function otherYear()
    {
        return $this->hasOne(GenCode::class, "gen_code", "ac_other_year")->withDefault([
            'description' => 'N/A',
        ]);
    }

    public function otherSubject()
    {
        return $this->hasOne(GenCode::class, "gen_code", "ac_other_subj")->withDefault([
            'description' => 'N/A',
        ]);
    }


    public function otherBoard()
    {
        return $this->hasOne(GenCode::class, "gen_code", "ac_other_board_name")->withDefault([
            'description' => 'N/A',
        ]);
    }


    public function Course()
    {
        return $this->hasOne(Course::class, 'id', "course")->withDefault([
            'course_name' => 'N/A',
        ]);
    }
    public function State()
    {
        return $this->hasOne(StateMaster::class, 'state_code', 'state')
            ->withDefault([
                'state_name' => 'N/A',
            ]);
    }
    public function District()
    {
        return $this->belongsTo(DistrictMaster::class, 'district')->withDefault([
            'district_name' => 'N/A',
        ]);
    }
}