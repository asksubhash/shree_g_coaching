<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function unauthorizedAccess(){
        return view('errors.custom.unauthorized_access');
    }

    public function pageMaintenance(){
        return view('errors.custom.page_maintenance');
    }
}