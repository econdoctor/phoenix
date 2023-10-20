<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$perm_id = $_GET['id'];
if(empty($perm_id)){
echo 'Missing information';
exit();}
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
$sqlcheck = "SELECT * FROM phoenix_permissions WHERE permission_id = '".$perm_id."'";
$rescheck = $mysqli -> query($sqlcheck);
$datacheck = mysqli_fetch_assoc($rescheck);
$teacher_id = $datacheck['permission_teacher'];
$active = $datacheck['permission_active'];
if($active == 1){
echo 'This rule is already active.';
exit();}
if($teacher_id != $user_id){
echo 'You are not allowed to manage this rule.';
exit();}
if(!array_filter($_POST['permission_student'])){
echo 'Please select at least one student.';
exit();}
if(!empty($_POST['permission_student'])){
foreach($_POST['permission_student'] as $check){
$sqlx = "INSERT INTO phoenix_permissions_users (permission_id, student_id) VALUES ('".$perm_id."', '".$check."')";
$resx = $mysqli -> query($sqlx);}
header("Location: permission_new_rule_type.php?id=$perm_id");}
?>