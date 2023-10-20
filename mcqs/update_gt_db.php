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
echo 'Missing information about the assignment.';
exit();}
$sql_as = "SELECT assign_name, assign_teacher, assign_syllabus FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_as = $mysqli -> query($sql_as);
$data_as = mysqli_fetch_assoc($res_as);
$assign_name = $data_as['assign_name'];
$assign_teacher = $data_as['assign_teacher'];
$assign_syllabus = $data_as['assign_syllabus'];
if($assign_teacher != $user_id){
echo 'You are not authorized to manage this assignment.';
exit();}
$min_a = $_POST['min_a'];
$min_b = $_POST['min_b'];
$min_c = $_POST['min_c'];
$min_d = $_POST['min_d'];
$min_e = $_POST['min_e'];
if($assign_syllabus == '1'){
$min_f = $_POST['min_f'];
$min_g = $_POST['min_g'];}
if(!isset($min_a) || !isset($min_b) || !isset($min_c) || !isset($min_d) || !isset($min_e)){
header("Location: update_gt.php?assign_id=$assign_id&error=1");
exit();}
if($assign_syllabus == '1' && (!isset($min_f) || !isset($min_g))){
header("Location: update_gt.php?assign_id=$assign_id&error=1");
exit();}
if(!ctype_digit($min_a) || !ctype_digit($min_b) || !ctype_digit($min_c) || !ctype_digit($min_d) || !ctype_digit($min_e)){
header("Location: update_gt.php?assign_id=$assign_id&error=2");
exit();}
if($assign_syllabus == '1' && (!ctype_digit($min_f) || !ctype_digit($min_g))){
header("Location: update_gt.php?assign_id=$assign_id&error=2");
exit();}
if($assign_syllabus != '1' && 100 >= $min_a && $min_a >= $min_b && $min_b >= $min_c && $min_c >= $min_d && $min_d >= $min_e && $min_e >= 0){
$sql_up = "UPDATE phoenix_assign SET assign_a = '".$min_a."', assign_b = '".$min_b."', assign_c = '".$min_c."', assign_d = '".$min_d."', assign_e = '".$min_e."' WHERE assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);
header("Location: assign_info.php?assign_id=$assign_id");
exit();}
if($assign_syllabus == '1' && 100 >= $min_a && $min_a >= $min_b && $min_b >= $min_c && $min_c >= $min_d && $min_d >= $min_e && $min_e >= $min_f && $min_f >= $min_g && $min_g >= 0){
$sql_up = "UPDATE phoenix_assign SET assign_a = '".$min_a."', assign_b = '".$min_b."', assign_c = '".$min_c."', assign_d = '".$min_d."', assign_e = '".$min_e."',  assign_f = '".$min_f."', assign_g = '".$min_g."' WHERE assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);
header("Location: assign_info.php?assign_id=$assign_id");
exit();}
else {
header("Location: update_gt.php?assign_id=$assign_id&error=3");}
?>