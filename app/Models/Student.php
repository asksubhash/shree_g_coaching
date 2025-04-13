<?php

namespace App\Models;

use App\Models\UserDepartmentMapping;
use Illuminate\Database\Eloquent\Model;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state', 'state_code');
    }

    public function studentSubjectMapping()
    {
        return $this->hasMany(StudentSubjectMapping::class, 'student_id', 'id');
    }

    public function studentNLSubjectMapping()
    {
        return $this->hasMany(StudentNLSubjectMapping::class, 'student_id', 'id');
    }

    public static function getStudentCount($eduType = [], $isApproved = '')
    {
        $role = auth()->user()->role_code;

        if (in_array($role, ['INS_DEO', 'INS_HEAD'])) {
            // Get the institute id
            $udmData = UserDepartmentMapping::where('user_id', auth()->user()->user_id)->get()->toArray();
            $instituteIdsMapped = array_column($udmData, 'department_id');
        }

        $query = Student::query();


        if (in_array($role, ['INS_DEO', 'INS_HEAD'])) {
            $query->whereIn('institute_id', $instituteIdsMapped);
        }

        if (in_array($isApproved, [0, 1, 2])) {
            $query->where('is_approved', $isApproved);
        }

        $query->where('record_status', 1);
        return $query->count();
    }


    public static function getHighSchoolStudentDetailsUsingId($id)
    {
        $data['user'] = Student::leftJoin('state_master as st', 'students.state', '=', 'st.state_code')
            ->leftJoin('institutes as ins', 'students.institute_id', '=', 'ins.id')
            // ->leftJoin('courses as cs', 'students.course', '=', 'cs.id')
            // ->leftJoin('academic_years as ay', 'students.academic_year', '=', 'ay.id')
            // ->leftJoin('admission_sessions as ads', 'students.adm_sesh', '=', 'ads.id')
            // ->leftJoin('gen_codes as gd1', 'students.adm_sesh', '=', 'gd1.gen_code')
            ->leftJoin('gen_codes as gd2', 'students.gender', '=', 'gd2.gen_code')
            ->leftJoin('gen_codes as gd3', 'students.religion', '=', 'gd3.gen_code')
            ->leftJoin('gen_codes as gd4', 'students.category', '=', 'gd4.gen_code')
            ->select(
                'students.*',
                // 'ay.academic_year as st_academic_year',
                // 'ads.session_name as st_admission_session',
                'ins.name as institute_name',
                // 'cs.course_name',
                'gd2.description as gender',
                'gd3.description as religion',
                'st.state_name',
                'gd4.description as category',
            )
            ->findOrFail($id);

        // $data['courseSubjects'] = StudentSubjectMapping::select('student_subject_mappings.*', 'sub.name as subject_name')
        //     ->leftJoin('subjects as sub', 'sub.id', '=', 'student_subject_mappings.subject_id')
        //     ->where('student_subject_mappings.student_id', $data['user']->id)
        //     ->get();

        $data['courseSubjects'] = []; //StudentSubjectMapping::getStudentSubjectsUsingStudentId($data['user']->id);

        // $data['nonLanguageSubjects'] = StudentNLSubjectMapping::select('student_nl_subject_mappings.*', 'sub.name as subject_name')
        //     ->leftJoin('subjects as sub', 'sub.id', '=', 'student_nl_subject_mappings.subject_id')
        //     ->where('student_nl_subject_mappings.student_id', $data['user']->id)
        //     ->get();

     
        return $data;
    }

    public static function getGraduationSchoolStudentDetailsUsingId($id)
    {
        $data['user'] = Student::leftJoin('state_master as st', 'students.state', '=', 'st.state_code')
            ->leftJoin('institutes as ins', 'students.institute_id', '=', 'ins.id')
            ->leftJoin('courses as cs', 'students.course', '=', 'cs.id')
            ->leftJoin('academic_years as ay', 'students.academic_year', '=', 'ay.id')
            ->leftJoin('admission_sessions as ads', 'students.adm_sesh', '=', 'ads.id')
            // ->leftJoin('gen_codes as gd1', 'students.adm_sesh', '=', 'gd1.gen_code')
            ->leftJoin('gen_codes as gd2', 'students.gender', '=', 'gd2.gen_code')
            ->leftJoin('gen_codes as gd3', 'students.religion', '=', 'gd3.gen_code')
            ->leftJoin('gen_codes as gd4', 'students.category', '=', 'gd4.gen_code')
            ->select(
                'students.*',
                'ay.academic_year as st_academic_year',
                'ads.session_name as st_admission_session',
                'ins.name as institute_name',
                'cs.course_name',
                'gd2.description as gender',
                'gd3.description as religion',
                'st.state_name',
                'gd4.description as category',
            )
            ->findOrFail($id);

        $data['courseSubjects'] = CourseSubjectMapping::select('course_subject_mappings.*', 'sub.name as subject_name')
            ->leftJoin('subjects as sub', 'sub.id', '=', 'course_subject_mappings.subject_id')
            ->where('course_subject_mappings.course_id', $data['user']->course)
            ->get();

        $data['nonLanguageSubjects'] = CourseNLSubjectMapping::select('course_nl_subject_mappings.*', 'sub.name as subject_name')
            ->leftJoin('non_language_subjects as sub', 'sub.id', '=', 'course_nl_subject_mappings.subject_id')
            ->where('course_nl_subject_mappings.course_id', $data['user']->course)
            ->get();

        return $data;
    }

    public static function getStudentDetailsUsingId($id)
    {
        return Student::where([
            'id' => $id
        ])->first();
    }

    public static function getStudentAllDetailsUsingId($id)
    {
        $data['studentDetails'] = Student::leftJoin('state_master as st', 'students.state', '=', 'st.state_code')
            ->leftJoin('institutes as ins', 'students.institute_id', '=', 'ins.id')
            ->leftJoin('courses as cs', 'students.course', '=', 'cs.id')
            ->leftJoin('academic_years as ay', 'students.academic_year', '=', 'ay.id')
            ->leftJoin('admission_sessions as ads', 'students.adm_sesh', '=', 'ads.id')
            // ->leftJoin('gen_codes as gd1', 'students.adm_sesh', '=', 'gd1.gen_code')
            ->leftJoin('gen_codes as gd2', 'students.gender', '=', 'gd2.gen_code')
            ->leftJoin('gen_codes as gd3', 'students.religion', '=', 'gd3.gen_code')
            ->leftJoin('gen_codes as gd4', 'students.category', '=', 'gd4.gen_code')
            ->select(
                'students.*',
                'ay.academic_year as st_academic_year',
                'ads.session_name as st_admission_session',
                'ins.name as institute_name',
                'cs.course_name',
                'gd2.description as gender',
                'gd3.description as religion',
                'st.state_name',
                'gd4.description as category',
            )
            ->where([
                'students.id' => $id
            ])->first();

        // If graduation, then fetch the data from course subject mapping and course non language subject mapping
        if ($data['studentDetails']->edu_type == 'GRADUATION') {
            $data['courseSubjects'] = CourseSubjectMapping::select('course_subject_mappings.*', 'sub.name as subject_name')
                ->leftJoin('subjects as sub', 'sub.id', '=', 'course_subject_mappings.subject_id')
                ->where('course_subject_mappings.course_id', $data['studentDetails']->course)
                ->get();

            $data['nonLanguageSubjects'] = CourseNLSubjectMapping::select('course_nl_subject_mappings.*', 'sub.name as subject_name')
                ->leftJoin('non_language_subjects as sub', 'sub.id', '=', 'course_nl_subject_mappings.subject_id')
                ->where('course_nl_subject_mappings.course_id', $data['studentDetails']->course)
                ->get();
        } else {
            $data['courseSubjects'] = StudentSubjectMapping::select('student_subject_mappings.*', 'sub.name as subject_name')
                ->leftJoin('subjects as sub', 'sub.id', '=', 'student_subject_mappings.subject_id')
                ->where('student_subject_mappings.student_id', $data['studentDetails']->id)
                ->get();

            $data['nonLanguageSubjects'] = StudentNLSubjectMapping::select('student_nl_subject_mappings.*', 'sub.name as subject_name')
                ->leftJoin('subjects as sub', 'sub.id', '=', 'student_nl_subject_mappings.subject_id')
                ->where('student_nl_subject_mappings.student_id', $data['studentDetails']->id)
                ->get();
        }

        return $data;
    }

    public static function getStudentDetailsUsingRollNo($rollNumber)
    {
        $data['studentDetails'] = Student::leftJoin('state_master as st', 'students.state', '=', 'st.state_code')
            ->leftJoin('institutes as ins', 'students.institute_id', '=', 'ins.id')
            ->leftJoin('gen_codes as gd2', 'students.gender', '=', 'gd2.gen_code')
            ->leftJoin('gen_codes as gd3', 'students.religion', '=', 'gd3.gen_code')
            ->leftJoin('gen_codes as gd4', 'students.category', '=', 'gd4.gen_code')
            ->select(
                'students.*',
                'ins.name as institute_name',
                'gd2.description as gender',
                'gd3.description as religion',
                'st.state_name',
                'gd4.description as category',
            )
            ->where([
                // 'roll_number' => $rollNumber
                'application_no' => $rollNumber
            ])->first();

        // If graduation, then fetch the data from course subject mapping and course non language subject mapping
        // if ($data['studentDetails']->edu_type == 'GRADUATION') {
        //     $data['courseSubjects'] = CourseSubjectMapping::select('course_subject_mappings.*', 'sub.name as subject_name')
        //         ->leftJoin('subjects as sub', 'sub.id', '=', 'course_subject_mappings.subject_id')
        //         ->where('course_subject_mappings.course_id', $data['studentDetails']->course)
        //         ->get();

        //     $data['nonLanguageSubjects'] = CourseNLSubjectMapping::select('course_nl_subject_mappings.*', 'sub.name as subject_name')
        //         ->leftJoin('non_language_subjects as sub', 'sub.id', '=', 'course_nl_subject_mappings.subject_id')
        //         ->where('course_nl_subject_mappings.course_id', $data['studentDetails']->course)
        //         ->get();
        // } else {
        //     $data['courseSubjects'] = StudentSubjectMapping::select('student_subject_mappings.*', 'sub.name as subject_name')
        //         ->leftJoin('subjects as sub', 'sub.id', '=', 'student_subject_mappings.subject_id')
        //         ->where('student_subject_mappings.student_id', $data['studentDetails']->id)
        //         ->get();

        //     $data['nonLanguageSubjects'] = StudentNLSubjectMapping::select('student_nl_subject_mappings.*', 'sub.name as subject_name')
        //         ->leftJoin('subjects as sub', 'sub.id', '=', 'student_nl_subject_mappings.subject_id')
        //         ->where('student_nl_subject_mappings.student_id', $data['studentDetails']->id)
        //         ->get();
        // }

        return $data;
    }

    public static function fetchStudyCenterStudentNewApplications()
    {
        $query = Student::leftJoin('state_master as sm', 'students.state', '=', 'sm.state_code');

        $query->leftJoin('institutes as ins', 'students.institute_id', '=', 'ins.id');
        $query->leftJoin('courses as cs', 'students.course', '=', 'cs.id');
        $query->leftJoin('academic_years as ay', 'students.academic_year', '=', 'ay.id');
        $query->leftJoin('admission_sessions as ads', 'students.adm_sesh', '=', 'ads.id');

        $query->select(
            'students.*',
            'sm.state_name',
            'ay.academic_year as st_academic_year',
            'ads.session_name as st_admission_session',
            'ins.name as institute_name',
            'cs.course_name',
        );

        if (in_array(auth()->user()->role_code, ['INS_DEO', 'INS_HEAD'])) {
            // Get the institute id
            $udmData = UserDepartmentMapping::where('user_id', auth()->user()->user_id)->get()->toArray();
            $instituteIdsMapped = array_column($udmData, 'department_id');
            $query->whereIn('students.institute_id', $instituteIdsMapped);
        }


        $query->where('students.is_approved', 0);
        $query->where('students.record_status', 1);
        $query->where('students.edu_type', NULL);
        $query->where('students.register_through', "ONLINE");

        $allUsers = $query->orderBy('students.id', 'desc')->get();
        return $allUsers;
    }
}
