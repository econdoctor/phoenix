<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
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
$copy_id = $_GET['copy_id'];
if(empty($copy_id)){
echo 'Missing information';
exit();}
$sql_info = "SELECT assign_teacher, assign_active, assign_type FROM phoenix_assign WHERE assign_id = '".$copy_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
if($data_info['assign_type'] == '4'){
echo 'You cannot copy offline assignments';
exit();}
if($data_info['assign_teacher'] != $user_id){
echo 'You are not authorized to copy this assignment';
exit();}
if($data_info['assign_active'] == 0){
echo 'The original assignment is inactive';
exit();}
$assign_key = rand(10000000,99999999);
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
$s = $_POST['syllabus'];
$n = $_POST['name'];
$t = $_POST['type'];
if($t != '4' && $t != '5'){
$sql_limit = "SELECT COUNT(*) FROM phoenix_assign WHERE assign_teacher = '".$user_id."' AND assign_active = '1' AND assign_type <> '4' AND assign_type <> '5' AND assign_deadline > '".$now."'";
$res_limit = $mysqli -> query($sql_limit);
$data_limit = mysqli_fetch_assoc($res_limit);
$ongoing = $data_limit['COUNT(*)'];
if($ongoing >= 10){
header("Location: assign_copy.php?assign_id=$copy_id&error=7");
exit();}}
$ta = $_POST['time_allowed'];
$start_dtl = $_POST['start'];
$deadline_dtl = $_POST['deadline'];
$start_d = $_POST['start_date'];
$start_t = $_POST['start_time'];
$deadline_d = $_POST['deadline_date'];
$deadline_t = $_POST['deadline_time'];
if(empty($s) || $s == "" || empty($t) || $t == "" || empty($n)){
header("Location: assign_copy.php?error=1&s=$s&n=$n&t=$t&ta=$ta&start_d=$start_d&start_t=$start_t&deadline_d=$deadline_d&deadline_t=$deadline_t&start=$start_dtl&deadline=$deadline_dtl");
exit();}
if($t == "5"){
$n_db = $mysqli -> real_escape_string($n);
$sql_ins = "INSERT INTO phoenix_assign (assign_teacher, assign_name, assign_syllabus, assign_type, assign_active, assign_key) VALUES ('".$user_id."', '".$n_db."', '".$s."', '".$t."', '0', '".$assign_key."')";
$res_ins = $mysqli -> query($sql_ins);
$sql_g = "SELECT MAX(assign_id) FROM phoenix_assign WHERE assign_teacher = '".$user_id."'";
$res_g = $mysqli -> query($sql_g);
$data_g = mysqli_fetch_assoc($res_g);
$assign_id = $data_g['MAX(assign_id)'];
header("Location: assign_copy_students.php?copy_id=$copy_id&assign_id=$assign_id");
exit();}
if($t != '5'){
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE){
$start = $_POST['start'];
if(empty($start)){
$start_tz = "00-00-00 00:00:00";}
if(!empty($start)){
$start_date = date('Y-m-d H:i:s', strtotime(''.$start.''));
if($tz >= 0){
$start_tz = date('Y-m-d H:i:s', strtotime(''.$start_date.' - '.$tz.' minutes'));}
if($tz < 0){
$start_tz = date('Y-m-d H:i:s', strtotime(''.$start_date.' + '.abs($tz).' minutes'));}}}
elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE){
$start_date_f = $_POST['start_date'];
$start_time_f = $_POST['start_time'];
if(empty($start_date_f) && empty($start_time_f)){
$start_tz = "00-00-00 00:00:00";}
if((empty($start_date_f) && !empty($start_time_f)) || (!empty($start_date_f) && empty($start_time_f))){
header("Location: assign_copy.php?error=1&s=$s&n=$n&t=$t&ta=$ta&start_d=$start_d&start_t=$start_t&deadline_d=$deadline_d&deadline_t=$deadline_t");
exit();}
if(!empty($start_date_f) && !empty($start_time_f)){
$start = $start_date_f . ' ' . $start_time_f;
$start_date = date('Y-m-d H:i:s', strtotime(''.$start.''));
if($tz >= 0){
$start_tz = date('Y-m-d H:i:s', strtotime(''.$start_date.' - '.$tz.' minutes'));}
if($tz < 0){
$start_tz = date('Y-m-d H:i:s', strtotime(''.$start_date.' + '.abs($tz).' minutes'));}}}
elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE){
$start_date_f = $_POST['start_date'];
$start_time_f = $_POST['start_time'];
if(empty($start_date_f) && empty($start_time_f)){
$start_tz = "00-00-00 00:00:00";}
if((empty($start_date_f) && !empty($start_time_f)) || (!empty($start_date_f) && empty($start_time_f))){
header("Location: assign_copy.php?error=1&s=$s&n=$n&t=$t&ta=$ta&start_d=$start_d&start_t=$start_t&deadline_d=$deadline_d&deadline_t=$deadline_t");
exit();}
if(!empty($start_date_f) && !empty($start_time_f)){
$start = $start_date_f . ' ' . $start_time_f;
$start_date = date('Y-m-d H:i:s', strtotime(''.$start.''));
if($tz >= 0){
$start_tz = date('Y-m-d H:i:s', strtotime(''.$start_date.' - '.$tz.' minutes'));}
if($tz < 0){
$start_tz = date('Y-m-d H:i:s', strtotime(''.$start_date.' + '.abs($tz).' minutes'));}}}
else {
$start = $_POST['start'];
if(empty($start)){
$start_tz = "00-00-00 00:00:00";}
if(!empty($start)){
$start_date = date('Y-m-d H:i:s', strtotime(''.$start.''));
if($tz >= 0){
$start_tz = date('Y-m-d H:i:s', strtotime(''.$start_date.' - '.$tz.' minutes'));}
if($tz < 0){
$start_tz = date('Y-m-d H:i:s', strtotime(''.$start_date.' + '.abs($tz).' minutes'));}}}
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE){
$ddl = $_POST['deadline'];}
elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE){
$deadline_date_f = $_POST['deadline_date'];
$deadline_time_f = $_POST['deadline_time'];
if(!isset($deadline_date_f) || !isset($deadline_time_f)){
header("Location: assign_copy.php?error=1&s=$s&n=$n&t=$t&ta=$ta&start_d=$start_d&start_t=$start_t&deadline_d=$deadline_d&deadline_t=$deadline_t");
exit();}
$ddl = date('Y-m-d H:i:s', strtotime(''.$deadline_date_f.' '.$deadline_time_f.''));}
elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE){
$deadline_date_f = $_POST['deadline_date'];
$deadline_time_f = $_POST['deadline_time'];
if(!isset($deadline_date_f) || !isset($deadline_time_f)){
header("Location: assign_copy.php?error=1&s=$s&n=$n&t=$t&ta=$ta&start_d=$start_d&start_t=$start_t&deadline_d=$deadline_d&deadline_t=$deadline_t");
exit();}
$ddl = date('Y-m-d H:i:s', strtotime(''.$deadline_date_f.' '.$deadline_time_f.''));}
else {
$ddl = $_POST['deadline'];}
$ddl_date = date('Y-m-d H:i:s', strtotime(''.$ddl.''));
if($tz >= 0){
$ddl_tz = date('Y-m-d H:i:s', strtotime(''.$ddl_date.' - '.$tz.' minutes'));}
if($tz < 0){
$ddl_tz = date('Y-m-d H:i:s', strtotime(''.$ddl_date.' + '.abs($tz).' minutes'));}
if(!isset($ddl)){
header("Location: assign_copy.php?error=1&s=$s&n=$n&t=$t&ta=$ta&start_d=$start_d&start_t=$start_t&deadline_d=$deadline_d&deadline_t=$deadline_t&start=$start_dtl&deadline=$deadline_dtl");
exit();}
if(isset($start) && isset($ddl) && $start >= $ddl){
header("Location: assign_copy.php?error=2&s=$s&n=$n&t=$t&ta=$ta&start_d=$start_d&start_t=$start_t&deadline_d=$deadline_d&deadline_t=$deadline_t&start=$start_dtl&deadline=$deadline_dtl");
exit();}
if(empty($ta) && $ta != 0){
header("Location: assign_copy.php?error=1&s=$s&n=$n&t=$t&ta=$ta&start_d=$start_d&start_t=$start_t&deadline_d=$deadline_d&deadline_t=$deadline_t&start=$start_dtl&deadline=$deadline_dtl");
exit();}
if(!empty($ta) && $ta <= 0){
header("Location: assign_copy.php?error=3&s=$s&n=$n&t=$t&ta=$ta&start_d=$start_d&start_t=$start_t&deadline_d=$deadline_d&deadline_t=$deadline_t&start=$start_dtl&deadline=$deadline_dtl");
exit();}
if(!empty($ta) && !ctype_digit($ta)){
header("Location: assign_copy.php?error=4&s=$s&n=$n&t=$t&ta=$ta&start_d=$start_d&start_t=$start_t&deadline_d=$deadline_d&deadline_t=$deadline_t&start=$start_dtl&deadline=$deadline_dtl");
exit();}
if(!empty($start) && $now > $start_tz){
header("Location: assign_copy.php?error=5&s=$s&n=$n&t=$t&ta=$ta&start_d=$start_d&start_t=$start_t&deadline_d=$deadline_d&deadline_t=$deadline_t&start=$start_dtl&deadline=$deadline_dtl");
exit();}
if(!empty($ddl_tz) && $now > $ddl_tz){
header("Location: assign_copy.php?error=6&s=$s&n=$n&t=$t&ta=$ta&start_d=$start_d&start_t=$start_t&deadline_d=$deadline_d&deadline_t=$deadline_t&start=$start_dtl&deadline=$deadline_dtl");
exit();}
$n_db = $mysqli -> real_escape_string($n);
$ta_db = $mysqli -> real_escape_string($ta);
$sql_ins = "INSERT INTO phoenix_assign (assign_teacher, assign_name, assign_syllabus, assign_type, assign_deadline, assign_time_allowed, assign_active, assign_start, assign_key) VALUES ('".$user_id."', '".$n_db."', '".$s."', '".$t."', '".$ddl_tz."', '".$ta_db."', '0', '".$start_tz."', '".$assign_key."')";
$res_ins = $mysqli -> query($sql_ins);
$sql_g = "SELECT MAX(assign_id) FROM phoenix_assign WHERE assign_teacher = '".$user_id."'";
$res_g = $mysqli -> query($sql_g);
$data_g = mysqli_fetch_assoc($res_g);
$assign_id = $data_g['MAX(assign_id)'];
header("Location: assign_copy_students.php?copy_id=$copy_id&assign_id=$assign_id");}
?>