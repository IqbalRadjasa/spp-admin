import Swal from "sweetalert2";

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
});

export function toastSuccess(message) {
    Toast.fire({
        icon: "success",
        title: message,
    });
}

export function toastError(message) {
    Toast.fire({
        icon: "error",
        title: message,
    });
}

export function successAlert(message) {
    Swal.fire({
        icon: "success",
        title: "Success",
        text: message,
        timer: 2000,
        showConfirmButton: false,
    });
}

export function errorAlert(message) {
    Swal.fire({
        icon: "error",
        title: "Error",
        text: message,
    });
}

export function confirmDelete(callback) {
    Swal.fire({
        title: "Delete Data?",
        text: "This action cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc2626",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Delete",
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}
