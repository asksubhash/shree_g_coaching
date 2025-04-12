<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class UserDepartmentMapping extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    protected $guarded = [];

    public static function getUserMappedDepartmentCodes()
    {
        $departments = UserDepartmentMapping::where('user_id', Auth::user()->user_id)->where('record_status', 1)->get();
        $departments = $departments->toArray();

        $deparmentCodes = array_column($departments, 'department_id');

        return $deparmentCodes;
    }

    public static function getUserMappedInstitutes($userId)
    {
        return UserDepartmentMapping::select('user_department_mappings.*', 'ins.id as institute_id', 'ins.name', 'ins.institute_code')
            ->leftJoin('institutes as ins', 'ins.id', '=', 'user_department_mappings.department_id')
            ->where('user_id', $userId)
            ->get();
    }
}
