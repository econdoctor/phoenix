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
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
$group_name_raw = $_POST['group_name'];
$group_curriculum = $_POST['group_curriculum'];
if(empty($group_name_raw) || empty($group_curriculum)){
echo 'Please go back and fill all the information required.';
exit();}
if(isset($group_name_raw) && isset($group_curriculum)){
$group_name = $mysqli -> real_escape_string($group_name_raw);
$sql2 = "INSERT INTO phoenix_groups (group_name, group_curriculum, group_teacher) VALUES ('".$group_name."', '".$group_curriculum."', '".$user_id."')";
$res2 = $mysqli -> query($sql2);
header("Location: manage_groups.php");}
?>