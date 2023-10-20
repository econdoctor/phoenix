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
echo 'Missing information';
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
$sqlcheck = "SELECT assign_teacher, assign_active FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$rescheck = $mysqli -> query($sqlcheck);
$datacheck = mysqli_fetch_assoc($rescheck);
$teacher_id = $datacheck['assign_teacher'];
$assign_active = $datacheck['assign_active'];
if($assign_active == 1){
echo 'This assignment is already active.';
exit();}
if($teacher_id != $user_id){
echo 'You are not authorized to manage this assignment';
exit();}
if(!array_filter($_POST['question_id'])){
header("Location: assign_questions.php?assign_id=$assign_id&error=1");
exit();}
if(!empty($_POST['question_id'])){
$sql_delete = "DELETE FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."'";
$res_delete = $mysqli -> query($sql_delete);
$i = 0;
foreach($_POST['question_id'] as $check){
$sql_insert = "INSERT INTO phoenix_assign_questions (assign_id, question_id) VALUES ('".$assign_id."', '".$check."')";
$res_insert = $mysqli -> query($sql_insert);
$i++;
if($i > 100){
$sql_tm = "DELETE FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."'";
$res_tm = $mysqli -> query($sql_tm);
header("Location: assign_questions.php?assign_id=$assign_id&error=2");
exit();}}
$sql_nq = "UPDATE phoenix_assign SET assign_nq = '".$i."' WHERE assign_id = '".$assign_id."'";
$res_nq = $mysqli -> query($sql_nq);
header("Location: assign_options.php?assign_id=$assign_id");
exit();}
?>