<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - ChocoHeaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">My Orders</h2>
        
        <div class="card">
            <div class="card-body">
                <div class="text-center py-4">
                    <h5>No orders yet</h5>
                    <p class="text-muted">Start shopping to see your orders here!</p>
                    <a href="products.php" class="btn btn-primary">Browse Products</a>
                </div>
                
                <!-- Sample future order (commented out for now) -->
                <!--
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6>Order #CH12345</h6>
                                <p class="mb-1">Placed on: January 15, 2024</p>
                                <p class="mb-1">Status: <span class="badge bg-success">Delivered</span></p>
                            </div>
                            <div class="col-md-4 text-end">
                                <h6>Total: ₹1,250.00</h6>
                                <button class="btn btn-outline-primary btn-sm">View Details</button>
                                <button class="btn btn-outline-success btn-sm">Reorder</button>
                            </div>
                        </div>
                    </div>
                </div>
                -->
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>