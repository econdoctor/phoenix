<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$assign_id = $_GET['assign_id'];
if(empty($assign_id)){
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
$sqlcheck = "SELECT assign_teacher, assign_deadline, assign_active, assign_type FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$rescheck = $mysqli -> query($sqlcheck);
$datacheck = mysqli_fetch_assoc($rescheck);
$teacher_id = $datacheck['assign_teacher'];
$assign_deadline = $datacheck['assign_deadline'];
$assign_type = $datacheck['assign_type'];
$assign_active = $datacheck['assign_active'];
if($assign_active == 1){
echo 'This assignment is already active.';
exit();}
if($teacher_id != $user_id){
echo 'You are not authorized to manage this assignment';
exit();}
if(!array_filter($_POST['assign_user'])){
header("Location: assign_students.php?assign_id=$assign_id&error=1");
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
header("Location: assign_teams.php?assign_id=$assign_id");}
if($i == 1){
header("Location: assign_type.php?assign_id=$assign_id");}}
if($assign_type != '5'){
header("Location: assign_type.php?assign_id=$assign_id");}}
?>