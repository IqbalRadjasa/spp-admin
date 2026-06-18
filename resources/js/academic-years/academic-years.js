export function initializeAcademicYears() {
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
                alert(res.message);

                window.dispatchEvent(
                    new CustomEvent("close-modal", {
                        detail: "add-academic-year",
                    })
                );

                location.reload();
            },

            error: function (xhr) {
                try {
                    let errors = xhr.responseJSON.errors;

                    Object.keys(errors).forEach((field) => {
                        console.log(field);

                        $(`[data-input-error="${field}"]`).text(
                            errors[field][0]
                        );
                    });
                } catch (e) {
                    console.error(e);
                }
            },
        });
    });
}
