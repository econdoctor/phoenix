<?php
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
if(empty($assign_id)){
echo 'Missing info';
exit();}
$sql_info = "SELECT assign_type, assign_game_status, assign_name, assign_teams, assign_nq, assign_teacher FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
if($data_info['assign_teacher'] != $user_id){
echo 'Not your assignment';
exit();}
if($data_info['assign_type'] != '5'){
echo 'Not a game';
exit();}
if($data_info['assign_game_status'] == 0){
header("Location: assign_game_start.php?assign_id=$assign_id");
exit();}
if($data_info['assign_game_status'] == 1){
header("Location: assign_game_question.php?assign_id=$assign_id");
exit();}
if($data_info['assign_game_status'] == 2){
header("Location: assign_game_answer.php?assign_id=$assign_id");
exit();}
if($data_info['assign_game_status'] == 4){
header("Location: assign_game_final.php?assign_id=$assign_id");
exit();}
$sql_c = "SELECT COUNT(*) FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."' AND assign_question_status = '0'";
$res_c = $mysqli -> query($sql_c);
$data_c = mysqli_fetch_assoc($res_c);
if($data_c['COUNT(*)'] == 0){
$sql_update = "UPDATE phoenix_assign SET assign_game_status= '4' WHERE assign_id = '".$assign_id."'";
$res_update = $mysqli -> query($sql_update);
header("Location: assign_game_final.php?assign_id=$assign_id");
exit();}
if($data_c['COUNT(*)'] > 0){
$sql_update = "UPDATE phoenix_assign SET assign_game_status= '1' WHERE assign_id = '".$assign_id."'";
$res_update = $mysqli -> query($sql_update);
header("Location: assign_game_question.php?assign_id=$assign_id");
exit();}
?>