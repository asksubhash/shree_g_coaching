<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use Response;
use Auth;
use App\Models\Exam;
use App\Models\ExamStudentsEnrollment;
use App\Models\ExamSubjectTiming;
use App\Models\NonLanguageSubject;
use App\Models\Question;
use App\Models\Student;
use App\Models\StudentAnswer;
use App\Models\StudentExamDetails;
use App\Models\Subject;
use App\Traits\FileUploadTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentExaminationController extends Controller
{
    use FileUploadTrait;
    /**
     * Function for student exam zone
     */
    public function studentExamZone(Request $request)
    {
        $pageTitle = 'Exam Zone';

        $username = auth()->user()->username;
        $data = Student::getStudentDetailsUsingRollNo($username);

        // -=======================================
        // Get the student course details
        // $assignments = Assignment::getStudentAssignments($user->course);

        // Get the student admit card
        $studentEnrollment = ExamStudentsEnrollment::getStudentExamEnrollmentUsingStudentId($data['studentDetails']['id']);

        // Get the exam details
        $exam = Exam::where('id', $studentEnrollment->exam_id)->first();

        if (!$exam) {
            $errorMessage = 'No Exams are not available';
            return view('errors.custom.common_error', compact('errorMessage'));
        }

        $subjects = $data['courseSubjects']->toArray();
        $subjectIds = array_column($subjects, 'subject_id');
        $studentSubjects = Subject::getStudentSubjectWithExamTimings($subjectIds, $studentEnrollment->exam_id);

        $nlSubjects = $data['nonLanguageSubjects']->toArray();
        $nlSubjectIds = array_column($nlSubjects, 'subject_id');
        $nlStudentSubjects = NonLanguageSubject::getNLStudentSubjectWithExamTimings($nlSubjectIds, $studentEnrollment->exam_id);


        if ($data) {
            return view('student.exam_zone', [
                'page_title' => $pageTitle,
                'user' => $data['studentDetails'],
                'studentEnrollment' => $studentEnrollment,
                'exam' => $exam,
                'studentSubjects' => $studentSubjects,
                'nlStudentSubjects' => $nlStudentSubjects,
                // 'assignments' => $assignments
            ]);
        } else {
            return redirect()->back();
        }
    }

    /** 
     * Function to start the exam
     */
    public function startExam(Request $request)
    {
        $examId = $request->exam_id;
        $subjectId = $request->subject_id;
        $subjectType = $request->subject_type;

        if ($examId && $subjectId && $subjectType) {
            $examId = base64_decode($examId);
            $subjectId = base64_decode($subjectId);
            $subjectType = base64_decode($subjectType);

            // Get the exam details
            $exam = Exam::where('id', $examId)->first();

            if (!$exam) {
                return redirect()->back();
            }

            // Get the student details
            $username = auth()->user()->username;
            $studentDetails = Student::getStudentDetailsUsingRollNo($username);

            if (!$studentDetails) {
                return redirect()->back();
            }

            // Get the exam subject timings
            $examSubjectTimings = ExamSubjectTiming::where([
                'exam_id' => $examId,
                'subject_id' => $subjectId,
                'subject_type' => $subjectType,
            ])->first();

            if (!$examSubjectTimings) {
                return redirect()->back();
            }

            $examRemainingTime = AppHelper::examRemainingTime($examSubjectTimings);
            if ($examRemainingTime < 1) {
                $errorMessage = 'Exam time over, you can not attend exam now.';
                return view('errors.custom.common_error', compact('errorMessage'));
            }
            // dd($examRemainingTime);

            $questionsCount = 0;
            $examMcqQuestions = Question::getExamMcqQuestions($examId, $subjectId, $subjectType);
            $questionsCount += count($examMcqQuestions);
            $examTextQuestions = Question::getExamTextQuestions($examId, $subjectId, $subjectType);
            $questionsCount += count($examTextQuestions);

            // Store the student exam details
            // Check if there is record
            $studentExamDetails = StudentExamDetails::where([
                'student_id' => $studentDetails['studentDetails']['id'],
                'exam_id' => $examId,
                'subject_id' => $subjectId,
                'subject_type' => $subjectType,
            ])->first();


            if (!$studentExamDetails) {
                $studentExamDetails = StudentExamDetails::create([
                    'student_id' => $studentDetails['studentDetails']['id'],
                    'exam_id' => $examId,
                    'start_date_time' => Carbon::now(),
                    'subject_id' => $subjectId,
                    'subject_type' => $subjectType,
                    'exam_status' => 'PENDING',
                    'record_status' => 1,
                    'created_by' => Auth::user()->user_id,
                    'created_at' => now(),
                    'updated_by' => Auth::user()->user_id,
                    'updated_at' => now(),
                ]);

                if (!$studentExamDetails) {
                    return redirect()->back();
                }
            }

            // Get student MCQ Answers
            $mcqAnswers = StudentAnswer::getStudentMCQAnswerUsingSEDID($studentExamDetails->id)->toArray();

            // Get student TEXT Answers
            $textAnswers = StudentAnswer::getStudentTextAnswerUsingSEDID($studentExamDetails->id)->toArray();

            // Set the student exam id in session
            session()->put('student_exam_id', Crypt::encrypt($studentExamDetails->id));

            $page_title = 'Exam';

            return view('student.start_exam', compact('examMcqQuestions', 'examTextQuestions', 'examSubjectTimings', 'exam', 'page_title', 'studentExamDetails', 'mcqAnswers', 'textAnswers', 'questionsCount'));
        } else {
            return redirect()->back();
        }
    }

    public function saveMCQExamAnswer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'optionSelected' => 'required',
            'questionId' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            $studentExamDetailsId = session()->get('student_exam_id');

            if (!$studentExamDetailsId) {
                return Response::json([
                    'status' => false,
                    'message' => "Invalid request made."
                ]);
            }

            $studentExamDetailsId = Crypt::decrypt($studentExamDetailsId);

            // Check if the answer is already submitted or not
            $studentAnswerDetails = StudentAnswer::where([
                'student_exam_details_id' => $studentExamDetailsId,
                'question_id' => $request->questionId,
            ])->first();

            if (!$studentAnswerDetails) {
                $insertData = [
                    'student_exam_details_id' => $studentExamDetailsId,
                    'question_id' => $request->questionId,
                    'answer' => $request->optionSelected,
                    'record_status' => 1,
                    'created_by' => Auth::user()->user_id,
                    'created_at' => now(),
                    'updated_by' => Auth::user()->user_id,
                    'updated_at' => now(),
                ];

                $result = StudentAnswer::create($insertData);
            } else {
                $updateData = [
                    'answer' => $request->optionSelected,
                    'updated_by' => Auth::user()->user_id,
                    'updated_at' => now(),
                ];

                $result = StudentAnswer::where([
                    'student_exam_details_id' => $studentExamDetailsId,
                    'question_id' => $request->questionId,
                ])->update($updateData);
            }


            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Answer saved successfully.'
                ]);
            } else {
                DB::rollBack();
                return Response::json([
                    'status' => false,
                    'message' => 'Error while saving answer, please refresh your page and try again.'
                ]);
            }
        }
    }

    public function saveTextExamAnswer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'questionId' => 'required',
            'answerFile' => 'required|mimes:pdf|max:10240',
        ]);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            $uploadedFileName = '';
            $studentExamDetailsId = session()->get('student_exam_id');

            if (!$studentExamDetailsId) {
                return Response::json([
                    'status' => false,
                    'message' => "Invalid request made."
                ]);
            }

            $studentExamDetailsId = Crypt::decrypt($studentExamDetailsId);

            // Check if the answer is already submitted or not
            $studentAnswerDetails = StudentAnswer::where([
                'student_exam_details_id' => $studentExamDetailsId,
                'question_id' => $request->questionId,
            ])->first();

            if (!$studentAnswerDetails) {
                $insertData = [
                    'student_exam_details_id' => $studentExamDetailsId,
                    'question_id' => $request->questionId,
                    'answer_document' => '',
                    'record_status' => 1,
                    'created_by' => Auth::user()->user_id,
                    'created_at' => now(),
                    'updated_by' => Auth::user()->user_id,
                    'updated_at' => now(),
                ];

                $filePath = Config::get('constants.files_storage_path')['STUDENT_QUESTION_ANSWER_UPLOAD_PATH'];
                // CUSTOM TRAIT: Using the trait function to upload the file
                if ($request->file('answerFile')) {
                    $document = $this->uploadSingleFile($request->answerFile, $filePath, true);
                    $insertData['answer_document'] =  $document['filename'];
                    $uploadedFileName = $document['filename'];
                }

                $result = StudentAnswer::create($insertData);
            } else {
                $updateData = [
                    'updated_by' => Auth::user()->user_id,
                    'updated_at' => now(),
                ];

                $filePath = Config::get('constants.files_storage_path')['STUDENT_QUESTION_ANSWER_UPLOAD_PATH'];
                // CUSTOM TRAIT: Using the trait function to upload the file
                if ($request->file('answerFile')) {
                    $document = $this->uploadSingleFile($request->answerFile, $filePath, true);
                    $updateData['answer_document'] =  $document['filename'];
                    $uploadedFileName = $document['filename'];
                }

                $result = StudentAnswer::where([
                    'student_exam_details_id' => $studentExamDetailsId,
                    'question_id' => $request->questionId,
                ])->update($updateData);
            }

            if ($result) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'answer_file' => asset('storage/' . Config::get('constants.files_storage_path')['STUDENT_QUESTION_ANSWER_VIEW_PATH'] . '/' . $document['filename']),
                    'message' => 'Answer saved successfully.'
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

    public function finalSubmitExam(Request $request)
    {
        DB::beginTransaction();

        $studentExamDetailsId = session()->get('student_exam_id');

        if (!$studentExamDetailsId) {
            return Response::json([
                'status' => false,
                'message' => "Invalid request made."
            ]);
        }

        $studentExamDetailsId = Crypt::decrypt($studentExamDetailsId);

        $updateData = [
            'exam_status' => 'SUBMITTED',
            'exam_submitted_on' => Carbon::now(),
            'updated_by' => Auth::user()->user_id,
            'updated_at' => now(),
        ];

        $result = StudentExamDetails::where([
            'id' => $studentExamDetailsId
        ])->update($updateData);

        if ($result) {
            DB::commit();
            return Response::json([
                'status' => true,
                'message' => 'Exam submitted successfully'
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
