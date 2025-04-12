<?php

namespace App\Http\Controllers;



use App\Models\MarksEntry;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Traits\FileUploadTrait;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\CourseSubjectMapping;
use App\Models\OldMarkEntry;
use App\Models\OldStudentData;
use App\Models\UserDepartmentMapping;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ResultEntryController extends Controller
{
    use FileUploadTrait;

    public function index()
    {
        $page_title = 'Result Entry';
        return view('old_result_entries.index', compact('page_title'));
    }

    public function fetchDataForDatatable(Request $request)
    {
        $query = DB::table('old_marks_entry as ome');
        $query->select('ome.*', 'osd.exam_date', 'osd.exam_dist', 'osd.publication_date');
        $query->leftJoin('old_student_data as osd', 'osd.roll_no', '=', 'ome.student_roll_no');
        $query->groupBy('ome.student_roll_no', 'exam_type');
        $allData = $query->orderBy('ome.student_roll_no', 'ASC');

        return DataTables::of($allData)
            ->addColumn('action', function ($data) {
                $button = "<a href='" . url('result-entry/edit/student-result?exam_type=' . $data->exam_type . '&student_roll_number=' . $data->student_roll_no) . "' class='btn btn-warning btn-sm'><i class='bx bx-download'></i> Edit</a> <a href='" . url('result-entry/show/student-result?exam_type=' . $data->exam_type . '&student_roll_number=' . $data->student_roll_no) . "' class='btn btn-success btn-sm btnViewStudentResult' id='" . $data->id . "' data-toggle='tooltip' data-placement='left' title='View Student Result'><i class='bx bx-file'></i> View</a>";
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function showStudentResult(Request $request)
    {
        $examType = $request->input('exam_type');
        $studentRollNo = $request->input('student_roll_number');

        $studentResult = DB::table('old_marks_entry as ome')
            ->select('ome.*', 'osd.exam_center', 'osd.exam_dist', 'osd.exam_name', 'osd.exam_date', 'osd.exam_division', 'osd.publication_date', 'osd.exam_controller')
            ->leftJoin('old_student_data as osd', 'osd.roll_no', '=', 'ome.student_roll_no')
            ->where([
                'ome.student_roll_no' => $studentRollNo,
                'ome.exam_type' => $examType
            ])
            ->get();

        if (count($studentResult->toArray()) < 1) {
            return back();
        }

        return view('old_result_entries.show_student_result', [
            'page_title' => 'Student Result',
            'studentResult' => $studentResult
        ]);
    }


    public function importExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            // Path
            $filePath = Config::get('constants.files_storage_path')['RESULT_ENTRY_UPLOAD_PATH'];

            // -----------------------------
            // Read Spreadsheet
            // CUSTOM TRAIT: Using the trait function to upload the file
            if ($request->file('file')) {
                $resultsFile = $this->uploadSingleFile($request->file, $filePath, true);

                if ($resultsFile['status'] == false) {
                    DB::rollBack();
                    return Response::json($resultsFile);
                }
                $resultsFileName =  $resultsFile['filename'];
                $resultsFilePath =  $resultsFile['path'];
            }

            // ------------------------------------------------
            // Load the spreadsheet
            $spreadsheet = IOFactory::load(storage_path("app/" . $resultsFilePath));

            // Get the active sheet
            $sheet = $spreadsheet->getActiveSheet();

            // Get all rows as an array
            $dataInArray = $sheet->toArray();
            $resultsArray = array_slice($dataInArray, 2);

            // ---------------------------------------------
            $uniqueArray = [];
            $resultEntryArray = [];

            $oldData = OldStudentData::all()->toArray();
            $oldStudentRollNos = array_column($oldData, 'roll_no');

            $currentDuplicateStuRollNo = [];

            foreach ($resultsArray as $key2 => $result) {
                $student_roll_no = $result[0];

                $studentData = [
                    'batch_id' => time(),
                    'student_roll_no' => $student_roll_no,
                    'student_name' => $result[1],
                    'father_name' => $result[2],
                    'exam_type' => $result[5],
                    'subject_name' => $result[10],
                    'total_mark' => $result[11],
                    'mark_obtained' => $result[12],
                    'record_status' => 1,
                    'created_by' => Auth::user()->user_id,
                    'updated_by' => Auth::user()->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                array_push($resultEntryArray, $studentData);

                // ==============================================================
                // Check if the student roll no does not exist in $oldStudentRollNos
                if (!in_array($student_roll_no, $oldStudentRollNos) && !in_array($student_roll_no, $currentDuplicateStuRollNo)) {

                    $studentArray = [
                        'roll_no' => $student_roll_no,
                        'student_name' => $result[1],
                        'father_name' => $result[2],
                        'exam_center' => $result[3],
                        'exam_dist' => $result[4],
                        'exam_name' => $result[5],
                        'exam_date' => $result[6],
                        'publication_date' => $result[7],
                        'exam_controller' => $result[8],
                        'exam_division' => $result[9],
                    ];

                    array_push($uniqueArray, $studentArray);
                }

                array_push($currentDuplicateStuRollNo, $student_roll_no);
            }

            // dd($resultEntryArray);
            // print_r($uniqueArray);
            // die;

            if (count($resultEntryArray) > 0) {
                $chunkSize = 500; // Set your desired chunk size

                // Chunk the $resultEntryArray and insert each chunk
                foreach (array_chunk($resultEntryArray, $chunkSize) as $chunk) {
                    $OldMarkEntryResult = OldMarkEntry::insert($chunk);

                    if (!$OldMarkEntryResult) {
                        DB::rollBack();
                        return Response::json([
                            'status' => false,
                            'message' => 'Error while saving old marks entry data. Please try again.'
                        ]);
                    }
                }
            }

            if (count($uniqueArray) > 0) {
                $chunkSize = 500; // Set your desired chunk size

                // Chunk the $uniqueArray and insert each chunk
                foreach (array_chunk($uniqueArray, $chunkSize) as $chunk) {
                    $OldStudentDataResult = OldStudentData::insert($chunk);

                    if (!$OldStudentDataResult) {
                        DB::rollBack();
                        return Response::json([
                            'status' => false,
                            'message' => 'Error while saving old student data. Please try again.'
                        ]);
                    }
                }
            }

            DB::commit();
            return Response::json([
                'status' => true,
                'message' => 'Marks entries uploaded successfully.'
            ]);
        }
        // try {
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     return Response::json([
        //         'status' => false,
        //         'message' => 'Server failed while processing data. Please try again. '
        //     ]);
        // }
    }


    public function downloadTemplate(Request $request)
    {

        // GENERATE FILENAME
        $filename = "resultUpload" . '_' . time();

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

        $columnArray = [
            'Student Roll No',
            'Student Name',
            'Father Name',
            'Exam Center',
            'Exam Dist',
            'Exam Name',
            'Exam Date',
            'Publication Date',
            'Exam Controller',
            'Exam Division',
            'Subject Name',
            'Total Marks',
            'Marks Obtained'
        ];


        // Convert the array size (index) to an Excel column name
        $headingEndColumn = Coordinate::stringFromColumnIndex(count($columnArray));

        // HEADER
        $spreadsheet->getActiveSheet()->mergeCells('A1:' . $headingEndColumn . '1');
        $spreadsheet->getActiveSheet()->setCellValue('A1', 'Result Entry');
        $spreadsheet->getActiveSheet()->getStyle('A1:' . $headingEndColumn . '1')->applyFromArray($headerStyling);

        $alphabet = 'A';

        // Generate the rows
        $spreadsheet->getActiveSheet()->getStyle('A2')->applyFromArray($headerCellStyling);
        // Set the width of column A to fit its content
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

        foreach ($columnArray as $key => $column) {
            $spreadsheet->getActiveSheet()->setCellValue($alphabet . '2', $column);
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

    public function editStudentResult(Request $request)
    {
        $examType = $request->input('exam_type');
        $studentRollNo = $request->input('student_roll_number');

        $studentResult = DB::table('old_marks_entry as ome')
            ->select('ome.*', 'osd.exam_center', 'osd.exam_dist', 'osd.exam_name', 'osd.exam_date', 'osd.exam_division', 'osd.publication_date', 'osd.exam_controller', 'osd.id as osd_id')
            ->leftJoin('old_student_data as osd', 'osd.roll_no', '=', 'ome.student_roll_no')
            ->where([
                'ome.student_roll_no' => $studentRollNo,
                'ome.exam_type' => $examType
            ])
            ->get();

        if (count($studentResult->toArray()) < 1) {
            return back();
        }

        return view('old_result_entries.edit_student_result', [
            'page_title' => 'Student Result',
            'studentResult' => $studentResult
        ]);
    }

    // -------------------------------------------------
    public function updateStudentResult(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_type' => 'required',
            'student_roll_no' => 'required',
            'student_name' => 'required',
            'father_name' => 'required',
            'exam_date' => 'required',
            'publication_date' => 'required',
            'exam_center' => 'required',
            'exam_dist' => 'required',
            'exam_division' => 'required',
            'exam_controller' => 'required'
        ]);
        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        } else {
            DB::beginTransaction();

            // Get the data first
            $examType = $request->input('exam_type');
            $studentRollNo = $request->input('student_roll_no');

            $studentResult = DB::table('old_marks_entry as ome')
                ->select('ome.*', 'osd.exam_center', 'osd.exam_dist', 'osd.exam_name', 'osd.exam_date', 'osd.exam_division', 'osd.publication_date', 'osd.exam_controller')
                ->leftJoin('old_student_data as osd', 'osd.roll_no', '=', 'ome.student_roll_no')
                ->where([
                    'ome.student_roll_no' => $studentRollNo,
                    'ome.exam_type' => $examType
                ])
                ->get();

            if (count($studentResult->toArray()) < 1) {
                DB::rollback();
                return Response::json([
                    'status' => false,
                    'message' => 'Invalid action performed. Please try again or contact support team'
                ]);
            }

            foreach ($studentResult as $key => $sr) {
                $studentSubjectData = [
                    // 'student_roll_no' => $request->student_roll_no,
                    'student_name' => $request->student_name,
                    'father_name' => $request->father_name,
                    // 'exam_type' => $request->exam_type,
                    'subject_name' => $request->input('subject_name_' . $sr->id),
                    'total_mark' => $request->input('total_mark_' . $sr->id),
                    'mark_obtained' => $request->input('mark_obtained_' . $sr->id),
                    'record_status' => 1,
                    'updated_by' => Auth::user()->user_id,
                    'updated_at' => now(),
                ];

                $ssResult = OldMarkEntry::where([
                    'student_roll_no' => $request->student_roll_no,
                    'exam_type' => $request->exam_type,
                    'id' => $sr->id
                ])->update($studentSubjectData);

                if (!$ssResult) {
                    DB::rollback();
                    return Response::json([
                        'status' => false,
                        'message' => 'Unable to update subjects data. Please try again or contact support team'
                    ]);
                }
            }

            $model = OldStudentData::findOrFail($request->osd_id); // Retrieve the model instance
            // Update the model attributes directly
            $model->student_name = $request->student_name;
            $model->father_name = $request->father_name;
            $model->exam_center = $request->exam_center;
            $model->exam_dist = $request->exam_dist;
            $model->exam_date = $request->exam_date;
            $model->publication_date = $request->publication_date;
            $model->exam_controller = $request->exam_controller;
            $model->exam_division = $request->exam_division;

            // Save the changes to the database
            $sResult = $model->save();

            if (!$sResult) {
                DB::rollback();
                return Response::json([
                    'status' => false,
                    'message' => 'Unable to update students data. Please try again or contact support team'
                ]);
            }

            DB::commit();
            return Response::json([
                'status' => true,
                'message' => 'Result updated uploaded successfully.'
            ]);
        }
    }
}
