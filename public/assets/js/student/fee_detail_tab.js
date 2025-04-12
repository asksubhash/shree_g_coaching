$(document).ready(function () {
    $("#subjects").select2({
        dropdownParent: $("#feeModal"),
    });
    var feeDetailDataTable = $("#feeDetailDataTable").DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        scrollX: true,
        scrollCollapse: true,
        ajax: {
            url: base_url + "/payment/fees/offline/get-for-datatable",
            type: "POST",
            data: function (d) {
                d._token = $("meta[name=csrf-token]").attr("content");
            },
        },
        initComplete: function () {
            // $('[data-toggle="tooltip"]').tooltip()
        },
        columns: [
            {
                data: null,
                name: "id",
                className: "text-center",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
            },
            {
                data: "action",
                className: "text-center",
            },
            {
                data: "status_desc",
                name: "id",
                className: "text-center",
            },
            {
                data: "academic_year",
                name: "b.academic_year",
                className: "text-left",
            },
            {
                data: "session_name",
                name: "c.session_name",
                className: "text-left",
            },
            {
                data: "class_name",
                name: "a.name",
                className: "text-left",
            },
            {
                data: "subject_name",
                name: "id",
                className: "text-center",
            },
            {
                data: "student_name",
                name: "name",
                className: "text-left",
            },
            {
                data: "payment_type",
                name: "payment_type",
                className: "text-left",
            },
            {
                data: "payment_method",
                name: "payment_method",
                className: "text-left",
            },
            {
                data: "payment_date",
                name: "payment_date",
                className: "text-left",
            },
            {
                data: "transaction_id",
                name: "transaction_id",
                className: "text-center",
            },
            {
                data: "amount",
                name: "amount",
                className: "text-center",
            },
            {
                data: null,
                name: "late_fees_if_any",
                className: "text-center",
                render: function (data, type, row, meta) {
                    return data.late_fees_if_any && data.late_fees_if_any == 1
                        ? '<span class="badge bg-success">Yes</span>'
                        : '<span class="badge bg-danger">No</span>';
                },
            },
            {
                data: "late_fees_amount",
                name: "late_fees_amount",
                className: "text-center",
            },
            {
                data: null,
                name: "id",
                className: "text-center",
                render: function (data, type, row, meta) {
                    const amount = parseFloat(data.amount) || 0;
                    const lateFees = data.late_fees_if_any
                        ? parseFloat(data.late_fees_amount) || 0
                        : 0;
                    return amount + lateFees;
                },
            },
        ],
        columnDefs: [
            {
                targets: [0, 1, 2, 3, 4, 5],
                orderable: false,
                sorting: false,
            },
        ],
        dom:
            "<'row'<'col-12 col-md-4'B><'col-12 col-md-4'l><'col-12 col-md-4'f>>" +
            "<'row'<'col-12'tr>>" +
            "<'row'<'col-12 col-md-5'i><'col-12 col-md-7'p>>",
        buttons: [],
    });
    $(window).on("load", function () {
        $(".dataTables_wrapper .dt-buttons").append(
            '<button class="btn btn-primary mb-2" id="addFeeDetail" data-bs-toggle="modal" data-bs-target="#feeModal">Add New</button>'
        );
    });

    $(document).on("click", "#addFeeDetail", function () {
        $("#operation_type").val("ADD");
        $("#feeModalLabel").html('<i class="fas fa-plus"></i> Add Fee Detail');
        $("#btnPaymentForm").html('<i class="fas fa-paper-plane"></i> Submit');
        $("#feeModal").modal("show");
    });

    $(document).on("change", "#class_id", function () {
        let class_id = $(this).val();
        $("#subjects").html("").select2();
        if (class_id) {
            getSubjectsListUsingClassId(class_id, "subjects", "");
        }
    });

    // fee related
    // On change of payment type
    $(document).on("change", "#payment_type", function () {
        let paymentType = $(this).val();

        $(".offlinePaymentCol").hide();
        $(".paymentOtherDetailsCol").hide();

        if (paymentType == "OFFLINE") {
            $(".offlinePaymentCol").show();
            $(".paymentOtherDetailsCol").show();
        }
    });

    // ---------------------------------------------
    // On change of payment method
    $(document).on("change", "#payment_method", function () {
        let paymentType = $(this).val();
        $(".upiDetailsCol").hide();
        $(".bankDetailsCol").hide();
        $(".receiptDetailsCol").hide();

        if (paymentType == "UPI") {
            $(".upiDetailsCol").show();
        }
        if (paymentType == "BANK") {
            $(".bankDetailsCol").show();
        }
        if (paymentType == "CASH") {
            $(".receiptDetailsCol").show();
        }
    });

    // ---------------------------------------------
    // On change of late fees if any
    $(document).on("change", "#late_fees_if_any", function () {
        let lateFees = $(this).val();
        $(".late_fees_amount_col").hide();

        if (lateFees == 1) {
            $(".late_fees_amount_col").show();
        }
    });

    // -----------------------------------------------
    // On submitting the add payment form
    $("#addPaymentForm").validate({
        errorClass: "text-danger validation-error",
        rules: {},
        submitHandler: function (form, event) {
            event.preventDefault();
            var formData = new FormData(
                document.getElementById("addPaymentForm")
            );
            // Check the operation type
            var url = base_url + "/payment/student/fees";

            // Send Ajax Request
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.status == true) {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            html: response.message,
                        }).then(() => {
                            feeDetailDataTable.ajax.reload();
                            toastr.success(response.message);
                            resetForm();
                        });
                    } else if (response.status == "validation_errors") {
                        Swal.fire({
                            icon: "error",
                            title: "Validation Error",
                            html: response.message,
                        });
                    } else if (response.status == false) {
                        toastr.error(response.message);
                    } else {
                        toastr.error("Something went wrong. Please try again.");
                    }
                },
                error: function (error) {
                    toastr.error("Something went wrong. Please try again.");
                },
            });
        },
    });
});

function resetForm() {
    $("#subjects").select2({
        dropdownParent: $("#feeModal"),
    });
    document.getElementById("addPaymentForm").reset();
    $("#addPaymentForm").validate().resetForm();
}

// ============================================
/**
 * On change in class, get the subjects
 */
function getSubjectsListUsingClassId(class_id, elementId, selectedId = "") {
    $.ajax({
        url: base_url + "/ajax/get/subjects",
        type: "POST",
        data: {
            _token: $("meta[name=csrf-token]").attr("content"),
            class_id: btoa(class_id),
        },
        dataType: "json",
        success: function (response) {
            if (response.status == true) {
                let courseSubjects = response.data;
                if (courseSubjects.length > 0) {
                    let language_subject = `<option value="">--Select--</option>`;
                    courseSubjects.forEach((subject) => {
                        if (selectedId == subject.subject_id) {
                            language_subject += `<option value="${subject.subject_id}" selected>${subject.subject_name}</option>`;
                        } else {
                            language_subject += `<option value="${subject.subject_id}">${subject.subject_name}</option>`;
                        }
                    });
                    $("#" + elementId).html(language_subject);
                    $("#" + elementId).select2({
                        dropdownParent: $("#feeModal"),
                    });
                }
            } else if (response.status == "validation_errors") {
                Swal.fire({
                    icon: "error",
                    title: "Validation Error",
                    html: response.message,
                });
            } else if (response.status == false) {
                toastr.error(response.message);
            } else {
                toastr.error("Something went wrong. Please try again.");
            }
        },
        error: function (error) {
            toastr.error("Something went wrong. Please try again.");
        },
    });
}
