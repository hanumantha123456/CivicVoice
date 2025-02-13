<?php
include 'db.php';
session_start();

// Ensure officer is logged in and retrieve their category
if (!isset($_SESSION['officer'])) {
    header("Location: officer_login.php");
    exit;
}

$category = $_SESSION['officer']['category'];
$officer_id = $_SESSION['officer']['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Problems - CivicVoice</title>
    <link rel="stylesheet" href="css/style2.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        #map {
            height: 200px;
            width: 100%;
        }
    </style>
</head>
<body>
   
    <main>
        <div style="text-align:center;">
        <table border="1" cellspacing="0" cellpadding="10">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>UID</th>
                    <th>PID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Location</th> <!-- New column for map -->
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT uname, uid, id, title, description, status, file_path, latitude, longitude FROM problems WHERE category = ? AND assigned_officer = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $category, $officer_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Display problem details
                        echo "<tr>
                                <td>{$row['uname']}</td>
                                <td>{$row['uid']}</td>
                                <td>{$row['id']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['description']}</td>
                                <td>{$row['status']}</td>
                                <td>";

                        // Display map for location
                        if ($row['latitude'] && $row['longitude']) {
                            echo "<div id='map-{$row['id']}' style='height: 200px;'></div>"; // Individual map container for each problem
                            echo "<script>
                                var map = L.map('map-{$row['id']}').setView([{$row['latitude']}, {$row['longitude']}], 13);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'
                                }).addTo(map);
                                L.marker([{$row['latitude']}, {$row['longitude']}]).addTo(map);
                              </script>";
                        } else {
                            echo "Location not available";
                        }

                        echo "</td>
                                <td>";
                        if ($row['file_path']) {
                            echo "<a href='{$row['file_path']}' target='_blank'>View File</a>";
                        } else {
                            echo "No file attached";
                        }
                        echo "</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No problems assigned to you.</td></tr>";
                }

                $stmt->close();
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>
