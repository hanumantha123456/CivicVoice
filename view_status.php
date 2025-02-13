<?php
// Database connection
include('db.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: user_login.php");
    exit;
}

// Get the logged-in user's ID
$uid = $_SESSION['user']['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Problem Status</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>CivicVoice - View Problem Status</h1>
    </header>
    <main>
        <div class="container" style="text-align:center;">
            <h2>Your Submitted Problems</h2>
            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>Problem ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Category</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch problems submitted by the logged-in user
                    $sql = "SELECT id, title, description, status, category FROM problems WHERE uid = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $uid);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        // Display each problem
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['description']}</td>
                                <td>{$row['status']}</td>
                                <td>{$row['category']}</td>
                              </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No problems found.</td></tr>";
                    }

                    $stmt->close();
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <footer>
        <p>2024 CivicVoice. All rights reserved.</p>
    </footer>
</body>
</html>
