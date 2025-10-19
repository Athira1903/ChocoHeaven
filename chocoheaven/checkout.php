<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once 'config/database.php';

// Calculate cart total
$cart = json_decode($_POST['cart_data'] ?? '[]', true);
$total_amount = 0;

foreach ($cart as $item) {
    $total_amount += floatval($item['price']) * intval($item['quantity']);
}

// If cart is empty, redirect to cart page
if (empty($cart)) {
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - ChocoHeaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    
    <!-- Razorpay Checkout Integration -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Order Summary</h4>
                    </div>
                    <div class="card-body">
                        <?php foreach ($cart as $item): ?>
                            <div class="row mb-3 border-bottom pb-3">
                                <div class="col-md-8">
                                    <h6><?php echo htmlspecialchars($item['name']); ?></h6>
                                    <p class="text-muted mb-0">Quantity: <?php echo $item['quantity']; ?></p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <p class="fw-bold">₹<?php echo number_format(floatval($item['price']) * intval($item['quantity']), 2); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <div class="row mt-3">
                            <div class="col-md-8">
                                <h5>Total Amount</h5>
                            </div>
                            <div class="col-md-4 text-end">
                                <h5>₹<?php echo number_format($total_amount, 2); ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Payment</h4>
                    </div>
                    <div class="card-body">
                        <p>Complete your purchase using Razorpay secure payment gateway.</p>
                        
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" value="athira" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="athirabjiu2027@mca.ajce.in" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" value="8086241092" required>
                        </div>
                        
                        <input type="hidden" id="total_amount" value="<?php echo $total_amount * 100; ?>">
                        <input type="hidden" id="cart_data" value='<?php echo json_encode($cart); ?>'>
                        
                        <button type="button" class="btn btn-success w-100" onclick="initiatePayment()">
                            Pay ₹<?php echo number_format($total_amount, 2); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        function initiatePayment() {
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const amount = document.getElementById('total_amount').value;
            const cartData = document.getElementById('cart_data').value;

            // Create options for Razorpay
            const options = {
                key: "rzp_test_qz3vZymFK7JynA", // Your Razorpay Key ID
                amount: amount, // Amount in paise
                currency: "INR",
                name: "ChocoHeaven",
                description: "Chocolate Order Payment",
                image: "https://example.com/your_logo", // Add your logo URL
                handler: function (response) {
                    // Payment successful
                    alert('Payment Successful! Payment ID: ' + response.razorpay_payment_id);
                    
                    // Clear cart
                    localStorage.removeItem('chocoheaven_cart');
                    
                    // Redirect to success page
                    window.location.href = 'payment_success.php?payment_id=' + response.razorpay_payment_id;
                },
                prefill: {
                    name: name,
                    email: email,
                    contact: phone
                },
                theme: {
                    color: "#F37254"
                }
            };

            const rzp = new Razorpay(options);
            rzp.open();
            
            // Handle payment failure
            rzp.on('payment.failed', function (response) {
                alert('Payment Failed. Please try again. Error: ' + response.error.description);
                console.log(response.error);
            });
        }
    </script>
</body>
</html>