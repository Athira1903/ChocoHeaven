<?php
require_once 'config/database.php';
require_once 'models/Order.php';
require_once 'models/OrderItem.php';
require_once 'models/User.php';
require_once 'core/Session.php';

$session = new Session();
$session->requireAdmin();

$database = new Database();
$db = $database->getConnection();

$orderModel = new Order($db);
$orderItemModel = new OrderItem($db);
$userModel = new User($db);

// Get order ID from URL
$order_id = $_GET['id'] ?? null;

if (!$order_id) {
    header("Location: admin-orders.php");
    exit;
}

// Get order details
$order = $orderModel->read($order_id);
$orderItems = $orderItemModel->getOrderItems($order_id);
$user = $userModel->read($order['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - ChocoHeaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin-dashboard.php">🍫 ChocoHeaven Admin</a>
            <div class="navbar-nav ms-auto">
                <a href="admin-orders.php" class="btn btn-outline-light btn-sm me-2">Back to Orders</a>
                <a href="admin-logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Order Details #<?php echo $order_id; ?></h2>
                    <span class="badge bg-<?php 
                        echo $order['status'] == 'completed' ? 'success' : 
                             ($order['status'] == 'pending' ? 'warning' : 'danger'); 
                    ?> fs-6">
                        <?php echo ucfirst($order['status']); ?>
                    </span>
                </div>

                <div class="row">
                    <!-- Order Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Order Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <strong>Order ID:</strong><br>
                                        <strong>Order Date:</strong><br>
                                        <strong>Total Amount:</strong><br>
                                        <strong>Status:</strong>
                                    </div>
                                    <div class="col-6">
                                        #<?php echo $order_id; ?><br>
                                        <?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?><br>
                                        ₹<?php echo number_format($order['total_amount'], 2); ?><br>
                                        <span class="badge bg-<?php 
                                            echo $order['status'] == 'completed' ? 'success' : 
                                                 ($order['status'] == 'pending' ? 'warning' : 'danger'); 
                                        ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="card">
                            <div class="card-header">
                                <h5>Customer Information</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($user): ?>
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>User ID:</strong><br>
                                            <strong>Username:</strong><br>
                                            <strong>Email:</strong><br>
                                            <strong>Member Since:</strong>
                                        </div>
                                        <div class="col-6">
                                            #<?php echo $user['id']; ?><br>
                                            <?php echo htmlspecialchars($user['username']); ?><br>
                                            <?php echo htmlspecialchars($user['email']); ?><br>
                                            <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">User information not available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Order Items</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($orderItems->rowCount() > 0): ?>
                                    <?php while ($item = $orderItems->fetch(PDO::FETCH_ASSOC)): ?>
                                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                            <img src="<?php echo $item['image_url']; ?>" 
                                                 alt="<?php echo $item['name']; ?>" 
                                                 class="rounded me-3" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1"><?php echo $item['name']; ?></h6>
                                                <p class="mb-1 text-muted">Quantity: <?php echo $item['quantity']; ?></p>
                                                <p class="mb-0">₹<?php echo number_format($item['price'], 2); ?> each</p>
                                            </div>
                                            <div class="text-end">
                                                <strong>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></strong>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                    
                                    <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                                        <strong>Total Amount:</strong>
                                        <strong class="fs-5">₹<?php echo number_format($order['total_amount'], 2); ?></strong>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">No items found in this order.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="admin-orders.php" class="btn btn-secondary">Back to Orders</a>
                            <div>
                                <button class="btn btn-warning me-2" onclick="updateOrderStatus()">Update Status</button>
                                <button class="btn btn-danger" onclick="cancelOrder()">Cancel Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateOrderStatus() {
            const newStatus = prompt('Enter new status (pending/completed/cancelled):');
            if (newStatus) {
                // In real app, make AJAX call to update status
                alert(`Order status would be updated to: ${newStatus}`);
            }
        }

        function cancelOrder() {
            if (confirm('Are you sure you want to cancel this order?')) {
                // In real app, make AJAX call to cancel order
                alert('Order would be cancelled');
            }
        }
    </script>
</body>
</html>