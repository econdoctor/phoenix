<?php
date_default_timezone_set('UTC');
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
include "../connectdb.php";
if ($mysqli -> connect_errno) {
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$assign_id = $_GET['assign_id'];
$question_id = $_GET['question_id'];
if(empty($assign_id) || empty($question_id)){
echo 'Missing info';
$mysqli -> close();
exit();}
$sql_check = "SELECT assign_question_status, assign_question_number FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."'";
$res_check = $mysqli -> query($sql_check);
$data_check = mysqli_fetch_assoc($res_check);
if($data_check['assign_question_status'] != 2){
echo 'Wrong question status';
$mysqli -> close();
exit();}
$assign_question_number = $data_check['assign_question_number'];
$sql_info = "SELECT assign_type, assign_game_status, assign_teacher, assign_show_ranking FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
if($data_info['assign_teacher'] != $user_id){
echo 'Not your assignment';
$mysqli -> close();
exit();}
if($data_info['assign_type'] != '5'){
echo 'Not a game';
$mysqli -> close();
exit();}
if($data_info['assign_game_status'] == 0){
header("Location: assign_game_start.php?assign_id=$assign_id");
$mysqli -> close();
exit();}
if($data_info['assign_game_status'] == 1){
header("Location: assign_game_question.php?assign_id=$assign_id");
$mysqli -> close();
exit();}
if($data_info['assign_game_status'] == 3){
header("Location: assign_game_ranking.php?assign_id=$assign_id");
$mysqli -> close();
exit();}
if($data_info['assign_game_status'] == 4){
header("Location: assign_game_final.php?assign_id=$assign_id");
$mysqli -> close();
exit();}
$assign_show_ranking = $data_info['assign_show_ranking'];
$sql_f = "UPDATE phoenix_assign_questions SET assign_question_status = '3' WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."'";
$res_f = $mysqli -> query($sql_f);
$sql_c = "SELECT COUNT(*) FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."' AND assign_question_status = '0'";
$res_c = $mysqli -> query($sql_c);
$data_c = mysqli_fetch_assoc($res_c);
if($data_c['COUNT(*)'] == 0){
$sql_update = "UPDATE phoenix_assign SET assign_game_status= '4' WHERE assign_id = '".$assign_id."'";
$res_update = $mysqli -> query($sql_update);
$now = date("Y-m-d H:i:s");
$sql_update_2 = "UPDATE phoenix_assign_users SET assign_student_end = '".$now."' WHERE assign_id = '".$assign_id."'";
$res_update_2 = $mysqli -> query($sql_update_2);
header("Location: assign_game_final.php?assign_id=$assign_id");
$mysqli -> close();
exit();}
if($data_c['COUNT(*)'] > 0){
if($assign_show_ranking > 0 && $assign_question_number % $assign_show_ranking == 0){
$sql_update = "UPDATE phoenix_assign SET assign_game_status = '3' WHERE assign_id = '".$assign_id."'";
$res_update = $mysqli -> query($sql_update);
$mysqli -> close();
header("Location: assign_game_ranking.php?assign_id=$assign_id");}
if($assign_show_ranking == 0 || ($assign_show_ranking > 0 && $assign_question_number % $assign_show_ranking != 0)){
$sql_update = "UPDATE phoenix_assign SET assign_game_status = '1' WHERE assign_id = '".$assign_id."'";
$res_update = $mysqli -> query($sql_update);
$mysqli -> close();
header("Location: assign_game_question.php?assign_id=$assign_id");}}
?>