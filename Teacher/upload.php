<?php
session_start();
$source_path=$_FILES["pdtimg"]["tmp_name"];
$target_path="../shared/images/".$_FILES["pdtimg"]["name"];
move_uploaded_file($source_path,$target_path);
$conn=new mysqli("localhost","root","","school",3306);
$que="insert into subject(name,detail,impath,owner)values('$_POST[name]','$_POST[detail]','$target_path',$_SESSION[userid])";
mysqli_query($conn,$que);
if($que)
{
    header("location:view.php");
}
else
{
    echo "Adding Subject failed ";
}
?> 