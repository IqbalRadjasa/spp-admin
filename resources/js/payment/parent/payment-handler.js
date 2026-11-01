import { errorAlert, warningAlert } from "../../helpers/sweetalert";

window.payBill = function (billId) {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    fetch(`/parent/bills/${billId}/pay`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === "success") {
                // Trigger Midtrans Snap Popup
                window.snap.pay(data.snap_token, {
                    onSuccess: function (result) {
                        alert("Pembayaran berhasil!");
                        window.location.reload();
                    },
                    onPending: function (result) {
                        alert("Menunggu pembayaran Anda.");
                        window.location.reload();
                    },
                    onError: function (result) {
                        alert("Pembayaran gagal!");
                        console.log(result);
                    },
                    onClose: function () {
                        warningAlert({
                            title: "Anda menutup popup sebelum menyelesaikan pembayaran.",
                        });
                    },
                });
            } else {
                errorAlert({
                    title: "Gagal memproses pembayaran: ",
                    message: data.message,
                });

                // alert("Gagal memproses pembayaran: " + data.message);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
};
