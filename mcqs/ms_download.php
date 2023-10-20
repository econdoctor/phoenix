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
$user_active = $data['user_active'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
$paper_id = $_GET['paper_id'];
if(empty($paper_id)){
echo 'Missing information about the mark scheme.';
exit();}
$sql2 = "SELECT * FROM phoenix_papers WHERE paper_id = '".$paper_id."'";
$res2 = $mysqli -> query($sql2);
$data2 = mysqli_fetch_assoc($res2);
$paper_syllabus = $data2['paper_syllabus'];
$paper_reference = $data2['paper_reference'];
$paper_version = $data2['paper_version'];
$paper_hidden = $data2['paper_hidden'];
if($user_active == 0 && $paper_hidden == 1){
echo 'You are not authorized to access this mark scheme.';
exit();}
if($paper_version != '0'){
$ms_reference = substr_replace($paper_reference, 'ms', 9, 2);}
if($paper_version == '0'){
$ms_reference = substr_replace($paper_reference, 'sm', 9, 2);}
header("Location: ms/$paper_syllabus/$ms_reference.pdf");
?>