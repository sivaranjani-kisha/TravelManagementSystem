<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Payment</title>
    <style>
.modal-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.modal-box {
    background: #fff;
    padding: 20px 30px;
    border-radius: 8px;
    text-align: center;
    min-width: 300px;
}
</style>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<h3>Pay ₹500</h3>

<button id="payBtn">Pay Now</button>
<!-- Custom Modal -->
<div id="paymentModal" class="modal-overlay">
    <div class="modal-box">
        <h3 id="modalTitle">Status</h3>
        <p id="modalMessage"></p>
    </div>
</div>

<script>
$('#payBtn').click(function () {

    $.ajax({
        url: "<?= base_url('payment/createOrder') ?>",
        type: "POST",
        success: function (res) {
            var data = JSON.parse(res);

            var options = {
                "key": data.key,
                "amount": data.amount,
                "currency": "INR",
                "name": "My Website",
                "description": "Test Payment",
                "order_id": data.order_id,

                "handler": function (response) {
                    $.ajax({
                        url: "<?= base_url('payment/verifyPayment') ?>",
                        type: "POST",
                        data: response,
                       success: function(res){
                            if(res.status === 'success'){
                                showModal('Success ✅', res.message);
                            } else {
                                showModal('Failed ❌', res.message);
                            }
                        }

                    });
                }
            };

            var rzp = new Razorpay(options);
            rzp.open();
        }
    });

});
</script>
<script>
function showModal(title, message) {
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalMessage').innerText = message;

    document.getElementById('paymentModal').style.display = 'flex';

    // auto close after 5 seconds
    setTimeout(function () {
        document.getElementById('paymentModal').style.display = 'none';
    }, 5000);
}
</script>

</body>
</html>
