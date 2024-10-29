<?php
session_start();
if (isset($_POST['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: login.php"); // Redirect to login
    exit();
}
?>
