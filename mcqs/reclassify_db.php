<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
if($user_id != 1){
echo 'Access is restricted';
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$topic_id = $_GET['topic_id'];
$question_id = $_GET['question_id'];
$new_unit = $_POST['topic_unit'];
$new_module = $_POST['topic_module'];
$new_syllabus = $_POST['new_syllabus'];
$m = $_POST['m'];
if(empty($topic_id) || empty($question_id) || empty($new_unit) || empty($new_module) || empty($new_syllabus) || $new_unit == "" || $new_module == ""){
echo 'Missing information';
exit();}
$sql_info = "SELECT question_syllabus FROM phoenix_questions WHERE question_id = '".$question_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
$question_syllabus = $data_info['question_syllabus'];
$sql_get = "SELECT topic_id FROM phoenix_topics WHERE topic_syllabus = '".$new_syllabus."' AND topic_unit_id = '".$new_unit."' AND topic_module_id = '".$new_module."'";
$res_get = $mysqli -> query($sql_get);
$data_get = mysqli_fetch_assoc($res_get);
$new_topic_id = $data_get['topic_id'];
$sql_q = "UPDATE phoenix_questions SET question_unit = '".$new_unit."', question_module = '".$new_module."', question_topic_id = '".$new_topic_id."' WHERE question_id = '".$question_id."'";
$res_q = $mysqli -> query($sql_q);
if($m == '2' && $question_syllabus == '3'){
$sql_m = "UPDATE phoenix_questions SET question_move = '1', question_new_syllabus = '2' WHERE question_id = '".$question_id."'";
$res_m = $mysqli -> query($sql_m);
$sql_as = "UPDATE phoenix_answers SET answer_syllabus = '2' WHERE question_id = '".$question_id."'";
$res_as = $mysqli -> query($sql_as);}
if($m == '3' && $question_syllabus == '2'){
$sql_m = "UPDATE phoenix_questions SET question_move = '2', question_new_syllabus = '3' WHERE question_id = '".$question_id."'";
$res_m = $mysqli -> query($sql_m);
$sql_as = "UPDATE phoenix_answers SET answer_syllabus = '3' WHERE question_id = '".$question_id."'";
$res_as = $mysqli -> query($sql_as);}
if($m == '3' && $question_syllabus == '3'){
$sql_m = "UPDATE phoenix_questions SET question_move = '0', question_new_syllabus = '3' WHERE question_id = '".$question_id."'";
$res_m = $mysqli -> query($sql_m);
$sql_as = "UPDATE phoenix_answers SET answer_syllabus = '3' WHERE question_id = '".$question_id."'";
$res_as = $mysqli -> query($sql_as);}
if($m == '2' && $question_syllabus == '2'){
$sql_m = "UPDATE phoenix_questions SET question_move = '0', question_new_syllabus = '2' WHERE question_id = '".$question_id."'";
$res_m = $mysqli -> query($sql_m);
$sql_as = "UPDATE phoenix_answers SET answer_syllabus = '2' WHERE question_id = '".$question_id."'";
$res_as = $mysqli -> query($sql_as);}
$sql_ans = "UPDATE phoenix_answers SET topic_id = '".$new_topic_id."' WHERE question_id = '".$question_id."' AND answer_type = '2'";
$res_ans = $mysqli -> query($sql_ans);
header("Location: topic_view.php?topic_id=$topic_id");
?>