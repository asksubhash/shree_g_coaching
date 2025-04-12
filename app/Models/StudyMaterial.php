<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyMaterial extends Model
{
    use HasFactory;

    protected $table = 'study_materials';
    protected $guarded = [];

    public static function getStudentCourseStudyMaterials($courseId)
    {
        $query = StudyMaterial::select('study_materials.*', 'ins.name as institute_name', 'c.course_name', 'c.course_code', 's.name as language_subject', 'nls.name as non_language_subject');

        $query->leftJoin('institutes as ins', 'ins.id', '=', 'study_materials.institute_id');
        $query->leftJoin('courses as c', 'c.id', '=', 'study_materials.course_id');
        $query->leftJoin('subjects as s', function ($join) {
            $join->on('s.id', '=', 'study_materials.subject_id');
            $join->where('study_materials.subject_type', '=', 'LANGUAGE'); // Add your condition here
        });
        $query->leftJoin('non_language_subjects as nls', function ($join) {
            $join->on('nls.id', '=', 'study_materials.subject_id');
            $join->where('study_materials.subject_type', '=', 'NON_LANGUAGE'); // Add your condition here
        });

        $query->where('study_materials.course_id', $courseId);
        $query->where('study_materials.record_status', 1);
        return $query->get();
    }

    public static function getStudentSubjectStudyMaterials($subjectIds)
    {
        $query = StudyMaterial::select('study_materials.*', 's.name as language_subject');

        $query->leftJoin('subjects as s', function ($join) {
            $join->on('s.id', '=', 'study_materials.subject_id');
            $join->where('study_materials.subject_type', '=', 'LANGUAGE'); // Add your condition here
        });

        $query->where('study_materials.subject_type', 'LANGUAGE');
        $query->whereIn('study_materials.subject_id', $subjectIds);
        $query->where('study_materials.record_status', 1);
        return $query->get();
    }

    public static function getStudentNLSubjectStudyMaterials($subjectIds)
    {
        $query = StudyMaterial::select('study_materials.*', 'nls.name as non_language_subject');

        $query->leftJoin('non_language_subjects as nls', function ($join) {
            $join->on('nls.id', '=', 'study_materials.subject_id');
            $join->where('study_materials.subject_type', '=', 'NON_LANGUAGE'); // Add your condition here
        });

        $query->where('study_materials.subject_type', 'NON_LANGUAGE');
        $query->whereIn('study_materials.subject_id', $subjectIds);
        $query->where('study_materials.record_status', 1);
        return $query->get();
    }
}
