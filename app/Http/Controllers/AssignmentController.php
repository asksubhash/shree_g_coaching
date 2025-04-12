<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Student;
use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Traits\FileUploadTrait;
use App\Models\StudentAssignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
    use FileUploadTrait;
    /**
     * Function for student assignments list
     */
    public function studentAssignments(Request $request)
    {
        $pageTitle = 'Assignments';

        $username = auth()->user()->username;
        $data = Student::getStudentDetailsUsingRollNo($username);

        // -=======================================
        // Get the student course details
        $assignments = Assignment::getStudentAssignments($data['studentDetails']->course);

        if ($data['studentDetails']) {
            return view('student.assignments', [
                'page_title' => $pageTitle,
                'user' => $data['studentDetails'],
                'assignments' => $assignments
            ]);
        } else {
            return redirect()->back();
        }
    }

    function uploadAssignments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'assignments_id' => 'required',
            'document' => 'required|mimes:pdf|max:20480',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            $assignmentsData = [
                'user_id' => Auth::user()->user_id,
                'assignment_id' => Crypt::decryptString(base64_decode($request->assignments_id)),
                'uploaded_on' => Carbon::now()->toDateString(),
                'record_status' => 1,
                'created_by' => Auth::user()->user_id,
                'created_at' => now(),
                'updated_by' => Auth::user()->user_id,
                'updated_at' => now(),
            ];

            $filePath = Config::get('constants.files_storage_path')['STUDENT_UPLOAD_ASSIGNMENTS_UPLOAD_PATH'];
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('document')) {
                $document = $this->uploadSingleFile($request->document, $filePath, true);
                $assignmentsData['document'] =  $document['filename'];
            }

            $result = StudentAssignment::create($assignmentsData);

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Assignment uploaded successfully.'
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
