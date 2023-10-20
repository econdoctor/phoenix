<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
$s = $_GET['s'];
if(empty($s)){
echo 'Missing information about the course.';
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_alias = $data['user_alias'];
if($user_type == 2){
echo 'What are you doing here? This page is for students only.';
exit();}
if($user_alias != ''){
header("Location: ranking.php?s=$s");
exit();}
if($user_alias == ''){
$alias_post = $_POST['alias'];
if(empty($alias_post)){
header("Location: set_alias.php?s=$s&error=1");
exit();}
if(!empty($alias_post)){
$sql_check = "SELECT * FROM phoenix_users WHERE user_type = '1' AND user_alias = '".$alias_post."'";
$res_check = $mysqli -> query($sql_check);
$nr_check = mysqli_num_rows($res_check);
if($nr_check > 0){
header("Location: set_alias.php?s=$s&error=2&alias=$alias_post");
exit();}
if($nr_check == 0){
$alias_clear = $mysqli -> real_escape_string($alias_post);
$sql_up = "UPDATE phoenix_users SET user_alias = '".$alias_clear."' WHERE user_id = '".$user_id."'";
$res_up = $mysqli -> query($sql_up);
header("Location: ranking.php?s=$s");}}}
?>