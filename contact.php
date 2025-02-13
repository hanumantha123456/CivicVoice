<?php
$pageTitle = "Contact Us";
include('header.php'); // Include header file for consistent layout
?>

<div class="container">
    <h1><?php echo $pageTitle; ?></h1>
    <form action="submit_contact.php" method="POST">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="message">Message:</label>
            <textarea id="message" name="message" required></textarea>
        </div>
        <button type="submit">Send Message</button>
    </form>
</div>

<?php include('footer.php'); // Include footer file for consistent layout ?>