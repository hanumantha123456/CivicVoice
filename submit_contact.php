<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Here you can add code to send an email or save to a database

    echo "<h1>Thank You, $name!</h1>";
    echo "<p>Your message has been received.</p>";
} else {
    echo "<h1>Error</h1>";
    echo "<p>Please submit the form.</p>";
}
?>