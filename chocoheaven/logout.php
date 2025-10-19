<?php
session_start();

// Store username for farewell message (optional)
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Destroy all session data
session_unset();
session_destroy();

// Set a farewell message in URL parameter
$message = $username ? "Goodbye, $username! Hope to see you again soon!" : "You have been logged out successfully.";

// Redirect to home page with message
header("Location: index.php?message=" . urlencode($message));
exit;
?>