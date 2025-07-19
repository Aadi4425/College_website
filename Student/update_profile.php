<?php
session_start();
include "../shared/connection.php";

if (!isset($_SESSION["login_status"]) || $_SESSION["login_status"] == false) {
    echo "Login Failed";
    die;
}


$userid = $_SESSION["userid"];


$dob = isset($_POST['dob']) ? mysqli_real_escape_string($conn, $_POST['dob']) : '';
$mobile = isset($_POST['mobile']) ? mysqli_real_escape_string($conn, $_POST['mobile']) : '';
$profileImage = null;

if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == UPLOAD_ERR_OK) {
    $source_path = $_FILES['profileImage']['tmp_name'];
    $target_path = "../shared/images/" . basename($_FILES['profileImage']['name']);
    

    if (move_uploaded_file($source_path, $target_path)) {
        $profileImage = mysqli_real_escape_string($conn, $target_path);
    } else {
        echo "Image upload failed.";
        die;
    }
}


$sql = "UPDATE user SET dob = '$dob', mobile = '$mobile'" . ($profileImage ? ", profile_image = '$profileImage'" : "") . " WHERE userid = $userid";

if (mysqli_query($conn, $sql)) {
    echo "Profile updated successfully.";
    header("Location: home.php"); 
    echo "Error updating profile: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
