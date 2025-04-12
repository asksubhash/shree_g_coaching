<?php

namespace App\Http\Controllers\superadmin;
use App\Http\Controllers\Controller;

use App\Models\LoginDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LoginDetailController extends Controller
{
    public function index()
    {
        return view('superadmin.login-details');
    }

    // Function to get all country in for data table;
    public function getAllLoginDetails()
    {

        $data = LoginDetail::with('user','userAuth')->orderBy('login_datetime', 'desc');
        return DataTables::eloquent($data)
            ->addColumn('name', function ($data) {
               return (isset($data->user->f_name) && isset($data->user->l_name)) ?$data->user->f_name.'  '.$data->user->l_name:'NA';
            })
            ->addColumn('role', function ($data) {
                return (isset($data->userAuth->role->role_name))?$data->userAuth->role->role_name:'NA';
            })
            ->make(true);
    }
}
