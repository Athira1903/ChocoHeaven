<?php
session_start();
require_once 'config/database.php';

// Include Razorpay PHP library
require_once 'razorpay-php/Razorpay.php'; // You'll need to download this

use Razorpay\Api\Api;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $amount = $input['amount'];
    $currency = 'INR';

    try {
        $api = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);
        
        $orderData = [
            'receipt'         => 'rcptid_' . time(),
            'amount'          => $amount, // amount in paise
            'currency'        => $currency,
            'payment_capture' => 1 // auto capture
        ];

        $razorpayOrder = $api->order->create($orderData);
        
        echo json_encode([
            'success' => true,
            'order_id' => $razorpayOrder['id']
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
?>