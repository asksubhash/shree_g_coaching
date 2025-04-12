<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\UserDepartmentMapping;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    use FileUploadTrait;

    public function studentFeesPayment(Request $request)
    {

        // Validation rules
        $validationRules = [
            'academic_year' => 'required|string',
            'admission_session' => 'required|string',
            'class_id' => 'required|string',
            'subjects' => 'required|string',
            'student_application_no' => 'required',
            'payment_type' => 'required',
            'payment_method' => 'required_if:payment_type,OFFLINE|in:UPI,BANK,CASH',
            'upi_name' => 'required_if:payment_method,UPI',
            'payment_through' => 'required_if:payment_method,BANK',
            'receipt_no' => 'required_if:payment_method,CASH',
            'payment_date' => 'required|date',
            'amount' => 'required|string',
            'transaction_id' => 'required|string',
            'transaction_details' => 'nullable|string',
            'remarks' => 'nullable|string',
            'payment_document' => 'required|mimes:jpg,jpeg,pdf',
            'late_fees_amount' => 'required_if:late_fees_if_any,1',
        ];

        // Apply validation
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return Response::json([
                'status' => 'validation_errors',
                'message' => $validator->errors()->all()
            ]);
        }

        $paymentData = [
            'class_id' => $request->input('class_id'),
            'academic_year' => $request->input('academic_year'),
            'admission_session_id' => $request->input('admission_session'),
            'subject_id' => $request->input('subjects'),



            'student_application_no' => $request->input('student_application_no'),
            'payment_type' => $request->input('payment_type'),
            'payment_method' => $request->input('payment_method') ? $request->input('payment_method') : "",
            'upi_name' => $request->input('upi_name') ? $request->input('upi_name') : '',
            'payment_through' => $request->input('payment_through') ? $request->input('payment_through') : '',
            'receipt_no' => $request->input('receipt_no') ? $request->input('receipt_no') : '',
            'payment_date' => $request->input('payment_date'),
            'amount' => $request->input('amount'),
            'late_fees_if_any' => $request->input('late_fees_if_any'),
            'late_fees_amount' => '',
            'transaction_id' => $request->input('transaction_id'),
            'transaction_details' => $request->input('transaction_details'),
            'remarks' => $request->input('remarks'),
            'payment_added_by' => Auth::user()->user_id,
            'created_by' => Auth::user()->user_id,
            'created_at' => now()
        ];

        if ($request->input('late_fees_if_any') == 1) {
            $paymentData['late_fees_amount'] = $request->late_fees_amount;
        }

        $filePath = Config::get('constants.files_storage_path')['STUDENT_FEES_PAY_DOC_UPLOAD_PATH'];
        // CUSTOM TRAIT: Using the trait function to upload the file
        if ($request->file('payment_document')) {
            $paymentDocument = $this->uploadSingleFile($request->payment_document, $filePath, true);
            $paymentData['payment_document'] =  $paymentDocument['filename'];
        }

        $result = Payment::create($paymentData);

        if ($result) {

            // Update the student table with payment received
            Student::where([
                'application_no' => $request->input('student_application_no'),
            ])->update([
                'payment_received' => 1
            ]);

            DB::commit();
            return Response::json([
                'status' => true,
                'message' => 'Payment data saved successfully for application number: ' . $request->input('student_application_no')
            ]);
        } else {
            DB::rollBack();
            return Response::json([
                'status' => false,
                'message' => 'Server is not responding. Please try again.'
            ]);
        }
    }

    public function showStudentFeesPayment(Request $request)
    {
        $paymentId = $request->input('payment_id');
        if (!$paymentId) {
            return redirect('page-not-found');
        }

        $page_title = 'Payment Details';
        $paymentData = Payment::getStudentPaymentData(base64_decode($paymentId));

        if ($paymentData) {
            return view('payments.show_student_fees_payment', compact('paymentData', 'page_title'));
        } else {
            return redirect('page-not-found');
        }
    }

    public function offlineFeesPayments()
    {
        $page_title = 'Payments: Offline Fees';
        return view('payments.offline_fees', compact('page_title'));
    }
    public function fetchOfflineFeesPaymentsForDatatable(Request $request)
    {
        $query = Payment::select(
            'payments.*',
            'sm.name as student_name',
            'sm.application_no',
            'a.name as class_name',
            'b.academic_year',
            'c.session_name',
            'e.name as subject_name',
        );

        $query->leftJoin('class_masters as a', 'payments.class_id', '=', 'a.id');
        $query->leftJoin('academic_years as b', 'payments.academic_year', '=', 'b.id');
        $query->leftJoin('admission_sessions as c', 'payments.admission_session_id', '=', 'c.id');
        $query->leftJoin('subjects as e', 'payments.subject_id', '=', 'e.id');

        $query->leftJoin('students as sm', 'sm.application_no', '=', 'payments.student_application_no');

        if (in_array(auth()->user()->role_code, ['INS_DEO', 'INS_HEAD'])) {
            $udmData = UserDepartmentMapping::where('user_id', Auth::user()->user_id)->get()->toArray();
            $instituteIdsMapped = array_column($udmData, 'department_id');
            $query->whereIn('sm.institute_id', $instituteIdsMapped);
        }

        // $query->where('sm.is_approved', 1);
        $query->where('payments.record_status', 1);

        $data = $query->orderBy('payments.id', 'desc')->get();

        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                // <a href='" . url('high-school/edit/' . base64_encode($data->id)) . "' class='btn btn-warning btn-sm editUserBtn' data-toggle='tooltip' data-placement='left' title='Edit'><i class='bx bx-edit'></i></a> 

                $button = '';

                if ($data->payment_document) {
                    $button .= "<a href='" . asset('storage/' . Config::get('constants.files_storage_path')['STUDENT_FEES_PAY_DOC_VIEW_PATH'] . '/' . $data->payment_document) . "' class='btn btn-success btn-sm'  data-toggle='tooltip' data-placement='left' title='Payment Document' target='_BLANK'><i class='bx bx-file'></i> </a>";
                }
                return $button;
            })
            ->editColumn('status_desc', function ($data) {
                $status = ($data->record_status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                return $status;
            })
            ->rawColumns(['status_desc', 'action'])
            ->make(true);
    }
}
