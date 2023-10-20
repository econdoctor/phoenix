<?php
date_default_timezone_set('UTC');
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
include "../connectdb.php";
if ($mysqli -> connect_errno) {
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
if(empty($assign_id)){
echo 'Missing information about the assignment.';
exit();}
$sql_check = "SELECT assign_teacher, assign_type, assign_game_status FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_check = $mysqli -> query($sql_check);
$data_check = mysqli_fetch_assoc($res_check);
$assign_teacher = $data_check['assign_teacher'];
$assign_type = $data_check['assign_type'];
$assign_game_status = $data_check['assign_game_status'];
if($user_id != $assign_teacher){
echo 'You are not authorized to manage this assignment.';
exit();}
if($assign_game_status == 0){
echo 'The game has not started yet';
exit();}
if($assign_game_status == 4){
echo 'The game is already over';
exit();}
$sql_term = "UPDATE phoenix_assign SET assign_game_status = '4' WHERE assign_id = '".$assign_id."'";
$res_term = $mysqli -> query($sql_term);
$now = date("Y-m-d H:i:s");
$sql_term2 = "UPDATE phoenix_assign_users SET assign_student_end = '".$now."' WHERE assign_id = '".$assign_id."'";
$res_term2 = $mysqli -> query($sql_term2);
$sql_term3 = "UPDATE phoenix_assign_questions SET assign_question_hide = '0', assign_question_status = '3', assign_question_refresh = '1', assign_question_deadline = '".$now."' WHERE assign_id = '".$assign_id."'";
$res_term3 = $mysqli -> query($sql_term3);
header("Location: assign.php?r=1");
?>
