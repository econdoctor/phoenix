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
$sql_assign = "SELECT assign_name, assign_teacher, assign_syllabus, assign_pt FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
$assign_teacher = $data_assign['assign_teacher'];
$assign_pt = $data_assign['assign_pt'];
$s = $data_assign['assign_syllabus'];
if($assign_teacher != $user_id){
echo 'You are not authorized to manage this assignment.';
exit();}
if($assign_pt == '1'){
echo 'You cannot select questions by topic.';
exit();}
$sql_check_start = "SELECT COUNT(*) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_start IS NOT NULL";
$res_check_start = $mysqli -> query($sql_check_start);
$data_check_start = mysqli_fetch_assoc($res_check_start);
if($data_check_start['COUNT(*)'] > 0){
echo 'You can no longer add questions to this assignment.';
exit();}
if(empty($_POST['assign_topic_1']) && empty($_POST['assign_topic_2']) && empty($_POST['assign_topic_3']) && empty($_POST['assign_topic_4']) && empty($_POST['assign_topic_5']) && empty($_POST['assign_topic_6'])){
header("Location: assign_add_questions_topic.php?assign_id=$assign_id&error=1");
exit();}
$sql_delete_all = "DELETE FROM phoenix_assign_t WHERE assign_id = '".$assign_id."'";
$res_delete_all = $mysqli -> query($sql_delete_all);
if(!empty($_POST['assign_topic_1'])){
foreach($_POST['assign_topic_1'] as $check1){
$sql1 = "INSERT INTO phoenix_assign_t (assign_id, topic_id) VALUES ('".$assign_id."', '".$check1."')";
$res1 = $mysqli -> query($sql1);}}
if(!empty($_POST['assign_topic_2'])){
foreach($_POST['assign_topic_2'] as $check2){
$sql2 = "INSERT INTO phoenix_assign_t (assign_id, topic_id) VALUES ('".$assign_id."', '".$check2."')";
$res2 = $mysqli -> query($sql2);}}
if(!empty($_POST['assign_topic_3'])){
foreach($_POST['assign_topic_3'] as $check3){
$sql3 = "INSERT INTO phoenix_assign_t (assign_id, topic_id) VALUES ('".$assign_id."', '".$check3."')";
$res3 = $mysqli -> query($sql3);}}
if(!empty($_POST['assign_topic_4'])){
foreach($_POST['assign_topic_4'] as $check4){
$sql4 = "INSERT INTO phoenix_assign_t (assign_id, topic_id) VALUES ('".$assign_id."', '".$check4."')";
$res4 = $mysqli -> query($sql4);}}
if(!empty($_POST['assign_topic_5'])){
foreach($_POST['assign_topic_5'] as $check5){
$sql5 = "INSERT INTO phoenix_assign_t (assign_id, topic_id) VALUES ('".$assign_id."', '".$check5."')";
$res5 = $mysqli -> query($sql5);}}
if(!empty($_POST['assign_topic_6'])){
foreach($_POST['assign_topic_6'] as $check6){
$sql6 = "INSERT INTO phoenix_assign_t (assign_id, topic_id) VALUES ('".$assign_id."', '".$check6."')";
$res6 = $mysqli -> query($sql6);}}
$sql_get = "SELECT question_id FROM phoenix_questions WHERE question_topic_id IN (SELECT topic_id FROM phoenix_assign_t WHERE assign_id = '".$assign_id."') AND question_answer <> '0' AND question_repeat = '0' AND question_obsolete = '0'";
$res_get = $mysqli -> query($sql_get);
while($data_get = mysqli_fetch_assoc($res_get)){
$qid_get = $data_get['question_id'];
$sql_or = "SELECT COUNT(*) FROM phoenix_assign_questions_originality WHERE teacher_id = '".$user_id."' AND question_id = '".$qid_get."'";
$res_or = $mysqli -> query($sql_or);
$data_or = mysqli_fetch_assoc($res_or);
$nr_or = $data_or['COUNT(*)'];
if($nr_or == 0){
$sql_ins = "INSERT INTO phoenix_assign_questions_originality (teacher_id, question_id, originality) VALUES ('".$user_id."', '".$qid_get."', '0')";
$res_ins = $mysqli -> query($sql_ins);}}
header("Location: assign_add_questions.php?assign_id=$assign_id");
?>