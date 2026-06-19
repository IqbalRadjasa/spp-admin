import Swal from "sweetalert2";

export function initializeAcademicYears() {
    if ($("#academicYearsTable").length === 0) {
        return;
    }

    $("#create-academic-year-form")[0].reset();
    $("#edit-academic-year-form")[0].reset();

    // Create Form Setup
    $("#close-modal-add-academic-year").on("click", function () {
        $("#create-academic-year-form")[0].reset();

        $("[data-input-error]").text("");
    });

    $("#create-academic-year-form").on("submit", function (e) {
        e.preventDefault();

        $("[data-input-error]").text("");

        $.ajax({
            url: $(this).data("url"),
            method: "POST",
            data: $(this).serialize(),
            headers: {
                Accept: "application/json",
            },

            success: function (res) {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: true,
                    icon: "success",
                    title: res.message,
                }).then(() => {
                    window.dispatchEvent(
                        new CustomEvent("close-modal", {
                            detail: "add-academic-year",
                        })
                    );

                    location.reload();
                });
            },

            error: function (xhr) {
                if (xhr.status === 422) {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        icon: "error",
                        title: xhr.responseJSON.message,
                    });
                }

                try {
                    let errors = xhr.responseJSON.errors;

                    Object.keys(errors).forEach((field) => {
                        $(`[data-input-error="${field}"]`).text(
                            errors[field][0]
                        );
                    });
                } catch (e) {}
            },
        });
    });

    // Get Academic Year
    function getAcademicYear(id) {
        $.ajax({
            url: `/academic-years/getAcademicYear/${id}`,
            method: "GET",
            data: $(this).serialize(),
            headers: {
                Accept: "application/json",
            },
            success: function (res) {
                const data = res.data;

                $("#edit-input-from").val(data.from);
                $("#edit-input-to").val(data.to);
            },
            error: function (xhr) {
                if (xhr.status === 404) {
                    Swal.fire({
                        icon: "error",
                        title: "Not Found",
                        text: "Academic year not found.",
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Something went wrong, try again later.",
                    });
                }
            },
        });
    }

    let academicYear_id;

    $(document).on("click", ".edit-academic-year", function () {
        academicYear_id = $(this).data("id");

        getAcademicYear(academicYear_id);
    });

    // Edit Form Setup
    $("#close-modal-edit-academic-year").on("click", function () {
        $("#edit-input-from").val("");
        $("#edit-input-to").val("");

        $("[data-input-error]").text("");
    });

    $("#edit-academic-year-form").on("submit", function (e) {
        e.preventDefault();

        $("[data-input-error]").text("");

        $.ajax({
            url: `/settings/academic-years/${academicYear_id}`,
            method: "PUT",
            data: $(this).serialize(),
            headers: {
                Accept: "application/json",
            },

            success: function (res) {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: true,
                    icon: "success",
                    title: res.message,
                }).then(() => {
                    window.dispatchEvent(
                        new CustomEvent("close-modal", {
                            detail: "edit-academic-year",
                        })
                    );

                    location.reload();
                });
            },

            error: function (xhr) {
                if (xhr.status === 422) {
                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                        icon: "error",
                        title: xhr.responseJSON.message,
                    });
                }

                try {
                    let errors = xhr.responseJSON.errors;

                    Object.keys(errors).forEach((field) => {
                        $(`[data-input-error="${field}"]`).text(
                            errors[field][0]
                        );
                    });
                } catch (e) {}
            },
        });
    });
}
