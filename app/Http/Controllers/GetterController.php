<?php

namespace App\Http\Controllers;

use App\Models\DistrictMaster;
use Illuminate\Http\Request;

class GetterController extends Controller
{
    public function fetchDistrict(Request $request)
    {
        $state = DistrictMaster::where("fk_state_code", $request->state)->get(["district_name", "district_code"]);
        if ($state) {
            return response()->json(['status' => true, 'data' => $state]);
        } else {
            return response()->json(['status' => false]);
        }
    }
}
