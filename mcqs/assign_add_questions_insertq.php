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
echo 'Missing information about the assignment.';
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
$sql_assign = "SELECT assign_teacher, assign_scramble, assign_pt, assign_order, assign_hide, assign_nq FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
$assign_teacher = $data_assign['assign_teacher'];
$assign_hide = $data_assign['assign_hide'];
$scramble = $data_assign['assign_scramble'];
$assign_nq = $data_assign['assign_nq'];
if($scramble == "0"){
$scramble = '2';}
$assign_pt = $data_assign['assign_pt'];
$order = $data_assign['assign_order'];
if($assign_teacher != $user_id){
echo 'You are not authorized to manage this assignment.';
exit();}
$sql_check_start = "SELECT COUNT(*) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_start IS NOT NULL";
$res_check_start = $mysqli -> query($sql_check_start);
$data_check_start = mysqli_fetch_assoc($res_check_start);
if($data_check_start['COUNT(*)'] > 0){
echo 'You can no longer add questions to this assignment.';
exit();}
if(!array_filter($_POST['question_id'])){
header("Location: assign_add_questions.php?assign_id=$assign_id&error=1");
exit();}
if(!empty($_POST['question_id'])){
$add = 1;
foreach($_POST['question_id'] as $check){
if($add + $assign_nq > 100){
header("Location: assign_add_questions.php?assign_id=$assign_id&error=2");
exit();}
$add++;}
$i = 0;
foreach($_POST['question_id'] as $check){
$sql_insert = "INSERT INTO phoenix_assign_questions (assign_id, question_id) VALUES ('".$assign_id."', '".$check."')";
$res_insert = $mysqli -> query($sql_insert);
$sql_up = "UPDATE phoenix_assign_questions_originality SET originality = originality + 1 WHERE teacher_id = '".$user_id."' AND question_id = '".$check."'";
$res_up = $mysqli -> query($sql_up);
$i++;}
$sql_nq = "UPDATE phoenix_assign SET assign_nq = assign_nq + '".$i."' WHERE assign_id = '".$assign_id."'";
$res_nq = $mysqli -> query($sql_nq);
if($scramble == '1'){
$sql1 = "SELECT phoenix_assign_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_serie DESC";}
if($scramble == '2' && $assign_pt == "1" && $order == '1'){
$sql1 = "SELECT phoenix_assign_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_serie DESC, phoenix_questions.question_paper_id DESC, phoenix_questions.question_number ASC, phoenix_questions.question_random ASC";}
if($scramble == '2' && $order == '2'){
$sql1 = "SELECT phoenix_assign_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_unit ASC, phoenix_questions.question_module ASC, phoenix_questions.question_number ASC, phoenix_questions.question_serie DESC, phoenix_questions.question_random ASC";}
if($scramble == '2' && $order == '3'){
$sql1 = "SELECT phoenix_assign_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_serie DESC, phoenix_questions.question_unit ASC, phoenix_questions.question_module ASC, phoenix_questions.question_number ASC, phoenix_questions.question_random ASC";}
if($scramble == '2' && $order == '4'){
$sql1 = "SELECT phoenix_assign_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_serie ASC, phoenix_questions.question_unit ASC, phoenix_questions.question_module ASC, phoenix_questions.question_number ASC, phoenix_questions.question_random ASC";}
if($scramble == '2' && $order == '5'){
$sql1 = "SELECT phoenix_assign_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_rate DESC, phoenix_questions.question_random ASC";}
if($scramble == '2' && $order == '6'){
$sql1 = "SELECT phoenix_assign_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY -phoenix_questions.question_rate DESC, phoenix_questions.question_random ASC";}
if($scramble == '2' && $order == '7'){
$sql1 = "SELECT phoenix_assign_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_random ASC";}
$res1 = $mysqli -> query($sql1);
$i = 1;
while($data1 = mysqli_fetch_assoc($res1)){
$question_id = $data1['question_id'];
$sql2 = "UPDATE phoenix_assign_questions SET assign_question_number = '".$i."' WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."'";
$res2 = $mysqli -> query($sql2);
$i++;}
if($assign_hide == 1){
$sql_up = "UPDATE phoenix_assign_questions SET assign_question_hide = '1' WHERE assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);}
header("Location: assign_info.php?assign_id=$assign_id");
exit();}
?>