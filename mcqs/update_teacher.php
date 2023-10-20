<?php
if(!array_filter($_POST['manage_user'])){
echo 'Please select at least one student.';
exit();}
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
$new_teacher = $_POST['teacher_code'];
if(empty($new_teacher)){
echo 'Missing information about the new teacher';
exit();}
if(!is_numeric($new_teacher) || strlen($new_teacher) != 8){
echo 'A teacher identification code is made of 8 digits.';
exit();}
$sql_check = "SELECT user_id FROM phoenix_users WHERE user_code = '".$new_teacher."' AND user_type = '2'";
$res_check = $mysqli -> query($sql_check);
$nr_check = mysqli_num_rows($res_check);
if($nr_check == 0){
echo 'This identification code is not associated with any teacher account.';
exit();}
if($nr_check > 0){
$data_check = mysqli_fetch_assoc($res_check);
$new_teacher_id = $data_check['user_id'];
if(!empty($_POST['manage_user'])){
foreach($_POST['manage_user'] as $check){
$sql_up = "UPDATE phoenix_users SET user_teacher = '".$new_teacher_id."' WHERE user_id = '".$check."'";
$res_up = $mysqli -> query($sql_up);
$sql_perm = "DELETE FROM phoenix_permissions_users WHERE student_id = '".$check."'";
$res_perm = $mysqli -> query($sql_perm);
$sql_group = "DELETE FROM phoenix_group_users WHERE user_id = '".$check."'";
$res_group = $mysqli -> query($sql_group);}}
header("Location: manage_students.php");
exit();}
?>