<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
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
$topic_id = $_GET['topic_id'];
$member_id = $_GET['member_id'];
$s = $_GET['s'];
if(empty($topic_id) || empty($member_id) || empty($s)){
echo 'Missing information';
exit();}
$sql2 = "SELECT user_teacher FROM phoenix_users WHERE user_id = '".$member_id."'";
$res2 = $mysqli -> query($sql2);
$data2 = mysqli_fetch_assoc($res2);
if($user_id != $data2['user_teacher']){
echo 'You are not authorized to manage this account.';
exit();}
$sql_del = "DELETE FROM phoenix_answers WHERE user_id = '".$member_id."' AND answer_type = '2' AND topic_id = '".$topic_id."'";
$res_del = $mysqli -> query($sql_del);
$sql_up = "UPDATE phoenix_questions SET question_refresh = '1' WHERE question_topic_id = '".$topic_id."'";
$res_up = $mysqli -> query($sql_up);
header("Location: user_view_practice.php?member_id=$member_id&s=$s");
?>