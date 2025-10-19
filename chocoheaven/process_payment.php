<?php
session_start();
require_once 'config/database.php';

// Include Razorpay PHP library
require_once 'razorpay-php/Razorpay.php';

use Razorpay\Api\Api;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $paymentResponse = $input['payment_response'];
    $cartData = $input['cart_data'];
    $amount = $input['amount'];

    try {
        $api = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);
        
        // Verify payment signature
        $attributes = array(
            'razorpay_order_id' => $paymentResponse['razorpay_order_id'],
            'razorpay_payment_id' => $paymentResponse['razorpay_payment_id'],
            'razorpay_signature' => $paymentResponse['razorpay_signature']
        );

        $api->utility->verifyPaymentSignature($attributes);
        
        // Payment verified successfully - Save order to database
        $database = new Database();
        $db = $database->getConnection();
        
        // Calculate total amount
        $cart = json_decode($cartData, true);
        $total_amount = 0;
        foreach ($cart as $item) {
            $total_amount += floatval($item['price']) * intval($item['quantity']);
        }
        
        // Save order to database
        $query = "INSERT INTO orders (user_id, total_amount, status) VALUES (:user_id, :total_amount, 'completed')";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':total_amount', $total_amount);
        $stmt->execute();
        
        $order_id = $db->lastInsertId();
        
        // Save order items
        foreach ($cart as $item) {
            $query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                      VALUES (:order_id, :product_id, :quantity, :price)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':product_id', $item['id']);
            $stmt->bindParam(':quantity', $item['quantity']);
            $stmt->bindParam(':price', $item['price']);
            $stmt->execute();
        }
        
        // Save payment details
        $query = "INSERT INTO payments (order_id, razorpay_payment_id, razorpay_order_id, amount, status) 
                  VALUES (:order_id, :payment_id, :order_id_razorpay, :amount, 'success')";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':payment_id', $paymentResponse['razorpay_payment_id']);
        $stmt->bindParam(':order_id_razorpay', $paymentResponse['razorpay_order_id']);
        $stmt->bindParam(':amount', $total_amount);
        $stmt->execute();
        
        echo json_encode([
            'success' => true,
            'order_id' => $order_id
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
?>