<?php

namespace App\Http\Controllers\master_setup;

use Auth;
use Response;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    public function index()
    {
        return view('master_setup.announcements_setup', [
            'page_title' => 'Announcements Setup'
        ]);
    }


    function fetchForDatatable(Request $request)
    {

        $query = Announcement::select(
            'announcements.*'
        );

        $query->orderBy('announcements.id', 'DESC');

        $allData = $query->get();

        return DataTables::of($allData)
            ->addColumn('action', function ($data) {
                $button = "<button class='btn btn-warning btn-sm editAnnouncementsBtn' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Edit'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteAnnouncementsBtn' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Delete'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('status_desc', function ($data) {
                $status = ($data->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Deleted</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action', 'announcement'])
            ->make(true);
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'announcement' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            $ayData = [
                'announcement' => $request->announcement,
                'from_date' => date('Y-m-d', strtotime($request->from_date)),
                'to_date' => date('Y-m-d', strtotime($request->to_date)),
                'record_status' => $request->status,
                'created_by' => Auth::user()->user_id,
                'created_at' => now(),
                'updated_by' => Auth::user()->user_id,
                'updated_at' => now(),
            ];

            $result = Announcement::create($ayData);

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Annoucement added successfully.'
                ]);
            } else {
                DB::rollBack();
                return Response::json([
                    'status' => false,
                    'message' => 'Server is not responding. Please try again.'
                ]);
            }
        }
    }

    /**
     * Function to fetch the single details
     */
    function fetchSingleDetails(Request $request)
    {
        $id = base64_decode($request->id);
        $details = Announcement::where(['id' => $id])->select('*')->first();
        if ($details) {
            $output = [
                'status' => true,
                'data' => $details
            ];
        } else {
            $output = [
                'status' => false,
                'message' => 'Something went wrong. Please try again or contact support team.'
            ];
        }

        return Response::json($output);
    }

    /**
     * Function to update the details
     */
    function update(Request $request)
    {
        $id = base64_decode($request->announcements_id);
        $admSession = Announcement::find($id);

        if (!$admSession) {
            return Response::json([
                'status' => false,
                'message' => 'Announcement data not found, please contact the support team'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'announcement' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'status' => 'validation_errors',
                    'message' => $validator->errors()->all()
                ]);
            } else {
                DB::beginTransaction();
                try {
                    // Update course details

                    $ayData = [
                        'announcement' => $request->announcement,
                        'from_date' => date('Y-m-d', strtotime($request->from_date)),
                        'to_date' => date('Y-m-d', strtotime($request->to_date)),
                        'record_status' => $request->status,
                        'updated_by' => Auth::user()->user_id,
                        'updated_at' => now(),
                    ];

                    $result = Announcement::where('id', $id)->update($ayData);

                    if ($result) {
                        DB::commit();
                        return Response::json([
                            'status' => true,
                            'message' => 'Annoucement updated successfully.'
                        ]);
                    } else {
                        DB::rollBack();
                        return Response::json([
                            'status' => false,
                            'message' => 'Server is not responding. Please try again.'
                        ]);
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    return Response::json([
                        'status' => false,
                        'message' => 'Server is not responding. Please try again.'
                    ]);
                }
            }
        }
    }

    /**
     * Function to delete the details
     */
    function delete(Request $request)
    {
        $id = base64_decode($request->id);

        $data = Announcement::where(['id' => $id])->first();
        if ($data) {
            $data->record_status = 0;
            $data->updated_by = Auth::user()->user_id;
            $data->updated_at = now();

            if ($data->save()) {
                $output = [
                    'status' => true,
                    'message' => 'Record deleted successfully.'
                ];
            } else {
                $output = [
                    'status' => false,
                    'message' => 'Server is not responding. Please try again.'
                ];
            }
        } else {
            $output = [
                'status' => false,
                'message' => 'State not found. Please try again or contact support.'
            ];
        }

        return Response::json($output);
    }
}
