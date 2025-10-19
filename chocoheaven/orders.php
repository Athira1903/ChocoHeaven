<?php
require_once 'config/database.php';
require_once 'models/Order.php';
require_once 'models/OrderItem.php';
require_once 'core/Session.php';

$session = new Session();
$session->requireLogin();

$database = new Database();
$db = $database->getConnection();

$orderModel = new Order($db);
$orderItemModel = new OrderItem($db);

$userOrders = $orderModel->getOrdersByUser($session->get('user_id'));
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

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="profile.php">Profile</a></li>
                        <li class="breadcrumb-item active">My Orders</li>
                    </ol>
                </nav>
                
                <h2 class="mb-4">📦 My Orders</h2>

                <?php if ($userOrders->rowCount() > 0): ?>
                    <div class="row">
                        <?php while ($order = $userOrders->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Order #CH<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></h6>
                                        <span class="badge 
                                            <?php 
                                            switch($order['status']) {
                                                case 'completed': echo 'bg-success'; break;
                                                case 'pending': echo 'bg-warning'; break;
                                                case 'cancelled': echo 'bg-danger'; break;
                                                default: echo 'bg-secondary';
                                            }
                                            ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">Order Date</small>
                                                <p class="mb-1"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></p>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Total Amount</small>
                                                <p class="mb-1 fw-bold">₹<?php echo number_format($order['total_amount'], 2); ?></p>
                                            </div>
                                        </div>
                                        
                                        <!-- Order Items Preview -->
                                        <?php 
                                        $orderItems = $orderItemModel->getOrderItems($order['id']);
                                        if ($orderItems->rowCount() > 0):
                                            $firstItem = $orderItems->fetch(PDO::FETCH_ASSOC);
                                        ?>
                                            <div class="mt-3 p-2 bg-light rounded">
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo $firstItem['image_url']; ?>" 
                                                         alt="<?php echo $firstItem['name']; ?>" 
                                                         class="rounded me-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                    <div class="flex-grow-1">
                                                        <p class="mb-0 small"><?php echo $firstItem['name']; ?></p>
                                                        <p class="mb-0 text-muted small">Qty: <?php echo $firstItem['quantity']; ?> × ₹<?php echo $firstItem['price']; ?></p>
                                                    </div>
                                                </div>
                                                <?php if ($orderItems->rowCount() > 1): ?>
                                                    <p class="mb-0 mt-1 text-center small text-muted">
                                                        +<?php echo ($orderItems->rowCount() - 1); ?> more item(s)
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-between">
                                            <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                View Details
                                            </a>
                                            <?php if ($order['status'] == 'completed'): ?>
                                                <button class="btn btn-sm btn-outline-success" onclick="reorder(<?php echo $order['id']; ?>)">
                                                    Reorder
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="card">
                            <div class="card-body py-5">
                                <div class="text-muted mb-4">
                                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                        <line x1="3" y1="6" x2="21" y2="6"></line>
                                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                                    </svg>
                                </div>
                                <h4 class="text-muted">No orders yet</h4>
                                <p class="text-muted mb-4">Start your chocolate journey with our delicious collection!</p>
                                <a href="products.php" class="btn btn-primary me-2">Browse Products</a>
                                <a href="index.php" class="btn btn-outline-primary">Back to Home</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function reorder(orderId) {
            if (confirm('Add all items from this order to your cart?')) {
                // In a real application, this would make an AJAX call to add items to cart
                alert('Reorder functionality would be implemented here!');
                // Example: window.location.href = 'reorder.php?id=' + orderId;
            }
        }

        function viewOrderDetails(orderId) {
            // In a real application, this would redirect to order details page
            alert('Order details for #CH' + orderId.toString().padStart(6, '0'));
            // window.location.href = 'order-details.php?id=' + orderId;
        }
    </script>

    <style>
        .breadcrumb {
            background-color: transparent;
            padding: 0;
        }
        
        .card {
            border: 1px solid #e0d6d1;
            border-radius: 10px;
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(93, 64, 55, 0.1);
        }
    </style>
</body>
</html>