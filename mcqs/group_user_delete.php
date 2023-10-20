<?php
if(!array_filter($_POST['select_members'])){
echo 'Please select at least one group member.';
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
$group_id = $_GET['group_id'];
if(empty($group_id)){
echo 'Missing info. Please go back and try again.';
exit();}
if(!empty($_POST['select_members'])){
foreach($_POST['select_members'] as $check){
$sql_2 = "DELETE FROM phoenix_group_users WHERE user_id = '".$check."' AND group_id = '".$group_id."'";
$res_2 = $mysqli -> query($sql_2);}
header("Location: group_view.php?group_id=$group_id");}
?>