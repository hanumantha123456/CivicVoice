<?php
include 'db.php';

$message = ""; // Initialize message variable

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $id = $_POST['id'];

    // Check if the username already exists
    $checkUser = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($checkUser);

    if ($result->num_rows > 0) {
        $message = "Username already exists. Please choose another.";
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO users (username, password, id) VALUES ('$username', '$password', '$id')";
        if ($conn->query($sql) === TRUE) {
            $message = "Registration successful! You can now <a href='user_login.php'>Login</a>.";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="container">
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="id">ID:</label>
            <input type="text" id="id" name="id" required>
            <button type="submit" name="register">Register</button>
        </form>
        
        <!-- Display the message here -->
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
