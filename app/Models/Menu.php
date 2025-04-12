<?php

namespace App\Models;

use App\Models\SubMenu;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id', 'id');
    }

    public function submenus()
    {
        return $this->hasMany(SubMenu::class, 'menu_id', 'id');
    }
}
