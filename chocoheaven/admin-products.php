<?php
require_once 'config/database.php';
require_once 'models/Product.php';
require_once 'core/Session.php';

$session = new Session();
if (!$session->get('admin_id')) {
    header("Location: admin-login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$productModel = new Product($db);

// Handle product actions
if ($_POST) {
    if (isset($_POST['add_product'])) {
        $productData = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'image_url' => $_POST['image_url'],
            'category' => $_POST['category']
        ];
        $productModel->create($productData);
        header("Location: admin-products.php");
        exit;
    } elseif (isset($_POST['delete_product'])) {
        $productModel->delete($_POST['product_id']);
        header("Location: admin-products.php");
        exit;
    }
}

$products = $productModel->read();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - ChocoHeaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table img {
            border-radius: 5px;
        }
        .badge {
            font-size: 0.75em;
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
                        <a class="nav-link text-white active" href="admin-products.php">Products</a>
                        <a class="nav-link text-white" href="admin-orders.php">Orders</a>
                        <a class="nav-link text-white" href="admin-users.php">Users</a>
                        <a class="nav-link text-white" href="index.php">View Store</a>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2>Manage Products</h2>
                
                <!-- Add Product Form -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">➕ Add New Product</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Product Name</label>
                                        <input type="text" class="form-control" name="name" placeholder="Enter product name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Price (₹)</label>
                                        <input type="number" step="0.01" class="form-control" name="price" placeholder="0.00" min="0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select class="form-select" name="category" required>
                                            <option value="">Select Category</option>
                                            <option value="Dark">Dark Chocolate</option>
                                            <option value="Milk">Milk Chocolate</option>
                                            <option value="White">White Chocolate</option>
                                            <option value="Gift Box">Gift Box</option>
                                            <option value="Premium">Premium Collection</option>
                                            <option value="Spiced">Spiced Chocolates</option>
                                            <option value="Regional">Regional Specials</option>
                                            <option value="Fusion">Indian Fusion</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="4" placeholder="Enter product description" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Image URL</label>
                                        <input type="url" class="form-control" name="image_url" placeholder="https://example.com/image.jpg" required>
                                        <div class="form-text">Enter a valid image URL from Unsplash or other sources</div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <button type="reset" class="btn btn-secondary">Reset Form</button>
                                <button type="submit" name="add_product" class="btn btn-success">
                                    ➕ Add Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Products List -->
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">📦 All Products (<?php echo $products->rowCount(); ?>)</h5>
                        <span class="badge bg-light text-dark">Total: <?php echo $products->rowCount(); ?> products</span>
                    </div>
                    <div class="card-body">
                        <?php if ($products->rowCount() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($product = $products->fetch(PDO::FETCH_ASSOC)): ?>
                                            <tr>
                                                <td><strong>#<?php echo $product['id']; ?></strong></td>
                                                <td>
                                                    <img src="<?php echo $product['image_url']; ?>" 
                                                         alt="<?php echo $product['name']; ?>" 
                                                         style="width: 60px; height: 60px; object-fit: cover;"
                                                         class="border rounded"
                                                         onerror="this.src='https://via.placeholder.com/60x60/5D4037/FFFFFF?text=No+Image'">
                                                </td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?php echo substr($product['description'], 0, 50); ?>...</small>
                                                </td>
                                                <td>
                                                    <span class="badge 
                                                        <?php 
                                                        $badgeColors = [
                                                            'Dark' => 'bg-dark',
                                                            'Milk' => 'bg-warning text-dark',
                                                            'White' => 'bg-light text-dark',
                                                            'Gift Box' => 'bg-primary',
                                                            'Premium' => 'bg-danger',
                                                            'Spiced' => 'bg-warning',
                                                            'Regional' => 'bg-info',
                                                            'Fusion' => 'bg-success'
                                                        ];
                                                        echo $badgeColors[$product['category']] ?? 'bg-secondary';
                                                        ?>">
                                                        <?php echo $product['category']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong class="text-success">₹<?php echo number_format($product['price'], 2); ?></strong>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php echo date('M j, Y', strtotime($product['created_at'])); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="admin-edit-product.php?id=<?php echo $product['id']; ?>" 
                                                           class="btn btn-sm btn-outline-primary" title="Edit Product">
                                                            ✏️ Edit
                                                        </a>
                                                        <form method="POST" class="d-inline">
                                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                            <button type="submit" name="delete_product" class="btn btn-sm btn-outline-danger" 
                                                                    onclick="return confirm('Are you sure you want to delete \"<?php echo $product['name']; ?>\"? This action cannot be undone.')"
                                                                    title="Delete Product">
                                                                🗑️ Delete
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
                            <div class="text-center py-5">
                                <div class="text-muted mb-3">
                                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                </div>
                                <h5>No Products Found</h5>
                                <p class="text-muted">Start by adding your first chocolate product using the form above.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>