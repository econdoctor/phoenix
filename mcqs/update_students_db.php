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
$assign_id = $_GET['assign_id'];
if(!isset($assign_id)){
echo 'Missing information';
exit();}
$sql_as = "SELECT assign_teams, assign_type, assign_teacher, assign_deadline, assign_game_status FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_as = $mysqli -> query($sql_as);
$data_as = mysqli_fetch_assoc($res_as);
$assign_type = $data_as['assign_type'];
$assign_game_status = $data_as['assign_game_status'];
$assign_teams = $data_as['assign_teams'];
if($assign_type == '5' && $assign_game_status > 0){
echo 'You can no longer add or remove students to this game';}
$assign_teacher = $data_as['assign_teacher'];
$assign_deadline = $data_as['assign_deadline'];
if($assign_teacher != $user_id){
echo 'You are not authorized to manage this assignment.';
exit();}
if(!array_filter($_POST['assign_user'])){
header("Location: update_students.php?assign_id=$assign_id&error=1");
exit();}
if(!empty($_POST['assign_user'])){
if($assign_type == '5'){
if($assign_teams == 0){
foreach($_POST['assign_user'] as $check){
$sql_count = "SELECT COUNT(*) FROM phoenix_assign_users WHERE student_id = '".$check."' AND assign_id = '".$assign_id."'";
$res_count = $mysqli -> query($sql_count);
$data_count = mysqli_fetch_assoc($res_count);
if($data_count['COUNT(*)'] > 0){
$sql_up = "UPDATE phoenix_assign_users SET assign_student_keep = '1' WHERE student_id = '".$check."' AND assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);}
if($data_count['COUNT(*)'] == 0){
$sqlx = "INSERT INTO phoenix_assign_users (assign_id, student_id, assign_student_keep) VALUES ('".$assign_id."', '".$check."', '1')";
$resx = $mysqli -> query($sqlx);}}
$sql_del = "DELETE FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_keep = '0'";
$res_del = $mysqli -> query($sql_del);
$sql_up = "UPDATE phoenix_assign_users SET assign_student_keep = '0' WHERE assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);
$sql_del2 = "DELETE FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND user_id NOT IN (SELECT student_id FROM phoenix_assign_users WHERE assign_id = '".$assign_id."')";
$res_del2 = $mysqli -> query($sql_del2);
header("Location: assign_students_stats.php?assign_id=$assign_id");}
if($assign_teams == 1){
$i = 0;
$d = 0;
foreach($_POST['assign_user'] as $check){
if(empty($_POST['team_'.$check.'']) || $_POST['team_'.$check.''] == ""){
echo 'You must assign all students to a team';
exit();}
if($i > 0 && $d == 0 && $_POST['team_'.$check.''] != $previous){
$d = 1;}
$previous = $_POST['team_'.$check.''];
$i++;}
if($d == 0){
echo 'You must assign students to at least 2 different teams';
exit();}
if($i < 2){
echo 'You cannot make teams with less than 2 players';
exit();}
foreach($_POST['assign_user'] as $check){
$team = $_POST['team_'.$check.''];
$sql_count = "SELECT COUNT(*) FROM phoenix_assign_users WHERE student_id = '".$check."' AND assign_id = '".$assign_id."'";
$res_count = $mysqli -> query($sql_count);
$data_count = mysqli_fetch_assoc($res_count);
if($data_count['COUNT(*)'] > 0){
$sql_up = "UPDATE phoenix_assign_users SET assign_student_keep = '1', assign_student_team = '".$team."' WHERE student_id = '".$check."' AND assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);}
if($data_count['COUNT(*)'] == 0){
$sqlx = "INSERT INTO phoenix_assign_users (assign_id, student_id, assign_student_keep, assign_student_team) VALUES ('".$assign_id."', '".$check."', '1', '".$team."')";
$resx = $mysqli -> query($sqlx);}}
$sql_del = "DELETE FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_keep = '0'";
$res_del = $mysqli -> query($sql_del);
$sql_up = "UPDATE phoenix_assign_users SET assign_student_keep = '0' WHERE assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);
$sql_tn = "SELECT COUNT(*) FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."'";
$res_tn = $mysqli -> query($sql_tn);
$data_tn = mysqli_fetch_assoc($res_tn);
$nr_teams = $data_tn['COUNT(*)'];
for($i = 1; $i <= $nr_teams; $i++){
$sql_gt = "SELECT COUNT(*) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_team = '".$i."'";
$res_gt = $mysqli -> query($sql_gt);
$data_gt = mysqli_fetch_assoc($res_gt);
$nr_stu = $data_gt['COUNT(*)'];
if($nr_stu == 0){
$sql_del = "DELETE FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."' AND team = '".$i."'";
$res_del = $mysqli -> query($sql_del);}
if($nr_stu > 0){
$sql_del = "UPDATE phoenix_assign_teams SET members = '".$nr_stu."' WHERE assign_id = '".$assign_id."' AND team = '".$i."'";
$res_del = $mysqli -> query($sql_del);}}
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
header("Location: assign_students_stats.php?assign_id=$assign_id");}}
if($assign_type != '5'){
foreach($_POST['assign_user'] as $check){
$sql_count = "SELECT COUNT(*) FROM phoenix_assign_users WHERE student_id = '".$check."' AND assign_id = '".$assign_id."'";
$res_count = $mysqli -> query($sql_count);
$data_count = mysqli_fetch_assoc($res_count);
if($data_count['COUNT(*)'] > 0){
$sql_up = "UPDATE phoenix_assign_users SET assign_student_keep = '1' WHERE student_id = '".$check."' AND assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);}
if($data_count['COUNT(*)'] == 0){
$sqlx = "INSERT INTO phoenix_assign_users (assign_id, student_id, assign_student_end, assign_student_keep) VALUES ('".$assign_id."', '".$check."', '".$assign_deadline."', '1')";
$resx = $mysqli -> query($sqlx);}}
$sql_del = "DELETE FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_keep = '0'";
$res_del = $mysqli -> query($sql_del);
$sql_up = "UPDATE phoenix_assign_users SET assign_student_keep = '0' WHERE assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);
$sql_del2 = "DELETE FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND user_id NOT IN (SELECT student_id FROM phoenix_assign_users WHERE assign_id = '".$assign_id."')";
$res_del2 = $mysqli -> query($sql_del2);
header("Location: assign_students_stats.php?assign_id=$assign_id");}}
?>