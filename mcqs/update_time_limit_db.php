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
$sql_info = "SELECT assign_name, assign_teacher, assign_game_status, assign_type FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
$assign_type = $data_info['assign_type'];
if($assign_type != '5'){
echo 'Wrong assignment type';
exit();}
$assign_name = $data_info['assign_name'];
$assign_teacher = $data_info['assign_teacher'];
$assign_game_status = $data_info['assign_game_status'];
if($assign_game_status != 0){
echo 'The game has already started';
exit();}
if($assign_teacher != $user_id){
echo 'You are not authorized to manage this assignment.';
exit();}
$new_time_limit = $_POST['new_time_limit'];
if(!isset($new_time_limit)){
header("Location: update_time_limit.php?assign_id=$assign_id&error=1");
exit();}
$time_db = $mysqli -> real_escape_string($new_time_limit);
$sql_up = "UPDATE phoenix_assign SET assign_time_limit = '".$time_db."' WHERE assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);
header("Location: assign_info.php?assign_id=$assign_id");
?>