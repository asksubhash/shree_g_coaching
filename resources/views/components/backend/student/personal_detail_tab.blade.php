<div class="row">
    <div class="col-12">
        <div class="py-3">
            <form action="javascript:void(0)" id="studentForm" name="studentForm">
                @csrf
                @if (isset($user) && !empty($user->id))
                    <input type="hidden" name="hiddenId" value="{{ base64_encode($user->id) }}">
                    <input type="hidden" name="operation_type" id="operation_type" value="EDIT">
                @else
                    <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                @endif
                <hr>
                <div class="row">
                    {{-- Personal Details --}}
                    <div class="col-12">
                        <div class="card border-danger border-top border-2 border-0">
                            <div class="card-header card-header-light-bg">
                                <h6 class="mb-0 card-title text-dark fw-bold">
                                    <i class="bx bx-user fw-bold"></i> Personal Details
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <label for="name" class="form-label">Student Name<span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            value="{{ isset($user) && isset($user->name) ? $user->name : '' }}"
                                            required>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <label for="father_name" class="form-label">Father Name<span
                                                class="text-danger">*</span> </label>
                                        <input type="text" class="form-control" name="father_name" id="father_name"
                                            value="{{ isset($user) && isset($user->father_name) ? $user->father_name : '' }}"
                                            required>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <label for="mother_name" class="form-label">Mother Name<span
                                                class="text-danger">*</span> </label>
                                        <input type="text" class="form-control" name="mother_name" id="mother_name"
                                            value="{{ isset($user) && isset($user->mother_name) ? $user->mother_name : '' }}"
                                            required>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <label for="gender" class="form-label">Gender<span
                                                class="text-danger">*</span>
                                        </label>
                                        <select name="gender" class=" form-select form-control" id="gender"
                                            required>
                                            <option value="">Select Gender</option>
                                            @foreach ($gender as $item)
                                                <option value="{{ $item->gen_code }}" @selected(isset($user) && $user->gender == $item->gen_code)>
                                                    {{ $item->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <label for="dob" class="form-label">DOB<span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control" name="dob" id="dob"
                                            value="{{ isset($user) && $user->dob ? $user->dob : '' }}" required>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <label for="religion" class="form-label">Religion<span
                                                class="text-danger">*</span>
                                        </label>
                                        <select name="religion" class=" form-select form-control" id="religion"
                                            required>
                                            <option value="">Select Religion</option>
                                            @foreach ($religion as $item)
                                                <option value="{{ $item->gen_code }}" @selected(isset($user) && $user->religion == $item->gen_code)>
                                                    {{ $item->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="address" class="form-label">Address<span
                                                class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control" name="address" id="address" required>{{ isset($user) && $user->address ? $user->address : '' }}</textarea>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <label for="pincode" class="form-label">Pincode<span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="number" min="0" class="form-control" name="pincode"
                                            value="{{ isset($user) && $user->pincode ? $user->pincode : '' }}"
                                            id="pincode" required>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <label for="state" class="form-label">State<span class="text-danger">*</span>
                                        </label>
                                        <select name="state" class=" form-select form-control" id="state"
                                            required>
                                            <option value="">Select State</option>
                                            @foreach ($states as $item)
                                                <option value="{{ $item->state_code }}"
                                                    {{ isset($user->state) && $user->state == $item->state_code ? 'selected' : '' }}>
                                                    {{ $item->state_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <label for="email" class="form-label">Email Address<span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control" name="email" id="email"
                                            value="{{ isset($user) && isset($user->email) ? $user->email : '' }}"
                                            required>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <label for="contact" class="form-label">Contact Number<span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control" name="contact" id="contact"
                                            value="{{ isset($user) && isset($user->contact_number) ? $user->contact_number : '' }}"
                                            required>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <label for="category" class="form-label">Category<span
                                                class="text-danger">*</span>
                                        </label>
                                        <select name="category" class=" form-select form-control" id="category"
                                            required>
                                            <option value="">Select Category</option>
                                            @foreach ($category as $item)
                                                <option value="{{ $item->gen_code }}" @selected(isset($user) && $user->category == $item->gen_code)>
                                                    {{ $item->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <label for="aadhar_number" class="form-label">Aadhar Card Number
                                        </label>
                                        <input type="text" class="form-control" name="aadhar_number"
                                            id="aadhar_number"
                                            value="{{ isset($user) && $user->aadhar_number ? $user->aadhar_number : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card border-danger border-top border-2 border-0">
                            <div class="card-header card-header-light-bg">
                                <h6 class="mb-0 card-title text-dark fw-bold">
                                    <i class="bx bx-file fw-bold"></i> Document submit options
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <label for="photo" class="form-label">Photo
                                        </label>
                                        <input type="file" class="form-control file" name="photo"
                                            id="photo" accept=".jpg, .jpeg, .png">

                                        @if (isset($user->photo) && !empty($user->photo))
                                            <div class="mt-3">
                                                <div>
                                                    <img src="{{ asset('storage/' . Config::get('constants.files_storage_path')['STUDENT_PHOTO_VIEW_PATH'] . '/' . $user->photo) }}"
                                                        alt="Image" class="img-thumbnail w-100" />
                                                </div>

                                                <div class="mt-2 text-center">
                                                    <a href="{{ asset('storage/' . Config::get('constants.files_storage_path')['STUDENT_PHOTO_VIEW_PATH'] . '/' . $user->photo) }}"
                                                        class=" btn btn-danger btn-sm" target="_BLANK">
                                                        <i class='bx bx-download'></i> Download
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 mb-3">
                                        <label for="aadhar" class="form-label">Aadhar
                                        </label>
                                        <input type="file" class="form-control file" name="aadhar"
                                            id="aadhar" accept=".jpg, .jpeg, .pdf">

                                        @if (isset($user->aadhar) && !empty($user->aadhar))
                                            <div class="mt-3">
                                                <div>
                                                    <object
                                                        data="{{ asset('storage/' . Config::get('constants.files_storage_path')['STUDENT_AADHAAR_VIEW_PATH'] . '/' . $user->aadhar) }}"
                                                        class="img-thumbnail w-100" style="height: 280px;">
                                                    </object>
                                                </div>

                                                <div class="mt-2 text-center">
                                                    <a href="{{ asset('storage/' . Config::get('constants.files_storage_path')['STUDENT_AADHAAR_VIEW_PATH'] . '/' . $user->aadhar) }}"
                                                        class=" btn btn-danger btn-sm" target="_BLANK">
                                                        <i class='bx bx-download'></i> Download
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    @if (!isset($user))
                    <button class=" btn btn-inverse-dark" type="button" onclick="resetForm()"> <i
                        class=" bx bx-reset"></i>
                    Reset</button>
                    @endif
                    <button class=" btn btn-danger" type="submit">
                        @if (isset($user) && !empty($user->id))
                            <i class=" bx bx-pencil"></i>
                            Update
                        @else
                            <i class=" bx bx-planet"></i>
                            Submit
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
