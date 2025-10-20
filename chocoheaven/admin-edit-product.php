<?php
require_once 'config/database.php';
require_once 'models/Product.php';
require_once 'core/Session.php';

$session = new Session();
$session->requireAdmin();

$database = new Database();
$db = $database->getConnection();
$productModel = new Product($db);

// Get product ID from URL
$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    header("Location: admin-products.php");
    exit;
}

// Get product details
$product = $productModel->read($product_id);

if (!$product) {
    header("Location: admin-products.php");
    exit;
}

// Handle product update
if ($_POST && isset($_POST['update_product'])) {
    $updateData = [
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'price' => $_POST['price'],
        'image_url' => $_POST['image_url'],
        'category' => $_POST['category']
    ];
    
    $productModel->update($product_id, $updateData);
    header("Location: admin-products.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - ChocoHeaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin-dashboard.php">🍫 ChocoHeaven Admin</a>
            <div class="navbar-nav ms-auto">
                <a href="admin-products.php" class="btn btn-outline-light btn-sm me-2">Back to Products</a>
                <a href="admin-logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Product</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Product Name</label>
                                        <input type="text" class="form-control" name="name" 
                                               value="<?php echo htmlspecialchars($product['name']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Price (₹)</label>
                                        <input type="number" step="0.01" class="form-control" name="price" 
                                               value="<?php echo $product['price']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select class="form-select" name="category" required>
                                            <option value="Dark" <?php echo $product['category'] == 'Dark' ? 'selected' : ''; ?>>Dark Chocolate</option>
                                            <option value="Milk" <?php echo $product['category'] == 'Milk' ? 'selected' : ''; ?>>Milk Chocolate</option>
                                            <option value="White" <?php echo $product['category'] == 'White' ? 'selected' : ''; ?>>White Chocolate</option>
                                            <option value="Gift Box" <?php echo $product['category'] == 'Gift Box' ? 'selected' : ''; ?>>Gift Box</option>
                                            <option value="Premium" <?php echo $product['category'] == 'Premium' ? 'selected' : ''; ?>>Premium</option>
                                            <option value="Spiced" <?php echo $product['category'] == 'Spiced' ? 'selected' : ''; ?>>Spiced</option>
                                            <option value="Fusion" <?php echo $product['category'] == 'Fusion' ? 'selected' : ''; ?>>Indian Fusion</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="5" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Image URL</label>
                                        <input type="url" class="form-control" name="image_url" 
                                               value="<?php echo htmlspecialchars($product['image_url']); ?>" required>
                                        <?php if ($product['image_url']): ?>
                                            <div class="mt-2">
                                                <img src="<?php echo $product['image_url']; ?>" 
                                                     alt="Current image" 
                                                     style="max-width: 100px; max-height: 100px; object-fit: cover;"
                                                     class="border rounded">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="admin-products.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>