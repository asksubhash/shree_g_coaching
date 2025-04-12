<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Authentication;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class User extends Authenticatable implements AuditableContract
{
    use HasApiTokens, HasFactory, Notifiable;
    use Auditable;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function designation()
    {
        return $this->hasOne(Designation::class, 'designation', 'id');
    }

    public static function get_employee_users_list()
    {
        return User::where([
            'primary_role' => 'USER',
            'status' => 1
        ])->get();
    }

    public function authentication()
    {
        return $this->hasOne(Authentication::class, 'user_id', 'user_id');
    }

}
