<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$perm_id = $_GET['id'];
if(empty($perm_id)){
echo 'Missing information';
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$tz = $data['user_timezone'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
$sqlcheck = "SELECT * FROM phoenix_permissions WHERE permission_id = '".$perm_id."'";
$rescheck = $mysqli -> query($sqlcheck);
$datacheck = mysqli_fetch_assoc($rescheck);
$teacher_id = $datacheck['permission_teacher'];
$active = $datacheck['permission_active'];
if($active == 1){
echo 'This rule is already active.';
exit();}
if($teacher_id != $user_id) {
echo 'You are not authorized to manage this rule.';
exit();}
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE){
$expire = $_POST['expire_date_time'];
if(!empty($expire)){
$expire_date = date('Y-m-d H:i:s', strtotime(''.$expire.''));
if($tz >= 0){
$expire_utc = date('Y-m-d H:i:s', strtotime(''.$expire_date.' - '.$tz.' minutes'));}
if($tz < 0){
$expire_utc = date('Y-m-d H:i:s', strtotime(''.$expire_date.' + '.abs($tz).' minutes'));}}
if(empty($expire)){
$expire_utc = "00-00-00 00:00:00";}}
elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE){
$expire_date_f = $_POST['expire_date'];
$expire_time_f = $_POST['expire_time'];
if((empty($expire_date_f) && !empty($expire_time_f)) || (!empty($expire_date_f) && empty($expire_time_f))){
echo 'Missing information.';
exit();}
if(empty($expire_date_f) && empty($expire_time_f)){
$expire_utc = "00-00-00 00:00:00";}
if(!empty($expire_date_f) && !empty($expire_time_f)){
$expire = $expire_date_f . ' ' . $expire_time_f;
$expire_date = date('Y-m-d H:i:s', strtotime(''.$expire.''));
if($tz >= 0){
$expire_utc = date('Y-m-d H:i:s', strtotime(''.$expire_date.' - '.$tz.' minutes'));}
if($tz < 0){
$expire_utc = date('Y-m-d H:i:s', strtotime(''.$expire_date.' + '.abs($tz).' minutes'));}}}
elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE){
$expire_date_f = $_POST['expire_date'];
$expire_time_f = $_POST['expire_time'];
if((empty($expire_date_f) && !empty($expire_time_f)) || (!empty($expire_date_f) && empty($expire_time_f))){
echo 'Missing information.';
exit();}
if(empty($expire_date_f) && empty($expire_time_f)){
$expire_utc = "00-00-00 00:00:00";}
if(!empty($expire_date_f) && !empty($expire_time_f)){
$expire = $expire_date_f . ' ' . $expire_time_f;
$expire_date = date('Y-m-d H:i:s', strtotime(''.$expire.''));
if($tz >= 0){
$expire_utc = date('Y-m-d H:i:s', strtotime(''.$expire_date.' - '.$tz.' minutes'));}
if($tz < 0){
$expire_utc = date('Y-m-d H:i:s', strtotime(''.$expire_date.' + '.abs($tz).' minutes'));}}}
else {
$expire = $_POST['expire_date_time'];
if(!empty($expire)){
$expire_date = date('Y-m-d H:i:s', strtotime(''.$expire.''));
if($tz >= 0){
$expire_utc = date('Y-m-d H:i:s', strtotime(''.$expire_date.' - '.$tz.' minutes'));}
if($tz < 0){
$expire_utc = date('Y-m-d H:i:s', strtotime(''.$expire_date.' + '.abs($tz).' minutes'));}}
if(empty($expire)){
$expire_utc = "00-00-00 00:00:00";}}
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
if($expire_utc != "00-00-00 00:00:00"  && $expire_utc < $now){
echo 'The expiration date has already passed.';
exit();}
$sql_up = "UPDATE phoenix_permissions SET permission_active = '1', permission_expire = '".$expire_utc."', permission_created = '".$now."' WHERE permission_id = '".$perm_id."'";
$res_up = $mysqli -> query($sql_up);
header("Location: manage_permissions.php");
?>