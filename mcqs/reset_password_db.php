<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$member_id = $_GET['student_id'];
if(empty($member_id)){
echo 'Missing information about your student';
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_id = '".$member_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
if($user_id != $data['user_teacher']){
echo 'You are not authorized to managed this account';
exit();}
$password_new = $_POST['password_new'];
$password_new_confirm = $_POST['password_new_confirm'];
if(empty($password_new) || empty($password_new_confirm)){
header("Location: reset_password.php?student_id=$member_id&error=1");
exit();}
if($password_new != $password_new_confirm){
header("Location: reset_password.php?student_id=$member_id&error=2");
exit();}
if(strlen($password_new) < 8 || strlen($password_new_confirm) < 8){
header("Location: reset_password.php?student_id=$member_id&error=3");
exit();}
if($password_new == $password_new_confirm && strlen($password_new) >= 8 && $user_id == $data['user_teacher']){
$sql2 = "UPDATE phoenix_users SET user_password = '".md5($password_new)."' WHERE user_id ='".$member_id."'";
$res2 = $mysqli -> query($sql2);
header("Location: user_view.php?member_id=$member_id&password_change=1");}
?>