function getCoursesListUsingInstituteId(
    instituteId,
    elementId,
    selectedId = ""
) {
    $.ajax({
        url: base_url + "/ajax/course/get-list-using-institute-id",
        type: "POST",
        data: {
            _token: $("meta[name=csrf-token]").attr("content"),
            institute_id: instituteId,
        },
        success: function (response) {
            if (response.status == true) {
                let courses = response.data.courses;

                // Assignments Language Subjects
                if (courses.length > 0) {
                    let courseOptions = `<option value="">Select</option>`;
                    courses.forEach((course) => {
                        if (selectedId && course.id == selectedId) {
                            courseOptions += `<option value="${course.id}" selected>${course.course_name} (${course.course_code})</option>`;
                        } else {
                            courseOptions += `<option value="${course.id}">${course.course_name} (${course.course_code})</option>`;
                        }
                    });

                    $("#" + elementId).html(courseOptions);
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

function getSubjectsListUsingCourseSubType(
    courseId,
    subjectType,
    elementId,
    selectedId = ""
) {
    $.ajax({
        url: base_url + "/ajax/course/get/subjects-and-nl-subjects",
        type: "POST",
        data: {
            _token: $("meta[name=csrf-token]").attr("content"),
            course_id: courseId,
        },
        success: function (response) {
            if (response.status == true) {
                let data = response.data;
                let courseSubjects = data.courseSubjects;
                let courseNLSubjects = data.courseNLSubjects;

                if (subjectType == "LANGUAGE") {
                    // Course Language Subjects
                    if (courseSubjects.length > 0) {
                        let language_subject = "";
                        courseSubjects.forEach((subject) => {
                            if (selectedId == subject.id) {
                                language_subject += `<option value="${subject.id}" selected>${subject.name}</option>`;
                            } else {
                                language_subject += `<option value="${subject.id}">${subject.name}</option>`;
                            }
                        });

                        $("#" + elementId).html(language_subject);
                        $("#" + elementId).select2();
                    }
                }

                if (subjectType == "NON_LANGUAGE") {
                    // Course Non Language Subjects
                    if (courseNLSubjects.length > 0) {
                        let non_language_subject = "";
                        courseNLSubjects.forEach((subject) => {
                            if (selectedId == subject.id) {
                                non_language_subject += `<option value="${subject.id}" selected>${subject.name}</option>`;
                            } else {
                                non_language_subject += `<option value="${subject.id}">${subject.name}</option>`;
                            }
                        });
                        $("#" + elementId).html(non_language_subject);
                        $("#" + elementId).select2();
                    }
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

function fetchAndLoadAdmissionSesionsData(
    course_id,
    academic_year_id,
    institute_id,
    elementId,
    selectedId = ""
) {
    $.ajax({
        url:
            base_url +
            "/ajax/admission-sessions-setup/get-using-course-insitute-academic-year",
        type: "POST",
        data: {
            _token: $("meta[name=csrf-token]").attr("content"),
            course_id: course_id,
            academic_year_id: academic_year_id,
            institute_id: institute_id,
        },
        success: function (response) {
            if (response.status == true) {
                let admissionSessions = response.data;

                // Course Language Subjects
                if (admissionSessions.length > 0) {
                    let admissionSessionOptions =
                        '<option value="">Select</option>';
                    admissionSessions.forEach((as) => {
                        if (as.id == selectedId) {
                            admissionSessionOptions += `<option value="${as.id}" selected>${as.session_name}</option>`;
                        } else {
                            admissionSessionOptions += `<option value="${as.id}">${as.session_name}</option>`;
                        }
                    });

                    $("#" + elementId).html(admissionSessionOptions);
                } else {
                    $("#" + elementId).html('<option value="">Select</option>');
                    toastr.error(
                        "No Admission Session found for the selected Academic Year, Insitute and Course"
                    );
                }
            } else {
                toastr.error("Something went wrong. Please try again.");
            }
        },
        error: function (error) {
            toastr.error("Something went wrong. Please try again.");
        },
    });
}

function setDefaultSelect(elementId) {
    $("#" + elementId).html('<option value="">Select</option>');
}
