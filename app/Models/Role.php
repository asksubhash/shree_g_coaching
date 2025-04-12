<?php

namespace App\Models;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Role extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    public $timestamps = false;
    protected $fillable = ['role_code', 'role_name', 'status', 'resource_id'];

    public function users()
    {
        return $this->hasMany(Authentication::class, 'role_code', 'role_code');
    }

    public function resource()
    {
        return $this->hasOne(Resource::class, 'id', 'resource_id');
    }

    public static function getRolesForUserCreation()
    {
        return Role::where([
            'status' => 1,
        ])->where('role_name', '!=', 'SUPERADMIN')->orderBy('role_name', 'ASC')->get();
    }
}
