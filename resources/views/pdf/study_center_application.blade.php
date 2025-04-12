@extends('layouts.pdf_generator_layout')

@section('content')

<div>
    <div class="text-center" style="margin-bottom: 10px;">
        <img src="{{ asset('website_assets/images/sc-pdf-heading.jpg') }}" alt="Image" class="logo-img" />
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title stu-card-title mb-3">STUDY CENTER DETAIL</h4>
            <table class="w-100 table-for-students-data">
                <tr>
                    <td class="w-33"><strong class="form-label text-danger">Name Of Institute </strong></td>
                    <td>
                        <p class="mb-0 fw-bold">
                            {{ $scData->institute_name }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td class="w-33"><strong class="form-label">Institute Address</strong></td>
                    <td>
                        <p class="mb-0">
                            {{ isset($scData->city_name)?$scData->city_name:'' }},
                            {{ isset($scData->district_name)?$scData->district_name:'' }},
                            {{ isset($scData->state)?$scData->state_name:'' }} - {{ isset($scData->pin_code)?$scData->pin_code:'' }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong class="form-label">Property</strong>
                    </td>
                    <td>
                        <p class="mb-0">{{ isset($scData->property)?$scData->property:'' }}</p>
                    </td>

                </tr>

            </table>

        </div>
    </div>

    <div class="card">
        <div class="card-header card-header-light-bg">
            <h4 class="card-title stu-card-title mb-3">Personal Details</h4>
        </div>
        <div class="card-body">
            <table class="w-100 table table-for-students-data">
                <tr>
                    <td class="w-25">
                        <strong class="form-label">Name</strong>
                    </td>
                    <td class="w-50">
                        <p class="mb-0">
                            {{ isset($scData->name)?$scData->name:'' }}
                        </p>
                    </td>

                    <td rowspan="3" class="w-25 text-center" style="vertical-align: top;">
                        <img src="{{ asset('storage/'.Config::get('constants.files_storage_path')['STUDY_CENTER_PHOTO_VIEW_PATH'].'/' . $scData->passport_photo) }}" alt="Image" class="stu-profile-img" />
                    </td>
                </tr>
                <tr>
                    <td class="w-25">
                        <strong class="form-label">Email Id</strong>
                    </td>
                    <td class="w-50">
                        <p class="mb-0">{{ isset($scData->email_id)?$scData->email_id:'' }}</p>
                    </td>

                </tr>
                <tr>
                    <td class="w-25"><strong class="form-label">Contact Number</strong></td>
                    <td class="w-50">
                        <p class="mb-0">{{ isset($scData->contact_no)?$scData->contact_no:'' }}</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="text-center"><strong>Contact Person Address</strong></td>
                </tr>

                <tr>
                    <td>
                        <strong class="form-label">Address 1</strong>
                    </td>
                    <td>
                        <p class="mb-0">{{ isset($scData->address1)?$scData->address1:'' }}</p>
                    </td>
                </tr>
                <tr>
                    <td><strong class="form-label">Address 2</strong></td>
                    <td>
                        <p class="mb-0">{{ isset($scData->address2)?$scData->address2:'' }}</p>
                    </td>

                </tr>

                <tr>
                    <td><strong class="form-label">Education Qualification</strong></td>
                    <td>
                        <p class="mb-0">{{ isset($scData->education_qualification)?$scData->education_qualification:'' }}</p>
                    </td>
                </tr>
                <tr>
                    <td><strong class="form-label">Occupation</strong></td>
                    <td>
                        <p class="mb-0">{{ isset($scData->occupation)?$scData->occupation:'' }}</p>
                    </td>
                </tr>
                <tr>
                    <td><strong class="form-label">Nature Of Work</strong></td>
                    <td>

                        <p class="mb-0">{{ isset($scData->nature_of_work)?$scData->nature_of_work:'' }}</p>
                    </td>
                </tr>

            </table>

        </div>
    </div>
</div>
@endsection