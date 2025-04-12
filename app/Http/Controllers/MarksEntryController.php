<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use App\Models\Course;
use App\Models\MarksEntry;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Traits\FileUploadTrait;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\CourseSubjectMapping;
use App\Models\UserDepartmentMapping;
use Illuminate\Support\Facades\Config;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class MarksEntryController extends Controller
{
    use FileUploadTrait;

    public function index()
    {
        $userInstitutes = UserDepartmentMapping::where([
            'user_id' => Auth::user()->user_id,
            'record_status' => 1
        ])->get();

        $userInstitutes = $userInstitutes->toArray();

        $courses = Course::where([
            'record_status' => 1
        ])->whereIn('institute_id', array_column($userInstitutes, 'department_id'))->get();


        return view('marks_entry.index', [
            'page_title' => 'Marks Entry',
            'courses' => $courses
        ]);
    }

    function getMarksEntryList()
    {

        $marksData = MarksEntry::with('subject')->with('course')->with('institute')->orderBy('id', 'DESC')->groupBy('marks_entries.student_roll_no', 'marks_entries.exam_id', 'marks_entries.course_id')->get();

        return DataTables::of($marksData)
            ->addColumn('action', function ($data) {
                $button = "<a href='" . url('marks-entry/student-result/' . $data->exam_id . '/' . $data->student_roll_no) . "' class='btn btn-success btn-sm btnViewStudentResult' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='View Student Result'><i class='bx bx-file'></i> View Result</a> <button class='btn btn-danger btn-sm btnDeleteResult' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='Delete'><i class='bx bx-trash'></i></button>";
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function add()
    {
        $userInstitutes = UserDepartmentMapping::where([
            'user_id' => Auth::user()->user_id,
            'record_status' => 1
        ])->get();

        $userInstitutes = $userInstitutes->toArray();

        $courses = Course::where([
            'record_status' => 1
        ])->whereIn('institute_id', array_column($userInstitutes, 'department_id'))->get();

        // dd($courses);

        return view('marks_entry.add', [
            'page_title' => 'Add Marks Data',
            'courses' => $courses
        ]);
    }

    public function downloadTemplate($courseId, Request $request)
    {
        // Get the course details
        $course = Course::find($courseId);

        // Check if the course belongs to institute
        // PENDING

        // Get the subjects of courses
        $courseSubjectMappings = CourseSubjectMapping::with('subject')->where([
            'course_id' => $course->id
        ])->orderBy('id', 'ASC')->get();

        $courseSubjectsArray =  $courseSubjectMappings->toArray();

        if (count($courseSubjectsArray) < 1) {
            return 'No subjects are added in this course, please add subjects to proceed.';
        }

        // ------------------------------------------
        // GENERATE FILENAME
        $filename = str_replace([' ', ','], ['', ''], $course->course_name) . '_' . time();

        // ------------------------------------------
        // Generate the excel

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();

        // Set active sheet
        $spreadsheet->setActiveSheetIndex(0);

        // ------------------------------------------
        // Header Styling
        $headerStyling = array(
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '191919'],
                ],
            ],
            'font'  => array('bold'  => true, 'size' => '13'),
            'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
        );

        $headerCellStyling = array(
            'font'  => array('bold'  => true, 'size' => '11'),
            'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
        );


        // Convert the array size (index) to an Excel column name
        $headingEndColumn = Coordinate::stringFromColumnIndex(count($courseSubjectsArray) + 1);

        // HEADER
        $spreadsheet->getActiveSheet()->mergeCells('A1:' . $headingEndColumn . '1');
        $spreadsheet->getActiveSheet()->setCellValue('A1', $course->course_name);
        $spreadsheet->getActiveSheet()->getStyle('A1:' . $headingEndColumn . '1')->applyFromArray($headerStyling);

        $alphabet = 'B';

        // Generate the rows
        $spreadsheet->getActiveSheet()->setCellValue('A2', 'Student Roll No.');
        $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($headerCellStyling);
        // Set the width of column A to fit its content
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

        foreach ($courseSubjectsArray as $key => $courseSubject) {
            $spreadsheet->getActiveSheet()->setCellValue($alphabet . '2', $courseSubject['subject']['name'] . ' (' . $courseSubject['max_marks'] . ')');
            $spreadsheet->getActiveSheet()->getStyle($alphabet . '2')->applyFromArray($headerCellStyling);

            // Set the width of column to fit its content
            $spreadsheet->getActiveSheet()->getColumnDimension($alphabet)->setAutoSize(true);

            $alphabet++;
        }

        // -----------------------------------------
        // Create a writer object
        $writer = new Xlsx($spreadsheet);

        // Set headers for the response
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="' . $filename . '.xlsx"',
            'Cache-Control' => 'max-age=0',
        ];

        // Save the spreadsheet to a temporary file
        $tempFilePath = tempnam(sys_get_temp_dir(), 'your_prefix');
        $writer->save($tempFilePath);

        // Send the file as a download response
        return Response::download($tempFilePath, $filename . '.xlsx', $headers)
            ->deleteFileAfterSend(true);
    }

    // -------------------------------------------------
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'financial_year' => 'required',
            'course_id' => 'required',
            'upload_file' => 'required|mimes:xlsx,xls,csv',
        ]);
        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            // ------------------------------------------------
            // Get the course details
            $course = Course::find($request->course_id);

            if (!$course) {
                return Response::json([
                    'status' => false,
                    'message' => 'Invalid course id, this course is not available in our database'
                ]);
            }

            // Check if the course belongs to institute
            // PENDING

            // Get the subjects of courses
            $courseSubjectMappings = CourseSubjectMapping::with('subject')->where([
                'course_id' => $course->id
            ])->orderBy('id', 'ASC')->get();

            $courseSubjectsArray =  $courseSubjectMappings->toArray();

            if (count($courseSubjectsArray) < 1) {
                return Response::json([
                    'status' => false,
                    'message' => 'No subjects are added in this course, please add subjects and download the template again, and fill marks to proceed.'
                ]);
            }

            // ---------------------------------------------
            // Path
            $filePath = Config::get('constants.files_storage_path')['MARKS_ENTRY_UPLOAD_PATH'];

            // -----------------------------
            // Read Spreadsheet
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('upload_file')) {
                $marksFile = $this->uploadSingleFile($request->upload_file, $filePath, true);

                if ($marksFile['status'] == false) {
                    DB::rollBack();
                    return Response::json($marksFile);
                }

                $marksFileName =  $marksFile['filename'];
                $marksFilePath =  $marksFile['path'];
            }

            // ------------------------------------------------
            // Load the spreadsheet
            // $spreadsheet = IOFactory::load(storage_path("app/{$filePath}"));
            $spreadsheet = IOFactory::load(storage_path("app/" . $marksFilePath));

            // Get the active sheet
            $sheet = $spreadsheet->getActiveSheet();

            // Get all rows as an array
            $dataInArray = $sheet->toArray();
            $marksArray = array_slice($dataInArray, 2);

            // ---------------------------------------------
            $marksData = [];
            $subjectIds = array_column($courseSubjectsArray, 'subject_id');
            $maxMarks = array_column($courseSubjectsArray, 'max_marks');

            // print_r($subjectIds);
            // print_r($maxMarks);
            // die;

            foreach ($subjectIds as $key => $subjectId) {
                foreach ($marksArray as $key2 => $mark) {
                    $studentMarksData = [
                        'exam_id' => 1,
                        'institute_id' => $course->institute_id,
                        'course_id' => $course->id,
                        'student_roll_no' => $mark[0],
                        'max_marks' => $maxMarks[$key],
                        'marks_uploaded_file' => $marksFileName,
                        'created_by' => Auth::user()->user_id,
                        'updated_by' => Auth::user()->user_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $studentMarksData['subject_id'] = $subjectId;
                    $studentMarksData['marks_obtained'] = (isset($mark[$key + 1])) ? $mark[$key + 1] : 0;

                    array_push($marksData, $studentMarksData);
                }
            }

            // Sort the array based on 'student_roll_no'
            $sortedMarksData = array_values(Arr::sort($marksData, function ($student) {
                return $student['student_roll_no'];
            }));

            $insertionResult = MarksEntry::insert($sortedMarksData);

            if ($insertionResult) {
                DB::commit();
                return Response::json([
                    'status' => true,
                    'message' => 'Marks uploaded successfully.'
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

    public function studentResult($examId, $studentRollNo, Request $request)
    {
        $studentResult = MarksEntry::where([
            'exam_id' => $examId,
            'student_roll_no' => $studentRollNo
        ])->with('subject')->with('course')->with('institute')->orderBy('id', 'DESC')->get();


        if (count($studentResult->toArray()) < 1) {
            return back();
        }


        return view('marks_entry.student_result', [
            'page_title' => 'Student Result',
            'studentResult' => $studentResult
        ]);
    }
}
