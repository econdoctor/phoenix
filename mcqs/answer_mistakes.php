<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
date_default_timezone_set('UTC');
$answer_date = date("Y-m-d H:i:s");
if(!isset($_SESSION['phoenix_user_id'])){
echo 'die';
exit();}
include "../connectdb.php";
$sql_user = "SELECT user_refresh FROM phoenix_users WHERE user_id = '".$user_id."'";
$res_user = $mysqli -> query($sql_user);
$data_user = mysqli_fetch_assoc($res_user);
if($data_user['user_refresh'] == 0){
$sql_refresh_user = "UPDATE phoenix_users SET user_refresh = '1' WHERE user_id = '".$user_id."'";
$res_refresh_user = $mysqli -> query($sql_refresh_user);}
$question_id = $_GET['question_id'];
$answer = $_GET['answer'];
$sql_check = "SELECT question_answer, question_refresh, question_rate_a, question_rate_b, question_rate_c, question_rate_d FROM phoenix_questions WHERE question_id = '".$question_id."'";
$res_check = $mysqli -> query($sql_check);
$data_check = mysqli_fetch_assoc($res_check);
$correct_answer = $data_check['question_answer'];
$question_refresh = $data_check['question_refresh'];
if($question_refresh == 0){
$sql_refresh = "UPDATE phoenix_questions SET question_refresh = '1' WHERE question_id = '".$question_id."'";
$res_refresh = $mysqli -> query($sql_refresh);}
$question_rate_a = round($data_check['question_rate_a'], 0);
$question_rate_b = round($data_check['question_rate_b'], 0);
$question_rate_c = round($data_check['question_rate_c'], 0);
$question_rate_d = round($data_check['question_rate_d'], 0);
if($answer == $correct_answer){
$valid = '1';}
if($answer != $correct_answer){
$valid = '0';}
$sql_up = "UPDATE phoenix_answers SET answer = '".$answer."', answer_valid = '".$valid."', answer_date = '".$answer_date."' WHERE user_id = '".$user_id."' AND question_id = '".$question_id."' AND answer_type < '3'";
$res_up = $mysqli -> query($sql_up);
echo $question_rate_a.' '.$question_rate_b.' '.$question_rate_c.' '.$question_rate_d;
?>