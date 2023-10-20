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
$sql = "SELECT user_type, user_timezone, user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
$tz = $data['user_timezone'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
$assign_id = $_GET['assign_id'];
if(!isset($assign_id)){
echo 'Missing information about the assignment.';
exit();}
$sql_as = "SELECT assign_name, assign_time_allowed, assign_teacher, assign_deadline, assign_release FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_as = $mysqli -> query($sql_as);
$data_as = mysqli_fetch_assoc($res_as);
$assign_name = $data_as['assign_name'];
$assign_release = $data_as['assign_release'];
$assign_deadline = date("Y-m-d H:i:s", strtotime($data_as['assign_deadline']));
$assign_teacher = $data_as['assign_teacher'];
$assign_time_allowed = $data_as['assign_time_allowed'];
if($assign_teacher != $user_id){
echo 'You are not authorized to manage this assignment.';
exit();}
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE){
$ddl = $_POST['new_deadline'];
if(!isset($ddl)){
header("Location: update_deadline.php?assign_id=$assign_id&error=1");}}
elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE){
$deadline_date_f = $_POST['new_deadline_date'];
$deadline_time_f = $_POST['new_deadline_time'];
if(!isset($deadline_date_f) || !isset($deadline_time_f)){
header("Location: update_deadline.php?assign_id=$assign_id&error=1");
exit();}
$ddl = date('Y-m-d H:i:s', strtotime(''.$deadline_date_f.' '.$deadline_time_f.''));}
elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE){
$deadline_date_f = $_POST['new_deadline_date'];
$deadline_time_f = $_POST['new_deadline_time'];
if(!isset($deadline_date_f) || !isset($deadline_time_f)){
header("Location: update_deadline.php?assign_id=$assign_id&error=1");
exit();}
$ddl = date('Y-m-d H:i:s', strtotime(''.$deadline_date_f.' '.$deadline_time_f.''));}
else {
$ddl = $_POST['new_deadline'];
if(!isset($ddl)){
header("Location: update_deadline.php?assign_id=$assign_id&error=1");}}
$ddl_date = date('Y-m-d H:i:s', strtotime(''.$ddl.''));
if($tz >= 0){
$ddl_tz = date('Y-m-d H:i:s', strtotime(''.$ddl_date.' - '.$tz.' minutes'));}
if($tz < 0){
$ddl_tz = date('Y-m-d H:i:s', strtotime(''.$ddl_date.' + '.abs($tz).' minutes'));}
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
if($now > $ddl_tz){
header("Location: update_deadline.php?assign_id=$assign_id&error=2");
exit();}
$sql_up = "UPDATE phoenix_assign SET assign_deadline = '".$ddl_tz."' WHERE assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);
if($assign_time_allowed == 0 && $assign_release == '2'){
$sql_up = "UPDATE phoenix_assign_users SET assign_student_end = '".$ddl_tz."' WHERE assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);}
if($assign_time_allowed == 0 && $assign_release == '1'){
$sql_up = "UPDATE phoenix_assign_users SET assign_student_end = '".$ddl_tz."' WHERE assign_id = '".$assign_id."' AND assign_student_end = '".$assign_deadline."'";
$res_up = $mysqli -> query($sql_up);}
if($assign_time_allowed > 0){
$sql_up1 = "UPDATE phoenix_assign_users SET assign_student_end = '".$ddl_tz."' WHERE assign_id = '".$assign_id."' AND assign_student_start IS NULL";
$res_up1 = $mysqli -> query($sql_up1);
$sql_up2 = "UPDATE phoenix_assign_users SET assign_student_end = '".$ddl_tz."' WHERE assign_id = '".$assign_id."' AND assign_student_start IS NOT NULL AND assign_student_end < '".$assign_deadline."' AND assign_student_end > '".$ddl_tz."'";
$res_up2 = $mysqli -> query($sql_up2);
$limit = date('Y-m-d H:i:s', strtotime(''.$ddl_tz.' - '.$assign_time_allowed.' minutes'));
$sql_up3 = "UPDATE phoenix_assign_users SET assign_student_end = '".$ddl_tz."' WHERE assign_id = '".$assign_id."' AND assign_student_start IS NOT NULL AND assign_student_end = '".$assign_deadline."' AND assign_student_start >= '".$limit."'";
$res_up3 = $mysqli -> query($sql_up3);
$sql_up4 = "UPDATE phoenix_assign_users SET assign_student_end = DATE_ADD(assign_student_start, INTERVAL '".$assign_time_allowed."' minute) WHERE assign_id = '".$assign_id."' AND assign_student_start IS NOT NULL AND assign_student_end = '".$assign_deadline."' AND assign_student_start < '".$limit."'";
$res_up4 = $mysqli -> query($sql_up4);}
header("Location: assign_info.php?assign_id=$assign_id");
?>