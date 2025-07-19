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

include "../shared/connection.php";

$student_id = $_SESSION['userid'];

$sql = "SELECT s.name AS subject_name, a.attended_lectures, a.total_lectures, 
        (a.attended_lectures / a.total_lectures * 100) AS attendance_percentage
        FROM attendance a
        JOIN subject s ON a.sid = s.sid
        WHERE a.userid = $student_id";

$sql_result = mysqli_query($conn, $sql);

if (!$sql_result) {
    echo "Query failed: " . mysqli_error($conn);
    die;
}

$total_attended = 0;
$total_lectures = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .defaulter {
            color: red;
            font-weight: bold;
        }
    </style>
    <title>Total Attendance</title>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Total Attendance</h1>

    <div class="mt-4">
        <?php
        if (mysqli_num_rows($sql_result) > 0) {
            echo "<table class='table table-striped'>";
            echo "<thead><tr><th>Subject Name</th><th>Attended Lectures</th><th>Total Lectures</th><th>Attendance Percentage</th></tr></thead>";
            echo "<tbody>";
            while ($attendance = mysqli_fetch_assoc($sql_result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($attendance['subject_name']) . "</td>";
                echo "<td>" . htmlspecialchars($attendance['attended_lectures']) . "</td>";
                echo "<td>" . htmlspecialchars($attendance['total_lectures']) . "</td>";
                echo "<td>" . number_format((float)$attendance['attendance_percentage'], 2, '.', '') . "%</td>";
                echo "</tr>";

                // Accumulate totals
                $total_attended += $attendance['attended_lectures'];
                $total_lectures += $attendance['total_lectures'];
            }
            echo "</tbody></table>";

            // Calculate overall attendance percentage
            $overall_attendance_percentage = ($total_attended / $total_lectures) * 100;
            echo "<h3 class='text-center'>Total Attended: $total_attended</h3>";
            echo "<h3 class='text-center'>Total Lectures: $total_lectures</h3>";
            echo "<h3 class='text-center'>Overall Attendance Percentage: " . number_format((float)$overall_attendance_percentage, 2, '.', '') . "%</h3>";

            // Check if the total attendance is less than 75%
            if ($overall_attendance_percentage < 75) {
                echo "<h3 class='text-center defaulter'>Status: Defaulter (Attendance below 75%)</h3>";
            }
        } else {
            echo "<p class='text-center'>No attendance records found for this student.</p>";
        }
        ?>
    </div>

    <div class="text-center mt-4">
        <a href="home.php" class="btn btn-secondary">Back to Home</a>
    </div>
</div>

</body>
</html>
