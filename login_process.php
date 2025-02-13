<?php
// Start session for user login
session_start();

// Include database connection
include 'db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input data from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL query to find the user in the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); // "s" means string type
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify the password using password_verify() (hash comparison)
        if (password_verify($password, $user['password'])) {
            // Password is correct
            $_SESSION['user'] = $user; // Store user data in session
            header("Location: dashboard.php"); // Redirect to the dashboard page
        } else {
            // Invalid password
            $_SESSION['error'] = "Invalid credentials. Please try again.";
            header("Location: user_login.php");
        }
    } else {
        // User not found
        $_SESSION['error'] = "No user found with that username.";
        header("Location: user_login.php");
    }
}
?>
