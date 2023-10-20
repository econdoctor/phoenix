<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$member_id = $_GET['member_id'];
$s = $_GET['s'];
if(empty($member_id) || empty($s)){
echo 'Missing information';
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
$sql2 = "SELECT user_teacher FROM phoenix_users WHERE user_id = '".$member_id."'";
$res2 = $mysqli -> query($sql2);
$data2 = mysqli_fetch_assoc($res2);
if($user_id != $data2['user_teacher']){
echo 'You are not authorized to manage this student.';
exit();}
if(!array_filter($_POST['reset_papers'])){
echo 'Please select at least one paper.';
exit();}
if(!empty($_POST['reset_papers'])){
foreach($_POST['reset_papers'] as $check){
$sql_del = "DELETE FROM phoenix_answers WHERE user_id = '".$member_id."' AND answer_type = '1' AND paper_id = '".$check."'";
$res_del = $mysqli -> query($sql_del);
$sql_up = "UPDATE phoenix_questions SET question_refresh = '1' WHERE question_paper_id = '".$check."'";
$res_up = $mysqli -> query($sql_up);}
header("Location: user_view_practice.php?member_id=$member_id&s=$s");
exit();}
?>