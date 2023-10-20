<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_id ='".$user_id."'";
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
$s = $_GET['s'];
if(!isset($s)){
echo 'Missing information about the course.';
exit();}
$min_a = $_POST['min_a'];
$min_b = $_POST['min_b'];
$min_c = $_POST['min_c'];
$min_d = $_POST['min_d'];
$min_e = $_POST['min_e'];
$min_f = $_POST['min_f'];
$min_g = $_POST['min_g'];
if(!isset($min_a) || !isset($min_b) || !isset($min_c) || !isset($min_d) || !isset($min_e)){
echo 'All fields are required.<br>Please go back and try again.';
exit();}
if($s == '1' && (!isset($min_f) || !isset($min_g))){
echo 'All fields are required.<br>Please go back and try again.';
exit();}
if(!is_numeric($min_a) || !is_numeric($min_b) || !is_numeric($min_c) || !is_numeric($min_d) || !is_numeric($min_e)){
echo 'The grade thresholds must be integers.<br>Please go back and try again.';
exit();}
if($s == '1' && (!is_numeric($min_f) || !is_numeric($min_g))){
echo 'The grade thresholds must be integers.<br>Please go back and try again.';
exit();}
if($s != '1' && $min_a >= $min_b && $min_b >= $min_c && $min_c >= $min_d && $min_d >= $min_e){
$sql_up = "UPDATE phoenix_thresholds SET min_a = '".$min_a."', min_b = '".$min_b."', min_c = '".$min_c."', min_d = '".$min_d."', min_e = '".$min_e."' WHERE teacher_id = '".$user_id."' AND syllabus = '".$s."'";
$res_up = $mysqli -> query($sql_up);
header("Location: manage_thresholds.php");
exit();}
if($s == '1' && $min_a >= $min_b && $min_b >= $min_c && $min_c >= $min_d && $min_d >= $min_e && $min_e >= $min_f && $min_f >= $min_g){
$sql_up = "UPDATE phoenix_thresholds SET min_a = '".$min_a."', min_b = '".$min_b."', min_c = '".$min_c."', min_d = '".$min_d."', min_e = '".$min_e."', min_f = '".$min_f."', min_g = '".$min_g."' WHERE teacher_id = '".$user_id."' AND syllabus = '1'";
$res_up = $mysqli -> query($sql_up);
header("Location: manage_thresholds.php");
exit();}
else {
echo 'Your thresholds are inconsistent.<br>Please go back and try again.';
exit();}
?>