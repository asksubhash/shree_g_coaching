{{-- MODAL --}}
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addPaymentModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                <form action="" class="addPaymentForm" id="addPaymentForm">
                    @csrf
                    <input type="hidden" name="operation_type" id="operation_type">
                    <input type="hidden" name="student_application_no" id="student_application_no">

                    <div class="row">

                        <div class="mb-3 col-md-4 col-sm-6 col-12">
                            <label for="" class="form-label">Student Name</label>
                            <input type="text" name="student_name" id="student_name" class="form-control" disabled />
                        </div>
                        <div class="mb-3 col-md-4 col-sm-6 col-12">
                            <label class="form-label" for="payment_type">Payment Type <span class="text-danger">*</span></label>
                            <select name="payment_type" id="payment_type" class="form-control">
                                <option value="">---Select---</option>
                                <option value="ONLINE">Online</option>
                                <option value="OFFLINE">Offline</option>
                            </select>
                        </div>

                        <div class="col-12 offlinePaymentCol" style="display: none;">
                            <div class="row">
                                <div class="mb-3 col-md-4 col-sm-6 col-12">
                                    <label class="form-label" for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method" id="payment_method" class="form-control">
                                        <option value="">---Select---</option>
                                        <option value="UPI">UPI</option>
                                        <option value="BANK">Bank</option>
                                        <option value="CASH">Cash</option>
                                    </select>
                                </div>

                                <div class="col-12 upiDetailsCol" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-sm-6 col-12 mb-3">
                                            <label class="form-label" for="upi_name">UPI Name <span class="text-danger">*</span></label>
                                            <input type="text" name="upi_name" id="upi_name" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 bankDetailsCol" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-sm-6 col-12 mb-3">
                                            <label class="form-label" for="payment_through">Payment through <span class="text-danger">*</span></label>
                                            <select name="payment_through" id="payment_through" class="form-control">
                                                <option value="">---Select---</option>
                                                <option value="NEFT">NEFT</option>
                                                <option value="NEFT">RTGS</option>
                                                <option value="NEFT">IMPS</option>
                                                <option value="NEFT">Cheque</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 receiptDetailsCol" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-12 mb-3">
                                            <label class="form-label" for="receipt_no">Receipt No. <span class="text-danger">*</span></label>
                                            <input type="text" name="receipt_no" id="receipt_no" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 paymentOtherDetailsCol" style="display: none;">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-sm-12 col-12 mb-3">
                                    <label class="form-label" for="payment_date">Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" name="payment_date" id="payment_date" class="form-control">
                                </div>

                                <div class="col-md-4 col-sm-6 col-sm-12 col-12 mb-3">
                                    <label class="form-label" for="transaction_id">Transaction Id <span class="text-danger">*</span></label>
                                    <input type="text" name="transaction_id" id="transaction_id" class="form-control">
                                </div>

                                <div class="col-md-4 col-sm-6 col-sm-12 col-12 mb-3">
                                    <label class="form-label" for="payment_document">Payment Document <span class="text-danger">*</span></label>
                                    <input type="file" name="payment_document" id="payment_document" class="form-control">
                                    <small class="text-danger">
                                        Max size 2MB, only .jpg,.jpeg,.png,.pdf files are allowed
                                    </small>
                                </div>

                                <div class="col-md-4 col-sm-6 col-sm-12 col-12 mb-3">
                                    <label class="form-label" for="amount">Amount <span class="text-danger">*</span></label>
                                    <input type="text" name="amount" id="amount" class="form-control">
                                </div>


                                <div class="col-md-4 col-sm-6 col-sm-12 col-12 mb-3">
                                    <label class="form-label" for="late_fees_if_any">Late Fees (If Any) <span class="text-danger">*</span></label>
                                    <select name="late_fees_if_any" id="late_fees_if_any" class="form-control">
                                        <option value="">Select</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>

                                <div class="col-md-4 col-sm-6 col-sm-12 col-12 mb-3 late_fees_amount_col" style="display: none;">
                                    <label class="form-label" for="late_fees_amount">Late fees amount <span class="text-danger">*</span></label>
                                    <input type="text" name="late_fees_amount" id="late_fees_amount" class="form-control">
                                </div>


                                <div class="col-md-4 col-sm-6 col-12 mb-3">
                                    <label class="form-label" for="transaction_details">Transaction details</label>
                                    <textarea name="transaction_details" id="transaction_details" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="mb-3 col-md-4 col-sm-6 col-12">
                                    <label class="form-label" for="remarks">Remarks (if any)</label>
                                    <textarea class="form-control" name="remarks" id="remarks" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 col-12 text-center">
                            <button type="submit" class="btn btn-primary" id="btnPaymentForm"></button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

@section('stu-fees-modal-script')
<script>
    // ---------------------------------------------
    // On change of payment type
    $(document).on('change', '#payment_type', function() {
        let paymentType = $(this).val();

        $('.offlinePaymentCol').hide();
        $('.paymentOtherDetailsCol').hide();

        if (paymentType == 'OFFLINE') {
            $('.offlinePaymentCol').show();
            $('.paymentOtherDetailsCol').show();
        }
    })

    // ---------------------------------------------
    // On change of payment method
    $(document).on('change', '#payment_method', function() {
        let paymentType = $(this).val();
        $('.upiDetailsCol').hide();
        $('.bankDetailsCol').hide();
        $('.receiptDetailsCol').hide();

        if (paymentType == 'UPI') {
            $('.upiDetailsCol').show();
        }
        if (paymentType == 'BANK') {
            $('.bankDetailsCol').show();
        }
        if (paymentType == 'CASH') {
            $('.receiptDetailsCol').show();
        }
    })

    // ---------------------------------------------
    // On change of late fees if any
    $(document).on('change', '#late_fees_if_any', function() {
        let lateFees = $(this).val();
        $('.late_fees_amount_col').hide();

        if (lateFees == 1) {
            $('.late_fees_amount_col').show();
        }
    })

    // -----------------------------------------------
    // On submitting the add payment form
    $("#addPaymentForm").validate({
        errorClass: "text-danger validation-error",
        rules: {

        },
        submitHandler: function(form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('addPaymentForm'));
            // Check the operation type
            var url = base_url + '/payment/student/fees';

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