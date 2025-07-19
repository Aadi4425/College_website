<?php
session_start();
include "../shared/connection.php";

if (!isset($_GET['sid'])) {
    echo "Invalid subject!";
    exit;
}


$sid = intval($_GET['sid']);

$sql = "SELECT name, detail, impath, link FROM subject WHERE sid = $sid";
$result = mysqli_query($conn, $sql);
$subject = mysqli_fetch_assoc($result);


if (!$subject) {
    echo "Subject not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
        .subject-image {
            width: 25%; 
            height: auto; 
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1><?php echo htmlspecialchars($subject['name']); ?></h1>
    <img src="<?php echo htmlspecialchars($subject['impath']); ?>" alt="Subject Image" class="subject-image">
    <p><?php echo nl2br(htmlspecialchars($subject['detail'])); ?></p> 
    
    <h4>Additional Information:</h4>
    <?php if (!empty($subject['link'])): ?>
        <p>
            <a href="<?php echo htmlspecialchars($subject['link']); ?>" target="_blank">Click here for additional information</a>
        </p>
    <?php else: ?>
        <p>No additional resources available.</p>
    <?php endif; ?>
</div>
</body>
</html>
