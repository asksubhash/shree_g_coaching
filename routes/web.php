<?php

use App\Models\AcademicYear;
use App\Models\StudentDetail;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PDFController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\GetterController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdmitCardController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\GraduationController;
use App\Http\Controllers\HighSchoolController;
use App\Http\Controllers\MarksEntryController;
use App\Http\Controllers\InterSchoolController;
use App\Http\Controllers\ResultEntryController;
use App\Http\Controllers\StudyCenterController;
use App\Http\Controllers\DownloadResultController;
use App\Http\Controllers\auth\StudentAuthController;
use App\Http\Controllers\ExamStudentSetupController;
use App\Http\Controllers\ExamSubjectTimingController;
use App\Http\Controllers\master_setup\StateController;
use App\Http\Controllers\StudentApplicationController;
use App\Http\Controllers\master_setup\CourseController;
use App\Http\Controllers\master_setup\GenCodeController;
use App\Http\Controllers\master_setup\SubjectController;
use App\Http\Controllers\master_setup\CategoryController;
use App\Http\Controllers\master_setup\ExamSetupController;
use App\Http\Controllers\master_setup\InstituteController;
use App\Http\Controllers\master_setup\DepartmentController;
use App\Http\Controllers\master_setup\GenCodeGroupController;
use App\Http\Controllers\master_setup\DistrictMasterController;
use App\Http\Controllers\master_setup\AssignmentSetupController;
use App\Http\Controllers\master_setup\AdmissionSessionController;
use App\Http\Controllers\master_setup\AcademicYearSetupController;
use App\Http\Controllers\master_setup\AnnouncementController;
use App\Http\Controllers\master_setup\ClassMasterController;
use App\Http\Controllers\master_setup\ClassSubjectMappingController;
use App\Http\Controllers\master_setup\ExamAttendedController;
use App\Http\Controllers\master_setup\NonLanguageSubjectController;
use App\Http\Controllers\master_setup\StudyMaterialSetupController;
use App\Http\Controllers\StudentExaminationController;
use App\Http\Controllers\StudentRegistrationController;
use App\Http\Controllers\StudentResultController;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\StudentT;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// |--------------------------------------------------------------------------
// | SUPER ADMIN ROUTES
// |--------------------------------------------------------------------------
require_once __DIR__ . '/superadmin_routes.php';

// |--------------------------------------------------------------------------
// DASHBOARD CONTROLLER: View Dashboard page
// |--------------------------------------------------------------------------
Route::get('/ins_deo/dashboard', [DashboardController::class, 'insDeoDashboard'])->middleware('checkRole:INS_DEO');
Route::get('/ins_head/dashboard', [DashboardController::class, 'insHeadDashboard'])->middleware('checkRole:INS_HEAD');
Route::get('/chairperson/dashboard', [DashboardController::class, 'chairpersonDashboard'])->middleware('checkRole:CHAIRPERSON');
Route::get('/student/dashboard', [DashboardController::class, 'studentDashboard'])->middleware('checkRole:STUDENT');
Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->middleware('checkRole:ADMIN');

// ==========================================================
// High School Controller

Route::get('/student/high-school/add', [HighSchoolController::class, 'add'])->middleware('checkRole:STUDENT,ADMIN');
Route::get('/student/high-school/show/{id}', [HighSchoolController::class, 'show'])->middleware('checkRole:STUDENT');

Route::group(['prefix' => "high-school"], function () {

    Route::get('', [HighSchoolController::class, 'index'])->middleware('checkRole:INS_DEO,ADMIN');
    Route::post('/fetch-all', [HighSchoolController::class, 'fetchAll'])->middleware('checkRole:INS_DEO,ADMIN');

    Route::get('/add', [HighSchoolController::class, 'add'])->middleware('checkRole:STUDENT,INS_DEO');
    Route::post('/store', [HighSchoolController::class, 'store'])->middleware('checkRole:STUDENT,INS_DEO');

    Route::post('/delete', [HighSchoolController::class, 'delete'])->middleware('checkRole:INS_DEO,ADMIN');
    Route::get('/edit/{id}', [HighSchoolController::class, 'edit'])->middleware('checkRole:INS_DEO,ADMIN');
    Route::post('/update', [HighSchoolController::class, 'update'])->middleware('checkRole:INS_DEO,ADMIN');
});

Route::get('/high-school/new-applications', [HighSchoolController::class, 'adminNewApplication'])->middleware('checkRole:ADMIN');
Route::post('/high-school/new-applications/fetch-all-students', [HighSchoolController::class, 'fetchAllAdminNewApplication'])->middleware('checkRole:ADMIN');

Route::post('/high-school/approve', [HighSchoolController::class, 'approveStudent'])->middleware('checkRole:ADMIN');

Route::get('/high-school/students', [HighSchoolController::class, 'adminAllStudentApplications'])->middleware('checkRole:ADMIN');
// /all-students
Route::post('/high-school/students/fetch-all-students', [HighSchoolController::class, 'fetchAllAdminStudentApplications'])->middleware('checkRole:ADMIN');

Route::get('/high-school/show/{id}', [HighSchoolController::class, 'show'])->middleware('checkRole:ADMIN,INS_DEO');

// ==========================================================
// 12 class student operation 

Route::get('/student/inter/add', [InterSchoolController::class, 'add'])->middleware('checkRole:STUDENT');
Route::get('/student/inter/show/{id}', [InterSchoolController::class, 'show'])->middleware('checkRole:STUDENT');

Route::group(['prefix' => "inter"], function () {
    Route::get('', [InterSchoolController::class, 'index'])->middleware('checkRole:INS_DEO,ADMIN');

    Route::get('/add', [InterSchoolController::class, 'add'])->middleware('checkRole:STUDENT,INS_DEO');
    Route::post('/store', [InterSchoolController::class, 'store'])->middleware('checkRole:STUDENT,INS_DEO');

    Route::post('/delete', [InterSchoolController::class, 'delete'])->middleware('checkRole:INS_DEO,ADMIN');
    Route::post('/fetch-all', [InterSchoolController::class, 'fetchAll'])->middleware('checkRole:INS_DEO,ADMIN');
    Route::get('/edit/{id}', [InterSchoolController::class, 'edit'])->middleware('checkRole:INS_DEO,ADMIN');
    Route::post('/update', [InterSchoolController::class, 'update'])->middleware('checkRole:INS_DEO,ADMIN');
});

Route::get('/inter/show/{id}', [InterSchoolController::class, 'show'])->middleware('checkRole:ADMIN,INS_DEO');

Route::get('/inter/students', [InterSchoolController::class, 'adminAllStudentApplications'])->middleware('checkRole:ADMIN');
Route::post('/inter/students/fetch-all-students', [InterSchoolController::class, 'fetchAllAdminStudentApplications'])->middleware('checkRole:ADMIN');

Route::post('/inter/approve', [InterSchoolController::class, 'approveStudent'])->middleware('checkRole:ADMIN');

// Route::get('/new-applications/12th', [InterSchoolController::class, 'newApplication'])->middleware('checkRole:ADMIN');
// Route::post('/new_application/inter/fetch-all', [InterSchoolController::class, 'fetchAllNewApplication'])->middleware('checkRole:ADMIN');

// ==========================================================
// Graduation student controller
Route::get('/student/graduation/add', [GraduationController::class, 'add'])->middleware('checkRole:STUDENT');
Route::get('/student/graduation/show/{id}', [GraduationController::class, 'show'])->middleware('checkRole:STUDENT');

Route::group(['prefix' => "graduation"], function () {
    Route::get('', [GraduationController::class, 'index'])->middleware('checkRole:INS_DEO,ADMIN');
    Route::post('/delete', [GraduationController::class, 'delete'])->middleware('checkRole:INS_DEO,ADMIN');
    Route::post('/fetch-all', [GraduationController::class, 'fetchAll'])->middleware('checkRole:INS_DEO,ADMIN');
    Route::get('/edit/{id}', [GraduationController::class, 'edit'])->middleware('checkRole:INS_DEO,ADMIN');
    Route::post('/update', [GraduationController::class, 'update'])->middleware('checkRole:INS_DEO,ADMIN');

    Route::get('/add', [GraduationController::class, 'add'])->middleware('checkRole:STUDENT,INS_DEO');
    Route::post('/store', [GraduationController::class, 'store'])->middleware('checkRole:STUDENT,INS_DEO');
});

Route::get('/graduation/show/{id}', [GraduationController::class, 'show'])->middleware('checkRole:ADMIN,INS_DEO');

Route::get('/graduation/students', [GraduationController::class, 'adminAllStudentApplications'])->middleware('checkRole:ADMIN');
Route::post('/graduation/students/fetch-all-students', [GraduationController::class, 'fetchAllAdminStudentApplications'])->middleware('checkRole:ADMIN');

Route::post('/graduation/approve', [GraduationController::class, 'approveStudent'])->middleware('checkRole:ADMIN');

// ==========================================================
// Payment Controller
Route::group(['prefix' => "payment"], function () {
    Route::post('/student/fees', [PaymentController::class, 'studentFeesPayment'])->middleware('checkRole:INS_DEO,INS_HEAD');

    Route::get('/student/fees/show', [PaymentController::class, 'showStudentFeesPayment'])->middleware('checkRole:INS_DEO,ADMIN,INS_HEAD');

    Route::get('/fees/offline', [PaymentController::class, 'offlineFeesPayments'])->middleware('checkRole:INS_DEO,ADMIN');
    Route::post('/fees/offline/get-for-datatable', [PaymentController::class, 'fetchOfflineFeesPaymentsForDatatable'])->middleware('checkRole:INS_DEO,ADMIN,INS_HEAD');
});


// ==========================================================
// Complaint Controller
Route::get('/student/complaints', [ComplaintController::class, 'index'])->middleware('checkRole:STUDENT');
Route::post('/student/complaints/fetch-for-datatable', [ComplaintController::class, 'fetchForStudentDatatable'])->middleware('checkRole:STUDENT');
Route::post('/student/complaints/store', [ComplaintController::class, 'store'])->middleware('checkRole:STUDENT');
Route::post('/student/complaints/update', [ComplaintController::class, 'update'])->middleware('checkRole:STUDENT');
Route::post('/student/complaints/delete', [ComplaintController::class, 'destroy'])->middleware('checkRole:STUDENT');

Route::get('/admin/complaints/students/all', [ComplaintController::class, 'adminAllStudentComplaints'])->middleware('checkRole:ADMIN');
Route::post('/admin/complaints/students/fetch-for-datatable', [ComplaintController::class, 'fetchForAdminAllStudentCompDatatable'])->middleware('checkRole:ADMIN');

// ================================================
// USERS CONTROLLER
Route::get('/user-setup', [UserController::class, 'adminUserSetup'])->middleware('checkRole:ADMIN');
Route::post('/ajax/get/all-users', [UserController::class, 'getAllUsersList'])->middleware('checkRole:ADMIN,SUPERADMIN');
Route::post('/ajax/user/store', [UserController::class, 'storeUser'])->middleware('checkRole:ADMIN,SUPERADMIN');
Route::post('/ajax/user/update', [UserController::class, 'updateUser'])->middleware('checkRole:ADMIN,SUPERADMIN');
Route::post('/ajax/get/user-details', [UserController::class, 'userDetails'])->middleware('checkRole:ADMIN,SUPERADMIN');
Route::post('/ajax/user/delete', [UserController::class, 'deleteUser'])->middleware('checkRole:ADMIN,SUPERADMIN');

// ==========================================================
// DEPARTMENT CONTROLLER  
Route::get('/all-department', [DepartmentController::class, 'index'])->middleware('checkRole:ADMIN');
Route::post('/departments/add', [DepartmentController::class, 'addDepartment'])->middleware('checkRole:ADMIN');
Route::post('/departments/list', [DepartmentController::class, 'allDepartmentList'])->middleware('checkRole:ADMIN');
Route::post('/departments/delete', [DepartmentController::class, 'delete'])->middleware('checkRole:ADMIN');
Route::post('/departments/get-details', [DepartmentController::class, 'departmentDetails'])->middleware('checkRole:ADMIN');
Route::post('/departments/edit', [DepartmentController::class, 'update'])->middleware('checkRole:ADMIN');

// ==========================================================
// CATEGORY CONTROLLER
Route::get('/category-setup', [CategoryController::class, 'index'])->middleware('checkRole:ADMIN');
Route::post('/category/list', [CategoryController::class, 'getCategoryList'])->middleware('checkRole:ADMIN');
Route::post('/category/add', [CategoryController::class, 'addCategory'])->middleware('checkRole:ADMIN');
Route::post('/category/delete', [CategoryController::class, 'deleteCategory'])->middleware('checkRole:ADMIN');
Route::post('/category/get-details', [CategoryController::class, 'getDetails'])->middleware('checkRole:ADMIN');
Route::post('/category/edit', [CategoryController::class, 'updateCategory'])->middleware('checkRole:ADMIN');

// ==========================================================
// GEN CODE GROUP CONTROLLER
Route::get('gen-code-group-setup', [GenCodeGroupController::class, 'index'])->middleware('checkRole:ADMIN');
Route::post('/gen-code-group/list', [GenCodeGroupController::class, 'getGroupList'])->middleware('checkRole:ADMIN');
Route::post('/gen-code-group/add', [GenCodeGroupController::class, 'addGenCodeGroup'])->middleware('checkRole:ADMIN');

// ==========================================================
// GEN CODE CONTROLLER
Route::get('/gen-code-setup', [GenCodeController::class, 'index'])->middleware('checkRole:ADMIN');
Route::post('/gencode/list', [GenCodeController::class, 'GetGencodeList'])->middleware('checkRole:ADMIN');
Route::post('/gencode/add', [GenCodeController::class, 'addGenCode'])->middleware('checkRole:ADMIN');
Route::post('/gencode/delete', [GenCodeController::class, 'deleteGenCode'])->middleware('checkRole:ADMIN');
Route::post('/gencode/edit-details', [GenCodeController::class, 'editGenCode'])->middleware('checkRole:ADMIN');
Route::post('/gencode/edit', [GenCodeController::class, 'updateGenCode'])->middleware('checkRole:ADMIN');


// ==========================================================
// MASTER SETUP 
// ==========================================================
// State Controller
Route::get('/state-setup', [StateController::class, 'index'])->middleware('checkRole:ADMIN');
Route::post('/ajax/get/all-stats', [StateController::class, 'getStateList'])->middleware('checkRole:ADMIN');
Route::post('/ajax/state/store', [StateController::class, 'storeState'])->middleware('checkRole:ADMIN');
Route::post('/ajax/state/update', [StateController::class, 'updateState'])->middleware('checkRole:ADMIN');
Route::post('/ajax/get/state-details', [StateController::class, 'stateDetails'])->middleware('checkRole:ADMIN');
Route::post('/ajax/state/delete', [StateController::class, 'deleteState'])->middleware('checkRole:ADMIN');


// ==========================================================
// District Controller
Route::get('/district-setup', [DistrictMasterController::class, 'index'])->middleware('checkRole:ADMIN');
Route::post('/ajax/get/all-districts', [DistrictMasterController::class, 'getDistrictList'])->middleware('checkRole:ADMIN');
Route::post('/ajax/district/store', [DistrictMasterController::class, 'storeDistrict'])->middleware('checkRole:ADMIN');
Route::post('/ajax/district/update', [DistrictMasterController::class, 'updateDistrict'])->middleware('checkRole:ADMIN');
Route::post('/ajax/get/district-details', [DistrictMasterController::class, 'districtDetails'])->middleware('checkRole:ADMIN');
Route::post('/ajax/district/delete', [DistrictMasterController::class, 'deleteDistrict'])->middleware('checkRole:ADMIN');

// ==========================================================
// Institute Controller
Route::get('/institute-setup', [InstituteController::class, 'index'])->middleware('checkRole:ADMIN');
Route::post('/ajax/get/all-institutes', [InstituteController::class, 'getInstituteList'])->middleware('checkRole:ADMIN');
Route::post('/ajax/institute/store', [InstituteController::class, 'storeInstitute'])->middleware('checkRole:ADMIN');
Route::post('/ajax/institute/update', [InstituteController::class, 'updateInstitute'])->middleware('checkRole:ADMIN');
Route::post('/ajax/get/institute-details', [InstituteController::class, 'instituteDetail'])->middleware('checkRole:ADMIN');
Route::post('/ajax/institute/delete', [InstituteController::class, 'deleteInstitute'])->middleware('checkRole:ADMIN');

// ==========================================================
// Result Entry 
Route::get('result-entry', [ResultEntryController::class, 'index'])->middleware('checkRole:ADMIN');
Route::post('result-entry/get-datatable-data', [ResultEntryController::class, 'fetchDataForDatatable'])->middleware('checkRole:ADMIN');
Route::get('/result-entry/download-template', [ResultEntryController::class, 'downloadTemplate'])->middleware('checkRole:ADMIN');
Route::POST('result-entry/store', [ResultEntryController::class, 'importExcel'])->middleware('checkRole:ADMIN');

Route::get('result-entry/show/student-result', [ResultEntryController::class, 'showStudentResult'])->middleware('checkRole:ADMIN');
Route::get('result-entry/edit/student-result', [ResultEntryController::class, 'editStudentResult'])->middleware('checkRole:ADMIN')->name('result-entry.edit.student-result');
Route::post('result-entry/update/student-result', [ResultEntryController::class, 'updateStudentResult'])->middleware('checkRole:ADMIN');

// ==========================================================
// Course Controller
Route::get('/course-setup', [CourseController::class, 'index'])->middleware('checkRole:ADMIN');
Route::post('/ajax/get/all-courses', [CourseController::class, 'getCourseList'])->middleware('checkRole:ADMIN');
Route::post('/ajax/course/store', [CourseController::class, 'storeCourse'])->middleware('checkRole:ADMIN');
Route::post('/ajax/course/update', [CourseController::class, 'updateCourse'])->middleware('checkRole:ADMIN');
Route::post('/ajax/get/course-details', [CourseController::class, 'courseDetails'])->middleware('checkRole:ADMIN');
Route::post('/ajax/course/delete', [CourseController::class, 'deleteCourse'])->middleware('checkRole:ADMIN');

Route::post('/ajax/course/subject/store', [CourseController::class, 'storeCourseSubject'])->middleware('checkRole:ADMIN');
Route::post('/ajax/course/subject/delete', [CourseController::class, 'deleteCourseSubject'])->middleware('checkRole:ADMIN');

Route::post('/ajax/course/nl-subject/store', [CourseController::class, 'storeCourseNLSubject'])->middleware('checkRole:ADMIN');
Route::post('/ajax/course/nl-subject/delete', [CourseController::class, 'deleteCourseNLSubject'])->middleware('checkRole:ADMIN');

Route::post('/ajax/course/get/subjects-and-nl-subjects', [CourseController::class, 'getSubjectsAndNlSubjectsOfCourse'])->middleware('checkRole:STUDENT,INS_DEO,ADMIN');

Route::post('/ajax/get/subjects-and-nl-subjects', [CourseController::class, 'getSubjectsAndNlSubjects'])->middleware('checkRole:STUDENT,INS_DEO,ADMIN');

Route::post('/ajax/course/get-list-using-institute-id', [CourseController::class, 'getCourseListUsingInstituteId'])->middleware('checkRole:ADMIN');


// ==========================================================
// Admission Setup Controller
Route::get('/admission-sessions-setup', [AdmissionSessionController::class, 'index'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/get/all-admission-sessions-setup', [AdmissionSessionController::class, 'fetchForDatatable'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/admission-sessions-setup/store', [AdmissionSessionController::class, 'store'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/admission-sessions-setup/update', [AdmissionSessionController::class, 'update'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/get/admission-sessions-details', [AdmissionSessionController::class, 'fetchSingleDetails'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/admission-sessions-setup/delete', [AdmissionSessionController::class, 'delete'])->middleware('checkRole:ADMIN,INS_HEAD');

Route::post('/ajax/admission-sessions-setup/get-using-course-insitute-academic-year', [AdmissionSessionController::class, 'getUsingCourseinsituteAcademicYear'])->middleware('checkRole:STUDENT,INS_HEAD,INS_DEO,ADMIN');

// ==========================================================
// Study Material Setup Controller
Route::get('/study-material-setup', [StudyMaterialSetupController::class, 'index'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/get/all-study-material-setup', [StudyMaterialSetupController::class, 'fetchForDatatable'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/study-material-setup/store', [StudyMaterialSetupController::class, 'store'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/study-material-setup/update', [StudyMaterialSetupController::class, 'update'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/get/study-material-details', [StudyMaterialSetupController::class, 'fetchSingleDetails'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/study-material-setup/delete', [StudyMaterialSetupController::class, 'delete'])->middleware('checkRole:ADMIN,INS_HEAD');

Route::post('/ajax/get/subjects', [ClassSubjectMappingController::class, 'fetchSubject'])->middleware('checkRole:ADMIN,INS_HEAD');



// ==========================================================
// Assignment Setup Controller
Route::get('/assignments-setup', [AssignmentSetupController::class, 'index'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/get/all-assignments-setup', [AssignmentSetupController::class, 'fetchForDatatable'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/assignments-setup/store', [AssignmentSetupController::class, 'store'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/assignments-setup/update', [AssignmentSetupController::class, 'update'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/get/assignments-details', [AssignmentSetupController::class, 'fetchSingleDetails'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/assignments-setup/delete', [AssignmentSetupController::class, 'delete'])->middleware('checkRole:ADMIN,INS_HEAD');

// ==========================================================
// Exam Setup Controller
Route::get('/exams-setup', [ExamSetupController::class, 'index'])->middleware('checkRole:ADMIN');
Route::post('/ajax/get/all-exams-setup', [ExamSetupController::class, 'fetchForDatatable'])->middleware('checkRole:ADMIN');
Route::post('/ajax/exams-setup/store', [ExamSetupController::class, 'store'])->middleware('checkRole:ADMIN');
Route::post('/ajax/exams-setup/update', [ExamSetupController::class, 'update'])->middleware('checkRole:ADMIN');
Route::post('/ajax/get/exams-details', [ExamSetupController::class, 'fetchSingleDetails'])->middleware('checkRole:ADMIN');
Route::post('/ajax/exams-setup/delete', [ExamSetupController::class, 'delete'])->middleware('checkRole:ADMIN');

Route::get('/exams-setup/subjects', [ExamSetupController::class, 'examSubjects'])->middleware('checkRole:ADMIN');
Route::get('/exams-setup/subjects/question-setup', [ExamSetupController::class, 'examQuestionsSetup'])->middleware('checkRole:ADMIN');


// Text Question
Route::post('/exams-setup/subjects/question-setup/text-questions-datatable', [ExamSetupController::class, 'fetchForTextQuestionsDatatable'])->middleware('checkRole:ADMIN');
Route::post('/exams-setup/subjects/question-setup/store-text-question', [ExamSetupController::class, 'storeTextQuestion'])->middleware('checkRole:ADMIN');
Route::post('/exams-setup/subjects/question-setup/fetch-text-question-by-id', [ExamSetupController::class, 'fetchTextQuestionById'])->middleware('checkRole:ADMIN');
Route::post('/exams-setup/subjects/question-setup/update-text-question', [ExamSetupController::class, 'updateTextQuestion'])->middleware('checkRole:ADMIN');
Route::post('/exams-setup/subjects/question-setup/delete-text-question', [ExamSetupController::class, 'deleteTextQuestion'])->middleware('checkRole:ADMIN');

// MCQ Question
Route::post('/exams-setup/subjects/question-setup/mcq-questions-datatable', [ExamSetupController::class, 'fetchForMcqQuestionsDatatable'])->middleware('checkRole:ADMIN');
Route::post('/exams-setup/subjects/question-setup/store-mcq-question', [ExamSetupController::class, 'storeMcqQuestion'])->middleware('checkRole:ADMIN');
Route::post('/exams-setup/subjects/question-setup/fetch-mcq-question-by-id', [ExamSetupController::class, 'fetchMcqQuestionById'])->middleware('checkRole:ADMIN');
Route::post('/exams-setup/subjects/question-setup/update-mcq-question', [ExamSetupController::class, 'updateMcqQuestion'])->middleware('checkRole:ADMIN');
Route::post('/exams-setup/subjects/question-setup/delete-mcq-question', [ExamSetupController::class, 'deleteMcqQuestion'])->middleware('checkRole:ADMIN');

// ==========================================================
// Exam Attended Controller
Route::get('/exams-setup/students/exams-attended', [ExamAttendedController::class, 'index'])->middleware('checkRole:ADMIN');
Route::post('/exams-setup/students/exams-attended/get-datatable-data', [ExamAttendedController::class, 'fetchForDatatable'])->middleware('checkRole:ADMIN');

Route::get('/exams-setup/students/exams-attended/list', [ExamAttendedController::class, 'examAttendedList'])->middleware('checkRole:ADMIN');
Route::post('/exams-setup/students/exams-attended/list/get-datatable-data', [ExamAttendedController::class, 'fetchForExamAttendedListDatatable'])->middleware('checkRole:ADMIN');

Route::get('/exams-setup/students/exams-attended/view-student-result-details', [ExamAttendedController::class, 'viewStudentResultDetails'])->middleware('checkRole:ADMIN');

// ==========================================================
// Examl Subject Timings Setup
Route::post('/exam-subject-timing/store', [ExamSubjectTimingController::class, 'store'])->middleware('checkRole:ADMIN');
// Route::post('/exam-subject-timing/update', [ExamSubjectTimingController::class, 'update'])->middleware('checkRole:ADMIN');
Route::post('/academic-year-details/get', [ExamSubjectTimingController::class, 'fetchSingleDetails'])->middleware('checkRole:ADMIN');

// ==========================================================
// Exam Student Setup Controller
Route::get('/exams-setup/students', [ExamStudentSetupController::class, 'examStudents'])->middleware('checkRole:ADMIN');
Route::post('/exams-setup/all-students-for-enrollment', [ExamStudentSetupController::class, 'fetchExamStudentsForEnrollment'])->middleware('checkRole:ADMIN');
Route::post('/exams-setup/enroll-students-for-exam', [ExamStudentSetupController::class, 'storeExamStudentsForEnrollment'])->middleware('checkRole:ADMIN');

// ==========================================================
// Academic Year Setup Controller
Route::get('/academic-year-setup', [AcademicYearSetupController::class, 'index'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/get/all-academic-year-setup', [AcademicYearSetupController::class, 'fetchForDatatable'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/academic-year-setup/store', [AcademicYearSetupController::class, 'store'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/academic-year-setup/update', [AcademicYearSetupController::class, 'update'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/get/academic-year-details', [AcademicYearSetupController::class, 'fetchSingleDetails'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/academic-year-setup/delete', [AcademicYearSetupController::class, 'delete'])->middleware('checkRole:ADMIN,INS_HEAD');

// ==========================================================
// DEPARTMENT CONTROLLER  
Route::get('/announcements-setup', [AnnouncementController::class, 'index'])->middleware('checkRole:ADMIN');
Route::post('/announcements/store', [AnnouncementController::class, 'store'])->middleware('checkRole:ADMIN');
Route::post('/announcements/fetch-for-datatable', [AnnouncementController::class, 'fetchForDatatable'])->middleware('checkRole:ADMIN');
Route::post('/announcements/delete', [AnnouncementController::class, 'delete'])->middleware('checkRole:ADMIN');
Route::post('/announcements/get-details', [AnnouncementController::class, 'fetchSingleDetails'])->middleware('checkRole:ADMIN');
Route::post('/announcements/update', [AnnouncementController::class, 'update'])->middleware('checkRole:ADMIN');

// ==========================================================
// Subject Controller
Route::get('/subject-setup', [SubjectController::class, 'index'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/get/all-subjects', [SubjectController::class, 'getSubjectList'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/subject/store', [SubjectController::class, 'store'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/subject/update', [SubjectController::class, 'update'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/get/subject-details', [SubjectController::class, 'edit'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/subject/delete', [SubjectController::class, 'destroy'])->middleware('checkRole:ADMIN,INS_HEAD');

// ==========================================================
// Non language Subject Controller
Route::get('/non-language-subjects', [NonLanguageSubjectController::class, 'index'])->middleware('checkRole:ADMIN');
Route::post('/ajax/get/all-non-language-subjects', [NonLanguageSubjectController::class, 'getSubjectList'])->middleware('checkRole:ADMIN');
Route::post('/ajax/non-language-subjects/store', [NonLanguageSubjectController::class, 'store'])->middleware('checkRole:ADMIN');
Route::post('/ajax/non-language-subjects/update', [NonLanguageSubjectController::class, 'update'])->middleware('checkRole:ADMIN');
Route::post('/ajax/get/non-language-subjects-details', [NonLanguageSubjectController::class, 'edit'])->middleware('checkRole:ADMIN');
Route::post('/ajax/non-language-subjects/delete', [NonLanguageSubjectController::class, 'destroy'])->middleware('checkRole:ADMIN');

// ==========================================================
// Study center 
Route::get('/study-centers/new-applications', [StudyCenterController::class, 'newApplications'])->middleware('checkRole:ADMIN');
Route::get('/study-centers/new-applications/show/{id}', [StudyCenterController::class, 'showApplicationsDetails'])->middleware('checkRole:ADMIN');

Route::post('/study-centers/new-applications/approval', [StudyCenterController::class, 'approvalOrRejectionStore'])->middleware('checkRole:ADMIN');

Route::post('/study-center/fetch-new-registers', [StudyCenterController::class, 'fetchAllNewRegistered'])->middleware('checkRole:ADMIN');

Route::group(['prefix' => "study-center"], function () {
    Route::get('/registration', [StudyCenterController::class, 'register']);
    Route::post('/store', [StudyCenterController::class, 'store']);
    Route::get('/login', [StudyCenterController::class, 'login']);
});


// ==========================================================
// Class Master Controller
Route::get('/class-setup', [ClassMasterController::class, 'index'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/get/all-classes', [ClassMasterController::class, 'getSubjectList'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/class/store', [ClassMasterController::class, 'store'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/class/update', [ClassMasterController::class, 'update'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/get/class-details', [ClassMasterController::class, 'edit'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/class/delete', [ClassMasterController::class, 'destroy'])->middleware('checkRole:ADMIN,INS_HEAD');

// ==========================================================
// Class Subject Mapping Controller
Route::get('/class-subject-mapping', [ClassSubjectMappingController::class, 'index'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/get/all-class-subject-mapping', [ClassSubjectMappingController::class, 'getDataTableList'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/class-subject-mapping/store', [ClassSubjectMappingController::class, 'store'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/class-subject-mapping/update', [ClassSubjectMappingController::class, 'update'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/get/class-subject-mapping-details', [ClassSubjectMappingController::class, 'edit'])->middleware('checkRole:ADMIN,INS_HEAD');
Route::post('/ajax/class-subject-mapping/delete', [ClassSubjectMappingController::class, 'destroy'])->middleware('checkRole:ADMIN,INS_HEAD');



// ================================================
// Marks Entry Controller =============================
Route::get('/marks-entry', [MarksEntryController::class, 'index'])->middleware('checkRole:ADMIN');
Route::post('/marks-entry/get-all-list', [MarksEntryController::class, 'getMarksEntryList'])->middleware('checkRole:ADMIN');

Route::get('/marks-entry/download-template/{courseId}', [MarksEntryController::class, 'downloadTemplate'])->middleware('checkRole:ADMIN');
Route::get('/marks-entry/add', [MarksEntryController::class, 'add'])->middleware('checkRole:ADMIN');
Route::post('/marks-entry/store', [MarksEntryController::class, 'store'])->middleware('checkRole:ADMIN');

Route::get('/marks-entry/student-result/{examId}/{studentRollNo}', [MarksEntryController::class, 'studentResult'])->middleware('checkRole:ADMIN');


// ================================================
// Download Result Controller =============================
// Route::get('download/student-result/{examId}/{studentRollNo}', [DownloadResultController::class, 'downloadSingleStudentResult']);

// ==========================================================
// Download result controller
Route::get('download/student-result/{examId}/{studentRollNo}', [DownloadResultController::class, 'downloadSingleStudentResult']);

// ================================================
// Profile Controller
Route::get('/profile', [ProfileController::class, 'profile'])->middleware('checkRole:ADMIN,DEPARTMENT,HOD,CHAIRPERSON,STUDENT,INS_DEO,INS_HEAD');


// |--------------------------------------------------------------------------
// | ADMIN AND Update detail
Route::post('/ajax/profile/update', [LoginController::class, 'updateProfileDetail'])->middleware('checkRole:SUPERADMIN,ADMIN,DEPARTMENT,HOD,CHAIRPERSON,STUDENT,INS_DEO,INS_HEAD');
Route::post('/ajax/profile/change-password', [LoginController::class, 'changePassword'])->middleware('checkRole:SUPERADMIN,ADMIN,DEPARTMENT,HOD,CHAIRPERSON,STUDENT,INS_DEO,INS_HEAD');


// =================================================================
// CAPTCHA CONTROLLER
Route::get('/reload-captcha', [App\Http\Controllers\CaptchaController::class, 'reloadCaptcha']);

// =================================================================
// LOGIN CONTROLLER
Route::get('logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/check-login', [LoginController::class, 'checkLogin']);
Route::post('/student/check-login', [LoginController::class, 'studentCheckLogin']);
Route::get('/login', [LoginController::class, 'index'])->name('login');

// ERROR CONTROLLER
Route::get('/unauthorized-access', [ErrorController::class, 'unauthorizedAccess']);
Route::get('/page-maintenance', [ErrorController::class, 'pageMaintenance']);


// INSTITUTE HEAD 
Route::get('/add-student/{page?}/{student_code?}', [StudentRegistrationController::class, 'add'])->middleware('checkRole:INS_HEAD')->name('student.register');
// Route::post('/ajax/student/store', [StudentRegistrationController::class, 'store'])->middleware('checkRole:INS_HEAD');
Route::get('/all-students', [StudentRegistrationController::class, 'allStudentApplications'])->middleware('checkRole:INS_HEAD');
Route::post('/ajax/students/fetch-all-students', [StudentRegistrationController::class, 'studentDataTableList'])->middleware('checkRole:INS_HEAD');
Route::get('/student/view/detail/{id?}', [StudentRegistrationController::class, 'view'])->name('student.view')->middleware('checkRole:INS_HEAD');
Route::post('/ajax/student/store/personal_detail', [StudentRegistrationController::class, 'storePersonalDetail'])->middleware('checkRole:INS_HEAD');
Route::post('/ajax/student/store/academic_detail', [StudentRegistrationController::class, 'storeAcademicDetail'])->middleware('checkRole:INS_HEAD');
Route::post('/ajax/get/student-academic-detail', [StudentRegistrationController::class, 'studentAcademicDataTableList'])->middleware('checkRole:INS_HEAD');
Route::post('/ajax/get/student-academic-detail-edit', [StudentRegistrationController::class, 'studentAcademicDetail'])->middleware('checkRole:INS_HEAD');
Route::post('/ajax/student/update/academic_detail', [StudentRegistrationController::class, 'updateAcademicDetail'])->middleware('checkRole:INS_HEAD');
Route::post('/ajax/student/update/personal_detail', [StudentRegistrationController::class, 'updatePersonalDetail'])->middleware('checkRole:INS_HEAD');



// =================================================================
// DEFAULT ROUTE

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/result', [HomeController::class, 'result'])->name('result');
Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contact-us');
Route::get('/find-courses', [HomeController::class, 'findCourses'])->name('find-courses');
Route::get('/fetch-districts', [GetterController::class, 'fetchDistrict']);

// ===================================================
// Student Controller
Route::group(['prefix' => "student"], function () {
    Route::get('/login', [StudentAuthController::class,  'studentLogin'])->name('student-login');
    // Route::get('/register', [StudentAuthController::class,  'studentRegister'])->name('student-register');
    Route::post('/store', [StudentController::class, 'store']);

    // ===================================================
    Route::get('/my-profile', [StudentController::class,  'studentMyProfile'])->middleware('checkRole:STUDENT');
    // Route::get('/personal-details', [StudentController::class,  'studentPersonalDetails'])->middleware('checkRole:STUDENT');
    // Route::get('/course-details', [StudentController::class,  'studentCourseDetails'])->middleware('checkRole:STUDENT');
    Route::get('/study-material', [StudentController::class,  'studentStudyMaterial'])->middleware('checkRole:STUDENT');
});

// ===================================================
Route::get('/student/application', [StudentApplicationController::class,  'studentApplication'])->name('student-application');
Route::post('/student/application/store', [StudentApplicationController::class,  'storeStudentApplication']);

Route::get('/study-center/student/new-applications', [StudentApplicationController::class,  'studyCenterStudentNewApplications'])->middleware('checkRole:INS_DEO,INS_HEAD');
Route::post('/study-center/student/new-applications/fetch-for-datatable', [StudentApplicationController::class,  'fetchStudyCenterStudentNewApplications'])->middleware('checkRole:INS_DEO,INS_HEAD');
Route::get('/study-center/student/new-applications/show/{id}', [StudentApplicationController::class,  'showStudyCenterStudentNewApplications'])->middleware('checkRole:INS_DEO,INS_HEAD');

Route::post('/student/application/approval', [StudentApplicationController::class,  'studentApplicationApproval'])->middleware('checkRole:ADMIN');

// ===================================================
// student download result 
Route::post('check-student-result', [StudentController::class, 'checkStudentResult'])->name('check-student-result');
Route::get('student-result/show', [StudentController::class, 'showStudentResult']);

// ===================================================
// Assignment Controller
Route::get('/student/assignment', [AssignmentController::class,  'studentAssignments'])->middleware('checkRole:STUDENT');
Route::post('/student/assignment/upload', [AssignmentController::class,  'uploadAssignments'])->middleware('checkRole:STUDENT');

// ===================================================
// Assignment Controller
Route::get('/student/admit-card/download', [AdmitCardController::class,  'downloadstudentAdmitCard'])->middleware('checkRole:STUDENT');
Route::get('/student/admit-card', [AdmitCardController::class,  'studentAdmitCard'])->middleware('checkRole:STUDENT');


// ===================================================
// Assignment Controller
Route::get('/student/exam-zone', [StudentExaminationController::class,  'studentExamZone'])->middleware('checkRole:STUDENT');
Route::get('/student/exam/start', [StudentExaminationController::class,  'startExam'])->middleware('checkRole:STUDENT');
Route::post('/student/exam/answer/mcq', [StudentExaminationController::class,  'saveMCQExamAnswer'])->middleware('checkRole:STUDENT');
Route::post('/student/exam/answer/text', [StudentExaminationController::class,  'saveTextExamAnswer'])->middleware('checkRole:STUDENT');
Route::post('/student/exam/final-submit', [StudentExaminationController::class,  'finalSubmitExam'])->middleware('checkRole:STUDENT');

// ===================================================
// Student Result Controller
Route::get('/student/result', [StudentResultController::class,  'studentResult'])->middleware('checkRole:STUDENT');

// ---------------------------------------------------------
// PDF Controller
Route::get('/pdf/high-school/show/{id}', [PDFController::class, 'showHighSchoolApplication'])->middleware('checkRole:ADMIN,INS_DEO,INS_HEAD');
Route::get('/pdf/inter/show/{id}', [PDFController::class, 'showInterApplication'])->middleware('checkRole:ADMIN,INS_DEO,INS_HEAD');
Route::get('/pdf/graduation/show/{id}', [PDFController::class, 'showGraduationApplication'])->middleware('checkRole:ADMIN,INS_DEO,INS_HEAD');

Route::get('/pdf/study-centers/show/{id}', [PDFController::class, 'showStudyCenterApplication'])->middleware('checkRole:ADMIN');

// ---------------------------------------------------
// Enquiry Form 
Route::post('/enquiry/store', [EnquiryController::class, 'storeEnquiry']);
