<?php
include 'db.php';
session_start();

// Check if the officer is logged in
if (!isset($_SESSION['officer'])) {
    header("Location: officer_login.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $problem_id = $_POST['problem_id'];
    $new_status = $_POST['status'];

    $sql = "UPDATE problems SET status = ? WHERE id = ? AND assigned_officer = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $new_status, $problem_id, $_SESSION['officer']['id']);

    $message = "";
    if ($stmt->execute()) {
        $message = "Problem status updated successfully.";
    } else {
        $message = "Error updating status.";
    }
    $stmt->close();
}

// Fetch problems assigned to the logged-in officer
$sql = "SELECT id, title, status FROM problems WHERE assigned_officer = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['officer']['id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Problem Status</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 30px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1, h2, h3 {
            color: #4CAF50;
            text-align: center;
        }

        .message {
            margin: 20px 0;
            padding: 10px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        select, button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 10px;
        }

        button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        hr {
            border: 1px solid #ddd;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Problem Status</h2>

        <!-- Display Success/Error Message -->
        <?php if (!empty($message)): ?>
            <div class="message"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- List Assigned Problems -->
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <form method="POST">
                    <h3><?= htmlspecialchars($row['title']); ?></h3>
                    <label for="status">Status:</label>
                    <select name="status" id="status">
                        <option value="Submitted" <?= $row['status'] == 'Submitted' ? 'selected' : ''; ?>>Submitted</option>
                        <option value="In Progress" <?= $row['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Resolved" <?= $row['status'] == 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                    </select>
                    <input type="hidden" name="problem_id" value="<?= htmlspecialchars($row['id']); ?>">
                    <button type="submit">Update Status</button>
                </form>
                <hr>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No problems assigned to you.</p>
        <?php endif; ?>

        <?php
        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
