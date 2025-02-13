<?php
// Database connection
include('db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicVoice Analytics</title>
    <link rel="stylesheet" href="css/style7.css"> <!-- Reuse the dashboard styles -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js Library -->
</head>
<body>
    <header class="dashboard-header">
        <div class="logo">
            <h1>CivicVoice</h1>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="logout.php" class="logout-btn">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-main">
        <div class="sidebar">
            <ul>
                <li><a href="submit_problem.php">Submit Problem</a></li>
                <li><a href="view_problems.php">View Problems</a></li>
                <li><a href="analytics.php">Analytics</a></li>
            </ul>
        </div>
        <div class="content">
            <h2>Analytics</h2>
            <p>Visual representation of problem data.</p>

            <!-- Bar Chart -->
            <div style="width: 80%; margin: auto;">
                <h3>Problems by Category</h3>
                <canvas id="barChart"></canvas>
            </div>

            <!-- Pie Chart -->
            <div style="width: 80%; margin: auto; margin-top: 50px;">
                <h3>Resolved vs. Pending Issues</h3>
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </main>

    <footer class="dashboard-footer">
        <p>&copy; 2024 CivicVoice. All rights reserved.</p>
    </footer>

    <!-- Fetch Data from Database -->
    <?php
    // Query for problems by category
    $category_data = [];
    $categories = ['Water', 'Road', 'Electricity'];
    foreach ($categories as $category) {
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM problems WHERE category = ?");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $category_data[] = $row['count'];
        $stmt->close();
    }

    // Query for resolved vs pending problems
    $stmt = $conn->prepare("SELECT 
        (SELECT COUNT(*) FROM problems WHERE status = 'Resolved') AS resolved, 
        (SELECT COUNT(*) FROM problems WHERE status = 'Pending') AS pending");
    $stmt->execute();
    $result = $stmt->get_result();
    $status_data = $result->fetch_assoc();
    $stmt->close();

    $conn->close();
    ?>

    <!-- Chart.js Scripts -->
    <script>
        // Data from PHP
        const categoryData = <?= json_encode($category_data); ?>;
        const statusData = [<?= $status_data['resolved']; ?>, <?= $status_data['pending']; ?>];

        // Bar Chart for problems by category
        const barChartCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barChartCtx, {
            type: 'bar',
            data: {
                labels: ['Water', 'Road', 'Electricity'],
                datasets: [{
                    label: 'Number of Problems',
                    data: categoryData,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Pie Chart for resolved vs pending problems
        const pieChartCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieChartCtx, {
            type: 'pie',
            data: {
                labels: ['Resolved', 'Pending'],
                datasets: [{
                    data: statusData,
                    backgroundColor: ['#36A2EB', '#FF6384'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                }
            }
        });
    </script>
</body>
</html>
