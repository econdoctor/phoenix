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
echo 'Missing information about the paper.';
exit();}
$sql_check = "SELECT * FROM phoenix_papers WHERE paper_reference = '".$paper_reference."'";
$res_check = $mysqli -> query($sql_check);
$num_rows_check = mysqli_num_rows($res_check);
if($num_rows_check == 0){
header("Location: add_paper.php");
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
header("Location: add_paper_ms.php?paper_reference=$paper_reference");
exit();}
for($x = 1; $x <= 40; $x++){
$question_number = 'q' . $x;
$key = $_POST[$question_number];
if(!isset($key)){
echo 'Missing answer to some questions.';
exit();}
$key_u = strtoupper($key);
if($key_u != 'A' && $key_u != 'B' && $key_u != 'C' && $key_u != 'D' && $key_u != 'X'){
echo 'Incorrect answers to some questions.';
exit();}
if($key_u == 'X'){
$key_u_db = 0;}
if($key_u == 'A'){
$key_u_db = 1;}
if($key_u == 'B'){
$key_u_db = 2;}
if($key_u == 'C'){
$key_u_db = 3;}
if($key_u == 'D'){
$key_u_db = 4;}
$sql_up = "UPDATE phoenix_questions SET question_answer = '".$key_u_db."' WHERE question_paper_id = '".$paper_id."' AND question_number = '".$x."'";
$res_up = $mysqli -> query($sql_up);}
header("Location: add_paper_q.php?paper_reference=$paper_reference");
?>