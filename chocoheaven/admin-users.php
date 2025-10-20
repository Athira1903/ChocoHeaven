<?php
require_once 'config/database.php';
require_once 'models/User.php';
require_once 'core/Session.php';

$session = new Session();
$session->requireAdmin();

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);

// Handle user actions
if ($_POST && isset($_POST['delete_user'])) {
    $userModel->delete($_POST['user_id']);
}

$users = $userModel->read();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - ChocoHeaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
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
                        <a class="nav-link text-white" href="admin-orders.php">Orders</a>
                        <a class="nav-link text-white active" href="admin-users.php">Users</a>
                        <a class="nav-link text-white" href="index.php">View Store</a>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <h2>Manage Users</h2>
        
        <!-- Users Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary"><?php echo $users->rowCount(); ?></h3>
                        <p class="text-muted">Total Users</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success"><?php echo date('M Y'); ?></h3>
                        <p class="text-muted">This Month</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning">0</h3>
                        <p class="text-muted">Active Today</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-info">₹0</h3>
                        <p class="text-muted">Avg. Spending</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users List -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>All Registered Users (<?php echo $users->rowCount(); ?>)</h5>
                <button class="btn btn-sm btn-outline-primary" onclick="exportUsers()">Export Users</button>
            </div>
            <div class="card-body">
                <?php if ($users->rowCount() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>User ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Joined Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($user = $users->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td>#<?php echo $user['id']; ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <?php 
                                            $joined = new DateTime($user['created_at']);
                                            echo $joined->format('M j, Y');
                                            ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Active</span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-info" 
                                                        onclick="viewUserDetails(<?php echo $user['id']; ?>)">
                                                    View
                                                </button>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    <button type="submit" name="delete_user" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Are you sure you want to delete user <?php echo $user['username']; ?>? This action cannot be undone.')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
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
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <h5>No Users Found</h5>
                        <p class="text-muted">No users have registered yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- User Details Modal -->
        <div class="modal fade" id="userDetailsModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">User Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="userDetailsContent">
                        <!-- User details will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewUserDetails(userId) {
            // In a real application, this would fetch user details via AJAX
            const userDetails = `
                <div class="text-center">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px; font-size: 2rem;">
                            👤
                        </div>
                    </div>
                    <h6>User #${userId}</h6>
                    <p class="text-muted">Detailed user information would be displayed here.</p>
                    <div class="row text-start">
                        <div class="col-6">
                            <small class="text-muted">Total Orders:</small>
                            <p><strong>0</strong></p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Total Spent:</small>
                            <p><strong>₹0.00</strong></p>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('userDetailsContent').innerHTML = userDetails;
            new bootstrap.Modal(document.getElementById('userDetailsModal')).show();
        }

        function exportUsers() {
            alert('User export functionality would be implemented here!');
            // In real app: window.location.href = 'export-users.php';
        }
    </script>
</body>
</html>