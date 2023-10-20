<?php
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
$sql_check = "SELECT assign_teacher, assign_start FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_check = $mysqli -> query($sql_check);
$data_check = mysqli_fetch_assoc($res_check);
$assign_teacher = $data_check['assign_teacher'];
if($user_id != $assign_teacher){
echo 'You are not authorized to manage this assignment.';
exit();}
$assign_start = date("Y-m-d H:i:s", strtotime($data_check['assign_start']));
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
if($now > $assign_start){
echo 'The assignment has already started';
exit();}
if($now < $assign_start){
$sql_start = "UPDATE phoenix_assign SET assign_start = '".$now."' WHERE assign_id = '".$assign_id."'";
$res_start = $mysqli -> query($sql_start);
header("Location: assign.php?r=1");}
?>