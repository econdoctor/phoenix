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
$sql = "SELECT * FROM phoenix_users WHERE user_id = '".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_active = $data['user_active'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
$s = $_GET['s'];
if(empty($s)){
echo 'Please go back and choose a course.';
exit();}
$paper_id = $_GET['paper_id'];
if(empty($paper_id)){
echo 'Missing information about the question paper.';
exit();}
$sql2 = "SELECT * FROM phoenix_papers WHERE paper_id = '".$paper_id."'";
$res2 = $mysqli -> query($sql2);
$data2 = mysqli_fetch_assoc($res2);
$paper_syllabus = $data2['paper_syllabus'];
$paper_reference = $data2['paper_reference'];
$paper_hidden = $data2['paper_hidden'];
if($user_active == 0 && $paper_hidden == 1){
echo 'You are not authorized to access this paper.';
exit();}
header("Location: qp/$paper_syllabus/$paper_reference.pdf");
?>