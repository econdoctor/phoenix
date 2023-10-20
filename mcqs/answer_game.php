<?php
date_default_timezone_set('UTC');
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
echo 'die';
exit();}
include "../connectdb.php";
$assign_id = $_GET['assign_id'];
$question_id = $_GET['question_id'];
$answer_date_init = $_GET['answer_date'];
$answer = $_GET['answer'];
if($answer == 0){
$answer = "IDK";}
if(empty($assign_id) || empty($question_id) || empty($answer) || empty($answer_date_init)){
echo 0;
exit();}
$answer_date = date("Y-m-d H:i:s", strtotime($answer_date_init));
$sql_assign = "SELECT assign_game_status, assign_teams, assign_game_pause, assign_type, assign_time_limit FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
$assign_game_status = $data_assign['assign_game_status'];
$assign_type = $data_assign['assign_type'];
$assign_teams = $data_assign['assign_teams'];
$assign_time_limit = $data_assign['assign_time_limit'];
$assign_game_pause = $data_assign['assign_game_pause'];
if($assign_game_status != 1 || $assign_type != '5' || $assign_game_pause == 1){
echo 0;
exit();}
$sql_auth = "SELECT assign_student_team, assign_student_coeff FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND student_id = '".$user_id."'";
$res_auth = $mysqli -> query($sql_auth);
$nr_auth = mysqli_num_rows($res_auth);
if($nr_auth == 0){
echo 0;
exit();}
$data_auth = mysqli_fetch_assoc($res_auth);
$assign_student_team = $data_auth['assign_student_team'];
$sql_q = "SELECT assign_question_status, question_refresh, assign_question_deadline, question_new_syllabus, question_answer FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE assign_id = '".$assign_id."' AND phoenix_assign_questions.question_id = '".$question_id."'";
$res_q = $mysqli -> query($sql_q);
$data_q = mysqli_fetch_assoc($res_q);
$assign_question_status = $data_q['assign_question_status'];
$assign_question_deadline = $data_q['assign_question_deadline'];
$question_answer = $data_q['question_answer'];
$question_new_syllabus = $data_q['question_new_syllabus'];
$question_refresh = $data_q['question_refresh'];
if($assign_question_status != 1 || $answer_date > $assign_question_deadline){
echo 0;
exit();}
$sql_d = "SELECT COUNT(*) FROM phoenix_answers WHERE user_id = '".$user_id."' AND question_id = '".$question_id."' AND assign_id = '".$assign_id."'";
$res_d = $mysqli -> query($sql_d);
$data_d = mysqli_fetch_assoc($res_d);
$nr_d = $data_d['COUNT(*)'];
if($nr_d > 0){
echo 0;
exit();}
if($question_refresh == 0){
$sql_refresh = "UPDATE phoenix_questions SET question_refresh = '1' WHERE question_id = '".$question_id."'";
$res_refresh = $mysqli -> query($sql_refresh);}
if($answer == "IDK"){
$answer_db = '0';
$sql_insert = "INSERT INTO phoenix_answers (user_id, question_id, answer, answer_type, assign_id, answer_date, answer_valid, answer_syllabus, answer_points) VALUES ('".$user_id."', '".$question_id."', '".$answer_db."', '3', '".$assign_id."', '".$answer_date."', '0', '".$question_new_syllabus."', '0')";
$res_insert = $mysqli -> query($sql_insert);
$sql_score_ind = "UPDATE phoenix_assign_users SET student_refresh = '1' WHERE assign_id = '".$assign_id."' AND student_id = '".$user_id."'";
$res_score_ind = $mysqli -> query($sql_score_ind);}
if($answer != "IDK"){
if($answer == $question_answer){
$valid = 1;
$start = date("Y-m-d H:i:s", strtotime(''.$assign_question_deadline.' - '.$assign_time_limit.' seconds'));
$answer_points = round(100 - (50 / $assign_time_limit) * (strtotime($answer_date) - strtotime($start)), 0);}
if($answer != $question_answer){
$valid = 0;
$answer_points = -50;}
$sql_insert = "INSERT INTO phoenix_answers (user_id, question_id, answer, answer_type, assign_id, answer_date, answer_valid, answer_syllabus, answer_points) VALUES ('".$user_id."', '".$question_id."', '".$answer."', '3', '".$assign_id."', '".$answer_date."', '".$valid."', '".$question_new_syllabus."', '".$answer_points."')";
$res_insert = $mysqli -> query($sql_insert);
$sql_score_ind = "UPDATE phoenix_assign_users SET student_refresh = '1', assign_student_points = assign_student_points + '".$answer_points."' WHERE assign_id = '".$assign_id."' AND student_id = '".$user_id."'";
$res_score_ind = $mysqli -> query($sql_score_ind);
if($assign_teams == 1){
$assign_student_coeff = $data_auth['assign_student_coeff'];
$answer_points_team = round($answer_points * $assign_student_coeff / 100, 0);
$sql_score_team = "UPDATE phoenix_assign_teams SET score = score + '".$answer_points_team."' WHERE assign_id = '".$assign_id."' AND team = '".$assign_student_team."'";
$res_score_team = $mysqli -> query($sql_score_team);}}
$sql_saved = "SELECT answer FROM phoenix_answers WHERE user_id = '".$user_id."' AND question_id = '".$question_id."' AND assign_id = '".$assign_id."'";
$res_saved = $mysqli -> query($sql_saved);
$nr_saved = mysqli_num_rows($res_saved);
$data_saved = mysqli_fetch_assoc($res_saved);
$answer_callback = $data_saved['answer'];
echo $nr_saved.$answer_callback;
?>