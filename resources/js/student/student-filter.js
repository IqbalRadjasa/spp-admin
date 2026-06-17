export function initializeStudentFilter() {
    const majorFilter = document.querySelector("#major-filter");

    const classroomFilter = document.querySelector("#classroom-filter");

    if (!majorFilter || !classroomFilter) {
        return;
    }

    function toggleClassroom() {
        if (!majorFilter.value) {
            classroomFilter.style.display = "none";

            classroomFilter.value = "";
        } else {
            classroomFilter.style.display = "block";
        }
    }

    toggleClassroom();

    majorFilter.addEventListener("change", function () {
        if (!this.value) {
            classroomFilter.value = "";
        }
    });
}
