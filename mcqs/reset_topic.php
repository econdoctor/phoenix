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
$sql = "SELECT * FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 2){
echo 'What are you doing here? This page is for students only.';
exit();}
$topic_id = $_GET['topic_id'];
if(empty($topic_id)){
echo 'Missing information about the set of topical questions.';
exit();}
$s = $_GET['s'];
if(empty($s)){
echo 'Missing information about your course.';
exit();}
$sql_del = "DELETE FROM phoenix_answers WHERE user_id = '".$user_id."' AND topic_id = '".$topic_id."'";
$res_del = $mysqli -> query($sql_del);
header("Location: practice_topic.php?s=$s");
?>