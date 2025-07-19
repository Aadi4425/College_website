<?php
$name=$_GET["name"];
include "../shared/connection.php";
$status=mysqli_query($conn,"delete from subject where name=$name");
if($status)
{
header("location:view.php");
}

?>