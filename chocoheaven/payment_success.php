<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$payment_id = $_GET['payment_id'] ?? 'Unknown';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - ChocoHeaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="text-success mb-4">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <h2 class="text-success">Payment Successful!</h2>
                        <p class="lead">Thank you for your order at ChocoHeaven</p>
                        <p>Your Payment ID: <strong><?php echo htmlspecialchars($payment_id); ?></strong></p>
                        <p>Your chocolates will be delivered soon!</p>
                        
                        <div class="mt-4">
                            <a href="orders.php" class="btn btn-primary me-2">View Orders</a>
                            <a href="index.php" class="btn btn-outline-primary">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>