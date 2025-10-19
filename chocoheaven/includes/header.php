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
    <!-- Navigation Bar - Brown Theme -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #4A2C2A;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                🍫 ChocoHeaven
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            🛒 Cart <span id="cart-count" class="badge" style="background-color: #D2691E;">0</span>
                        </a>
                    </li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <!-- Display when user is logged in -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="profile.php" role="button" data-bs-toggle="dropdown">
                                👋 <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="profile.php">
                                    👤 My Profile
                                </a></li>
                                <li><a class="dropdown-item" href="orders.php">
                                    📦 My Orders
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout.php">
                                    🚪 Logout
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Display when user is not logged in -->
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Welcome Message Banner for Logged-in Users - Reduced Height -->
    <?php if(isset($_SESSION['user_id'])): ?>
    <div class="py-1" style="background: linear-gradient(135deg, #8B4513, #A0522D);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <small class="text-white">🎉 Welcome back, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! Ready for some delicious chocolates?</small>
                </div>
                <div class="col-md-4 text-end">
                    <small>
                        <a href="profile.php" class="text-white text-decoration-underline me-3">View Profile</a>
                        <a href="logout.php" class="text-white text-decoration-underline">Logout</a>
                    </small>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Hero Section - Brown Theme & Reduced Height -->
    <div class="py-3 mb-3 text-white" style="background: linear-gradient(135deg, #5D4037, #8D6E63);">
        <div class="container text-center">
            <h1 class="display-5 mb-2">Welcome to ChocoHeaven</h1>
            <p class="lead mb-2">Indulge in our premium collection of handcrafted chocolates</p>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <div class="mt-2">
                    <a href="register.php" class="btn btn-light btn-sm me-2">Join Now</a>
                    <a href="login.php" class="btn btn-outline-light btn-sm">Login</a>
                </div>
            <?php else: ?>
                <div class="mt-2">
                    <a href="products.php" class="btn btn-light btn-sm me-2">Shop Now</a>
                    <a href="profile.php" class="btn btn-outline-light btn-sm">My Profile</a>
                </div>
            <?php endif; ?>
        </div>
    </div>