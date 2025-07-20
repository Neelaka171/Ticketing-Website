<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}
echo "Welcome User, " . $_SESSION['name'];
echo "<br><a href='logout.php'>Logout</a>";
?>