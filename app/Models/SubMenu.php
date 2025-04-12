<?php

namespace App\Models;

use App\Models\Menu;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubMenu extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id', 'id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }
}
