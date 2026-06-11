<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Only destroy user session variables, keep admin session intact
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
header("Location: /Atlast/login.php");
exit();
?>
