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


$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$sql = "SELECT * FROM subject WHERE name LIKE '%$search%'"; 
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
    <style>
        .pdt-container {
            background-color: bisque;
            margin: 10px;
            display: inline-block;
            padding: 10px;
            width: 100%;
            height: fit-content;
            vertical-align: top;
        }
        img {
            width: 100%;
            height: 250px;
            object-fit: contain;
        }
        .name {
            font-size: 24px;
            font-weight: bold;
            color: blueviolet;
        }
        .container {
            margin-top: 60px; 
        }
        .top-left-buttons {
            position: absolute;
            top: 10px;
            left: 2px;
        }
        .profile-icon {
            position: absolute;
            top: 60px;
            right: 20px;
        }
        .logout-btn {
            position: absolute;
            top: 5px;
            right: 10px;
        }
    </style>
</head>
<body>

<div class="top-left-buttons">
    <a href="about_us.php" class="btn btn-secondary me-2">About Us</a>
    <a href="contact_us.php" class="btn btn-secondary me-2">Contact Us</a>
    <a href="totala.php" class="btn btn-secondary">Total Attendance</a> 
</div>

<div class="profile-icon">
    <a href="viewp.php">
        <img src="../shared/images/profile-icon.png" alt="Profile" style="width: 40px; height: 40px;">
    </a>
</div>

<div class="logout-btn">
    <a href="../shared/logout.php">
        <button class="btn btn-danger">Logout</button>
    </a>
</div>

<div class="container">
    <form method="GET" action="home.php">
        <div class="row">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Search subjects..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    <div class="row mt-4">
        <?php
        $student_id = $_SESSION['userid'];

        while ($row = mysqli_fetch_assoc($sql_result)) {
            echo "<div class='col-md-3'>";
            echo "<div class='pdt-container'>";
            echo "<div class='name'>" . htmlspecialchars($row['name']) . "</div>";
            echo "<img src='" . htmlspecialchars($row['impath']) . "' alt='Subject Image'>";
            echo "<div class='detail'>" . htmlspecialchars($row['detail']) . "</div>";
            echo "<div class='mt-2 d-flex'>";
            echo "<a href='subject_detail.php?sid=" . intval($row['sid']) . "' class='btn btn-info me-2'>View Details</a>";
            echo "<a href='view_faculty.php?sid=" . intval($row['sid']) . "' class='btn btn-success me-2'>View Faculty</a>";
            echo "<a href='viewa.php?sid=" . intval($row['sid']) . "&userid=" . intval($student_id) . "' class='btn btn-warning'>View Attendance</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
</div>

</body>
</html>
