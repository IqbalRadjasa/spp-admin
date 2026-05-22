import "./bootstrap";

import Alpine from "alpinejs";
import { DataTable } from "simple-datatables";

window.Alpine = Alpine;

Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
    const studentsTable = document.querySelector("#studentsTable");
    const billsTable = document.querySelector("#billsTable");

    if (studentsTable) {
        new DataTable(studentsTable);
    }

    if (billsTable) {
        new DataTable(billsTable);
    }
});
