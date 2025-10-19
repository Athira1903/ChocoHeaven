<?php
require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/Order.php';
require_once 'models/OrderItem.php';
require_once 'core/Session.php';

$session = new Session();
$session->requireLogin();

$database = new Database();
$db = $database->getConnection();

$userModel = new User($db);
$orderModel = new Order($db);
$orderItemModel = new OrderItem($db);

$user = $userModel->read($session->get('user_id'));
$userOrders = $orderModel->getOrdersByUser($session->get('user_id'));
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

    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; font-size: 2rem;">
                                👤
                            </div>
                        </div>
                        <h5><?php echo htmlspecialchars($user['username']); ?></h5>
                        <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                        <div class="list-group list-group-flush">
                            <a href="#profile-info" class="list-group-item list-group-item-action active">Profile Info</a>
                            <a href="#order-history" class="list-group-item list-group-item-action">Order History</a>
                            <a href="#account-settings" class="list-group-item list-group-item-action">Account Settings</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Profile Information -->
                <div class="card mb-4" id="profile-info">
                    <div class="card-header bg-brown text-white">
                        <h4 class="mb-0">👤 Profile Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Username</strong></label>
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($user['username']); ?></p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Email Address</strong></label>
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($user['email']); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Member Since</strong></label>
                                    <p class="form-control-plaintext">
                                        <?php 
                                        $created = new DateTime($user['created_at']);
                                        echo $created->format('F j, Y');
                                        ?>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Account Status</strong></label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-success">Active</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-primary"><?php echo $userOrders->rowCount(); ?></h3>
                                <p class="text-muted">Total Orders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-success">₹0</h3>
                                <p class="text-muted">Total Spent</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="text-warning">0</h3>
                                <p class="text-muted">Pending Orders</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order History -->
                <div class="card mb-4" id="order-history">
                    <div class="card-header bg-brown text-white">
                        <h4 class="mb-0">📦 Order History</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($userOrders->rowCount() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($order = $userOrders->fetch(PDO::FETCH_ASSOC)): ?>
                                            <tr>
                                                <td>#CH<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                                <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                                <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                                <td>
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
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" 
                                                            onclick="viewOrderDetails(<?php echo $order['id']; ?>)">
                                                        View Details
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <div class="text-muted mb-3">
                                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                        <line x1="3" y1="6" x2="21" y2="6"></line>
                                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                                    </svg>
                                </div>
                                <h5>No orders yet</h5>
                                <p class="text-muted">Start shopping to see your order history here!</p>
                                <a href="products.php" class="btn btn-primary">Browse Products</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Account Settings -->
                <div class="card" id="account-settings">
                    <div class="card-header bg-brown text-white">
                        <h4 class="mb-0">⚙️ Account Settings</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-grid gap-2">
                                    <a href="cart.php" class="btn btn-outline-primary">
                                        🛒 View Shopping Cart
                                    </a>
                                    <a href="products.php" class="btn btn-outline-success">
                                        🍫 Continue Shopping
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        🔒 Change Password
                                    </button>
                                    <a href="logout.php" class="btn btn-outline-danger">
                                        🚪 Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Preferences Section -->
                        <div class="mt-4">
                            <h6>Preferences</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                <label class="form-check-label" for="emailNotifications">
                                    Email notifications for new products
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="specialOffers" checked>
                                <label class="form-check-label" for="specialOffers">
                                    Special offers and discounts
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="changePassword()">Update Password</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewOrderDetails(orderId) {
            alert('Order details for #CH' + orderId.toString().padStart(6, '0') + '\nThis would show detailed order information in a real application.');
            // In real application: window.location.href = 'order-details.php?id=' + orderId;
        }

        function changePassword() {
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (!currentPassword || !newPassword || !confirmPassword) {
                alert('Please fill all fields');
                return;
            }

            if (newPassword !== confirmPassword) {
                alert('New passwords do not match');
                return;
            }

            // Simulate password change (in real app, make AJAX call)
            alert('Password change functionality would be implemented here with proper backend validation.');
            
            // Close modal and reset form
            const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
            modal.hide();
            document.getElementById('changePasswordForm').reset();
        }

        // Smooth scroll for sidebar links
        document.querySelectorAll('.list-group-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                document.getElementById(targetId).scrollIntoView({
                    behavior: 'smooth'
                });
                
                // Update active state
                document.querySelectorAll('.list-group-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>

    <style>
        .bg-brown {
            background-color: #5D4037 !important;
        }
        
        .list-group-item.active {
            background-color: #5D4037;
            border-color: #5D4037;
        }
        
        .card {
            border: 1px solid #e0d6d1;
            border-radius: 10px;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: #5D4037;
        }
    </style>
</body>
</html>