<?php

namespace App\Models;

use App\Models\Menu;
use App\Models\Role;
use App\Models\SubMenu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resource extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    public function role()
    {
        return $this->hasOne(Role::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'resource_id', 'id');
    }

    public function submenu()
    {
        return $this->belongsTo(SubMenu::class, 'resource_id', 'id');
    }
}
