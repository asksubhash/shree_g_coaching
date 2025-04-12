<div class="row">
    <div class="col-md-8 col-12">
        <div class="card p-3">
            <div class="table-responsive">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100 nowrap" id="academicDetail">
                        <thead>
                            <tr>
                                <th width="8%">#</th>
                                <th width="12%">Action</th>
                                <th width="10%">Medium <br>of Instruction</th>
                                <th width="10%">Academic Year</th>
                                <th width="10%">Admission <br>Session</th>
                                <th width="10%">Class</th>
                                <th width="10%">Subjects</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="card">
            <div class="card-header">
                <strong class="form_title"> Add New</strong>
            </div>
            <div class="card-body">
                <form action="javascript:void(0)" class="addForm" id="addForm">
                    @csrf
                    <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                    <input type="hidden" name="hidden_code" id="hidden_code">
                    <input type="hidden" name="student_id" value="{{ $user->id }}">


                    <div class=" col-12 mb-3">
                        <label class="form-label">Medium of Instruction <span class="text-danger">*</span></label>
                        <div class="radio_group d-block">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="medium_off_inst" id="hindi"
                                    value="HINDI">
                                <label class="form-check-label" for="hindi">Hindi</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="medium_off_inst" id="english"
                                    value="ENGLISH" >
                                <label class="form-check-label" for="english">English</label>
                            </div>
                        </div>
                    </div>

                    <div class=" col-12 mb-3">
                        <label for="academic_year" class="form-label">Academic Year<span class="text-danger">*</span>
                        </label>
                        <select name="academic_year" class=" form-select form-control" id="academic_year" required>
                            <option value="">--Select--</option>
                            @foreach ($academic_years as $ay)
                                <option value="{{ $ay->id }}">
                                    {{ $ay->academic_year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class=" col-12 mb-3">
                        <label for="admission_session" class="form-label">Admission Session<span
                                class="text-danger">*</span> </label>
                        <select name="admission_session" class=" form-select form-control" id="admission_session"
                            required>
                            <option value="">--Select--</option>
                            @foreach ($admission_sessions as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->session_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class=" col-12 mb-3">
                        <label for="class_id" class="form-label">Class<span class="text-danger">*</span>
                        </label>
                        <select name="class_id" class=" form-select form-control" id="class_id" required>
                            <option value="">--Select--</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class=" col-12 mb-3">
                        <label for="subjects" class="form-label">Subjects<span class="text-danger">*</span>
                        </label>
                        <select class="form-control select2" name="subjects[]" id="subjects" multiple required>
                        </select>
                    </div>
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-custom" id="formSubmitBtn"><i
                                class="bx bx-paper-plane"></i> Submit</button>
                        <button type="button" class="btn btn-default" onclick="resetForm()"><i
                                class="bx bx-refresh"></i> Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
