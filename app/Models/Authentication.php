<?php

namespace App\Models;

use App\Models\Role;
use App\Models\User;
use App\Models\EmployeeSurvey;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Authentication extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = false;

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_code', 'role_code');
    }

    public function userDetail()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function designation()
    {
        return $this->hasOne(Designation::class, 'designation', 'id');
    }



    public function departmentMapping()
    {
        return $this->hasOne(UserDepartmentMapping::class, 'user_id', 'user_id');
    }

    public function institute()
    {
        return $this->hasOneThrough(
            Institute::class,            // Final target model
            UserDepartmentMapping::class, // Intermediate model
            'user_id',                   // Foreign key on UserDepartmentMapping (referencing authentication.user_id)
            'id',                         // Primary key on Institute
            'user_id',                    // Foreign key on Authentication (referencing users.id)
            'department_id'               // Foreign key on UserDepartmentMapping (referencing institutes.id)
        );
    }
}
