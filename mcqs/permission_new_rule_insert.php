<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$s = $_GET['s'];
if (empty($s)){
echo 'Missing information about the course</br>';
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
$sql_ins = "INSERT INTO phoenix_permissions (permission_teacher, permission_syllabus, permission_active) VALUES ('".$user_id."', '".$s."', '0')";
$res_ins = $mysqli -> query($sql_ins);
$sql_id = "SELECT MAX(permission_id) FROM phoenix_permissions WHERE permission_teacher = '".$user_id."'";
$res_id = $mysqli -> query($sql_id);
$data_id = mysqli_fetch_assoc($res_id);
$perm_id = $data_id['MAX(permission_id)'];
header("Location: permission_new_rule_students.php?id=$perm_id");
?>