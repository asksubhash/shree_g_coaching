<div class="row">
    <div class="table-responsive">
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100 nowrap" id="feeDetailDataTable">
                <thead>
                    <tr>
                        <th width="8%" class="text-center">#</th>
                        <th width="12%" class="text-center">Action</th>
                        <th>Status</th>
                        <th width="10%">Academic Year</th>
                        <th width="10%">Admission Session</th>
                        <th width="10%">Class</th>
                        <th width="10%">Subjects</th>
                        <th width="15%">Student Name</th>
                        <th width="20%">Payment Type</th>
                        <th>Payment Method</th>
                        <th>Payment Date</th>
                        <th>Transaction Id</th>
                        <th>Amount</th>
                        <th>Late Fees (If Any)</th>
                        <th>Late Fees Amount</th>
                        <th>Total</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="feeModal" tabindex="-1" aria-labelledby="feeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feeModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <form action="" class="addPaymentForm" id="addPaymentForm">
                    @csrf
                    <input type="hidden" name="operation_type" id="operation_type">
                    <input type="hidden" name="student_application_no" id="student_application_no"
                        value="{{ $user->application_no }}">

                    <div class="row">

                        <div class="mb-3 col-md-4 col-sm-6 col-12">
                            <label for="" class="form-label">Student Name</label>
                            <input type="text" name="student_name" id="student_name" class="form-control"
                                value="{{ $user->name }}" disabled />
                        </div>

                        <div class=" col-md-4 mb-3">
                            <label for="academic_year" class="form-label">Academic Year<span
                                    class="text-danger">*</span>
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
                        <div class=" col-md-4 mb-3">
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


                        <div class=" col-md-4 mb-3">
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

                        <div class=" col-md-4 mb-3">
                            <label for="subjects" class="form-label">Subjects<span class="text-danger">*</span>
                            </label>
                            <select class="form-control select2" name="subjects" id="subjects" required>
                                <option value="">--Select--</option>
                            </select>
                        </div>

                        <div class="mb-3 col-md-4 col-sm-6 col-12">
                            <label class="form-label" for="payment_type">Payment Type <span
                                    class="text-danger">*</span></label>
                            <select name="payment_type" id="payment_type" class="form-control" required>
                                <option value="">---Select---</option>
                                {{-- <option value="ONLINE">Online</option> --}}
                                <option value="OFFLINE">Offline</option>
                            </select>
                        </div>

                        <div class="col-12 offlinePaymentCol" style="display: none;">
                            <div class="row">
                                <div class="mb-3 col-md-4 col-sm-6 col-12">
                                    <label class="form-label" for="payment_method">Payment Method <span
                                            class="text-danger">*</span></label>
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
                                            <label class="form-label" for="upi_name">UPI Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="upi_name" id="upi_name"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 bankDetailsCol" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-sm-6 col-12 mb-3">
                                            <label class="form-label" for="payment_through">Payment through <span
                                                    class="text-danger">*</span></label>
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
                                            <label class="form-label" for="receipt_no">Receipt No. <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="receipt_no" id="receipt_no"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 paymentOtherDetailsCol" style="display: none;">
                            <div class="row">
                                <div class="col-md-4 col-sm-6 col-sm-12 col-12 mb-3">
                                    <label class="form-label" for="payment_date">Payment Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="payment_date" id="payment_date"
                                        class="form-control">
                                </div>

                                <div class="col-md-4 col-sm-6 col-sm-12 col-12 mb-3">
                                    <label class="form-label" for="transaction_id">Transaction Id <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="transaction_id" id="transaction_id"
                                        class="form-control">
                                </div>

                                <div class="col-md-4 col-sm-6 col-sm-12 col-12 mb-3">
                                    <label class="form-label" for="payment_document">Payment Document <span
                                            class="text-danger">*</span></label>
                                    <input type="file" name="payment_document" id="payment_document"
                                        class="form-control">
                                    <small class="text-danger">
                                        Max size 2MB, only .jpg,.jpeg,.png,.pdf files are allowed
                                    </small>
                                </div>

                                <div class="col-md-4 col-sm-6 col-sm-12 col-12 mb-3">
                                    <label class="form-label" for="amount">Amount <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="amount" id="amount" class="form-control">
                                </div>


                                <div class="col-md-4 col-sm-6 col-sm-12 col-12 mb-3">
                                    <label class="form-label" for="late_fees_if_any">Late Fees (If Any) <span
                                            class="text-danger">*</span></label>
                                    <select name="late_fees_if_any" id="late_fees_if_any" class="form-control">
                                        <option value="">Select</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>

                                <div class="col-md-4 col-sm-6 col-sm-12 col-12 mb-3 late_fees_amount_col"
                                    style="display: none;">
                                    <label class="form-label" for="late_fees_amount">Late fees amount <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="late_fees_amount" id="late_fees_amount"
                                        class="form-control">
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
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="btnPaymentForm"><i
                                    class="bx bx-paper-plane"></i> Submit</button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
