<?php
date_default_timezone_set('UTC');
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
if(empty($assign_id)){
$mysqli -> close();
exit();}
$now = date("Y-m-d H:i:s");
$sql_ping = "UPDATE phoenix_assign_users SET assign_student_ping = '".$now."' WHERE assign_id = '".$assign_id."' AND student_id = '".$user_id."'";
$res_ping = $mysqli -> query($sql_ping);
$sql_s = "SELECT assign_game_status, assign_teams FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_s = $mysqli -> query($sql_s);
$data_s = mysqli_fetch_assoc($res_s);
$assign_game_status = $data_s['assign_game_status'];
if($assign_game_status != 0){
echo 'R';
$mysqli -> close();
exit();}
$assign_teams = $data_s['assign_teams'];
if($assign_teams == 1){
$team = $_GET['team'];
if(empty($team)){
$mysqli -> close();
exit();}
$sql_t = "SELECT assign_student_ping, student_id FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_team = '".$team."' AND student_id <> '".$user_id."'";
$res_t = $mysqli -> query($sql_t);
while($data_t = mysqli_fetch_assoc($res_t)){
$student_id = $data_t['student_id'];
$assign_student_ping = $data_t['assign_student_ping'];
$now = date("Y-m-d H:i:s");
$limit = date("Y-m-d H:i:s", strtotime(''.$now.' - 5 seconds'));
if($assign_student_ping >= $limit){
echo ' '.$student_id.'_1';}
if($assign_student_ping < $limit){
echo ' '.$student_id.'_0';}}}
$mysqli -> close();
?>