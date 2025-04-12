<?php 
namespace App\Traits;

use App\Models\Role;

trait MenuTrait{
    public function getLandingPage($role_code){
        $role = Role::where('role_code', $role_code)
                    ->first();

        return $role->resource->resource_link;
    }
}