<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT user_type, user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id ='".$user_id."'";
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
$assign_id = $_GET['assign_id'];
if(!isset($assign_id)){
echo 'Missing information about the assignment.';
exit();}
$sql_info = "SELECT assign_name, assign_teacher FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
$assign_name = $data_info['assign_name'];
$assign_teacher = $data_info['assign_teacher'];
if($assign_teacher != $user_id){
echo 'You are not authorized to manage this assignment.';
exit();}
$sql_check_start = "SELECT COUNT(*) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_start IS NOT NULL";
$res_check_start = $mysqli -> query($sql_check_start);
$data_check_start = mysqli_fetch_assoc($res_check_start);
if($data_check_start['COUNT(*)'] > 0){
echo 'The time allowed cannot be updated once students have started to complete the assignment.';
exit();}
$new_time_allowed = $_POST['new_time_allowed'];
if(!isset($new_time_allowed)){
header("Location: update_time_allowed.php?assign_id=$assign_id&error=1");
exit();}
if($new_time_allowed < 0){
header("Location: update_time_allowed.php?assign_id=$assign_id&error=2");
exit();}
if(!ctype_digit($new_time_allowed)){
header("Location: update_time_allowed.php?assign_id=$assign_id&error=3");
exit();}
$time_db = $mysqli -> real_escape_string($new_time_allowed);
$sql_up = "UPDATE phoenix_assign SET assign_time_allowed = '".$time_db."' WHERE assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);
header("Location: assign_info.php?assign_id=$assign_id");
?>