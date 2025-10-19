<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - ChocoHeaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">👤 My Profile</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Account Information</h5>
                                <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
                                <p><strong>Member since:</strong> <?php echo date('F Y'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5>Quick Actions</h5>
                                <div class="d-grid gap-2">
                                    <a href="orders.php" class="btn btn-outline-primary">My Orders</a>
                                    <a href="cart.php" class="btn btn-outline-success">View Cart</a>
                                    <a href="products.php" class="btn btn-outline-info">Continue Shopping</a>
                                    <a href="logout.php" class="btn btn-outline-danger">Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Section -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <p>Welcome to your ChocoHeaven dashboard! Here you can:</p>
                        <ul>
                            <li>View your order history</li>
                            <li>Track your deliveries</li>
                            <li>Update your preferences</li>
                            <li>Manage your chocolate subscriptions</li>
                        </ul>
                        <div class="alert alert-info">
                            <small>✨ <strong>Pro Tip:</strong> Save your favorite chocolates for quick reordering!</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>