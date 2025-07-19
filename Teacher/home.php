<?php
session_start();
if (!isset($_SESSION["login_status"])) {
    echo "Login Failed";
    die;
}
if ($_SESSION["login_status"] == false) {
    echo "Unauthorized Attempt";
    die;
}
if ($_SESSION["usertype"] != "Teacher") {
    echo "Forbidden access type";
    die;
}
include "menu.html";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .profile-icon {
            position: absolute;
            top: 80px; 
            right: 20px;
        }
    </style>
</head>
<body>
    <div class="profile-icon">
        <a href="viewp.php">
            <img src="../shared/images/profile-icon.png" alt="Profile" style="width: 40px; height: 40px;">
        </a>
    </div>

    <div class="d-flex justify-content-center align-items-center vh-100">
        <form class="w-50 bg-warning p-3" action="upload.php" method="post" enctype="multipart/form-data">
            <input required class="form-control mt-3" type="text" placeholder="Subject name" name="name">
            <textarea class="form-control mt-2" name="detail" cols="30" rows="5" placeholder="Subject Description" style="font-family: Arial, sans-serif; font-size: 1rem;"></textarea>
            <label required class="mt-2">Upload Subject Image</label>
            <input class="form-control mt-2" type="file" accept=".jpg,.png" name="pdtimg">
            <div class="mt-3 text-center">
                <button class="btn btn-success">Upload </button>
            </div>
        </form>
    </div>
</body>
</html>
