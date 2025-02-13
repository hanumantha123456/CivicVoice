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
    <link rel="stylesheet" href="css/style.css"> <!-- Link to external CSS file -->
</head>
<body>

    <header>
        <div class="container">
            <h1>CivicVoice</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="user_login.php">User Login</a></li>
                    <li><a href="officer_login.php">Officer Login</a></li>
                    <li><a href="about.php">About</a></li> <!-- Fixed About link -->
                    <li><a href="contact.php">Contact</a></li> <!-- Fixed Contact link -->
                    <li><a href="dashboard.php">Dashboard</a></li> <!-- Fixed Contact link -->
                
                    
                </ul>
            </nav>
        </div>
    </header>

    <section class="dashboard">
        <div class="container">
            <h2>Welcome to CivicVoice</h2>

            <!-- Check if the user or officer is logged in -->
            <?php if (isset($_SESSION['user'])): ?>
                <p>Hello, <?php echo $_SESSION['user']['username']; ?>! You can submit your problems now.</p>
                <a href="submit_problem.php" class="button">Submit Problem</a>
            <?php elseif (isset($_SESSION['officer'])): ?>
                <p>Hello, Officer <?php echo $_SESSION['officer']['username']; ?>! You can view and update problems assigned to you.</p>
                <a href="view_problems.php" class="button">View Problems</a>
            <?php else: ?>
                <p>Please log in to submit or view problems.</p>
            <?php endif; ?>

        </div>
    </section>

    <div style="text-align:center;">
        <img src="images/civic1.png" alt="CivicVoice Logo">
    </div>
    <h3 style="text-align:center;">About Us</h3>
    <p style="text-align:center;"><b>CivicVoice is a citizen-driven platform designed to bridge the gap between the public and government departments. Our mission is to empower individuals to report and track community problems efficiently, ensuring timely resolution and improved civic services. Whether it's road repairs, water issues, sanitation concerns, or other public grievances, CivicVoice provides an easy-to-use interface for citizens to voice their concerns and hold authorities accountable.

    We believe in transparency, collaboration, and innovation to create sustainable and responsive communities. With real-time updates and a focus on accountability, CivicVoice aims to foster trust and streamline communication between citizens and the departments responsible for resolving their issues.

    Together, we can make our cities and communities better, cleaner, and more livable. Join us in shaping the future of civic engagement.</b></p>

    <footer>
        <div class="container">
            <p>&copy; 2024 CivicVoice. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
