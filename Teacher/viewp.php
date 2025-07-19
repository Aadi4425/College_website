<?php
session_start();
include "../shared/connection.php";

if (!isset($_SESSION["login_status"]) || $_SESSION["login_status"] == false) {
    echo "Login Failed";
    die;
}

$userid = $_SESSION["userid"];

// student data nikalana
$sql = "SELECT * FROM user WHERE userid = $userid";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $student = mysqli_fetch_assoc($result);
} else {
    echo "Profile not found!";
    die;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>View Profile</title>
</head>
<body>
<div class="container mt-5">
    <h1>Your Profile</h1>
    <div class="mb-3">
        <label for="profileImage" class="form-label">Profile Image</label><br>
        <img src="<?php echo htmlspecialchars($student['profile_image']); ?>" alt="Profile Image" style="width: 150px; height: auto;">
    </div>

    <div class="mb-3">
        <label for="dob" class="form-label">Date of Birth</label>
        <p><?php echo htmlspecialchars($student['dob']); ?></p>
    </div>

    <div class="mb-3">
        <label for="mobile" class="form-label">Mobile Number</label>
        <p><?php echo htmlspecialchars($student['mobile']); ?></p>
    </div>

    <div>
        <a href="profile.php" class="btn btn-primary">Edit Profile</a>
        <a href="home.php" class="btn btn-secondary">Back to Home</a>
    </div>
</div>
</body>
</html>
