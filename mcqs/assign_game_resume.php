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
$sql_pause = "SELECT assign_game_pause, assign_game_status, assign_game_remaining FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_pause = $mysqli -> query($sql_pause);
$data_pause = mysqli_fetch_assoc($res_pause);
$assign_game_status = $data_pause['assign_game_status'];
if($assign_game_status != 1){
exit();
$mysqli -> close();}
if($assign_game_status == 1){
$sql_question = "SELECT assign_question_status FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."'";
$res_question = $mysqli -> query($sql_question);
$data_question = mysqli_fetch_assoc($res_question);
$assign_question_status = $data_question['assign_question_status'];
if($assign_question_status != 1){
$mysqli -> close();
exit();}
if($assign_question_status == 1 ){
$assign_game_pause = $data_pause['assign_game_pause'];
$assign_game_remaining = $data_pause['assign_game_remaining'];
if($assign_game_pause == 0 || $assign_game_remaining == NULL){
$mysqli -> close();
exit();}
if($assign_game_pause == 1 && $assign_game_remaining != NULL){
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
$new_deadline = date('Y-m-d H:i:s', strtotime(''.$now.' + '.$assign_game_remaining.' seconds'));
$new_deadline_corrected = date('Y-m-d H:i:s', strtotime(''.$new_deadline.' + 10 seconds'));
$sql_up = "UPDATE phoenix_assign_questions SET assign_question_deadline = '".$new_deadline_corrected."' WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."'";
$res_up = $mysqli -> query($sql_up);
$sql_up2 = "UPDATE phoenix_assign SET assign_game_pause = '0' WHERE assign_id = '".$assign_id."'";
$res_up2 = $mysqli -> query($sql_up2);
echo $assign_game_remaining;
$mysqli -> close();
exit();}}}
?>