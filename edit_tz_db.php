<?php
session_start();
if(empty($_SESSION['phoenix_user_id'])){
header("Location: login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
$timezone = $_POST['timezone'];
if($timezone == ""){
header("Location: edit_tz.php?error=1");
exit();}
include "connectdb.php";
if($mysqli -> connect_errno){
header("Location: edit_tz.php?error=2");
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_id = '".$user_id."'";
$result = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($result);
$timezone_db = $data['user_timezone'];
if($timezone_db == $timezone){
header("Location: edit_tz.php?error=3");
exit();}
if($timezone_db != $timezone){
$timezone_new_db = $mysqli->real_escape_string($timezone);
$sql3 = "UPDATE phoenix_users SET user_timezone = '".$timezone_new_db."' WHERE user_id ='".$user_id."'";
$result3 = $mysqli -> query($sql3);
header("Location: profile.php");
exit();}
?>