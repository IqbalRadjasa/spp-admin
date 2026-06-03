import "./bootstrap";

import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";
import { DataTable } from "simple-datatables";
import {
    toastSuccess,
    toastError,
    confirmDelete,
    successAlert,
    errorAlert,
} from "./helpers/sweetalert";

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

window.toastError = toastError;
window.toastSuccess = toastSuccess;

window.errorAlert = errorAlert;
window.successAlert = successAlert;
window.confirmDelete = confirmDelete;

Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
    const studentsTable = document.querySelector("#studentsTable");
    const billsTable = document.querySelector("#billsTable");
    const overdueReportTable = document.querySelector("#overdueReportTable");

    if (studentsTable) {
        new DataTable(studentsTable);
    }

    if (billsTable) {
        new DataTable(billsTable, {
            paging: false,
            searchable: false,
        });
    }

    if (overdueReportTable) {
        new DataTable(overdueReportTable, {
            paging: false,
            searchable: false,
        });
    }
});

const successMessage = document.body.dataset.success;
const errorMessage = document.body.dataset.error;

if (successMessage) {
    toastSuccess(successMessage);
}

if (errorMessage) {
    toastError(errorMessage);
}
