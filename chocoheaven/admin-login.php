<?php
require_once 'config/database.php';
require_once 'models/User.php';
require_once 'core/Session.php';

$session = new Session();
$database = new Database();
$db = $database->getConnection();

$error = '';

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Simple admin authentication (in real app, use proper admin table)
    if ($username === 'admin' && $password === 'admin123') {
        $session->set('admin_id', 1);
        $session->set('admin_username', 'admin');
        header("Location: admin-dashboard.php");
        exit;
    } else {
        $error = "Invalid admin credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - ChocoHeaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-dark text-white text-center">
                        <h4>🍫 ChocoHeaven Admin</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Admin Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-dark w-100">Admin Login</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="index.php" class="text-muted">Back to Store</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>