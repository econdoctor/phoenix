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
if(!array_filter($_POST['assign'])) {
echo 'Please select at least one assignment.';
exit();}
if(!empty($_POST['assign'])){
foreach($_POST['assign'] as $check){
$sql_get = "SELECT * FROM phoenix_assign_questions WHERE assign_id = '".$check."'";
$res_get = $mysqli -> query($sql_get);
while($data_get = mysqli_fetch_assoc($res_get)){
$question_id = $data_get['question_id'];
$sql_up = "UPDATE phoenix_assign_questions_originality SET originality = originality - 1 WHERE teacher_id = '".$user_id."' AND question_id = '".$question_id."'";
$res_up = $mysqli -> query($sql_up);}
$sqlx = "DELETE FROM phoenix_assign WHERE assign_id = '".$check."'";
$resx = $mysqli -> query($sqlx);
$sqly = "DELETE FROM phoenix_answers WHERE answer_type = '3' AND assign_id = '".$check."'";
$resy = $mysqli -> query($sqly);
$sqlz = "DELETE FROM phoenix_assign_questions WHERE assign_id = '".$check."'";
$resz = $mysqli -> query($sqlz);
$sqlt = "DELETE FROM phoenix_assign_qp WHERE assign_id = '".$check."'";
$rest = $mysqli -> query($sqlt);
$sqlu = "DELETE FROM phoenix_assign_t WHERE assign_id = '".$check."'";
$resu = $mysqli -> query($sqlu);
$sqlv = "DELETE FROM phoenix_assign_users WHERE assign_id = '".$check."'";
$resv = $mysqli -> query($sqlv);
$sqle = "DELETE FROM phoenix_assign_teams WHERE assign_id = '".$check."'";
$rese = $mysqli -> query($sqle);}
header("Location: assign.php");}
?>