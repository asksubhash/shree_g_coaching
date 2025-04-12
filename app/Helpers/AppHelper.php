<?php

namespace App\Helpers;

use App\Models\AdmissionSession;
use Illuminate\Support\Facades\Auth;
use App\Models\Designation;
use App\Models\StudentAssignment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class AppHelper
{
    public static function getDesignation()
    {
        $designation = Designation::where('code', auth()->user()->designation)->first();
        return isset($designation->name) ? $designation->name : 'N/A';
    }

    public static function generateUniqueCode()
    {
        return time() . rand(99, 999);
    }

    public static function getGenCode($type = null)
    {
        $query = DB::table('gen_codes as gd')->join('gen_code_groups as gdc', 'gd.gen_code_group_id', '=', 'gdc.id')->select('gdc.group_name', 'gd.gen_code');
        if ($type) {
            $query->where('gd.gen_code', $type);
        }
        $query->where('gd.status', 1);
        return $query->get();
    }

    public static function getUploadedAssignmentDetUsingAssignmentId($assignmentId)
    {
        return StudentAssignment::getStudentUploadedAssignment($assignmentId, Auth::user()->user_id);
    }

    public static function getAdmissionSessUsingAyInsCourse($academicYearId, $instituteId, $courseId)
    {
        return AdmissionSession::getAdmissionSessUsingAyInsCourse($academicYearId, $instituteId, $courseId);
    }

    public static function checkExamActive($subject)
    {
        if ($subject->exam_date && $subject->exam_time) {
            $examStartTime = Carbon::parse($subject->exam_date . ' ' . $subject->exam_time);
            $examEndTime = $examStartTime->copy()->addHours($subject->exam_duration);
            // Check if the current time is within the exam period
            $isExamActive = Carbon::now()->between($examStartTime, $examEndTime);

            return $isExamActive;
        } else {
            return false;
        }
    }

    public static function examRemainingTime($subject)
    {
        if ($subject->exam_date && $subject->exam_time) {
            $examStartTime = Carbon::parse($subject->exam_date . ' ' . $subject->exam_time);
            $examEndTime = $examStartTime->copy()->addHours($subject->exam_duration);

            $currentDateTime = Carbon::now();
            $timeRemainingInSeconds = $currentDateTime->diffInSeconds($examEndTime, false);

            // Ensure time remaining is not negative
            $timeRemainingInSeconds = max($timeRemainingInSeconds, 0);

            return $timeRemainingInSeconds;
        } else {
            return false;
        }
    }


    // for getting current user's institute id
    public static function getCurrentUserInstituteId()
    {
        return Auth::user()->institute?->id ?? 0;
    }



    public static function getStudentRegisterTabConstant($key=null)
    {
        $artistTabs = Config::get('constants.StudentRegister');
        if (array_key_exists($key, $artistTabs)) {
            return $artistTabs[$key];
        } else {
            return '';
        }
    }
}
