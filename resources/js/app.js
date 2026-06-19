import "./bootstrap";

import Alpine from "alpinejs";

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

import $ from "jquery";
window.$ = $;
window.jQuery = $;

import ApexCharts from "apexcharts";
window.ApexCharts = ApexCharts;

import {
    renderMonthlyIncomeChart,
    renderBillStatusChart,
    renderPaymentMethodChart,
} from "./charts/dashboard";

import { initializeClassroomPreview } from "./classroom/classroom-preview";
import { initializeStudentFilter } from "./student/student-filter";
import { initializeAcademicYears } from "./academic-years/academic-years";

import collapse from "@alpinejs/collapse";

import { DataTable } from "simple-datatables";

import {
    toastSuccess,
    toastError,
    confirmDelete,
    confirmAction,
    successAlert,
    errorAlert,
} from "./helpers/sweetalert";

window.toastError = toastError;
window.toastSuccess = toastSuccess;

window.errorAlert = errorAlert;
window.successAlert = successAlert;
window.confirmDelete = confirmDelete;
window.confirmAction = confirmAction;

document.addEventListener("DOMContentLoaded", () => {
    // For Classroom Create Form
    initializeClassroomPreview();

    // For Student Table and Create Form Filter
    initializeStudentFilter();

    // For Create Academic Year Form
    initializeAcademicYears();

    const billsTable = document.querySelector("#billsTable");
    const majorsTable = document.querySelector("#majorsTable");
    const academicYearsTable = document.querySelector("#academicYearsTable");
    const studentsTable = document.querySelector("#studentsTable");
    const classroomsTable = document.querySelector("#classroomsTable");
    const overdueReportTable = document.querySelector("#overdueReportTable");
    const paymentReportTable = document.querySelector("#paymentReportTable");

    if (studentsTable) {
        new DataTable(studentsTable, {
            responsive: true,
            paging: false,
            searchable: false,
        });
    }

    if (billsTable) {
        new DataTable(billsTable, {
            responsive: true,
            paging: false,
            searchable: false,
        });
    }

    if (overdueReportTable) {
        new DataTable(overdueReportTable, {
            responsive: true,
            paging: false,
            searchable: false,
        });
    }

    if (paymentReportTable) {
        new DataTable(paymentReportTable, {
            responsive: true,
            paging: false,
            searchable: false,
        });
    }

    if (majorsTable) {
        new DataTable(majorsTable, {
            responsive: true,
            paging: false,
            searchable: false,
        });
    }

    if (classroomsTable) {
        new DataTable(classroomsTable, {
            responsive: true,
            paging: false,
            searchable: false,
        });
    }

    if (academicYearsTable) {
        new DataTable(academicYearsTable, {
            responsive: true,
            paging: false,
            searchable: false,
        });
    }

    if (window.dashboardChartData) {
        renderMonthlyIncomeChart(
            window.dashboardChartData.months,
            window.dashboardChartData.totals
        );
    }

    if (window.billStatusChartData) {
        renderBillStatusChart(
            window.billStatusChartData.paid,
            window.billStatusChartData.unpaid
        );
    }

    if (window.paymentMethodChartData) {
        renderPaymentMethodChart(
            window.paymentMethodChartData.labels,

            window.paymentMethodChartData.totals
        );
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
