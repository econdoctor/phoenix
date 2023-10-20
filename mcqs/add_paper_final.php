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
$sql = "SELECT user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_id != 1){
echo 'Access restricted';
exit();}
$paper_reference = $_GET['paper_reference'];
if(empty($paper_reference)){
echo 'Missing information about the paper';
exit();}
$sql_check = "SELECT * FROM phoenix_papers WHERE paper_reference = '".$paper_reference."'";
$res_check = $mysqli -> query($sql_check);
$num_rows_check = mysqli_num_rows($res_check);
if($num_rows_check == 0){
echo 'Missing information about the paper.';
exit();}
$data_check = mysqli_fetch_assoc($res_check);
$paper_id = $data_check['paper_id'];
$paper_syllabus = $data_check['paper_syllabus'];
$paper_a = $data_check['paper_a'];
$paper_b = $data_check['paper_b'];
$paper_c = $data_check['paper_c'];
$paper_d = $data_check['paper_d'];
$paper_e = $data_check['paper_e'];
$paper_f = $data_check['paper_f'];
$paper_g = $data_check['paper_g'];
if($paper_syllabus != 1 && ($paper_a == '0' || $paper_b == '0' || $paper_c == '0' || $paper_d == '0' || $paper_e == '0')){
header("Location: add_paper_gt.php?paper_reference=$paper_reference");
exit();}
if($paper_syllabus == 1 && ($paper_a == '0' || $paper_b == '0' || $paper_c == '0' || $paper_d == '0' || $paper_e == '0' || $paper_f == '0' || $paper_g == '0')){
header("Location: add_paper_gt.php?paper_reference=$paper_reference");
exit();}
$sql_q = "SELECT * FROM phoenix_questions WHERE question_paper_id = '".$paper_id."'";
$res_q = $mysqli -> query($sql_q);
$num_rows_q = mysqli_num_rows($res_q);
if($num_rows_q == 0){
echo 'The questions were not inserted into the database.';
exit();}
$sql_ans = "SELECT question_answer FROM phoenix_questions WHERE question_paper_id = '".$paper_id."'";
$res_ans = $mysqli -> query($sql_ans);
while($data_ans = mysqli_fetch_assoc($res_ans)){
$answer = $data_ans['question_answer'];
if($answer != 1 && $answer != 2 && $answer != 3 && $answer != 4 && $answer != 0){
echo 'Some answers are missing or invalid.';
exit();}}
$target_check = 'q/' . $paper_syllabus . '/' . $paper_id . '/';
$files_in_directory = scandir($target_check);
$items_count = count($files_in_directory);
if($items_count != 32){
echo 'Some questions appear to be missing.';
exit();}
for($j=1; $j<=30; $j++){
$filename_check = $_SERVER['DOCUMENT_ROOT'] . '/q/'.$paper_syllabus.'/'.$paper_id . '/'.$j.'.png';
if(!file_exists($filename_check)){
echo 'Error. <br><a href="add_paper_upload.php?paper_reference='.$paper_reference.'">Click here to try again</a>';
exit();}}
$sql_class = "SELECT question_unit, question_module, question_topic_id FROM phoenix_questions WHERE question_paper_id = '".$paper_id."'";
$res_class = $mysqli -> query($sql_class);
while($data_class = mysqli_fetch_assoc($res_class)){
$question_unit = $data_class['question_unit'];
$question_module = $data_class['question_module'];
$question_topic_id = $data_class['question_topic_id'];
if($question_unit == 0 || $question_module == 0 || $question_topic_id == 0 || $question_topic_id == 999){
echo 'Some questions are not properly classified.';
exit();}}
header("Location: main.php");
?>