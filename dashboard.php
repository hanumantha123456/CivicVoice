<?php
// Include database connection and session management
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicVoice Dashboard</title>
    <link rel="stylesheet" href="css/style7.css"> <!-- Link to external CSS file -->
</head>
<body>
    <header class="dashboard-header">
        <div class="logo">
            <h1>CivicVoice</h1>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="user_login.php">User Login</a></li>
                <li><a href="officer_login.php">Officer Login</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="profile-menu">
            <?php if (isset($_SESSION['user'])): ?>
                <span>Hello, <?php echo $_SESSION['user']['username']; ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            <?php elseif (isset($_SESSION['officer'])): ?>
                <span>Hello, Officer <?php echo $_SESSION['officer']['username']; ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            <?php else: ?>
                <a href="login.php" class="login-btn">Login</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="dashboard-main">
        <div class="sidebar">
            <ul>
                <li><a href="submit_problem.php">Submit Problem</a></li>
                <li><a href="view_problems.php">View Problems</a></li>
                <li><a href="analytics.php">Analytics</a></li>
                
            </ul>
        </div>
        <div class="content">
            <h2>Welcome to CivicVoice Dashboard</h2>
            <p>This is the centralized hub for managing and tracking reported civic issues.</p>
            <div class="dashboard-cards">
                <div class="card">
                    <h3>Total Reports</h3>
                    <p>125</p>
                </div>
                <div class="card">
                    <h3>Resolved Issues</h3>
                    <p>89</p>
                </div>
                <div class="card">
                    <h3>Pending Issues</h3>
                    <p>36</p>
                </div>
            </div>
        </div>
    </main>

    <footer class="dashboard-footer">
        <p>&copy; 2024 CivicVoice. All rights reserved.</p>
    </footer>
</body>
</html>
