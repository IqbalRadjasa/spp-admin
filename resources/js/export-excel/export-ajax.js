import Swal from "sweetalert2";

window.handleAjaxExport = async function (event, exportUrl) {
    event.preventDefault();

    confirmAction(
        async () => {
            const response = await fetch(exportUrl, {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            if (!response.ok) {
                throw new Error("Gagal mengunduh file laporan.");
            }

            const disposition = response.headers.get("content-disposition");
            let filename = "laporan-tunggakan.xlsx"; // fallback

            if (disposition && disposition.includes("filename=")) {
                const filenameMatch = disposition.match(
                    /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/
                );
                if (filenameMatch && filenameMatch[1]) {
                    filename = filenameMatch[1].replace(/['"]/g, "");
                }
            }

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);

            // Success notification
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "File Excel laporan berhasil diunduh.",
                timer: 2000,
                showConfirmButton: false,
            });
        },
        {
            title: "Ekspor Data Laporan?",
            text: "File Excel akan disiapkan berdasarkan filter yang dipilih.",
            confirmButtonText: "Ya, Ekspor",
            loadingTitle: "Menyiapkan File...",
            loadingText: "Mohon tunggu, data sedang diproses...",
        }
    );
};
