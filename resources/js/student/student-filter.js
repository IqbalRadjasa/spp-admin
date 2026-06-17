export function initializeStudentFilter() {
    const majorFilter = $("#major-filter");

    const classroomFilter = $("#classroom-filter");
    const placeholder = classroomFilter.data("placeholder");

    const selectedClassroom = window.selectedClassroom;

    function loadClassrooms(majorId) {
        classroomFilter.empty();

        classroomFilter.append(
            `<option value="">
                ${placeholder}
            </option>`
        );

        if (!majorId) {
            classroomFilter.prop("disabled", true);

            return;
        }

        classroomFilter.prop("disabled", false);

        $.get(`/classrooms/by-major/${majorId}`, function (data) {
            data.forEach((classroom) => {
                const selected =
                    String(classroom.id) === String(selectedClassroom)
                        ? "selected"
                        : "";

                classroomFilter.append(
                    `
                            <option
                                value="${classroom.id}"
                                ${selected}
                            >
                                ${classroom.display_name}
                            </option>
                            `
                );
            });
        });
    }

    majorFilter.on("change", function () {
        loadClassrooms($(this).val());
    });

    loadClassrooms(majorFilter.val());
}
