<?php

namespace App\Http\Controllers\master_setup;


use Auth;
use Response;
use App\Models\Exam;
use App\Models\Institute;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Traits\FileUploadTrait;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\NonLanguageSubject;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class ExamSetupController extends Controller
{
    use FileUploadTrait;

    public function index()
    {
        $institutes = Institute::getInstitutes();
        $academic_years = AcademicYear::getAcademicYears();

        return view('master_setup.exams_setup.exams_setup', [
            'page_title' => 'Exams Setup',
            'institutes' => $institutes,
            'academic_years' => $academic_years
        ]);
    }

    function fetchForDatatable(Request $request)
    {

        $query = Exam::select(
            'exams.*',
            'ay.academic_year as academic_year',
        );

        $query->leftJoin('academic_years as ay', 'exams.academic_year_id', '=', 'ay.id');
        $query->orderBy('exams.id', 'DESC');

        $allData = $query->get();

        return DataTables::of($allData)
            ->addColumn('action', function ($data) {
                $button = "";

                $button .= "<a href='" . url('exams-setup/subjects?exam_id=' . $data->id) . "' class='btn btn-primary btn-sm' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Exam Subjects Setup'><i class='bx bx-book'></i></a> ";

                $button .= "<a href='" . url('exams-setup/students?exam_id=' . $data->id) . "' class='btn btn-success btn-sm' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Exam Students Setup'><i class='bx bx-user'></i></a> ";

                $button .= "<a href='" . url('exams-setup/students/exams-attended?exam_id=' . $data->id) . "' class='btn btn-info btn-sm' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Exam Attended by Students'><i class='bx bx-book-open'></i></a> ";

                $button .= " <button class='btn btn-warning btn-sm editExamsBtn' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Edit'><i class='bx bx-edit'></i></button> ";

                $button .= " <button class='btn btn-danger btn-sm deleteExamsBtn' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Delete'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('status_desc', function ($data) {
                $status = ($data->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Deleted</span>';
                return $status;
            })
            ->addColumn('is_published_desc', function ($data) {
                $status = ($data->is_published == 1) ? '<span class="badge bg-success">Published</span>' : '<span class="badge bg-danger">Not Published</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action', 'is_published_desc'])
            ->make(true);
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year_id' => 'required',
            'admission_session_id' => 'required',
            'exam_name' => 'required',
            'exam_start_date' => 'required',
            'exam_centre' => 'required',
            'exam_district' => 'required',
            'status' => 'required',
            'is_published' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            $examsData = [
                'academic_year_id' => $request->academic_year_id,
                'admission_session_id' => $request->admission_session_id,
                'exam_name' => $request->exam_name,
                'exam_start_date' => date('Y-m-d', strtotime($request->exam_start_date)),
                'exam_centre' => $request->exam_centre,
                'exam_district' => $request->exam_district,
                'is_published' => $request->is_published,
                'record_status' => $request->status,
                'created_by' => Auth::user()->user_id,
                'created_at' => now(),
                'updated_by' => Auth::user()->user_id,
                'updated_at' => now(),
            ];

            $result = Exam::create($examsData);

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Exam added successfully.'
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
        $details = Exam::where(['id' => $id])->select('*')->first();
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
        $id = base64_decode($request->exams_id);
        $admSession = Exam::find($id);

        if (!$admSession) {
            return Response::json([
                'status' => false,
                'message' => 'Exam data not found, please contact the support team'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'academic_year_id' => 'required',
                'admission_session_id' => 'required',
                'exam_name' => 'required',
                'exam_start_date' => 'required',
                'exam_centre' => 'required',
                'exam_district' => 'required',
                'status' => 'required',
                'is_published' => 'required',
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

                    $examsData = [
                        'academic_year_id' => $request->academic_year_id,
                        'admission_session_id' => $request->admission_session_id,
                        'exam_name' => $request->exam_name,
                        'exam_start_date' => date('Y-m-d', strtotime($request->exam_start_date)),
                        'exam_centre' => $request->exam_centre,
                        'exam_district' => $request->exam_district,
                        'is_published' => $request->is_published,
                        'record_status' => $request->status,
                        'updated_by' => Auth::user()->user_id,
                        'updated_at' => now(),
                    ];

                    $result = Exam::where('id', $id)->update($examsData);

                    if ($result) {
                        DB::commit();
                        return Response::json([
                            'status' => true,
                            'message' => 'Exam updated successfully.'
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
        $data = Exam::where(['id' => $id])->first();
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

    public function examSubjects(Request $request)
    {
        $id = $request->exam_id;
        $exam = Exam::find($id);

        if (!$exam) {
            return redirect()->back();
        }

        $subjects = Subject::getSubjectWithExamTimings($id);
        $nlSubjects = NonLanguageSubject::getNLSubjectWithExamTimings($id);

        return view('master_setup.exams_setup.exams_subjects', [
            'page_title' => 'Exams Subjects: ' . $exam->exam_name,
            'exam' => $exam,
            'subjects' => $subjects,
            'nlSubjects' => $nlSubjects,
        ]);
    }

    public function examQuestionsSetup(Request $request)
    {
        $exam_id = $request->exam_id;
        $subject_id = $request->subject_id;
        $subject_type = $request->subject_type;

        if (!in_array($subject_type, ['LANGUAGE', 'NON_LANGUAGE'])) {
            return redirect()->back();
        }

        if ($subject_type == "LANGUAGE") {
            $subject = Subject::findOrFail($subject_id);
        } elseif ($subject_type == "NON_LANGUAGE") {
            $subject = NonLanguageSubject::findOrFail($subject_id);
        }

        $exam = Exam::find($exam_id);

        if (!$exam) {
            return redirect()->back();
        }

        // Get the text questions


        // Get the mcq questions

        return view('master_setup.exams_setup.question_setup', [
            'page_title' => 'Question Setup: Exams Name: ' . $exam->exam_name . ' - Subject Name: ' . $subject->name,
            'exam' => $exam,
            'exam_id' => $exam_id,
            'subject_type' => $subject_type,
            'subject_id' => $subject_id,
        ]);
    }

    function fetchForTextQuestionsDatatable(Request $request)
    {

        $query = Question::select('*');
        $query->orderBy('id', 'ASC');
        $query->where([
            'exam_id' => $request->exam_id,
            'subject_type' => $request->subject_type,
            'subject_id' => $request->subject_id,
            'question_type' => 'TEXT',
            'record_status' => 1
        ]);
        $allData = $query->get();

        return DataTables::of($allData)
            ->addColumn('action', function ($data) {
                $button = "";

                $button .= " <button class='btn btn-warning btn-sm editTextQuestion' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Edit'><i class='bx bx-edit'></i></button> ";

                $button .= " <button class='btn btn-danger btn-sm deleteTextQuestion' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Delete'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('question_image_button', function ($data) {
                $question_image_button = ($data->question_image) ? "<a href='" . asset('storage/' . Config::get('constants.files_storage_path')['QUESTION_IMAGE_VIEW_PATH'] . '/' . $data->question_image) . "' class='' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Document' target='_BLANK'>
                    <img src='" . asset('storage/' . Config::get('constants.files_storage_path')['QUESTION_IMAGE_VIEW_PATH'] . '/' . $data->question_image) . "' alt='Image' class='w-100 img-thumbnail' />
                </a>" : '';
                return $question_image_button;
            })
            ->rawColumns(['action', 'question_text', 'question_image_button'])
            ->make(true);
    }

    function fetchTextQuestionById(Request $request)
    {
        $id = base64_decode($request->id);
        $details = Question::where(['id' => $id])->select('*')->first();
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

    function storeTextQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_type' => 'required',
            'subject_type' => 'required',
            'subject_id' => 'required',
            'question_image' => 'mimes:jpg,png,jpeg|max:10240',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            if ($request->question_text == "" && !$request->file('question_image')) {
                DB::rollBack();
                return Response::json([
                    'status' => false,
                    'message' => 'Either question text or question image or both is required.'
                ]);
            }

            $insertData = [
                'exam_id' => $request->exam_id,
                'subject_type' => $request->subject_type,
                'subject_id' => $request->subject_id,
                'question_text' => ($request->question_text) ? $request->question_text : '',
                'question_type' => 'TEXT',
                'record_status' => 1,
                'created_by' => Auth::user()->user_id,
                'created_at' => now(),
                'updated_by' => Auth::user()->user_id,
                'updated_at' => now(),
            ];

            $filePath = Config::get('constants.files_storage_path')['QUESTION_IMAGE_UPLOAD_PATH'];
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('question_image')) {
                $question_image = $this->uploadSingleFile($request->question_image, $filePath, true);
                $insertData['question_image'] =  $question_image['filename'];
            }

            $result = Question::create($insertData);

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Question added successfully.'
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

    function updateTextQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hidden_id' => 'required',
            'subject_type' => 'required',
            'subject_type' => 'required',
            'subject_id' => 'required',
            'question_image' => 'mimes:jpg,png,jpeg|max:10240',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            if ($request->question_text == "" && !$request->file('question_image')) {
                DB::rollBack();
                return Response::json([
                    'status' => false,
                    'message' => 'Either question text or question image or both is required.'
                ]);
            }

            $updateData = [
                'exam_id' => $request->exam_id,
                'subject_type' => $request->subject_type,
                'subject_id' => $request->subject_id,
                'question_text' => ($request->question_text) ? $request->question_text : '',
                'record_status' => 1,
                'updated_by' => Auth::user()->user_id,
                'updated_at' => now(),
            ];

            $filePath = Config::get('constants.files_storage_path')['QUESTION_UPLOAD_PATH'];
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('question_image')) {
                $question_image = $this->uploadSingleFile($request->question_image, $filePath, true);
                $updateData['question_image'] =  $question_image['filename'];
            }

            $result = Question::where('id', base64_decode($request->hidden_id))->update($updateData);

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Question updated successfully.'
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

    function deleteTextQuestion(Request $request)
    {
        $id = base64_decode($request->id);
        $data = Question::where(['id' => $id])->first();
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

    // ====================================================
    function fetchForMcqQuestionsDatatable(Request $request)
    {

        $query = Question::select('*');
        $query->orderBy('id', 'ASC');
        $query->where([
            'exam_id' => $request->exam_id,
            'subject_type' => $request->subject_type,
            'subject_id' => $request->subject_id,
            'question_type' => 'MCQ',
            'record_status' => 1
        ]);
        $allData = $query->get();

        return DataTables::of($allData)
            ->addColumn('action', function ($data) {
                $button = "";

                $button .= " <button class='btn btn-warning btn-sm editMcqQuestion' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Edit'><i class='bx bx-edit'></i></button> ";

                $button .= " <button class='btn btn-danger btn-sm deleteMcqQuestion' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Delete'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->addColumn('question_image_button', function ($data) {
                $question_image_button = ($data->question_image) ? "<a href='" . asset('storage/' . Config::get('constants.files_storage_path')['QUESTION_IMAGE_VIEW_PATH'] . '/' . $data->question_image) . "' class='' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Document' target='_BLANK'>
                <img src='" . asset('storage/' . Config::get('constants.files_storage_path')['QUESTION_IMAGE_VIEW_PATH'] . '/' . $data->question_image) . "' alt='Image' class='w-100 img-thumbnail' />
            </a>" : '';
                return $question_image_button;
            })
            ->addColumn('option_1_image_button', function ($data) {
                $option_1_image_button = ($data->option_1_image) ? "<a href='" . asset('storage/' . Config::get('constants.files_storage_path')['QUESTION_OPTIONS_IMAGE_VIEW_PATH'] . '/' . $data->option_1_image) . "' class='' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Document' target='_BLANK'>
                <img src='" . asset('storage/' . Config::get('constants.files_storage_path')['QUESTION_OPTIONS_IMAGE_VIEW_PATH'] . '/' . $data->option_1_image) . "' alt='Image' class='w-100 img-thumbnail' />
            </a>" : '';
                return $option_1_image_button;
            })
            ->addColumn('option_2_image_button', function ($data) {
                $option_2_image_button = ($data->option_2_image) ? "<a href='" . asset('storage/' . Config::get('constants.files_storage_path')['QUESTION_OPTIONS_IMAGE_VIEW_PATH'] . '/' . $data->option_2_image) . "' class='' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Document' target='_BLANK'>
                <img src='" . asset('storage/' . Config::get('constants.files_storage_path')['QUESTION_OPTIONS_IMAGE_VIEW_PATH'] . '/' . $data->option_2_image) . "' alt='Image' class='w-100 img-thumbnail' />
            </a>" : '';
                return $option_2_image_button;
            })
            ->addColumn('option_3_image_button', function ($data) {
                $option_3_image_button = ($data->option_3_image) ? "<a href='" . asset('storage/' . Config::get('constants.files_storage_path')['QUESTION_OPTIONS_IMAGE_VIEW_PATH'] . '/' . $data->option_3_image) . "' class='' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Document' target='_BLANK'>
                <img src='" . asset('storage/' . Config::get('constants.files_storage_path')['QUESTION_OPTIONS_IMAGE_VIEW_PATH'] . '/' . $data->option_3_image) . "' alt='Image' class='w-100 img-thumbnail' />
            </a>" : '';
                return $option_3_image_button;
            })
            ->addColumn('option_4_image_button', function ($data) {
                $option_4_image_button = ($data->option_4_image) ? "<a href='" . asset('storage/' . Config::get('constants.files_storage_path')['QUESTION_OPTIONS_IMAGE_VIEW_PATH'] . '/' . $data->option_4_image) . "' class='' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Document' target='_BLANK'>
                <img src='" . asset('storage/' . Config::get('constants.files_storage_path')['QUESTION_OPTIONS_IMAGE_VIEW_PATH'] . '/' . $data->option_4_image) . "' alt='Image' class='w-100 img-thumbnail' />
            </a>" : '';
                return $option_4_image_button;
            })
            ->rawColumns(['action', 'question_text', 'question_image_button', 'option_1_image_button', 'option_2_image_button', 'option_3_image_button', 'option_4_image_button'])
            ->make(true);
    }

    function fetchMcqQuestionById(Request $request)
    {
        $id = base64_decode($request->id);
        $details = Question::where(['id' => $id])->select('*')->first();
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

    function storeMcqQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_type' => 'required',
            'subject_type' => 'required',
            'subject_id' => 'required',
            'correct_answer' => 'required',
            'question_image' => 'mimes:jpg,png,jpeg|max:10240',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            if ($request->question_mcq == "" && !$request->file('question_image')) {
                DB::rollBack();
                return Response::json([
                    'status' => false,
                    'message' => 'Either question text or question image or both is required.'
                ]);
            }

            $insertData = [
                'exam_id' => $request->exam_id,
                'subject_type' => $request->subject_type,
                'subject_id' => $request->subject_id,
                'question_text' => ($request->question_mcq) ? $request->question_mcq : '',
                'option_1' => ($request->option_1) ? $request->option_1 : '',
                'option_2' => ($request->option_2) ? $request->option_2 : '',
                'option_3' => ($request->option_3) ? $request->option_3 : '',
                'option_4' => ($request->option_4) ? $request->option_4 : '',
                'option_1_image' => '',
                'option_2_image' => '',
                'option_3_image' => '',
                'option_4_image' => '',
                'correct_answer' => $request->correct_answer,
                'question_type' => 'MCQ',
                'record_status' => 1,
                'created_by' => Auth::user()->user_id,
                'created_at' => now(),
                'updated_by' => Auth::user()->user_id,
                'updated_at' => now(),
            ];

            $filePath = Config::get('constants.files_storage_path')['QUESTION_IMAGE_UPLOAD_PATH'];
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('question_image')) {
                $question_image = $this->uploadSingleFile($request->question_image, $filePath, true);
                $insertData['question_image'] =  $question_image['filename'];
            }

            // Options Image ===============

            // Option 1 Image
            $filePath = Config::get('constants.files_storage_path')['QUESTION_OPTIONS_IMAGE_UPLOAD_PATH'];
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('option_1_image')) {
                $option_1_image = $this->uploadSingleFile($request->option_1_image, $filePath, true);
                $insertData['option_1_image'] =  $option_1_image['filename'];
            }

            // Option 2 Image
            $filePath = Config::get('constants.files_storage_path')['QUESTION_OPTIONS_IMAGE_UPLOAD_PATH'];
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('option_2_image')) {
                $option_2_image = $this->uploadSingleFile($request->option_2_image, $filePath, true);
                $insertData['option_2_image'] =  $option_2_image['filename'];
            }

            // Option 3 Image
            $filePath = Config::get('constants.files_storage_path')['QUESTION_OPTIONS_IMAGE_UPLOAD_PATH'];
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('option_3_image')) {
                $option_3_image = $this->uploadSingleFile($request->option_3_image, $filePath, true);
                $insertData['option_3_image'] =  $option_3_image['filename'];
            }

            // Option 4 Image
            $filePath = Config::get('constants.files_storage_path')['QUESTION_OPTIONS_IMAGE_UPLOAD_PATH'];
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('option_4_image')) {
                $option_4_image = $this->uploadSingleFile($request->option_4_image, $filePath, true);
                $insertData['option_4_image'] =  $option_4_image['filename'];
            }

            $result = Question::create($insertData);

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Question added successfully.'
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

    function updateMcqQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hidden_id' => 'required',
            'subject_type' => 'required',
            'subject_type' => 'required',
            'subject_id' => 'required',
            'question_image' => 'mimes:jpg,png,jpeg|max:10240',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            if ($request->question_text == "" && !$request->file('question_image')) {
                DB::rollBack();
                return Response::json([
                    'status' => false,
                    'message' => 'Either question text or question image or both is required.'
                ]);
            }

            $updateData = [
                'exam_id' => $request->exam_id,
                'subject_type' => $request->subject_type,
                'subject_id' => $request->subject_id,
                'question_text' => ($request->question_text) ? $request->question_text : '',
                'record_status' => 1,
                'updated_by' => Auth::user()->user_id,
                'updated_at' => now(),
            ];

            $filePath = Config::get('constants.files_storage_path')['QUESTION_UPLOAD_PATH'];
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('question_image')) {
                $question_image = $this->uploadSingleFile($request->question_image, $filePath, true);
                $updateData['question_image'] =  $question_image['filename'];
            }

            $result = Question::where('id', base64_decode($request->hidden_id))->update($updateData);

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Question updated successfully.'
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

    function deleteMcqQuestion(Request $request)
    {
        $id = base64_decode($request->id);
        $data = Question::where(['id' => $id])->first();
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
