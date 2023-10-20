<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
echo 'die';
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
exit();}
$assign_id = $_GET['assign_id'];
$question_id = $_GET['question_id'];
if(empty($assign_id) || empty($question_id)){
$mysqli -> close();
exit();}
$sql_s = "SELECT assign_game_status FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_s = $mysqli -> query($sql_s);
$data_s = mysqli_fetch_assoc($res_s);
$assign_game_status = $data_s['assign_game_status'];
if($assign_game_status != 1){
echo 'R';
$mysqli -> close();
exit();}
$sql_stu = "SELECT COUNT(*) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."'";
$res_stu = $mysqli -> query($sql_stu);
$data_stu = mysqli_fetch_assoc($res_stu);
$nr_stu = $data_stu['COUNT(*)'];
$sql_answers = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."'";
$res_answers = $mysqli -> query($sql_answers);
$data_answers = mysqli_fetch_assoc($res_answers);
$nr_answers = $data_answers['COUNT(*)'];
echo round($nr_answers / $nr_stu * 100, 0);
$mysqli -> close();
?>