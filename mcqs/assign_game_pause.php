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
$count = $_GET['count'];
if(empty($assign_id) || empty($question_id) || empty($count)){
$mysqli -> close();
exit();}
$sql_pause = "SELECT assign_game_pause, assign_game_status FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_pause = $mysqli -> query($sql_pause);
$data_pause = mysqli_fetch_assoc($res_pause);
$assign_game_status = $data_pause['assign_game_status'];
if($assign_game_status != 1){
exit();
$mysqli -> close();}
if($assign_game_status == 1){
$sql_question = "SELECT assign_question_status, assign_question_deadline FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."'";
$res_question = $mysqli -> query($sql_question);
$data_question = mysqli_fetch_assoc($res_question);
$assign_question_status = $data_question['assign_question_status'];
$assign_question_deadline = $data_question['assign_question_deadline'];
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
if($assign_question_status != 1 || $assign_question_deadline <= $now){
$mysqli -> close();
exit();}
if($assign_question_status == 1 && $assign_question_deadline > $now){
$assign_game_pause = $data_pause['assign_game_pause'];
if($assign_game_pause == 1){
$mysqli -> close();
exit();}
if($assign_game_pause == 0){
$sql_up = "UPDATE phoenix_assign_questions SET assign_question_deadline = '2089-07-14 00:00:00' WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."'";
$res_up = $mysqli -> query($sql_up);
$sql_up2 = "UPDATE phoenix_assign SET assign_game_pause = '1', assign_game_remaining = '".$count."' WHERE assign_id = '".$assign_id."'";
$res_up2 = $mysqli -> query($sql_up2);
echo 1;
$mysqli -> close();
exit();}}}
?>