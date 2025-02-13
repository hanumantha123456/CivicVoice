<?php
    session_start();  // Make sure to call session_start() at the top
    include('db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Button Navigation</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Inline CSS for simplicity */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding-top: 70px; /* Adjust for fixed header */
        }

        /* Header Styles */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            color: white;
            padding: 15px 0;
            z-index: 1000;
        }

        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        header h1 {
            margin: 0;
        }

        header nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        header nav ul li {
            margin-right: 20px;
        }

        header nav ul li a {
            color: white;
            text-decoration: none;
        }

        header nav ul li a:hover {
            text-decoration: underline;
        }

        /* Button Container Styles */
        .button-container {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .button-container button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 15px 30px;
            margin: 10px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button-container button:hover {
            background-color: #0056b3;
        }

        /* Profile Menu */
        .profile-menu {
            display: flex;
            align-items: center;
        }

        .profile-menu .logout-btn, .profile-menu .login-btn {
            color: white;
            text-decoration: none;
            margin-left: 10px;
        }

        /* Profile menu on the right */
        .profile-menu span {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="container">
            <h1>CivicVoice</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="user_login.php">User Login</a></li>
                    <li><a href="officer_login.php">Officer Login</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="dashboard.php">Dashboard</a></li>
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
        </div>
    </header>

    <!-- Centered Buttons -->
    <div class="button-container">
        <button onclick="window.location.href='view_status.php'">View Status</button>
        <button onclick="window.location.href='submit_problem.php'">Submit Problem</button>
    </div>
</body>
</html>
