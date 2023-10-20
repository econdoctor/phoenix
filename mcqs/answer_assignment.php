<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
echo 'die';
exit();}
date_default_timezone_set('UTC');
$answer_date = date("Y-m-d H:i:s");
include "../connectdb.php";
$user_id = $_SESSION['phoenix_user_id'];
$assign_id = $_GET['assign_id'];
$question_id = $_GET['question_id'];
$answer = $_GET['answer'];
if(empty($assign_id) || empty($question_id) || empty($answer)){
exit();}
$sql_assign = "SELECT assign_start FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
$assign_start = date("Y-m-d H:i:s", strtotime($data_assign['assign_start']));
if($answer_date < $assign_start){
exit();}
$sql_auth = "SELECT assign_student_start, assign_student_end FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND student_id = '".$user_id."'";
$res_auth = $mysqli -> query($sql_auth);
$nr_auth = mysqli_num_rows($res_auth);
if($nr_auth == 0){
exit();}
$data_auth = mysqli_fetch_assoc($res_auth);
if($data_auth['assign_student_start'] == NULL){
exit();}
if($data_auth['assign_student_end'] < $answer_date){
exit();}
$sql_del = "DELETE FROM phoenix_answers WHERE user_id = '".$user_id."' AND question_id = '".$question_id."' AND answer_type = '3' AND assign_id = '".$assign_id."'";
$res_del = $mysqli -> query($sql_del);
$sql_check = "SELECT question_refresh, question_answer, question_new_syllabus FROM phoenix_questions WHERE question_id = '".$question_id."'";
$res_check = $mysqli -> query($sql_check);
$data_check = mysqli_fetch_assoc($res_check);
$question_refresh = $data_check['question_refresh'];
$s = $data_check['question_new_syllabus'];
if($question_refresh == 0){
$sql_refresh = "UPDATE phoenix_questions SET question_refresh = '1' WHERE question_id = '".$question_id."'";
$res_refresh = $mysqli -> query($sql_refresh);}
$correct_answer = $data_check['question_answer'];
if($answer == $correct_answer){
$valid = 1;}
if($answer != $correct_answer){
$valid = 0;}
$sql_insert = "INSERT INTO phoenix_answers (user_id, question_id, answer, answer_type, assign_id, answer_date, answer_valid, answer_syllabus) VALUES ('".$user_id."', '".$question_id."', '".$answer."', '3', '".$assign_id."', '".$answer_date."', '".$valid."', '".$s."')";
$res_insert = $mysqli -> query($sql_insert);
$sql_saved = "SELECT answer FROM phoenix_answers WHERE user_id = '".$user_id."' AND question_id = '".$question_id."' AND assign_id = '".$assign_id."' AND answer_type = '3'";
$res_saved = $mysqli -> query($sql_saved);
$nr_saved = mysqli_num_rows($res_saved);
$data_saved = mysqli_fetch_assoc($res_saved);
$answer_callback = $data_saved['answer'];
$sql_count = "SELECT COUNT(*) FROM phoenix_answers WHERE answer_type = '3' AND assign_id = '".$assign_id."' AND user_id = '".$user_id."'";
$res_count = $mysqli -> query($sql_count);
$data_count = mysqli_fetch_assoc($res_count);
$nr_count = $data_count['COUNT(*)'];
$sql1 = "UPDATE phoenix_assign_users SET student_refresh = '1' WHERE student_id = '".$user_id."' AND assign_id = '".$assign_id."'";
$res1 = $mysqli -> query($sql1);
$sql2 = "UPDATE phoenix_assign_questions SET assign_question_refresh = '1' WHERE question_id = '".$question_id."' AND assign_id = '".$assign_id."'";
$res2 = $mysqli -> query($sql2);
echo $nr_saved.$answer_callback.$nr_count;
?>