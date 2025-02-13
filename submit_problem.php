<?php
// Database connection
include('db.php');

$message = ""; // Initialize message variable



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $uid = $_POST['uid'];
    $uname = isset($_POST['uname']) ? $_POST['uname'] : null;
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    if (!$uname) {
        $message = "Username is required.";
    }

    // Directory for uploaded files
    $upload_dir = 'uploads/';
    $file_path = null; // Default to null in case no file is uploaded

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['file']['tmp_name'];
        $file_name = basename($_FILES['file']['name']);
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];

        // Validate file extension
        if (in_array(strtolower($file_extension), $allowed_extensions)) {
            // Generate unique file name
            $new_file_name = uniqid() . '_' . $file_name;
            $file_destination = $upload_dir . $new_file_name;

            // Move file to uploads directory
            if (move_uploaded_file($file_tmp_path, $file_destination)) {
                $file_path = $file_destination; // Save the file path
            } else {
                $message = "Error moving the uploaded file.";
            }
        } else {
            $message = "Invalid file type. Allowed types: " . implode(', ', $allowed_extensions);
        }
    }

    if (empty($message)) {
        // Find an officer who handles this category
        $stmt = $conn->prepare("SELECT id FROM officer WHERE category = ? LIMIT 1");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $officer = $result->fetch_assoc();
            $assigned_officer = $officer['id'];

            // Insert problem into the database with the assigned officer, user ID, and file path
            $insert_stmt = $conn->prepare("INSERT INTO problems (title, description, category, assigned_officer, uid, uname, latitude, longitude, file_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_stmt->bind_param("sssissdds", $title, $description, $category, $assigned_officer, $uid, $uname, $latitude, $longitude, $file_path);

            if ($insert_stmt->execute()) {
                $message = "Problem submitted and assigned to the relevant officer.";
            } else {
                $message = "Error submitting the problem.";
            }

            $insert_stmt->close();
        } else {
            $message = "No officer available for this category.";
        }

        $stmt->close();
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Problem</title>
    <link rel="stylesheet" href="css/style3.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Submit Your Problem</h2>

        <!-- Display Success or Error Message -->
        <?php if (!empty($message)): ?>
            <div class="message">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="Water">Water</option>
                <option value="Road">Road</option>
                <option value="Electricity">Electricity</option>
                <option value="sewage">Sewage</option>
            </select>

            <label for="uid">User ID:</label>
            <input type="text" id="uid" name="uid" required>

            <label for="uname">Username:</label>
            <input type="text" id="uname" name="uname" required>

            <label for="file">Attach File:</label>
            <input type="file" id="file" name="file">

            <!-- Hidden fields to store latitude and longitude -->
            <input type="hidden" id="latitude" name="latitude" value="">
            <input type="hidden" id="longitude" name="longitude" value="">

            <label for="map">Select Location:</label>
            <div id="map"></div>
            <button type="submit">Submit Problem</button>
        </form>
    </div>

    <footer>
        <p>2024 CivicVoice. All rights reserved.</p>
    </footer>

    <script>
       var map = L.map('map').setView([12.9716, 77.5946], 13); // Coordinates of Bangalore



        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add a click event to drop a pin
        var marker;
        map.on('click', function(event) {
    // Get clicked location's latitude and longitude
    var lat = event.latlng.lat;
    var lng = event.latlng.lng;

    // Remove existing marker if there is one
    if (marker) {
        map.removeLayer(marker);
    }

    // Add a new marker at the clicked location
    marker = L.marker([lat, lng]).addTo(map);

    // Set the latitude and longitude in the hidden form inputs
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;

    // Display the coordinates on the page for confirmation
    document.getElementById('coords').textContent = 'Selected Location: Latitude: ' + lat + ', Longitude: ' + lng;

    // Optionally, log the coordinates
    console.log('Latitude: ' + lat + ', Longitude: ' + lng);
});

    </script>
</body>
</html>
