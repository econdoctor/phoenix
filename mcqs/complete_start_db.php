<?php
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
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
$sql = "SELECT user_type FROM phoenix_users  WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
if($user_type == 2){
echo 'What are you doing here? This page is for students only.';
exit();}
$assign_id = $_GET['assign_id'];
if(empty($assign_id)){
echo 'Missing information';
exit();}
$sql_assign = "SELECT assign_start, assign_deadline, assign_time_allowed FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
$assign_time_allowed = $data_assign['assign_time_allowed'];
$assign_start = date("Y-m-d H:i:s", strtotime($data_assign['assign_start']));
$assign_deadline = date("Y-m-d H:i:s", strtotime($data_assign["assign_deadline"]));
if($now < $assign_start){
echo 'The assignment has not started yet.';
exit();}
if($now > $assign_deadline){
echo 'The assignment deadline has already passed.';
exit();}
$sql_check = "SELECT assign_student_start, assign_student_end FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND student_id = '".$user_id."'";
$res_check = $mysqli -> query($sql_check);
$nr_check = mysqli_num_rows($res_check);
if($nr_check == 0){
echo 'You are not authorized to complete this assignment.';
exit();}
$data_check = mysqli_fetch_assoc($res_check);
if($assign_time_allowed > 0 && $data_check['assign_student_start'] == NULL){
$student_deadline = date('Y-m-d H:i:s', strtotime(''.$now.' + '.$assign_time_allowed.' minutes'));
$ddl = min($student_deadline, $assign_deadline);
$sql_s = "UPDATE phoenix_assign_users SET assign_student_start = '".$now."', assign_student_end = '".$ddl."' WHERE student_id = '".$user_id."' AND assign_id = '".$assign_id."'";
$res_s = $mysqli -> query($sql_s);}
header("Location: complete_go.php?assign_id=$assign_id");
?>
