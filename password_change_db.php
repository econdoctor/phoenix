<?php
session_start();
if(empty($_SESSION['phoenix_user_id'])){
header("Location: login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
$password_old = $_POST['password_old'];
$password_new = $_POST['password_new'];
$password_new_confirm = $_POST['password_new_confirm'];
if(empty($password_old) || empty($password_new) || empty($password_new_confirm)){
header("Location: password_change.php?error=1");
exit();}
if($password_new != $password_new_confirm){
header("Location: password_change.php?error=2");
exit();}
if($password_old == $password_new){
header("Location: password_change.php?error=3");
exit();}
if(strlen($password_new) < 8 || strlen($password_new_confirm) < 8){
header("Location: password_change.php?error=4");
exit();}
if($password_new == $password_new_confirm){
include "connectdb.php";
if($mysqli -> connect_errno){
header("Location: password_change.php?error=5");
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_id = '".$user_id."'";
$result = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($result);
$password_db = $data['user_password'];
if(md5($password_old) != $password_db){
header("Location: password_change.php?error=6");}
if(md5($password_old) == $password_db){
$sql2 = "UPDATE phoenix_users SET user_password = '".md5($password_new)."' WHERE user_id ='".$user_id."'";
$result2 = $mysqli -> query($sql2);
session_destroy();
header("Location: login.php?cp=1");
exit();}}
?>