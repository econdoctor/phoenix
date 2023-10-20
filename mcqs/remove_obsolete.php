<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
if($user_id != 1){
echo 'Access is restricted';
exit();}
$topic_id = $_GET['topic_id'];
$question_id = $_GET['question_id'];
if(empty($question_id) || empty($topic_id)){
echo 'Missing information';
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql_up = "UPDATE phoenix_questions SET question_obsolete = '1' WHERE question_id = '".$question_id."'";
$res_up = $mysqli -> query($sql_up);
header("Location: topic_view.php?topic_id=$topic_id");
?>