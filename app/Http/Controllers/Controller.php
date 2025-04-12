<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Menu;
use App\Models\RoleMenuMapping;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        DB::statement("SET SQL_MODE=''");
    }
}
