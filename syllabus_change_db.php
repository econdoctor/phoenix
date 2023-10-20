<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$syllabus = $_POST['syllabus'];
if($syllabus == "" || empty($syllabus)){
header("Location: syllabus_change.php?error=1");
exit();}
include "connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_id = '".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$syllabus_db = $data['user_syllabus'];
if($syllabus == $syllabus_db){
header("Location: syllabus_change.php?error=2");
exit();}
if(!empty($syllabus) && $syllabus != $syllabus_db){
$sql2 = "UPDATE phoenix_users SET user_syllabus = '".$syllabus."' WHERE user_id = '".$user_id."'";
$res2 = $mysqli -> query($sql2);
header("Location: profile.php");
exit();}
?>
