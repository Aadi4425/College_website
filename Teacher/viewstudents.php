<?php
session_start();

if (!isset($_SESSION["login_status"]) || $_SESSION["login_status"] == false) {
    echo "Login Failed";
    die;
}

if ($_SESSION["usertype"] != "Teacher") {
    echo "Forbidden access type";
    die;
}

include "../shared/connection.php";

// Initialize variables
$search_name = isset($_GET['search_name']) ? mysqli_real_escape_string($conn, $_GET['search_name']) : '';
$teacher_id = $_SESSION['userid']; // Teacher's user ID or can use owner also
$students = [];

// Fetch the subjects taught by the teacher
$subject_sql = "SELECT sid, name FROM subject WHERE owner = $teacher_id";
$subject_result = mysqli_query($conn, $subject_sql);

$subjects = [];
if ($subject_result) {
    while ($row = mysqli_fetch_assoc($subject_result)) {
        $subjects[] = $row; // Store subjects in an array
    }
}

// If a search name is provided, fetch students
if (!empty($search_name)) {
    $sql = "SELECT * FROM user WHERE usertype = 'Student' AND username LIKE '%$search_name%'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row; // Store students' details in an array it getches all student details
        }
    } else {
        echo "Query failed: " . mysqli_error($conn);
        die;
    }
}

// Handle form submission for attendance update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = intval($_POST['userid']); // Get the student ID
    $attended_lectures = intval($_POST['attended_lectures']);
    $total_lectures = intval($_POST['total_lectures']);
    $sid = intval($_POST['sid']); // Get SID from the POST request

    // check teacher's ID owner is teacher ka id and sid are his subjects
    $sid_check_sql = "SELECT * FROM subject WHERE sid = $sid AND owner = $teacher_id";
    $sid_check_result = mysqli_query($conn, $sid_check_sql);

    if (mysqli_num_rows($sid_check_result) == 0) {
        echo "Invalid subject ID.";
        die;
    }
    
    // Calculate attendance percentage
    $attendance_percentage = ($total_lectures > 0) ? ($attended_lectures / $total_lectures) * 100 : 0;

    // Check if attendance record exists for this student and subject
    $check_sql = "SELECT * FROM attendance WHERE userid = $student_id AND sid = $sid";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        // Update existing record
        $update_sql = "UPDATE attendance SET attended_lectures = $attended_lectures, total_lectures = $total_lectures, attendance_percentage = $attendance_percentage WHERE userid = $student_id AND sid = $sid";
        mysqli_query($conn, $update_sql);
    } else {
        // Insert new record
        $insert_sql = "INSERT INTO attendance (userid, sid, attended_lectures, total_lectures, attendance_percentage) VALUES ($student_id, $sid, $attended_lectures, $total_lectures, $attendance_percentage)";
        if (!mysqli_query($conn, $insert_sql)) {
            echo "Error: " . mysqli_error($conn);
        } else {
            echo "Attendance updated successfully.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>View Students</title>
    <style>
        body {
            background-color: #f8f9fa; 
        }
        .profile-icon {
            position: absolute;
            top: 20px; 
            right: 20px; 
        }
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            margin: 10px; 
        }
        .search-container {
            margin-bottom: 20px; 
        }
        .back-button {
            margin: 20px 0; 
        }
    </style>
</head>
<body>

<div class="profile-icon">
    <a href="profile.php">
        <img src="../shared/images/profile-icon.png" alt="Profile" style="width: 40px; height: 40px;">
    </a>
</div>

<div class="container mt-5">
    <h1 class="text-center">View Students</h1>

    <!-- Search -->
    <div class="search-container text-center">
        <form method="GET" action="viewstudents.php">
            <input type="text" name="search_name" class="form-control w-50 d-inline" placeholder="Enter student name..." value="<?php echo htmlspecialchars($search_name); ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <!-- Student Data and Attendance Form -->
    <div class="row">
        <?php if (!empty($students)): ?>
            <?php foreach ($students as $student): ?>
                <div class="col-md-3">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($student['profile_image']); ?>" class="card-img-top" alt="Student Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($student['username']); ?></h5>
                            <p class="card-text">Phone: <?php echo htmlspecialchars($student['mobile']); ?></p>
                            <p class="card-text">DOB: <?php echo htmlspecialchars($student['dob']); ?></p>
                            
                            <!-- Attendance Form -->
                            <form method="POST" action="">
                                <input type="hidden" name="userid" value="<?php echo intval($student['userid']); ?>">

                                <div class="mb-3">
                                    <label for="sid" class="form-label">Subject:</label>
                                    <select name="sid" class="form-control" required>
                                        <option value="">Select Subject</option>
                                        <?php foreach ($subjects as $subject): ?>
                                            <option value="<?php echo intval($subject['sid']); ?>">
                                                <?php echo htmlspecialchars($subject['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="attended_lectures" class="form-label">Attended Lectures:</label>
                                    <input type="number" class="form-control" name="attended_lectures" required>
                                </div>
                                <div class="mb-3">
                                    <label for="total_lectures" class="form-label">Total Lectures:</label>
                                    <input type="number" class="form-control" name="total_lectures" required>
                                </div>
                                <button type="submit" class="btn btn-success">Update Attendance</button>
                            </form>

                            <!-- Display Current Attendance if Available -->
                            <?php
                            $attendance_sql = "SELECT * FROM attendance WHERE userid = " . intval($student['userid']) . " AND sid IN (SELECT sid FROM subject WHERE owner = $teacher_id)";
                            $attendance_result = mysqli_query($conn, $attendance_sql);
                            if (mysqli_num_rows($attendance_result) > 0) {
                                $attendance = mysqli_fetch_assoc($attendance_result);
                                echo "<p class='mt-3'>Attendance: " . htmlspecialchars($attendance['attendance_percentage']) . "%</p>";
                            } else {
                                echo "<p class='mt-3'>No attendance record found for this subject.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No students found.</p>
        <?php endif; ?>
    </div>

    <div class="back-button text-center">
        <a href="home.php" class="btn btn-secondary">Back to Home</a>
    </div>
</div>

</body>
</html>
