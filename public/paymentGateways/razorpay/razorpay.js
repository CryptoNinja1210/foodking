if (razorpayKey) {
    let options = {
        key: razorpayKey,
        amount: (razorpayTotalAmount * 100),
        currency: razorpayCurrencyCode,
        name: razorpayCompany,
        description: "Food Payment",
        image: razorpayLogo,
        prefill: {
            name: razorpayUserName,
            email: razorpayUserEmail
        },
        theme: {
            color: '#FF006B'
        },
        handler: function (response) {
            fetch(razorpayPayLink, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    razorpayPaymentId: response.razorpay_payment_id,
                    paymentMethod: 'razorpay'
                })
            }).then(function (response) {
                window.location.href = razorpaySuccessLink;
            }).catch(function (error) {
                alert(error);
            });
        },
        "modal": {
            "ondismiss": function () {
                window.location.href = razorpayCancelLink;
            }
        }
    };

    const razorPayPayment = new Razorpay(options);
    document.getElementById('razorpay').onclick = function (e) {
        document.getElementById("confirmBtn").disabled = true;
        razorPayPayment.open();
        e.preventDefault();
    };
}
