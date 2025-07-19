<?php
session_start();
$_SESSION["login_status"]=false;
$conn=new mysqli("localhost","root","","school",3306);
$sql_result=mysqli_query($conn,"select * from user where username='$_POST[username]' and password='$_POST[password]' and active_status=1");
//print_r($sql_result); 
if($sql_result->num_rows==0){
    echo "Invalid Credentials";
    die;
}
echo "Login Successful!<br>";
$dbrow=mysqli_fetch_assoc($sql_result);
print_r($dbrow);
$_SESSION["login_status"]=true;
$_SESSION["userid"]=$dbrow["userid"];
$_SESSION["username"]=$dbrow["username"];
$_SESSION["usertype"]=$dbrow["usertype"];
if($dbrow["usertype"]=="Teacher"){
    header("location:../Teacher/home.php");
}
else if($dbrow["usertype"]=="Student"){
    header("location:../Student/home.php");
}
/*
*TASK
check usertype of dbrow
redirect to teacher homepage if usertype is teacher
redirect to student homepage if usertype is student
*/
?>