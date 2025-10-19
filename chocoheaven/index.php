<?php
require_once 'config/database.php';
require_once 'models/Product.php';
require_once 'core/Session.php';

$session = new Session();
$database = new Database();
$db = $database->getConnection();

$productModel = new Product($db);
$products = $productModel->read();

$message = '';
$message_type = 'info';

if (isset($_GET['message'])) {
    $message = $_GET['message'];
    $message_type = 'info';
}
if (isset($_GET['welcome'])) {
    $message = $_GET['welcome'];
    $message_type = 'success';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChocoHeaven - Premium Chocolate Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <?php if ($message): ?>
    <div class="container mt-3">
        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Our Divine Chocolate Collection</h2>
        <div class="row">
            <?php if ($products->rowCount() > 0): ?>
                <?php while ($row = $products->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class='col-md-4 mb-4'>
                        <div class='card h-100'>
                            <img src='<?php echo $row['image_url']; ?>' class='card-img-top' alt='<?php echo $row['name']; ?>' style='height: 250px; object-fit: cover;'>
                            <div class='card-body d-flex flex-column'>
                                <h5 class='card-title'><?php echo $row['name']; ?></h5>
                                <p class='card-text flex-grow-1'><?php echo $row['description']; ?></p>
                                <div class='mt-auto'>
                                    <p class='price'><strong>₹<?php echo $row['price']; ?></strong></p>
                                    <button class='btn btn-primary add-to-cart' 
                                            data-id='<?php echo $row['id']; ?>' 
                                            data-name='<?php echo $row['name']; ?>' 
                                            data-price='<?php echo $row['price']; ?>'>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class='col-12'><p class='text-center'>No products found. Please add some products to the database.</p></div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>