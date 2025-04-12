{{-- MODAL --}}
<div class="modal fade" id="studentApprovalModal" tabindex="-1" aria-labelledby="studentApprovalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="studentApprovalModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <form action="" class="studentApprovalForm" id="studentApprovalForm">
                    @csrf
                    <input type="hidden" name="operation_type" id="approval_operation_type">
                    <input type="hidden" name="id" id="hidden_approval_id">

                    <div class="row">

                        <div class="mb-3 col-md-6 col-sm-6 col-12">
                            <label for="" class="form-label">Student Name</label>
                            <input type="text" name="student_name" id="approval_student_name" class="form-control" disabled />
                        </div>

                        <div class="mb-3 col-md-6 col-sm-6 col-12">
                            <label for="" class="form-label">Student Application No.</label>
                            <input type="text" name="approval_student_application_no" id="approval_student_application_no" class="form-control" disabled />
                        </div>

                        <div class="mb-3 col-md-6 col-sm-6 col-12">
                            <label for="" class="form-label">Roll Number</label>
                            <input type="text" name="roll_number" id="approval_roll_number" class="form-control" />
                        </div>

                        <div class="mb-3 col-md-6 col-sm-6 col-12">
                            <label class="form-label" for="status">Status <strong class="text-danger">*</strong></label>
                            <select class="form-control" name="status" id="status">
                                <option value="">Select</option>
                                <option value="1">Approve</option>
                                <!-- <option value="2">Reject</option> -->
                            </select>
                        </div>

                        <div class="mb-3 col-12 text-center">
                            <button type="submit" class="btn btn-primary" id="btnApprovalForm"></button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

@section('stu-approval-modal-script')
<script>
    // -----------------------------------------------
    // On submitting the add payment form
    $("#studentApprovalForm").validate({
        errorClass: "text-danger validation-error",
        rules: {

        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('studentApprovalForm'));
            // Check the operation type
            var url = base_url + '/student/application/approval';

            // Send Ajax Request
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            html: response.message
                        }).then(() => {
                            window.location.reload();
                        })
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
</script>
@endsection