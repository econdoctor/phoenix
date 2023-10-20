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
$sql = "SELECT * FROM phoenix_users WHERE user_id = '".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
if(!empty($_POST['manage_user'])){
foreach($_POST['manage_user'] as $check){
$sql_2 = "DELETE FROM phoenix_answers WHERE user_id = '".$check."'";
$res_2 = $mysqli -> query($sql_2);
$sql_3 = "DELETE FROM phoenix_group_users WHERE user_id = '".$check."'";
$res_3 = $mysqli -> query($sql_3);
$sql_4 = "DELETE FROM phoenix_permissions_users WHERE student_id = '".$check."'";
$res_4 = $mysqli -> query($sql_4);
$sql_6 = "DELETE FROM phoenix_assign_users WHERE student_id = '".$check."'";
$res_6 = $mysqli -> query($sql_6);}
header("Location: manage_students.php");}
?>