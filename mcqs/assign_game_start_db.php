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
if(empty($assign_id)){
echo 'Missing info';
$mysqli -> close();
exit();}
$sql_info = "SELECT assign_type, assign_game_status, assign_teacher, assign_teams FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
$assign_teams = $data_info['assign_teams'];
if($data_info['assign_teacher'] != $user_id){
echo 'Not your assignment';
$mysqli -> close();
exit();}
if($data_info['assign_type'] != '5'){
echo 'Not a game';
$mysqli -> close();
exit();}
if($data_info['assign_game_status'] == 1){
header("Location: assign_game_question.php?assign_id=$assign_id");
$mysqli -> close();
exit();}
if($data_info['assign_game_status'] == 2){
header("Location: assign_game_answer.php?assign_id=$assign_id");
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
$now = date("Y-m-d H:i:s");
$sql_ddl = "UPDATE phoenix_assign_users SET assign_student_start = '".$now."', assign_student_end = '2089-07-14 00:00:00' WHERE assign_id = '".$assign_id."'";
$res_ddl = $mysqli -> query($sql_ddl);
$sql_start = "UPDATE phoenix_assign SET assign_game_status = '1' WHERE assign_id = '".$assign_id."'";
$res_start = $mysqli -> query($sql_start);
if($assign_teams == 1){
$sql_gt = "SELECT team, team_name FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."'";
$res_gt = $mysqli -> query($sql_gt);
while($data_gt = mysqli_fetch_assoc($res_gt)){
$team = $data_gt['team'];
$team_name = $data_gt['team_name'];
if($team_name == ''){
$team_name_db = 'TEAM '.$team;
$sql_up = "UPDATE phoenix_assign_teams SET team_name = '".$team_name_db."' WHERE assign_id = '".$assign_id."' AND team = '".$team."'";
$res_up = $mysqli -> query($sql_up);}}}
$mysqli -> close();
header("Location: assign_game_question.php?assign_id=$assign_id");
?>
