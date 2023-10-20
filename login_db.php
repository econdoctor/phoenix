<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
header("Location: ./mcqs/main.php");
exit();}
$login = $_POST['login'];
$password  = $_POST['password'];
if(empty($login) || empty($password)){
header("Location: login.php?error=1&login=$login");
exit();}
if(!empty($login) && !empty($password)){
include 'connectdb.php';
if($mysqli -> connect_errno){
header("Location: login.php?error=4&login=$login");
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_name = '".$login."' OR user_email = '".$login."'";
$res = $mysqli -> query($sql);
$num_rows = mysqli_num_rows($res);
if($num_rows == 0){
header("Location: login.php?error=2");
exit();}
if($num_rows > 0){
$data = mysqli_fetch_assoc($res);
$user_password = $data['user_password'];
if($user_password != md5($password)){
header("Location: login.php?error=3&login=$login");
exit();}
if($user_password = md5($password_db)){
$user_id = $data['user_id'];
$sql_test = "SELECT user_teacher FROM phoenix_users WHERE user_id = '".$user_id."'";
$res_test = $mysqli -> query($sql_test);
$data_test = mysqli_fetch_assoc($res_test);
$_SESSION['phoenix_user_id'] = $user_id;
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
$sql2 = "UPDATE phoenix_users SET user_last_login = '".$now."' WHERE user_id = '".$user_id."'";
$res2 = $mysqli -> query($sql2);
header("Location: ./mcqs/main.php");
exit();}}}
else {
echo "Unexpected error";
exit();}
?>