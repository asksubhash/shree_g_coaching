$(document).ready(function () {
    var academicDetail = $("#academicDetail").DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        scrollX: true,
        scrollCollapse: true,
        ajax: {
            url: base_url + "/ajax/get/student-academic-detail",
            type: "POST",
            data: function (d) {
                d._token = $("meta[name=csrf-token]").attr("content");
                d.student_id = atob(student_code);
            },
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
                width: "12%",
            },
            {
                data: "medium_off_inst",
                name: "id",
                className: "text-center",
            },

            {
                data: "class_name",
                name: "a.name",
                className: "text-left",
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
                data: "subjects",
                name: "id",
                className: "text-center",
            },
        ],
        columnDefs: [
            {
                targets: ["_ALL"],
                orderable: false,
                sorting: false,
            },
        ],
    });

    $("#subjects").on("select2:select", function (e) {
        var selectedOptions = $(this).val() || [];
        if (selectedOptions.length > 2) {
            // Remove the last selected option if the limit is exceeded
            $(this).val(selectedOptions.slice(0, 2)).trigger("change.select2");
            toastr.error("You can select max 2 subjects.");
        }
    });
    $("#addForm").validate({
        errorClass: "text-danger validation-error",
        rules: {
            academic_year: {
                required: true,
            },
            admission_session: {
                required: true,
            },
            class_id: {
                required: true,
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById("addForm"));
            $(".loader").show();

            let operationType = $("#operation_type").val();
            let formUrl = base_url + "/ajax/student/store/academic_detail";

            // If EDIT, then override the value
            if (operationType == "EDIT") {
                formUrl = base_url + "/ajax/student/update/academic_detail";
            }

            $.ajax({
                url: formUrl,
                type: "POST",
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (response) {
                    $(".loader").hide();
                    var data = response;

                    if (response.status == true) {
                        academicDetail.ajax.reload();
                        toastr.success(response.message);
                        resetForm();
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
                    $(".loader").hide();
                    toastr.error(error.statusText);
                },
            });
        },
    });
    $(document).on("change", "#class_id", function () {
        let class_id = $(this).val();
        $("#subjects").html("").select2();
        if (class_id) {
            getSubjectsListUsingClassId(class_id, "subjects", "");
        }
    });

    $(document).on("click", ".editAcademicDetailBtn", function () {
        const id = $(this).attr("id");

        $.ajax({
            url: base_url + "/ajax/get/student-academic-detail-edit",
            type: "POST",
            data: {
                id: btoa(id),
                _token: $("meta[name=csrf-token]").attr("content"),
            },
            dataType: "json",
            success: function (response) {
                if (response.status === true) {
                    handleEditAcademicDetail(response);
                } else {
                    toastr.error(response.message || "Something went wrong.");
                }
            },
            error: function (errors) {
                console.log(errors);
                toastr.error("An error occurred. Please try again.");
            },
        });
    });
});

// 🔁 async handler separated
async function handleEditAcademicDetail(response) {
    const acDetail = response.ac_detail;
    const subjects = response.subjects;

    resetForm();
    $("#operation_type").val("EDIT");
    $("#hidden_code").val(btoa(acDetail.id));
    $("#academic_year").val(acDetail.academic_year);
    $("#admission_session").val(acDetail.admission_session_id);
    $("#class_id").val(acDetail.class_id);

    if (acDetail.medium_off_inst === "ENGLISH") {
        $("#english").prop("checked", true);
    }
    if (acDetail.medium_off_inst === "HINDI") {
        $("#hindi").prop("checked", true);
    }
    // Now it's safe to await
    await getSubjectsListUsingClassId(
        acDetail.class_id,
        "subjects",
        subjects.map((s) => s.subject_id)
    );

    $(".form_title").html("Edit Detail");
    $("#formSubmitBtn").html('<i class="bx bx-edit"></i> Update');
}

function resetForm() {
    $("#subjects").html("").select2();
    $(".form_title").html("Add New");
    $("#formSubmitBtn").html('<i class="bx bx-paper-plane"></i> Submit');
    document.getElementById("addForm").reset();
    $("#addForm").validate().resetForm();
}

// ============================================
/**
 * On change in class, get the subjects
 */
const getSubjectsListUsingClassId = async (
    class_id,
    elementId,
    selectedIds = []
) => {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: `${base_url}/ajax/get/subjects`,
            type: "POST",
            data: {
                _token: $("meta[name=csrf-token]").attr("content"),
                class_id: btoa(class_id),
            },
            dataType: "json",
            success: function (response) {
                if (response.status === true) {
                    const courseSubjects = response.data;
                    let optionsHtml = ``;

                    courseSubjects.forEach((subject) => {
                        const isSelected = selectedIds.includes(
                            subject.subject_id
                        )
                            ? "selected"
                            : "";
                        optionsHtml += `<option value="${subject.subject_id}" ${isSelected}>${subject.subject_name}</option>`;
                    });

                    const $element = $("#" + elementId);
                    $element.html(optionsHtml).select2();
                    resolve(); // signal successful completion
                } else if (response.status === "validation_errors") {
                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        html: response.message,
                    });
                    reject("Validation error");
                } else {
                    toastr.error(
                        response.message ||
                            "Something went wrong. Please try again."
                    );
                    reject("General failure");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                toastr.error("Something went wrong. Please try again.");
                reject(error);
            },
        });
    });
};
