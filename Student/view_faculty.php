<?php
session_start();

if (!isset($_SESSION["login_status"]) || $_SESSION["login_status"] == false) {
    echo "Login Failed";
    die;
}

include "../shared/connection.php";

$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;


$sql = "SELECT u.username, u.profile_image 
        FROM user AS u 
        INNER JOIN subject AS s ON u.userid = s.owner
        WHERE u.usertype = 'Teacher' AND s.sid = $sid";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Query failed: " . mysqli_error($conn);
    die;
}

$teacher = mysqli_fetch_assoc($result);

if (!$teacher) {
    echo "No teacher found for this subject.";
    die;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>View Faculty</title>
    <style>
        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
        }
    </style>
</head>
<body>

<div class="back-btn">
    <a href="home.php" class="btn btn-secondary">Back</a>
</div>

<div class="container mt-5">
    <h1>Faculty for the Subject</h1>
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <img src="<?php echo htmlspecialchars($teacher['profile_image']); ?>" class="card-img-top" alt="Teacher Image">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($teacher['username']); ?></h5>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
