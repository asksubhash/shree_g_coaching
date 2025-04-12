<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use App\Models\ExamSubjectTiming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExamSubjectTimingController extends Controller
{
    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required',
            'subject_id' => 'required',
            'subject_type' => 'required',
            'exam_date' => 'required',
            'exam_time' => 'required',
            'exam_duration' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            // Check if there is already a record
            $examTimingsData = ExamSubjectTiming::where([
                'exam_id' => $request->exam_id,
                'subject_id' => $request->subject_id,
                'subject_type' => $request->subject_type,
                'record_status' => 1
            ])->first();

            // If exist, then update the record
            if ($examTimingsData) {
                $ayData = [
                    'exam_id' => $request->exam_id,
                    'subject_id' => $request->subject_id,
                    'subject_type' => $request->subject_type,
                    'exam_date' => date('Y-m-d', strtotime($request->exam_date)),
                    'exam_time' => date('H:i:s', strtotime($request->exam_time)),
                    'exam_duration' => $request->exam_duration,
                    'record_status' => 1,
                    'updated_by' => Auth::user()->user_id,
                    'updated_at' => now(),
                ];

                $result = ExamSubjectTiming::where('id', $examTimingsData->id)->update($ayData);

                if ($result) {
                    DB::commit();
                    return Response::json([
                        'status' => true,
                        'message' => 'Timings updated successfully.'
                    ]);
                } else {
                    DB::rollBack();
                    return Response::json([
                        'status' => false,
                        'message' => 'Server is not responding. Please try again.'
                    ]);
                }
            } else {

                $ayData = [
                    'exam_id' => $request->exam_id,
                    'subject_id' => $request->subject_id,
                    'subject_type' => $request->subject_type,
                    'exam_date' => date('Y-m-d', strtotime($request->exam_date)),
                    'exam_time' => date('H:i:s', strtotime($request->exam_time)),
                    'exam_duration' => $request->exam_duration,
                    'record_status' => 1,
                    'created_by' => Auth::user()->user_id,
                    'created_at' => now(),
                    'updated_by' => Auth::user()->user_id,
                    'updated_at' => now(),
                ];

                $result = ExamSubjectTiming::create($ayData);

                if ($result) {
                    DB::commit();
                    return Response::json([
                        'status' => true,
                        'message' => 'Timings added successfully.'
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
    }

    /**
     * Function to fetch the single details
     */
    function fetchSingleDetails(Request $request)
    {
        $id = base64_decode($request->id);
        $details = ExamSubjectTiming::where(['id' => $id])->select('*')->first();
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
    // function update(Request $request)
    // {
    //     $id = base64_decode($request->announcements_id);
    //     $admSession = ExamSubjectTiming::find($id);

    //     if (!$admSession) {
    //         return Response::json([
    //             'status' => false,
    //             'message' => 'Data not found, please contact the support team'
    //         ]);
    //     } else {
    //         $validator = Validator::make($request->all(), [
    //             'exam_id' => 'required',
    //             'subject_id' => 'required',
    //             'subject_type' => 'required',
    //             'exam_date' => 'required',
    //             'exam_time' => 'required',
    //             'exam_duration' => 'required',
    //         ]);
    //         if ($validator->fails()) {
    //             return Response::json([
    //                 'status' => 'validation_errors',
    //                 'message' => $validator->errors()->all()
    //             ]);
    //         } else {
    //             DB::beginTransaction();
    //             try {
    //                 // Update course details

    //                 $ayData = [
    //                     'exam_id' => $request->exam_id,
    //                     'subject_id' => $request->subject_id,
    //                     'subject_type' => $request->subject_type,
    //                     'exam_date' => date('Y-m-d', strtotime($request->exam_date)),
    //                     'exam_time' => date('H:i:s', strtotime($request->exam_time)),
    //                     'exam_duration' => $request->exam_duration,
    //                     'record_status' => 1,
    //                     'updated_by' => Auth::user()->user_id,
    //                     'updated_at' => now(),
    //                 ];

    //                 $result = ExamSubjectTiming::where('id', $id)->update($ayData);

    //                 if ($result) {
    //                     DB::commit();
    //                     return Response::json([
    //                         'status' => true,
    //                         'message' => 'Timings updated successfully.'
    //                     ]);
    //                 } else {
    //                     DB::rollBack();
    //                     return Response::json([
    //                         'status' => false,
    //                         'message' => 'Server is not responding. Please try again.'
    //                     ]);
    //                 }
    //             } catch (\Exception $e) {
    //                 DB::rollBack();
    //                 return Response::json([
    //                     'status' => false,
    //                     'message' => 'Server is not responding. Please try again.'
    //                 ]);
    //             }
    //         }
    //     }
    // }
}
