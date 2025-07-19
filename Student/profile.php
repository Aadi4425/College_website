<?php
session_start();
include "../shared/connection.php";


if (!isset($_SESSION["login_status"]) || $_SESSION["login_status"] == false) {
    echo "Login Failed";
    die;
}

$userid = $_SESSION["userid"];
$sql = "SELECT * FROM user WHERE userid = $userid"; 
$result = mysqli_query($conn, $sql);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    echo "User not found!";
    die;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Update Profile</title>
</head>
<body>
<div class="container mt-5">
    <h1>Update Profile</h1>
    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="userid" value="<?php echo htmlspecialchars($student['userid']); ?>">
        
        <div class="mb-3">
            <label for="profileImage" class="form-label">Profile Image</label>
            <input type="file" class="form-control" id="profileImage" name="profileImage">
        </div>
        
        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo isset($student['dob']) ? htmlspecialchars($student['dob']) : ''; ?>">
        </div>

        <div class="mb-3">
            <label for="mobile" class="form-label">Mobile Number</label>
            <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo isset($student['mobile']) ? htmlspecialchars($student['mobile']) : ''; ?>">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
        <a href="viewp.php" class="btn btn-info" style="margin-left: 10px;">View Profile</a> 
    </form>
</div>
</body>
</html>
