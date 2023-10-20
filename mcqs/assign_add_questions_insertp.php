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
$assign_pt = $data_assign['assign_pt'];
$assign_teacher = $data_assign['assign_teacher'];
$s = $data_assign['assign_syllabus'];
if($assign_teacher != $user_id){
echo 'You are not authorized to manage this assignment.';
exit();}
if($assign_pt == '2'){
echo 'You cannot select questions by paper.';
exit();}
$sql_check_start = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."'";
$res_check_start = $mysqli -> query($sql_check_start);
$data_check_start = mysqli_fetch_assoc($res_check_start);
$nr_check_start = $data_check_start['COUNT(*)'];
if($nr_check_start > 0){
echo 'You can no longer add questions to this assignment.';
exit();}
if(!array_filter($_POST['assign_paper'])) {
header("Location: assign_add_questions_paper.php?assign_id=$assign_id&error=1");
exit();}
if(!empty($_POST['assign_paper'])){
$sql_delete = "DELETE FROM phoenix_assign_qp WHERE assign_id = '".$assign_id."'";
$res_delete = $mysqli -> query($sql_delete);
foreach($_POST['assign_paper'] as $check){
$sql_insert = "INSERT INTO phoenix_assign_qp (assign_id, paper_id) VALUES ('".$assign_id."', '".$check."')";
$res_insert = $mysqli -> query($sql_insert);}
$sql_type = "UPDATE phoenix_assign SET assign_pt = '1' WHERE assign_id = '".$assign_id."'";
$res_type = $mysqli -> query($sql_type);
$sql_del_aco = "DELETE FROM phoenix_assign_t WHERE assign_id = '".$assign_id."'";
$res_del_aco = $mysqli -> query($sql_del_aco);
$sql_get = "SELECT question_id FROM phoenix_questions WHERE question_paper_id IN (SELECT paper_id FROM phoenix_assign_qp WHERE assign_id = '".$assign_id."') AND question_answer <> '0'";
$res_get = $mysqli -> query($sql_get);
while($data_get = mysqli_fetch_assoc($res_get)){
$question_id = $data_get['question_id'];
$sql_or = "SELECT COUNT(*) FROM phoenix_assign_questions_originality WHERE teacher_id = '".$user_id."' AND question_id = '".$question_id."'";
$res_or = $mysqli -> query($sql_or);
$data_or = mysqli_fetch_assoc($res_or);
$nr_or = $data_or['COUNT(*)'];
if($nr_or == 0){
$sql_ins = "INSERT INTO phoenix_assign_questions_originality (teacher_id, question_id, originality) VALUES ('".$user_id."', '".$question_id."', '0')";
$res_ins = $mysqli -> query($sql_ins);}}
header("Location: assign_add_questions.php?assign_id=$assign_id");
exit();}
?>