<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .pdt-container {
            background-color: bisque;
            margin: 10px;
            padding: 10px;
            height: fit-content;
            vertical-align: top;
        }
        img {
            width: 100%;
            height: 270px;
        }
        .name {
            font-size: 24px;
            font-weight: bold;
            color: blueviolet;
        }
    </style>
</head>
<body>
    <?php
    include "menu.html";
    include "../shared/connection.php";
    session_start();

    echo "<div class='container'><div class='row'>";

    $sql_result = mysqli_query($conn, "SELECT * FROM subject WHERE owner=$_SESSION[userid]");
    while ($dbrow = mysqli_fetch_assoc($sql_result)) {
        echo "<div class='col-md-3'>
                <div class='pdt-container'> 
                    <div class='name'>" . $dbrow['name'] . "</div>
                    <img src='" . $dbrow['impath'] . "' alt='Product Image'>
                    <div class='detail'>" . $dbrow['detail'] . "</div>
                    <div class='d-flex justify-content-center gap-2 mt-2'>
                        <button class='btn btn-danger'>Delete Subject</button>
                    </div>
                </div>
              </div>"; 
    } 

    echo "</div></div>";
    ?>
</body>
</html>
  