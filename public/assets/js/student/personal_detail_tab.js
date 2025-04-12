$(document).ready(function () {
    $("#studentForm").validate({
        errorClass: "text-danger validation-error",
        // rules: {
        //     email_id: {
        //         required: true
        //     },
        // },
        submitHandler: function (form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById("studentForm"));
            $(".loader").show();

            let operationType = $("#operation_type").val();
            let formUrl = base_url + "/ajax/student/store/personal_detail";

            // If EDIT, then override the value
            if (operationType == "EDIT") {
                formUrl = base_url + "/ajax/student/update/personal_detail";
            }

            console.log();
            $.ajax({
                url: formUrl,
                type: "POST",
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function (response) {
                    $(".loader").hide();
                    var data = response;
                    if (data.status == true) {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            html: data.message,
                        }).then((result) => {
                            window.location.href = `${base_url}/add-student/PERSONAL_DETAIL/${data.student_id}`;
                        });
                    } else if (data.status == "validation_errors") {
                        Swal.fire({
                            icon: "error",
                            title: "Validation Error",
                            html: data.message,
                        });
                    } else if (data.status == false) {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: data.message,
                        });
                    } else {
                        toastr.error("Something went wrong. Please try again.");
                    }
                },
                error: function (error) {
                    $(".loader").hide();
                    toastr.error(error.statusText);
                },
            });
        },
    });
});

function resetForm() {
    document.getElementById("studentForm").reset();
    $("#studentForm").validate().resetForm();
}
