document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("payment-form");
    if (!form) return;

    const cashProofContainer = document.getElementById("cash-proof-container");
    const submitButton = form.querySelector('button[type="submit"]');
    const redirectUrl = form.dataset.redirectUrl || "/bills";

    function toggleCashProof() {
        const selectedRadio = form.querySelector(
            'input[name="payment_method_id"]:checked'
        );

        if (selectedRadio && selectedRadio.id === "tunai") {
            console.log("broo");
            cashProofContainer.classList.remove("hidden");
        } else {
            cashProofContainer.classList.add("hidden");
        }
    }

    form.addEventListener("change", function (e) {
        if (e.target && e.target.matches('input[name="payment_method_id"]')) {
            toggleCashProof();
        }
    });

    // Run initially on page load (handles old() input re-renders or default checks)
    toggleCashProof();

    // Handle form submission routing
    form.addEventListener("submit", function (e) {
        const selectedMethod = form.querySelector(
            'input[name="payment_method_id"]:checked'
        );

        if (!selectedMethod) return;

        // METHOD 1: Cash (Tunai) -> Proceed with normal form submission (including file upload)
        if (selectedMethod.value === "1") {
            return;
        }

        // METHOD 2: Transfer Bank (Midtrans) -> Intercept and trigger Midtrans Snap Popup
        if (selectedMethod.value === "2") {
            e.preventDefault();

            let formData = new FormData(form);

            if (submitButton) submitButton.disabled = true;

            fetch(form.action, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    Accept: "application/json",
                },
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === "success" && data.snap_token) {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function (result) {
                                window.location.href = redirectUrl;
                            },
                            onPending: function (result) {
                                alert("Menunggu penyelesaian pembayaran Anda.");
                                if (submitButton) submitButton.disabled = false;
                            },
                            onError: function (result) {
                                alert("Pembayaran gagal!");
                                if (submitButton) submitButton.disabled = false;
                            },
                            onClose: function () {
                                alert(
                                    "Anda menutup popup sebelum menyelesaikan pembayaran."
                                );
                                if (submitButton) submitButton.disabled = false;
                            },
                        });
                    } else {
                        alert(
                            "Gagal memproses pembayaran: " +
                                (data.message || "Unknown error")
                        );
                        if (submitButton) submitButton.disabled = false;
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan sistem.");
                    if (submitButton) submitButton.disabled = false;
                });
        }
    });
});
