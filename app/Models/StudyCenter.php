<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyCenter extends Model
{
    use HasFactory;

    public static function getStudyCenterDetailsUsingId($id)
    {
        $query = StudyCenter::leftJoin('state_master as sm', 'study_centers.state', '=', 'sm.state_code');
        $query->leftJoin('district_master as dm', 'study_centers.district', '=', 'dm.id');
        $query->select(
            'study_centers.*',
            'sm.state_name',
            'dm.district_name'
        );

        $query->where('study_centers.id', $id);
        $scData = $query->first();

        return $scData;
    }
}
