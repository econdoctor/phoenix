<?php
if(!array_filter($_POST['select_group'])){
echo 'Please select at least one group.';
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
if(!empty($_POST['select_group'])){
foreach($_POST['select_group'] as $check){
$sql_1 = "DELETE FROM phoenix_groups WHERE group_id = '".$check."'";
$res_1 = $mysqli -> query($sql_1);
$sql_2 = "DELETE FROM phoenix_group_users WHERE group_id = '".$check."'";
$res_2 = $mysqli -> query($sql_2);}
header("Location: manage_groups.php");}
?>