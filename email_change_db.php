<?php
session_start();
if(empty($_SESSION['phoenix_user_id']))
{
header("Location: login.php");
exit();
}
$user_id = $_SESSION['phoenix_user_id'];
$email_new = $_POST['email_new'];
$email_new_confirm = $_POST['email_new_confirm'];
$password = $_POST['password'];
if(empty($email_new) || empty($email_new_confirm) || empty($password))
{
header("Location: email_change.php?error=1&email_new=$email_new&email_new_confirm=$email_new_confirm");
exit();
}
if($email_new != $email_new_confirm)
{
header("Location: email_change.php?error=2");
exit();
}
if($email_new == $email_new_confirm)
{
include "connectdb.php";
if($mysqli -> connect_errno)
{
header("Location: email_change.php?error=3&email_new=$email_new&email_new_confirm=$email_new_confirm");
exit();
}
$sql2 = "SELECT * FROM phoenix_users WHERE user_email = '".$email_new."'";
$result2 = $mysqli -> query($sql2);
$num_rows = mysqli_num_rows($result2);
if($num_rows > 0)
{
header("Location: email_change.php?error=4");
exit();
}
if($num_rows == 0)
{
$sql = "SELECT * FROM phoenix_users WHERE user_id = '".$user_id."'";
$result = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($result);
$password_db = $data['user_password'];
$user_first_name = $data['user_first_name'];
if(md5($password) != $password_db)
{
header("Location: email_change.php?error=5&email_new=$email_new&email_new_confirm=$email_new_confirm");
exit();
}
if(md5($password) == $password_db)
{
$email_new_db = $mysqli->real_escape_string($email_new);
$sql3 = "UPDATE phoenix_users SET user_email = '".$email_new_db."' WHERE user_id ='".$user_id."'";
$result3 = $mysqli -> query($sql3);
header("Location: profile.php");
exit();
}
}
}
?>
