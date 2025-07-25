<?php
session_start();

// Redirect to login page if not logged in
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Handle logout
if(isset($_GET['logout'])) {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="..\Style\dashboardStyle.css">
</head>
<body>
    <div class="container">
        
        <h1>Welcome to the Dashboard</h1>
        
        <div class="welcome-message">
            <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>! You have successfully logged in.</p>
        </div>
        
        <div class="user-info">
            <h1>Welcome to Dashboard </h1>
            <h2>Your Account Information</h2>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p><strong>User ID:</strong> <?php echo htmlspecialchars($_SESSION['id']); ?></p>
        </div>
        
        <p>This is your secure dashboard area. You can add your application content here.</p>
        
        <a href="dashboard.php?logout=true" class="logout-btn">Logout</a>
    </div>
</body>
</html>
