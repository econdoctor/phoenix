<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
$student_code = $_POST['student_code'];
if(empty($student_code)){
echo 'Missing information about the student identification code.';
exit();}
if(!is_numeric($student_code) || strlen($student_code) != 8){
echo 'A student identification code is made of 8 digits.';
exit();}
$sql_check = "SELECT * FROM phoenix_users WHERE user_type = '1' AND user_code = '".$student_code."'";
$res_check = $mysqli -> query($sql_check);
$nr_check = mysqli_num_rows($res_check);
if($nr_check == 0){
echo 'This identification code is not associated with any student account.';
exit();}
if($nr_check > 0){
$data_check = mysqli_fetch_assoc($res_check);
$student_teacher = $data_check['user_teacher'];
$student_id = $data_check['user_id'];
if($student_teacher == $user_id){
echo 'You already manage this student account.';
exit();}
if($student_teacher != 0){
echo 'This student account is already managed by another teacher.';
exit();}
if($student_teacher == 0){
$sql_up = "UPDATE phoenix_users SET user_teacher = '".$user_id."' WHERE user_id = '".$student_id."'";
$res_up = $mysqli -> query($sql_up);
$sql_del ="DELETE FROM phoenix_thresholds WHERE teacher_id = '".$student_id."'";
$res_del = $mysqli -> query($sql_del);
header("Location: manage_students.php");}}
?>