<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$member_id = $_GET['student_id'];
if(empty($member_id)){
echo 'Missing information about your student.';
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
$sql2 = "SELECT * FROM phoenix_users WHERE user_id = '".$member_id."'";
$res2 = $mysqli -> query($sql2);
$data2 = mysqli_fetch_assoc($res2);
if($user_id != $data2['user_teacher']){
echo 'You are not authorized to manage this account.';
exit();}
$t_new = $_POST['user_title'];
$fn_new = $_POST['user_first_name'];
$ln_new = $_POST['user_last_name'];
if(empty($t_new) || empty($fn_new) || empty($ln_new) || $t_new == ""){
header("Location: update_name.php?student_id=$member_id&error=1");
exit();}
if(!empty($t_new) && !empty($fn_new) && !empty($ln_new) && $t_new != ""){
$t_new_db = $mysqli -> real_escape_string($t_new);
$fn_new_db = $mysqli -> real_escape_string($fn_new);
$ln_new_db = $mysqli -> real_escape_string($ln_new);
$sql2 = "UPDATE phoenix_users SET user_title = '".$t_new_db."', user_first_name = '".$fn_new_db."', user_last_name = '".$ln_new_db."'  WHERE user_id ='".$member_id."'";
$res2 = $mysqli -> query($sql2);
header("Location: user_view.php?member_id=$member_id");}
?>