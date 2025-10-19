<?php
require_once 'config/database.php';
require_once 'models/Product.php';
require_once 'core/Session.php';

$session = new Session();
$database = new Database();
$db = $database->getConnection();

$productModel = new Product($db);

// Handle search and filter
$searchTerm = $_GET['search'] ?? '';
$categoryFilter = $_GET['category'] ?? '';

if ($searchTerm) {
    $products = $productModel->searchProducts($searchTerm);
} elseif ($categoryFilter) {
    $products = $productModel->readByCategory($categoryFilter);
} else {
    $products = $productModel->read();
}

function getBadgeColor($category) {
    $colors = [
        'Dark' => 'bg-dark',
        'Milk' => 'bg-warning text-dark',
        'White' => 'bg-light text-dark',
        'Premium' => 'bg-danger',
        'Spiced' => 'bg-warning',
        'Regional' => 'bg-info',
        'Truffles' => 'bg-success',
        'Floral' => 'bg-purple',
        'Gift Box' => 'bg-primary',
        'Fruity' => 'bg-orange',
        'Fusion' => 'bg-indigo'
    ];
    return $colors[$category] ?? 'bg-secondary';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products - ChocoHeaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Our Complete Chocolate Collection</h2>
        
        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="products.php" id="filterForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="search" class="form-label">Search Chocolates</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           value="<?php echo htmlspecialchars($searchTerm); ?>" 
                                           placeholder="Find your favorite chocolate...">
                                </div>
                                <div class="col-md-6">
                                    <label for="category" class="form-label">Filter by Category</label>
                                    <select class="form-select" id="category" name="category" onchange="document.getElementById('filterForm').submit()">
                                        <option value="">All Categories</option>
                                        <option value="Dark" <?php echo $categoryFilter == 'Dark' ? 'selected' : ''; ?>>Dark Chocolate</option>
                                        <option value="Milk" <?php echo $categoryFilter == 'Milk' ? 'selected' : ''; ?>>Milk Chocolate</option>
                                        <option value="White" <?php echo $categoryFilter == 'White' ? 'selected' : ''; ?>>White Chocolate</option>
                                        <option value="Premium" <?php echo $categoryFilter == 'Premium' ? 'selected' : ''; ?>>Premium Collection</option>
                                        <option value="Spiced" <?php echo $categoryFilter == 'Spiced' ? 'selected' : ''; ?>>Spiced Chocolates</option>
                                        <option value="Regional" <?php echo $categoryFilter == 'Regional' ? 'selected' : ''; ?>>Regional Specials</option>
                                        <option value="Gift Box" <?php echo $categoryFilter == 'Gift Box' ? 'selected' : ''; ?>>Gift Boxes</option>
                                        <option value="Fusion" <?php echo $categoryFilter == 'Fusion' ? 'selected' : ''; ?>>Indian Fusion</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary me-2">Apply Filters</button>
                                    <a href="products.php" class="btn btn-outline-secondary">Clear Filters</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Counter -->
        <div class="row mb-3">
            <div class="col-12">
                <p class="text-muted">
                    Showing <?php echo $products->rowCount(); ?> product(s)
                    <?php if ($searchTerm): ?>
                        for "<?php echo htmlspecialchars($searchTerm); ?>"
                    <?php endif; ?>
                    <?php if ($categoryFilter): ?>
                        in <?php echo $categoryFilter; ?> category
                    <?php endif; ?>
                </p>
            </div>
        </div>
        
        <div class="row">
            <?php if ($products->rowCount() > 0): ?>
                <?php while ($row = $products->fetch(PDO::FETCH_ASSOC)): ?>
                    <?php $badge_color = getBadgeColor($row['category']); ?>
                    <div class='col-lg-4 col-md-6 mb-4'>
                        <div class='card h-100 product-card'>
                            <img src='<?php echo $row['image_url']; ?>' class='card-img-top' alt='<?php echo $row['name']; ?>'
                                 onerror="this.src='https://images.unsplash.com/photo-1575377427642-087cf684f29d?w=400&q=80'"
                                 style='height: 250px; object-fit: cover;'>
                            <div class='card-body d-flex flex-column'>
                                <h5 class='card-title text-brown'><?php echo $row['name']; ?></h5>
                                <span class='badge <?php echo $badge_color; ?> mb-2'><?php echo $row['category']; ?></span>
                                <p class='card-text flex-grow-1 text-muted'><?php echo $row['description']; ?></p>
                                <div class='mt-auto'>
                                    <p class='price h5 text-accent'><strong>₹<?php echo $row['price']; ?></strong></p>
                                    <button class='btn btn-primary add-to-cart w-100' 
                                            data-id='<?php echo $row['id']; ?>' 
                                            data-name='<?php echo $row['name']; ?>' 
                                            data-price='<?php echo $row['price']; ?>'
                                            data-category='<?php echo $row['category']; ?>'>
                                        🛒 Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="card">
                        <div class="card-body py-5">
                            <h4 class="text-muted">No chocolates found</h4>
                            <p class="text-muted">Try adjusting your search or filter criteria</p>
                            <a href="products.php" class="btn btn-outline-primary">Clear Filters</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>