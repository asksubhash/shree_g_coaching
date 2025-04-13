<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Auth;
use App\Models\User;
use App\Models\Student;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Models\UserDepartmentMapping;

class DashboardController extends Controller
{
    use HelperTrait;

    public function adminDashboard()
    {
        $data = $this->getTotalBasedStudentCounts();
        $newStudentsData = $this->getNewStudentCounts();
        $approvedStudentsData = $this->getApprovedStudentCounts();

        return view('dashboard.admin_dashboard', compact('data', 'newStudentsData', 'approvedStudentsData'));
    }

    public function userDashboard()
    {

        return view('user.dashboard', compact('months', 'totalSurvey', 'completeSurvey', 'terminateSurvey', 'quotaFullSurvey'));
    }

    public function departmentDashboard()
    {
        return view('dashboard.department_dashboard');
    }

    public function hodDashboard()
    {
        return view('dashboard.hod_dashboard');
    }

    public function chairpersonDashboard()
    {
        return view('dashboard.chairperson_dashboard');
    }

    public function insHeadDashboard()
    {
        $data = $this->getTotalBasedStudentCounts();
        $newStudentsData = $this->getNewStudentCounts();
        $approvedStudentsData = $this->getApprovedStudentCounts();

        $announcements = Announcement::getCurrentAnnouncements();

        return view('dashboard.ins_head_dashboard', compact('data', 'newStudentsData', 'approvedStudentsData', 'announcements'));
    }
    public function insDeoDashboard()
    {
        $udmData = UserDepartmentMapping::where('user_id', auth()->user()->user_id)->get()->toArray();
        $instituteIdsMapped = array_column($udmData, 'department_id');

        $stApplications = Student::fetchStudyCenterStudentNewApplications();

        $data = $this->getTotalBasedStudentCounts();
        $newStudentsData = $this->getNewStudentCounts();
        $approvedStudentsData = $this->getApprovedStudentCounts();

        $announcements = Announcement::getCurrentAnnouncements();

        return view('dashboard.ins_deo_dashboard', compact('data', 'newStudentsData', 'approvedStudentsData', 'stApplications', 'instituteIdsMapped', 'announcements'));
    }

    public function studentDashboard()
    {
        $username = auth()->user()->username;
        $data = Student::getStudentDetailsUsingRollNo($username);
        return view('dashboard.student_dashboard')->with($data);
    }
}
