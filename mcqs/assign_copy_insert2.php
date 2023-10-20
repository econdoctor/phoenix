<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$assign_id = $_GET['assign_id'];
$copy_id = $_GET['copy_id'];
if(empty($assign_id) || empty($copy_id)){
echo 'Missing information.';
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
$sqlcheck = "SELECT assign_teacher, assign_active, assign_deadline, assign_type FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$rescheck = $mysqli -> query($sqlcheck);
$datacheck = mysqli_fetch_assoc($rescheck);
$assign_type = $datacheck['assign_type'];
$teacher_id = $datacheck['assign_teacher'];
$assign_active = $datacheck['assign_active'];
$assign_deadline = $datacheck['assign_deadline'];
if($assign_active == 1){
echo 'This assignment is already active.';
exit();}
if($teacher_id != $user_id){
echo 'You are not authorized to manage this assignment';
exit();}
$sql_info = "SELECT assign_teacher, assign_active, assign_type FROM phoenix_assign WHERE assign_id = '".$copy_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
if($data_info['assign_type'] == '4'){
echo 'You cannot copy offline assignments';
exit();}
if($data_info['assign_teacher'] != $user_id){
echo 'You are not authorized to copy this assignment';
exit();}
if($data_info['assign_active'] == 0){
echo 'The original assignment is inactive';
exit();}
if(!array_filter($_POST['assign_user'])){
header("Location: assign_copy_students.php?copy_id=$copy_id&assign_id=$assign_id&error=1");
exit();}
if(!empty($_POST['assign_user'])){
$sql_del = "DELETE FROM phoenix_assign_users WHERE assign_id = '".$assign_id."'";
$res_del = $mysqli -> query($sql_del);
$i = 0;
foreach($_POST['assign_user'] as $check){
if($assign_type != '5'){
$sqlx = "INSERT INTO phoenix_assign_users (assign_id, student_id, assign_student_end) VALUES ('".$assign_id."', '".$check."', '".$assign_deadline."')";}
if($assign_type == '5'){
$sqlx = "INSERT INTO phoenix_assign_users (assign_id, student_id) VALUES ('".$assign_id."', '".$check."')";}
$resx = $mysqli -> query($sqlx);
$i++;}
if($assign_type == '5'){
if($i > 1){
header("Location: assign_copy_teams.php?copy_id=$copy_id&assign_id=$assign_id");}
if($i == 1){
header("Location: assign_copy_questions.php?copy_id=$copy_id&assign_id=$assign_id");}}
if($assign_type != '5'){
header("Location: assign_copy_questions.php?copy_id=$copy_id&assign_id=$assign_id");}}
?>