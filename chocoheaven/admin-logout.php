<?php
require_once 'core/Session.php';

$session = new Session();
$session->remove('admin_id');
$session->remove('admin_username');
header("Location: admin-login.php");
exit;
?>