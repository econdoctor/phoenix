<?php
session_start();
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
if(isset($_SESSION['phoenix_user_id'])){
if(empty($_GET['student_id'])){
echo 'Missing information';
exit();}
if(!empty($_GET['student_id'])){
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql2 = "SELECT * FROM phoenix_users WHERE user_id = '".$_GET['student_id']."'";
$res2 = $mysqli -> query($sql2);
$data2 = mysqli_fetch_assoc($res2);
if($_SESSION['phoenix_user_id'] != $data2['user_teacher']){
echo 'You are not authorized to manage this account.';
exit();}
if($_SESSION['phoenix_user_id'] == $data2['user_teacher']){
$_SESSION['phoenix_user_id'] = $_GET['student_id'];
header("Location: main.php");}}}
?>