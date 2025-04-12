<?php

namespace App\Http\Controllers\master_setup;

use App\Models\Course;
use App\Models\Subject;
use App\Models\Institute;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CourseSubjectMapping;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseNLSubjectMapping;
use App\Models\NonLanguageSubject;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index()
    {
        $subjects = Subject::where('record_status', 1)->get(['id', 'name']);
        $nlSubjects = NonLanguageSubject::where('record_status', 1)->get(['id', 'name']);
        $institutes = Institute::where('record_status', 1)->get();

        return view('master_setup.course_setup', [
            'page_title' => 'Course Setup',
            'subjects' => $subjects,
            'nlSubjects' => $nlSubjects,
            'institutes' => $institutes
        ]);
    }


    function getCourseList(Request $request)
    {

        $query = Course::select('courses.*');
        $query->with('institute');
        $query->with('courseSubjectMapping.subject');
        $query->with('courseNLSubjectMapping.nl_subject');
        if ($request->filter_institute) {
            $query->where('institute_id', $request->filter_institute);
        }
        $query->orderBy('courses.id', 'DESC');

        $allData = $query->get();

        return DataTables::of($allData)
            ->addColumn('action', function ($course) {
                $button = "<button class='btn btn-success btn-sm btnAddSubject' id='" . $course->id . "' data-toggle='tooltip' data-placement='left' title='Add Subjects'><i class='bx bx-file'></i></button> <button class='btn btn-info btn-sm btnAddNLSubject' id='" . $course->id . "' data-toggle='tooltip' data-placement='left' title='Add Non Language Subjects'><i class='bx bx-file-blank'></i></button> <button class='btn btn-warning btn-sm editCourseBtn' id='" . $course->id . "' data-toggle='tooltip' data-placement='left' title='Edit State'><i class='bx bx-edit'></i></button> <button class='btn btn-danger btn-sm deleteCourseBtn' id='" . $course->id . "' data-toggle='tooltip' data-placement='left' title='Delete State'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('status_desc', function ($course) {
                $status = ($course->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action'])
            ->make(true);
    }

    function storeCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'institute_id' => 'required',
            'course_for' => 'required',
            'course_name' => 'required',
            'course_code' => 'required',
            'duration' => 'required',
            'description' => 'required',
            'amount' => 'required',
            'status' => 'required',
            // "subject.*"  => "required",
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();
            $course = new Course();

            $course->institute_id = $request->institute_id;
            $course->course_for = $request->course_for;
            $course->course_name = $request->course_name;
            $course->course_code = $request->course_code;
            $course->duration = $request->duration;
            $course->description = $request->description;
            $course->amount = $request->amount;
            $course->record_status = $request->status;
            $course->created_by = Auth::user()->user_id;
            $course->created_on = now();
            $course->updated_by = Auth::user()->user_id;
            $course->updated_on = now();

            if ($course->save()) {
                // foreach ($request->subject as $key => $value) {
                //     $curseSubjectMapObj = new CourseSubjectMapping();
                //     $curseSubjectMapObj->course_id = $course->id;
                //     $curseSubjectMapObj->subject_id = $value;
                //     $curseSubjectMapObj->created_by = Auth::user()->user_id;
                //     $curseSubjectMapObj->created_at = now();
                //     $curseSubjectMapObj->save();
                // }
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Course added successfully.'
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

    // Delete Course Function 
    function deleteCourse(Request $request)
    {
        $id = base64_decode($request->id);
        $data = Course::where(['id' => $id])->first();
        if ($data) {
            $data->record_status = 0;
            $data->updated_by = Auth::user()->user_id;
            $data->updated_on = now();

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


    // Function for get details of Course for update
    function courseDetails(Request $request)
    {
        $id = base64_decode($request->id);
        $details = Course::where(['id' => $id])->select('*')->first();
        $subjects = CourseSubjectMapping::where('course_id', $id)->get(['subject_id'])->toArray();
        if ($details) {
            $output = [
                'status' => true,
                'data' => $details,
                'subjects' => $subjects
            ];
        } else {
            $output = [
                'status' => false,
                'message' => 'Something went wrong. Please try again or contact support team.'
            ];
        }

        return Response::json($output);
    }

    // Function for update Course
    function updateCourse(Request $request)
    {
        $id = base64_decode($request->course_id);
        $course = Course::find($id);
        if (!$course) {
            return Response::json([
                'status' => false,
                'message' => 'Course not found, please contact the support team'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'institute_id' => 'required',
                'course_for' => 'required',
                'course_name' => 'required',
                'course_code' => 'required',
                'duration' => 'required',
                'description' => 'required',
                'amount' => 'required',
                'status' => 'required',
                "subject.*"  => "required",
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
                    $course->institute_id = $request->institute_id;
                    $course->course_for = $request->course_for;
                    $course->course_name = $request->course_name;
                    $course->course_code = $request->course_code;
                    $course->duration = $request->duration;
                    $course->description = $request->description;
                    $course->amount = $request->amount;
                    $course->record_status = $request->status;
                    $course->updated_by = Auth::user()->user_id;
                    $course->updated_on = now();
                    $course->save();

                    // // Update or create subject mappings
                    // foreach ($request->subject as $value) {
                    //     // Before updateOrCreate
                    //     $CourseSubjectMapping = CourseSubjectMapping::updateOrCreate(
                    //         ['course_id' => $course->id, 'subject_id' => $value]
                    //     );
                    //     // Check if a new record was created
                    //     if (!$CourseSubjectMapping->wasRecentlyCreated) {
                    //         // Record already exists, manually set the updated_by and updated_at attributes
                    //         $CourseSubjectMapping->update([
                    //             'updated_by' => Auth::user()->user_id,
                    //             'updated_at' => now(),
                    //         ]);
                    //     } else {
                    //         // Record is new, set the created_by and created_at attributes
                    //         $CourseSubjectMapping->update([
                    //             'created_by' => Auth::user()->user_id,
                    //             'created_at' => now(),
                    //         ]);
                    //     }
                    // }
                    DB::commit();
                    return Response::json([
                        'status' => true,
                        'message' => 'Course updated successfully.'
                    ]);
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
     * Function to store course subject
     */
    function storeCourseSubject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hidden_course_id' => 'required',
            'subject_id' => 'required',
            'max_marks' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            $course = new CourseSubjectMapping();
            $course->course_id = $request->hidden_course_id;
            $course->subject_id = $request->subject_id;
            $course->max_marks = $request->max_marks;
            $course->created_by = Auth::user()->user_id;
            $course->created_at = now();
            $course->updated_by = Auth::user()->user_id;
            $course->updated_at = now();

            if ($course->save()) {

                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Subject added successfully.'
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
     * Function to store course non language subject
     */
    function storeCourseNLSubject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hidden_course_id' => 'required',
            'subject_id' => 'required',
            'max_marks' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            $course = new CourseNLSubjectMapping();
            $course->course_id = $request->hidden_course_id;
            $course->subject_id = $request->subject_id;
            $course->max_marks = $request->max_marks;
            $course->created_by = Auth::user()->user_id;
            $course->created_at = now();
            $course->updated_by = Auth::user()->user_id;
            $course->updated_at = now();

            if ($course->save()) {

                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Non language subject added successfully.'
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
     * Function to get subjects and non language subjects
     */
    function getSubjectsAndNlSubjectsOfCourse(Request $request)
    {
        $course_id = $request->course_id;

        $courseSubjects = CourseSubjectMapping::leftJoin('subjects as sub', 'sub.id', '=', 'course_subject_mappings.subject_id')->where('course_subject_mappings.course_id', $course_id)->get();

        $courseNLSubjects = CourseNLSubjectMapping::leftJoin('non_language_subjects as sub', 'sub.id', '=', 'course_nl_subject_mappings.subject_id')->where('course_nl_subject_mappings.course_id', $course_id)->get();

        $output = [
            'status' => true,
            'data' => [
                'courseSubjects' => $courseSubjects,
                'courseNLSubjects' => $courseNLSubjects,
            ]
        ];

        return Response::json($output);
    }

    /**
     * Function to get subjects and non language subjects
     */
    function getSubjectsAndNlSubjects(Request $request)
    {
        $subjects = Subject::where('record_status', 1)->get();
        $nlSubjects = NonLanguageSubject::where('record_status', 1)->get();

        $output = [
            'status' => true,
            'data' => [
                'subjects' => $subjects,
                'nlSubjects' => $nlSubjects,
            ]
        ];

        return Response::json($output);
    }

    /**
     * Function to get course list using institute id
     */
    function getCourseListUsingInstituteId(Request $request)
    {

        $query = Course::select('courses.*');
        $query->with('institute');
        $query->where('institute_id', $request->institute_id);
        $query->orderBy('courses.id', 'DESC');

        $allData = $query->get();
        return Response::json([
            'status' => true,
            'data' => [
                'courses' => $allData
            ]
        ]);
    }
}
