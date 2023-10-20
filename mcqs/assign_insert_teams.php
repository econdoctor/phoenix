<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$assign_id = $_GET['assign_id'];
if(empty($assign_id)){
echo 'Missing info about the assignment.';
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT user_type, user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
$sqlcheck = "SELECT assign_teacher, assign_active, assign_type, assign_name FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$rescheck = $mysqli -> query($sqlcheck);
$datacheck = mysqli_fetch_assoc($rescheck);
$teacher_id = $datacheck['assign_teacher'];
$assign_active = $datacheck['assign_active'];
$assign_type = $datacheck['assign_type'];
$assign_name = $datacheck['assign_name'];
if($assign_active == 1){
echo 'This assignment is already active.';
exit();}
if($teacher_id != $user_id){
echo 'You are not authorized to manage this assignment';
exit();}
if($assign_type != '5'){
echo 'This assignment is not a game.';
exit();}
$sql_count = "SELECT COUNT(*) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."'";
$res_count = $mysqli -> query($sql_count);
$data_count = mysqli_fetch_assoc($res_count);
$students_number = $data_count['COUNT(*)'];
if($students_number < 2){
echo 'You cannot make teams';
exit();}
$sql_up_1 = "UPDATE phoenix_assign_users SET assign_student_team = '0' WHERE assign_id = '".$assign_id."'";
$res_up_1 = $mysqli -> query($sql_up_1);
$sql_up_2 = "UPDATE phoenix_assign_teams SET members = '0' WHERE assign_id = '".$assign_id."'";
$res_up_2 = $mysqli -> query($sql_up_2);
foreach($_POST['team'] as $check){
if(empty($check) || $check == ""){
echo 'You must assign all students to a team';
exit();}}
foreach($_POST['team'] as $check){
$student_id = explode("_", $check)[0];
$team = explode("_", $check)[1];
$sqlx = "UPDATE phoenix_assign_users SET assign_student_team = '".$team."' WHERE assign_id = '".$assign_id."' AND student_id = '".$student_id."'";
$resx = $mysqli -> query($sqlx);
$sqly = "UPDATE phoenix_assign_teams SET members = members + 1 WHERE assign_id = '".$assign_id."' AND team = '".$team."'";
$resy = $mysqli -> query($sqly);}
$sql_z = "SELECT COUNT(*) FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."' AND members > '0'";
$res_z = $mysqli -> query($sql_z);
$data_z = mysqli_fetch_assoc($res_z);
if($data_z['COUNT(*)'] < 2){
$sql_up_3 = "UPDATE phoenix_assign_users SET assign_student_team = '0' WHERE assign_id = '".$assign_id."'";
$res_up_3 = $mysqli -> query($sql_up_3);
$sql_up_4 = "DELETE FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."'";
$res_up_4 = $mysqli -> query($sql_up_4);
header("Location: assign_teams_set.php?assign_id=$assign_id&error=1");
exit();}
$sql_del = "DELETE FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."' AND members = '0'";
$res_del = $mysqli -> query($sql_del);
$sql_coeff = "SELECT team, members FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."' ORDER BY members DESC";
$res_coeff = $mysqli -> query($sql_coeff);
$i = 1;
while($data_coeff = mysqli_fetch_assoc($res_coeff)){
$members = $data_coeff['members'];
if($i == 1){
$max = $members;}
$team = $data_coeff['team'];
$coeff = round($max / $members * 100, 0);
$sql_ins = "UPDATE phoenix_assign_users SET assign_student_coeff = '".$coeff."' WHERE assign_id = '".$assign_id."' AND assign_student_team = '".$team."'";
$res_ins = $mysqli -> query($sql_ins);
$i++;}
$sql_final = "UPDATE phoenix_assign SET assign_teams = '1' WHERE assign_id = '".$assign_id."'";
$res_final = $mysqli -> query($sql_final);
header("Location: assign_type.php?assign_id=$assign_id");
?>