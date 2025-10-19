<?php
$success = '';
$error = '';

if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    // In a real application, you would send an email here
    // For now, we'll just show a success message
    $success = "Thank you for your message, $name! We'll get back to you within 24 hours.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - ChocoHeaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h1 class="text-center mb-4">Contact ChocoHeaven</h1>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h4>Send us a Message</h4>
                                <form method="POST">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Your Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="subject" class="form-label">Subject</label>
                                        <select class="form-select" id="subject" name="subject" required>
                                            <option value="">Choose a subject</option>
                                            <option value="general">General Inquiry</option>
                                            <option value="order">Order Support</option>
                                            <option value="wholesale">Wholesale Inquiry</option>
                                            <option value="feedback">Feedback</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Send Message</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5>Contact Information</h5>
                                <div class="mb-3">
                                    <strong>📍 Store Address</strong>
                                    <p>123 Chocolate Lane,<br>Khan Market,<br>New Delhi - 110003</p>
                                </div>
                                <div class="mb-3">
                                    <strong>📍 Factory Address</strong>
                                    <p>45 Cocoa Street,<br>Industrial Area,<br>Mumbai - 400072</p>
                                </div>
                                <div class="mb-3">
                                    <strong>📞 Phone Numbers</strong>
                                    <p>Delhi: +91 11 2654 7890<br>Mumbai: +91 22 2789 4563<br>Toll Free: 1800-123-4567</p>
                                </div>
                                <div class="mb-3">
                                    <strong>📧 Email</strong>
                                    <p>orders@chocoheaven.com<br>support@chocoheaven.com<br>info@chocoheaven.com</p>
                                </div>
                                <div class="mb-3">
                                    <strong>🕒 Business Hours</strong>
                                    <p>Monday - Sunday: 9AM - 9PM<br>Express Delivery Available</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5>Our Stores Across India</h5>
                                <ul class="list-unstyled small">
                                    <li>🏪 Delhi - Khan Market</li>
                                    <li>🏪 Mumbai - Colaba</li>
                                    <li>🏪 Bangalore - MG Road</li>
                                    <li>🏪 Chennai - T Nagar</li>
                                    <li>🏪 Kolkata - Park Street</li>
                                    <li>🏪 Hyderabad - Banjara Hills</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>