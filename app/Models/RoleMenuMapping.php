<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleMenuMapping extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['code', 'role_code', 'menu_id', 'alias_menu', 'created_by', 'created_on', 'updated_by', 'updated_on', 'record_status'];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'menu_id', 'id');
    }
}
