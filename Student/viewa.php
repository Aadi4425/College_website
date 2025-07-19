<?php
session_start();

if (!isset($_SESSION["login_status"]) || $_SESSION["login_status"] == false) {
    echo "Login Failed";
    die;
}

if ($_SESSION["usertype"] != "Student") {
    echo "Forbidden access type";
    die;
}

// Include database connection
include "../shared/connection.php";

// Get the subject ID and user ID from the URL
$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;
$userid = isset($_GET['userid']) ? intval($_GET['userid']) : 0;

// Fetch attendance details for the specific subject and student
$sql = "SELECT * FROM attendance WHERE sid = $sid AND userid = $userid";
$sql_result = mysqli_query($conn, $sql);

if (!$sql_result) {
    echo "Query failed: " . mysqli_error($conn);
    die;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Attendance Details</title>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Attendance Details</h1>

    <div class="mt-4">
        <?php
        if (mysqli_num_rows($sql_result) > 0) {
            echo "<table class='table table-striped'>";
            echo "<thead><tr><th>Attended Lectures</th><th>Total Lectures</th><th>Attendance Percentage</th></tr></thead>";
            echo "<tbody>";
            while ($attendance = mysqli_fetch_assoc($sql_result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($attendance['attended_lectures']) . "</td>";
                echo "<td>" . htmlspecialchars($attendance['total_lectures']) . "</td>";
                echo "<td>" . htmlspecialchars($attendance['attendance_percentage']) . "%</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p class='text-center'>No attendance records found for this subject.</p>";
        }
        ?>
    </div>

    <div class="text-center mt-4">
        <a href="home.php" class="btn btn-secondary">Back to Home</a>
    </div>
</div>

</body>
</html>
