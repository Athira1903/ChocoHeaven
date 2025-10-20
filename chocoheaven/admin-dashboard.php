<?php
require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/Product.php';
require_once 'models/Order.php';
require_once 'core/Session.php';

$session = new Session();

// Redirect if not admin
if (!$session->get('admin_id')) {
    header("Location: admin-login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

$userModel = new User($db);
$productModel = new Product($db);
$orderModel = new Order($db);

// Get statistics
$totalUsers = $userModel->read()->rowCount();
$totalProducts = $productModel->read()->rowCount();
$totalOrders = $orderModel->read()->rowCount();

// Get recent orders
$recentOrders = $orderModel->read();
$recentOrders->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ChocoHeaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Admin Header -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin-dashboard.php">
                🍫 ChocoHeaven Admin
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    Welcome, <?php echo $session->get('admin_username'); ?>
                </span>
                <a href="admin-logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Admin Navigation -->
    <div class="bg-secondary py-2">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="nav">
                        <a class="nav-link text-white" href="admin-dashboard.php">Dashboard</a>
                        <a class="nav-link text-white" href="admin-products.php">Products</a>
                        <a class="nav-link text-white" href="admin-orders.php">Orders</a>
                        <a class="nav-link text-white" href="admin-users.php">Users</a>
                        <a class="nav-link text-white" href="index.php">View Store</a>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <h2>Admin Dashboard</h2>
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary"><?php echo $totalUsers; ?></h3>
                        <p class="text-muted">Total Users</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success"><?php echo $totalProducts; ?></h3>
                        <p class="text-muted">Total Products</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning"><?php echo $totalOrders; ?></h3>
                        <p class="text-muted">Total Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-info">₹0</h3>
                        <p class="text-muted">Total Revenue</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card">
            <div class="card-header">
                <h5>Recent Orders</h5>
            </div>
            <div class="card-body">
                <?php if ($recentOrders->rowCount() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>User ID</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = $recentOrders->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td>User #<?php echo $order['user_id']; ?></td>
                                        <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $order['status'] == 'completed' ? 'success' : 
                                                     ($order['status'] == 'pending' ? 'warning' : 'danger'); 
                                            ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">View</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No orders found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>