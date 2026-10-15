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

export function errorAlert(options = {}) {
    Swal.fire({
        icon: "error",
        title: options.title ?? "Error",
        html: options.html ?? "",
        text: options.message,
        confirmButtonColor: "#597928",
    });
}

export function confirmDelete(callback) {
    Swal.fire({
        title: "Hapus Data?",
        text: "Tindakan ini tidak dapat dibatalkan.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc2626",
        confirmButtonText: "Hapus",
        cancelButtonColor: "#d33",
        cancelButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

export function confirmAction(callback, options = {}) {
    Swal.fire({
        title: options.title ?? "Konfirmasi Tindakan?",
        text: options.text ?? "Apakah kamu yakin?",
        icon: options.icon ?? "warning",
        showCancelButton: true,
        confirmButtonColor: options.confirmButtonColor ?? "#dc2626",
        cancelButtonColor: options.cancelButtonColor ?? "#6b7280",
        confirmButtonText: options.confirmButtonText ?? "Ya",
        cancelButtonText: options.cancelButtonText ?? "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

export function confirmAndSubmit(formId, options = {}) {
    Swal.fire({
        title: options.title ?? "Simpan Data?",
        text: options.text ?? "Pastikan data sudah benar.",
        icon: options.icon ?? "question",
        showCancelButton: true,
        confirmButtonColor: options.confirmButtonColor ?? "#597928",
        cancelButtonColor: options.cancelButtonColor ?? "#d33",
        confirmButtonText: options.confirmButtonText ?? "Ya, Simpan!",
        cancelButtonText: options.cancelButtonText ?? "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state automatically
            Swal.fire({
                title: options.loadingTitle ?? "Sedang Menyimpan...",
                text:
                    options.loadingText ??
                    "Mohon tunggu sebentar, data sedang diproses.",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            // Submit the form dynamically by ID
            const form = document.getElementById(formId);
            if (form) {
                form.submit();
            } else {
                console.error(`Form with ID "${formId}" not found.`);
            }
        }
    });
}
