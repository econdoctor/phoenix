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
$assign_id = $_GET['assign_id'];
if(empty($assign_id)){
echo 'Missing information about the assignment.';
exit();}
$sql_assign = "SELECT assign_release, assign_start FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
if($data_assign['assign_release'] == '2'){
echo 'You cannot submit your answers';
exit();}
if($now < $assign_start){
echo 'The assignment has not yet started.';
exit();}
$sql_check = "SELECT assign_student_start, assign_student_end FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND student_id = '".$user_id."'";
$res_check = $mysqli -> query($sql_check);
$nr_check = mysqli_num_rows($res_check);
if($nr_check == 0){
echo 'You are not authorized to complete this assignment.';
exit();}
$data_check = mysqli_fetch_assoc($res_check);
if($data_check['assign_student_start'] == NULL){
header("Location: complete_start.php?assign_id=$assign_id");
exit();}
if($now >= $data_check['assign_student_end']){
echo 'The assignment is over.';
exit();}
$sql_up = "UPDATE phoenix_assign_users SET assign_student_end = '".$now."' WHERE student_id = '".$user_id."' AND assign_id = '".$assign_id."'";
$sql_up = $mysqli -> query($sql_up);
header("Location: complete.php?r=1");
?>
