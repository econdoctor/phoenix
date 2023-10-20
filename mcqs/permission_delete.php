<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
if(!array_filter($_POST['rule'])){
echo 'Please select at least one rule.';
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
if(!empty($_POST['rule'])){
foreach($_POST['rule'] as $check){
$sqlx = "DELETE FROM phoenix_permissions WHERE permission_id = '".$check."'";
$resx = $mysqli -> query($sqlx);
$sqly = "DELETE FROM phoenix_permissions_users WHERE permission_id = '".$check."'";
$resy = $mysqli -> query($sqly);
$sqlz = "DELETE FROM phoenix_permissions_papers WHERE permission_id = '".$check."'";
$resz = $mysqli -> query($sqlz);
$sqlt = "DELETE FROM phoenix_permissions_topics WHERE permission_id = '".$check."'";
$rest = $mysqli -> query($sqlt);}
header("Location: manage_permissions.php");}
?>