@extends('layouts.master_layout')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="page-content">

        <!-- Content Header (Page header) -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">

            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url()->to('admin/dashboard') }}"><i
                                    class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="row">
            <div class="col-md-8 col-12">
                <div class="card p-3">

                    <div class="table-responsive">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped w-100 nowrap" id="state-datatable">
                                <thead>
                                    <tr>
                                        <th width="8%">S. No.</th>
                                        <th width="12%">Action</th>
                                        <th width="10%">State Name</th>
                                        <th width="10%">District Name</th>
                                        <th width="10%">Status</th>
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
                        <strong class="form_title"> Add District</strong>
                    </div>
                    <div class="card-body">
                        <form action="javascript:void(0)" class="districtForm" id="districtForm">
                            @csrf
                            <input type="hidden" name="operation_type" id="operation_type" value="ADD">
                            <input type="hidden" name="district_id" id="district_id">
                            <div class="mb-3">
                                <label class="form-label" for="district_name">District Name <strong
                                        class="text-danger">*</strong></label>
                                <input type="text" class="form-control" name="district_name" id="district_name">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="state">State<strong
                                                class="text-danger">*</strong></label>
                                        <select name="state" id="state" class="form-control" required>
                                            <option value="">--Select--</option>
                                            @foreach ($states as $key => $state)
                                                <option value="{{ $state->id }}">{{ $state->state_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="status">Status <strong
                                                class="text-danger">*</strong></label>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="">--Select--</option>
                                            @foreach (Config::get('constants.status') as $key => $status)
                                                <option value="{{ $key }}">{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 text-center">
                                <button type="submit" class="btn btn-primary" id="formSubmitBtn"><i
                                        class="bx bx-paper-plane"></i> Submit</button>
                                <button type="button" class="btn btn-default" onclick="formReset()"><i
                                        class="bx bx-refresh"></i> Reset</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('pages-scripts')
    <script>
        var stateDataTable = $('#state-datatable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: true,
            scrollX: true,
            scrollCollapse: true,
            "ajax": {
                url: base_url + "/ajax/get/all-districts",
                type: 'POST',
                data: function(d) {
                    d._token = $('meta[name=csrf-token]').attr('content');
                }
            },
            "columns": [{
                    data: null,
                    name: 'id',
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }

                },
                {
                    data: 'action',
                    className: "text-center",
                    width: '12%'
                },

                {
                    data: 'state_name',
                    className: "text-left"
                },
                {
                    data: 'district_name',
                    className: "text-left"
                },

                {
                    data: 'status_desc',
                    name: 'id',
                    className: "text-center"
                },

            ],
            "columnDefs": [{
                "targets": ['_ALL'],
                "orderable": false,
                "sorting": false
            }],
        });


        $("#districtForm").validate({
            errorClass: 'validation-error w-100 text-danger',
            rules: {
                district_name: {
                    required: true
                },
                state: {
                    required: true
                },
                status: {
                    required: true
                }
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                var formData = new FormData(document.getElementById('districtForm'));
                // Check the operation type
                var url;
                var operationType = $('#operation_type').val();
                if (operationType == 'EDIT') {
                    url = base_url + '/ajax/district/update';
                } else if (operationType == 'ADD') {
                    url = base_url + '/ajax/district/store';
                } else {
                    return false;
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == true) {
                            stateDataTable.ajax.reload();
                            toastr.success(response.message);
                            formReset();
                        } else if (response.status == 'validation_errors') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: response.message
                            })
                        } else if (response.status == false) {
                            toastr.error(response.message);
                        } else {
                            toastr.error('Something went wrong. Please try again.');
                        }
                    },
                    error: function(error) {
                        toastr.error('Something went wrong. Please try again.')
                    }
                });
            }
        });


        // Onclick edit button
        $(document).on('click', '.editDistrictBtn', function() {
            var id = $(this).attr('id');
            $.ajax({
                url: base_url + '/ajax/get/district-details',
                type: 'POST',
                data: {
                    id: btoa(id),
                    _token: $('meta[name=csrf-token]').attr('content')
                },
                success: function(response) {

                    if (response.status == true) {
                        var data = response.data;
                        // Set the form data
                        formReset();
                        $('#operation_type').val('EDIT');
                        $('#district_id').val(btoa(data.id));
                        $('#district_name').val(data.district_name);
                        $('#state option[value="' + data.state + '"]').prop('selected', true);
                        $('#status option[value="' + data.record_status + '"]').prop('selected', true);
                        $('.form_title').html('Edit District');
                        $('#formSubmitBtn').html('<i class="bx bx-edit"></i> Update');
                    } else if (response.status == false) {
                        toastr.error(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(errors) {
                    console.log(errors);
                }
            });
        });

        $(document).on('click', '.deleteDistrictBtn', function() {
            var id = $(this).attr('id');
            if (id) {
                Swal.fire({
                    icon: 'question',
                    title: 'Are you sure?',
                    text: 'You want to delete this record?',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#555',
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel',
                }).then((result) => {

                    /* Read more about isConfirmed, isDenied below */
                    if (result.value) {
                        $.ajax({
                            url: base_url + '/ajax/district/delete',
                            type: 'POST',
                            data: {
                                id: btoa(id),
                                _token: $('meta[name=csrf-token]').attr('content')
                            },
                            success: function(response) {
                                if (response.status == true) {
                                    toastr.success(response.message);
                                    stateDataTable.ajax.reload();
                                } else if (response.status == false) {
                                    toastr.error(response.message);
                                } else {
                                    toastr.error(response.message);
                                }
                            },
                            error: function(errors) {
                                toastr.error(error)
                            }
                        });
                    }

                });
            } else {
                toastr.error('Something went wrong. Please try again.');
            }

        });



        function formReset() {
            document.getElementById("districtForm").reset();
            $('#operation_type').val('ADD');
            $('#formSubmitBtn').html('<i class="bx bx-paper-plane"></i> Submit');
            $('.form_title').html('Add District');
        }
    </script>
@endsection
