<?php
if(!array_filter($_POST['group_user_add'])) {
echo 'Please select at least one student.';
exit();}
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
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
$group_id = $_GET['group_id'];
if(empty($group_id)){
echo 'Missing info about the group you intend to edit. Please go back and try again.';
exit();}
if(!empty($_POST['group_user_add'])){
foreach($_POST['group_user_add'] as $check){
$sqlx = "INSERT INTO phoenix_group_users (group_id, user_id) VALUES ('".$group_id."', '".$check."')";
$resx = $mysqli -> query($sqlx);}
header("Location: group_view.php?group_id=$group_id");
exit();}
?>