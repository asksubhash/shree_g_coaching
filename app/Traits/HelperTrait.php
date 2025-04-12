<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Student;
use App\Models\UserDepartmentMapping;

trait HelperTrait
{
    public function getTotalBasedStudentCounts()
    {
        // Total Students
        $totalStudentsCount = Student::getStudentCount(['TEN', 'TWELVE', 'GRADUATION'], '');
        $tenthStudentsCount = Student::getStudentCount(['TEN'], '');
        $twelfthStudentsCount = Student::getStudentCount(['TWELVE'], '');
        $graduationStudentsCount = Student::getStudentCount(['GRADUATION'], '');

        return [
            'totalStudentsCount' => $totalStudentsCount,
            'tenthStudentsCount' => $tenthStudentsCount,
            'twelfthStudentsCount' => $twelfthStudentsCount,
            'graduationStudentsCount' => $graduationStudentsCount,
        ];
    }

    public function getNewStudentCounts()
    {
        // Total Students
        $totalStudentsCount = Student::getStudentCount(['TEN', 'TWELVE', 'GRADUATION'], 0);
        $tenthStudentsCount = Student::getStudentCount(['TEN'], 0);
        $twelfthStudentsCount = Student::getStudentCount(['TWELVE'], 0);
        $graduationStudentsCount = Student::getStudentCount(['GRADUATION'], 0);

        return [
            'totalStudentsCount' => $totalStudentsCount,
            'tenthStudentsCount' => $tenthStudentsCount,
            'twelfthStudentsCount' => $twelfthStudentsCount,
            'graduationStudentsCount' => $graduationStudentsCount,
        ];
    }

    public function getApprovedStudentCounts()
    {
        // Total Students
        $totalStudentsCount = Student::getStudentCount(['TEN', 'TWELVE', 'GRADUATION'], 1);
        $tenthStudentsCount = Student::getStudentCount(['TEN'], 1);
        $twelfthStudentsCount = Student::getStudentCount(['TWELVE'], 1);
        $graduationStudentsCount = Student::getStudentCount(['GRADUATION'], 1);

        return [
            'totalStudentsCount' => $totalStudentsCount,
            'tenthStudentsCount' => $tenthStudentsCount,
            'twelfthStudentsCount' => $twelfthStudentsCount,
            'graduationStudentsCount' => $graduationStudentsCount,
        ];
    }
}
