<?php
require_once 'config/database.php';
require_once 'models/Order.php';
require_once 'core/Session.php';

$session = new Session();
if (!$session->get('admin_id')) {
    header("Location: admin-login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$orderModel = new Order($db);

// Handle order status update
if ($_POST && isset($_POST['update_status'])) {
    $orderModel->update($_POST['order_id'], ['status' => $_POST['status']]);
    header("Location: admin-orders.php");
    exit;
}

$orders = $orderModel->read();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - ChocoHeaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .badge {
            font-size: 0.75em;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin-dashboard.php">🍫 ChocoHeaven Admin</a>
            <div class="navbar-nav ms-auto">
                <a href="admin-dashboard.php" class="btn btn-outline-light btn-sm me-2">Dashboard</a>
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
                        <a class="nav-link text-white active" href="admin-orders.php">Orders</a>
                        <a class="nav-link text-white" href="admin-users.php">Users</a>
                        <a class="nav-link text-white" href="index.php">View Store</a>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <h2>📦 Manage Orders</h2>
        
        <!-- Order Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary"><?php echo $orders->rowCount(); ?></h3>
                        <p class="text-muted">Total Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning">
                            <?php 
                            $pendingCount = 0;
                            $orders->execute();
                            while ($order = $orders->fetch(PDO::FETCH_ASSOC)) {
                                if ($order['status'] == 'pending') $pendingCount++;
                            }
                            $orders->execute(); // Reset pointer
                            echo $pendingCount; 
                            ?>
                        </h3>
                        <p class="text-muted">Pending</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success">
                            <?php 
                            $completedCount = 0;
                            $orders->execute();
                            while ($order = $orders->fetch(PDO::FETCH_ASSOC)) {
                                if ($order['status'] == 'completed') $completedCount++;
                            }
                            $orders->execute(); // Reset pointer
                            echo $completedCount; 
                            ?>
                        </h3>
                        <p class="text-muted">Completed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-danger">
                            <?php 
                            $cancelledCount = 0;
                            $orders->execute();
                            while ($order = $orders->fetch(PDO::FETCH_ASSOC)) {
                                if ($order['status'] == 'cancelled') $cancelledCount++;
                            }
                            $orders->execute(); // Reset pointer
                            echo $cancelledCount; 
                            ?>
                        </h3>
                        <p class="text-muted">Cancelled</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📋 All Orders (<?php echo $orders->rowCount(); ?>)</h5>
                <div>
                    <span class="badge bg-warning text-dark me-2">Pending: <?php echo $pendingCount; ?></span>
                    <span class="badge bg-success me-2">Completed: <?php echo $completedCount; ?></span>
                    <span class="badge bg-danger">Cancelled: <?php echo $cancelledCount; ?></span>
                </div>
            </div>
            <div class="card-body">
                <?php if ($orders->rowCount() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Order ID</th>
                                    <th>User ID</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                    <th>Update Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = $orders->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td>
                                            <strong>#<?php echo $order['id']; ?></strong>
                                        </td>
                                        <td>
                                            <span class="text-muted">User #<?php echo $order['user_id']; ?></span>
                                        </td>
                                        <td>
                                            <strong class="text-success">₹<?php echo number_format($order['total_amount'], 2); ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $order['status'] == 'completed' ? 'success' : 
                                                     ($order['status'] == 'pending' ? 'warning' : 'danger'); 
                                            ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo date('M j, Y', strtotime($order['created_at'])); ?>
                                                <br>
                                                <small><?php echo date('g:i A', strtotime($order['created_at'])); ?></small>
                                            </small>
                                        </td>
                                        <td>
                                            <a href="admin-order-details.php?id=<?php echo $order['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary" title="View Order Details">
                                                👁️ View Details
                                            </a>
                                        </td>
                                        <td>
                                            <form method="POST" class="d-flex align-items-center">
                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                <select name="status" class="form-select form-select-sm me-2" style="min-width: 120px;">
                                                    <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>⏳ Pending</option>
                                                    <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>✅ Completed</option>
                                                    <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>❌ Cancelled</option>
                                                </select>
                                                <button type="submit" name="update_status" class="btn btn-primary btn-sm" title="Update Status">
                                                    📤 Update
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="text-muted mb-3">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                        </div>
                        <h5>No Orders Found</h5>
                        <p class="text-muted">No customers have placed orders yet.</p>
                        <a href="admin-dashboard.php" class="btn btn-primary">Back to Dashboard</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>