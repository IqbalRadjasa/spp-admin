export function initializeStudentFilter() {
    const educationalLevelVal = $("#educational-level").val();
    const majorFilter = $("#major-filter");
    const classroomFilter = $("#classroom-filter");
    const placeholder = classroomFilter.data("placeholder") || "Pilih Kelas";
    const selectedClassroom = window.selectedClassroom;

    const isParallelLetterLevel = ["SD", "SMP"].includes(educationalLevelVal);

    function resetClassroomOptions(disable = false) {
        classroomFilter.empty();
        classroomFilter.append(`<option value="">${placeholder}</option>`);
        classroomFilter.prop("disabled", disable);
    }

    function appendClassrooms(data) {
        data.forEach((classroom) => {
            const isSelected = String(classroom.id) === String(selectedClassroom);
            const option = new Option(classroom.display_name, classroom.id, isSelected, isSelected);
            classroomFilter.append(option);
        });
    }

    function loadClassrooms(majorId = null) {
        if (isParallelLetterLevel) {
            resetClassroomOptions(false);

            $.get("/classrooms/getAll", function (data) {
                appendClassrooms(data);
            });

            return;
        }

        if (!majorId) {
            resetClassroomOptions(true);
            return;
        }

        resetClassroomOptions(false);

        $.get(`/classrooms/by-major/${majorId}`, function (data) {
            appendClassrooms(data);
        });
    }

    if (isParallelLetterLevel) {
        loadClassrooms();
    } else {
        majorFilter.on("change", function () {
            loadClassrooms($(this).val());
        });

        loadClassrooms(majorFilter.val());
    }
}