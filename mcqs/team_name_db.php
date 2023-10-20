<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$assign_id = $_GET['assign_id'];
if(empty($assign_id)){
echo 'Missing information about the assignment.';
exit();}
$sql_assign = "SELECT assign_name, assign_teams, assign_game_status FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
$assign_name = $data_assign['assign_name'];
$assign_teams = $data_assign['assign_teams'];
if($assign_teams == 0){
echo 'Not a team game';
exit();}
$assign_game_status = $data_assign['assign_game_status'];
if($assign_game_status == 1){
header("Location: complete_game_question.php?assign_id=$assign_id");
exit();}
if($assign_game_status == 2){
header("Location: complete_game_answer.php?assign_id=$assign_id");
exit();}
if($assign_game_status == 3){
header("Location: complete_game_ranking.php?assign_id=$assign_id");
exit();}
if($assign_game_status == 4){
header("Location: complete_game_final.php?assign_id=$assign_id");
exit();}
$sql_check = "SELECT assign_student_team FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND student_id = '".$user_id."'";
$res_check = $mysqli -> query($sql_check);
$nr_check = mysqli_num_rows($res_check);
if($nr_check == 0){
echo 'You are not authorized to complete this assignment.';
exit();}
$data_check = mysqli_fetch_assoc($res_check);
$team = $data_check['assign_student_team'];
$team_name = $_POST['team_name'];
if(empty($team_name) || $team_name == '' || strlen(trim($team_name)) == 0){
header("Location: team_name.php?assign_id=$assign_id&error=1");
exit();}
$sql_a = "SELECT COUNT(*) FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."' AND team <> '".$team."' AND team_name = '".$team_name."'";
$res_a = $mysqli -> query($sql_a);
$data_a = mysqli_fetch_assoc($res_a);
$nr_a = $data_a['COUNT(*)'];
if($nr_a > 0){
header("Location: team_name.php?assign_id=$assign_id&error=2");
exit();}
$team_name_clear = $mysqli -> real_escape_string($team_name);
$sql_up = "UPDATE phoenix_assign_teams SET team_name = '".$team_name_clear."' WHERE assign_id = '".$assign_id."' AND team = '".$team."'";
$res_up = $mysqli -> query($sql_up);
header("Location: complete_game_start.php?assign_id=$assign_id");
?>