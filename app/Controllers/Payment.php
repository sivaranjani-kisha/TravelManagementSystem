<?php

namespace App\Controllers;

use App\Models\PaymentModel;

class Payment extends BaseController
{
    /**
     * Show payment page
     */
    public function index()
    {
        return view('payment_view');
    }

    /**
     * Create Razorpay Order (AJAX)
     */
    public function createOrder()
    {
        $keyId     = "rzp_test_tczlMaH3gh6pl5";     // YOUR KEY ID
        $keySecret = "iKdT12MFb3QSxmLObCm8sXxO";       // YOUR KEY SECRET

        $amount = 500 * 100; // ₹500 -> paisa

        $data = [
            "amount"   => $amount,
            "currency" => "INR",
            "receipt"  => "rcpt_" . time()
        ];

        $ch = curl_init("https://api.razorpay.com/v1/orders");
        curl_setopt($ch, CURLOPT_USERPWD, $keyId . ":" . $keySecret);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($ch);
        curl_close($ch);

        $order = json_decode($response);

        echo json_encode([
            'key'      => $keyId,
            'amount'   => $amount,
            'order_id' => $order->id
        ]);
    }

    /**
     * Verify Razorpay Payment & Save to DB
     */
    public function verifyPayment()
    {
        $razorpay_order_id   = $this->request->getPost('razorpay_order_id');
        $razorpay_payment_id = $this->request->getPost('razorpay_payment_id');
        $razorpay_signature  = $this->request->getPost('razorpay_signature');

        $keySecret = "iKdT12MFb3QSxmLObCm8sXxO"; // YOUR KEY SECRET
        $amount    = 500; // should come from server/cart in real project

        // Generate signature
        $generated_signature = hash_hmac(
            'sha256',
            $razorpay_order_id . "|" . $razorpay_payment_id,
            $keySecret
        );

        $paymentModel = new PaymentModel();

        if ($generated_signature === $razorpay_signature) {

            // SUCCESS
            $paymentModel->insert([
                'order_id'   => $razorpay_order_id,
                'payment_id'=> $razorpay_payment_id,
                'signature' => $razorpay_signature,
                'amount'    => $amount,
                'status'    => 'SUCCESS'
            ]);

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Payment Successful & Saved'
            ]);

        } else {

            // FAILED
            $paymentModel->insert([
                'order_id'   => $razorpay_order_id,
                'payment_id'=> $razorpay_payment_id,
                'signature' => $razorpay_signature,
                'amount'    => $amount,
                'status'    => 'FAILED'
            ]);

            return $this->response->setJSON([
                'status'  => 'failed',
                'message' => 'Payment Verification Failed'
            ]);
        }
    }
}
