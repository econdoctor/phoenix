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
$sql = "SELECT user_type, user_timezone, user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
$tz = $data['user_timezone'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
$assign_id = $_GET['assign_id'];
if(!isset($assign_id)){
echo 'Missing information about the assignment.';
exit();}
$sql_info = "SELECT assign_name, assign_start, assign_teacher FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
$assign_name = $data_info['assign_name'];
$assign_start = date("Y-m-d H:i:s", strtotime($data_info['assign_start']));
$assign_teacher = $data_info['assign_teacher'];
if($assign_teacher != $user_id){
echo 'You are not authorized to manage this assignment.';
exit();}
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
if($now > $assign_start){
header("Location: update_start.php?assign_id=$assign_id&error=3");
exit();}
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE){
$new_start = $_POST['new_start'];
if(!isset($new_start)){
header("Location: update_start.php?assign_id=$assign_id&error=1");
exit();}}
elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE){
$deadline_date_f = $_POST['new_start_date'];
$deadline_time_f = $_POST['new_start_time'];
if(!isset($deadline_date_f) || !isset($deadline_time_f)){
header("Location: update_start.php?assign_id=$assign_id&error=1");
exit();}
$new_start = date('Y-m-d H:i:s', strtotime(''.$deadline_date_f.' '.$deadline_time_f.''));}
elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE){
$deadline_date_f = $_POST['new_start_date'];
$deadline_time_f = $_POST['new_start_time'];
if(!isset($deadline_date_f) || !isset($deadline_time_f)){
header("Location: update_start.php?assign_id=$assign_id&error=1");
exit();}
$new_start = date('Y-m-d H:i:s', strtotime(''.$deadline_date_f.' '.$deadline_time_f.''));}
else {
$new_start = $_POST['new_start'];
if(!isset($new_start)){
header("Location: update_start.php?assign_id=$assign_id&error=1");
exit();}}
$new_start_date = date('Y-m-d H:i:s', strtotime(''.$new_start.''));
if($tz >= 0){
$new_start_tz = date('Y-m-d H:i:s', strtotime(''.$new_start_date.' - '.$tz.' minutes'));}
if($tz < 0){
$new_start_tz = date('Y-m-d H:i:s', strtotime(''.$new_start_date.' + '.abs($tz).' minutes'));}
if($now > $new_start_tz){
header("Location: update_start.php?assign_id=$assign_id&error=2");
exit();}
$sql_up = "UPDATE phoenix_assign SET assign_start = '".$new_start_tz."' WHERE assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);
header("Location: assign_info.php?assign_id=$assign_id");
?>