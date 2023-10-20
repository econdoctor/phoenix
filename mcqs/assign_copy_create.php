<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
$assign_id = $_GET['assign_id'];
$copy_id = $_GET['copy_id'];
if(empty($assign_id) || empty($copy_id)){
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
$sqlcheck = "SELECT assign_teacher, assign_active, assign_pt, assign_start, assign_deadline, assign_syllabus, assign_type FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$rescheck = $mysqli -> query($sqlcheck);
$datacheck = mysqli_fetch_assoc($rescheck);
$teacher_id = $datacheck['assign_teacher'];
$assign_type = $datacheck['assign_type'];
$assign_active = $datacheck['assign_active'];
$assign_pt = $datacheck['assign_pt'];
$start_db = $datacheck['assign_start'];
$deadline = $datacheck['assign_deadline'];
$s = $datacheck['assign_syllabus'];
if($assign_active == 1){
echo 'This assignment is already active.';
exit();}
if($teacher_id != $user_id) {
echo 'You are not authorized to manage this assignment';
exit();}
$sql_info = "SELECT assign_teacher, assign_active, assign_type FROM phoenix_assign WHERE assign_id = '".$copy_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
if($data_info['assign_type'] == '4'){
echo 'You cannot copy offline assignments';
exit();}
if($data_info['assign_teacher'] != $user_id){
echo 'You are not authorized to copy this assignment';
exit();}
if($data_info['assign_active'] == 0){
echo 'The original assignment is inactive';
exit();}
if($assign_type == '5'){
$order = $_POST['order'];
$order_db = $mysqli -> real_escape_string($order);
$time_limit = $_POST['time_limit'];
$time_limit_db = $mysqli -> real_escape_string($time_limit);
$show_ranking = $_POST['show_ranking'];
$min_a = $_POST['min_a'];
$min_b = $_POST['min_b'];
$min_c = $_POST['min_c'];
$min_d = $_POST['min_d'];
$min_e = $_POST['min_e'];
if($s == '1'){
$min_f = $_POST['min_f'];
$min_g = $_POST['min_g'];
if(!isset($min_f) || !isset($min_g)){
header("Location: assign_copy_options.php?copy_id=$copy_id&assign_id=$assign_id&error=1&show_ranking=$show_ranking&min_a=$min_a&min_b=$min_b&min_c=$min_c&min_d=$min_d&min_e=$min_e&min_f=$min_f&min_g=$min_g&order=$order&time_limit=$time_limit");
exit();}}
if($show_ranking == "" || empty($show_ranking) || $order == "" || empty($order) || $time_limit == "" || empty($time_limit) || !isset($min_a) || !isset($min_b) || !isset($min_c) || !isset($min_d) || !isset($min_e)){
header("Location: assign_copy_options.php?copy_id=$copy_id&assign_id=$assign_id&error=1&show_ranking=$show_ranking&min_a=$min_a&min_b=$min_b&min_c=$min_c&min_d=$min_d&min_e=$min_e&min_f=$min_f&min_g=$min_g&order=$order&time_limit=$time_limit");
exit();}
if($s != '1' && ($min_a < $min_b || $min_b < $min_c || $min_c < $min_d || $min_d < $min_e)){
header("Location: assign_copy_options.php?copy_id=$copy_id&assign_id=$assign_id&error=2&show_ranking=$show_ranking&min_a=$min_a&min_b=$min_b&min_c=$min_c&min_d=$min_d&min_e=$min_e&min_f=$min_f&min_g=$min_g&order=$order&time_limit=$time_limit");
exit();}
if($s == '1' && ($min_a < $min_b || $min_b < $min_c || $min_c < $min_d || $min_d < $min_e || $min_e < $min_f || $min_f < $min_g)){
header("Location: assign_copy_options.php?copy_id=$copy_id&assign_id=$assign_id&error=2&show_ranking=$show_ranking&min_a=$min_a&min_b=$min_b&min_c=$min_c&min_d=$min_d&min_e=$min_e&min_f=$min_f&min_g=$min_g&order=$order&time_limit=$time_limit");
exit();}
if($show_ranking == 'end'){
$show_ranking = 0;}
$show_ranking_db = $mysqli -> real_escape_string($show_ranking);
if($s != '1' && $min_a >= $min_b && $min_b >= $min_c && $min_c >= $min_d && $min_d >= $min_e){
$sql_main = "UPDATE phoenix_assign SET assign_created = '".$now."', assign_start = '".$now."', assign_deadline = '".$now."', assign_active = '1', assign_game_status = '0', assign_order = '".$order_db."', assign_show_ranking = '".$show_ranking_db."', assign_a = '".$min_a."', assign_b = '".$min_b."', assign_c = '".$min_c."', assign_d = '".$min_d."', assign_e = '".$min_e."', assign_time_limit = '".$time_limit_db."' WHERE assign_id = '".$assign_id."'";}
if($s == '1' && $min_a >= $min_b && $min_b >= $min_c && $min_c >= $min_d && $min_d >= $min_e && $min_e >= $min_f && $min_f >= $min_g){
$sql_main = "UPDATE phoenix_assign SET assign_created = '".$now."', assign_start = '".$now."', assign_deadline = '".$now."', assign_active = '1', assign_game_status = '0', assign_order = '".$order_db."', assign_show_ranking = '".$show_ranking_db."', assign_a = '".$min_a."', assign_b = '".$min_b."', assign_c = '".$min_c."', assign_d = '".$min_d."', assign_e = '".$min_e."', assign_f = '".$min_f."', assign_g = '".$min_g."', assign_time_limit = '".$time_limit_db."' WHERE assign_id = '".$assign_id."'";}
$res_main = $mysqli -> query($sql_main);
$sql_del1 = "DELETE FROM phoenix_assign_qp WHERE assign_id = '".$assign_id."'";
$res_del1 = $mysqli -> query($sql_del1);
$sql_del2 = "DELETE FROM phoenix_assign_t WHERE assign_id = '".$assign_id."'";
$res_del2 = $mysqli -> query($sql_del2);
if($assign_pt == "1" && $order == '1'){
$sql1 = "SELECT phoenix_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_serie DESC, phoenix_questions.question_paper_id DESC, phoenix_questions.question_number ASC, phoenix_questions.question_random ASC";}
if($order == '2'){
$sql1 = "SELECT phoenix_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_unit ASC, phoenix_questions.question_module ASC, phoenix_questions.question_number ASC, phoenix_questions.question_serie DESC, phoenix_questions.question_random ASC";}
if($order == '3'){
$sql1 = "SELECT phoenix_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_serie DESC, phoenix_questions.question_unit ASC, phoenix_questions.question_module ASC, phoenix_questions.question_number ASC, phoenix_questions.question_random ASC";}
if($order == '4'){
$sql1 = "SELECT phoenix_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_serie ASC, phoenix_questions.question_unit ASC, phoenix_questions.question_module ASC, phoenix_questions.question_number ASC, phoenix_questions.question_random ASC";}
if($order == '5'){
$sql1 = "SELECT phoenix_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_rate DESC, phoenix_questions.question_random ASC";}
if($order == '6'){
$sql1 = "SELECT phoenix_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY -phoenix_questions.question_rate DESC, phoenix_questions.question_random ASC";}
if($order == '7'){
$sql1 = "SELECT phoenix_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_random ASC";}
$res1 = $mysqli -> query($sql1);
$i = 1;
while($data1 = mysqli_fetch_assoc($res1)){
$q_id = $data1['question_id'];
$sql2 = "UPDATE phoenix_assign_questions SET assign_question_number = '".$i."' WHERE assign_id = '".$assign_id."' AND question_id = '".$q_id."'";
$res2 = $mysqli -> query($sql2);
$sql3 = "UPDATE phoenix_assign_questions_originality SET originality = originality + 1 WHERE teacher_id = '".$user_id."' AND question_id = '".$q_id."'";
$res3 = $mysqli -> query($sql3);
$i++;}
header("Location: assign.php?r=1");
exit();}
if($assign_type != '5'){
$release = $_POST['release'];
$scramble = $_POST['scramble'];
$order = $_POST['order'];
$order_db = $mysqli -> real_escape_string($order);
$hide = $_POST['hide'];
$min_a = $_POST['min_a'];
$min_b = $_POST['min_b'];
$min_c = $_POST['min_c'];
$min_d = $_POST['min_d'];
$min_e = $_POST['min_e'];
if($s == '1'){
$min_f = $_POST['min_f'];
$min_g = $_POST['min_g'];
if(!isset($min_f) || !isset($min_g)){
header("Location: assign_copy_options.php?copy_id=$copy_id&assign_id=$assign_id&error=1&scramble=$scramble&hide=$hide&release=$release&min_a=$min_a&min_b=$min_b&min_c=$min_c&min_d=$min_d&min_e=$min_e&min_f=$min_f&min_g=$min_g&order=$order");
exit();}}
if(!isset($scramble) || !isset($hide) || !isset($release) || !isset($min_a) || !isset($min_b) || !isset($min_c) || !isset($min_d) || !isset($min_e)){
header("Location: assign_copy_options.php?copy_id=$copy_id&assign_id=$assign_id&error=1&scramble=$scramble&hide=$hide&release=$release&min_a=$min_a&min_b=$min_b&min_c=$min_c&min_d=$min_d&min_e=$min_e&min_f=$min_f&min_g=$min_g&order=$order");
exit();}
if($scramble == '2' && (!isset($order) || $order == "")){
header("Location: assign_copy_options.php?copy_id=$copy_id&assign_id=$assign_id&error=1&scramble=$scramble&hide=$hide&release=$release&min_a=$min_a&min_b=$min_b&min_c=$min_c&min_d=$min_d&min_e=$min_e&min_f=$min_f&min_g=$min_g&order=$order");
exit();}
if($start_db == "0000-00-00 00:00:00"){
$sql_time_update = "UPDATE phoenix_assign SET assign_start = '".$now."' WHERE assign_id = '".$assign_id."'";
$res_time_update = $mysqli -> query($sql_time_update);}
if($s != '1' && ($min_a < $min_b || $min_b < $min_c || $min_c < $min_d || $min_d < $min_e)){
header("Location: assign_copy_options.php?copy_id=$copy_id&assign_id=$assign_id&error=2&scramble=$scramble&hide=$hide&release=$release&min_a=$min_a&min_b=$min_b&min_c=$min_c&min_d=$min_d&min_e=$min_e&min_f=$min_f&min_g=$min_g&order=$order");
exit();}
if($s == '1' && ($min_a < $min_b || $min_b < $min_c || $min_c < $min_d || $min_d < $min_e || $min_e < $min_f || $min_f < $min_g)){
header("Location: assign_copy_options.php?copy_id=$copy_id&assign_id=$assign_id&error=2&scramble=$scramble&hide=$hide&release=$release&min_a=$min_a&min_b=$min_b&min_c=$min_c&min_d=$min_d&min_e=$min_e&min_f=$min_f&min_g=$min_g&order=$order");
exit();}
if($s != '1' && $min_a >= $min_b && $min_b >= $min_c && $min_c >= $min_d && $min_d >= $min_e){
$sql_main = "UPDATE phoenix_assign SET assign_created = '".$now."', assign_active = '1', assign_scramble = '".$scramble."', assign_order = '".$order_db."', assign_a = '".$min_a."', assign_b = '".$min_b."', assign_c = '".$min_c."', assign_d = '".$min_d."', assign_e = '".$min_e."', assign_release = '".$release."', assign_hide = '".$hide."' WHERE assign_id = '".$assign_id."'";}
if($s == '1' && $min_a >= $min_b && $min_b >= $min_c && $min_c >= $min_d && $min_d >= $min_e && $min_e >= $min_f && $min_f >= $min_g){
$sql_main = "UPDATE phoenix_assign SET assign_created = '".$now."', assign_active = '1', assign_scramble = '".$scramble."', assign_order = '".$order_db."', assign_a = '".$min_a."', assign_b = '".$min_b."', assign_c = '".$min_c."', assign_d = '".$min_d."', assign_e = '".$min_e."', assign_f = '".$min_f."', assign_g = '".$min_g."', assign_release = '".$release."', assign_hide = '".$hide."' WHERE assign_id = '".$assign_id."'";}
$res_main = $mysqli -> query($sql_main);
$sql_del1 = "DELETE FROM phoenix_assign_qp WHERE assign_id = '".$assign_id."'";
$res_del1 = $mysqli -> query($sql_del1);
$sql_del2 = "DELETE FROM phoenix_assign_t WHERE assign_id = '".$assign_id."'";
$res_del2 = $mysqli -> query($sql_del2);
if($scramble == '1'){
$sql1 = "SELECT phoenix_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_serie DESC";}
if($scramble == '2' && $assign_pt == "1" && $order == '1'){
$sql1 = "SELECT phoenix_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_serie DESC, phoenix_questions.question_paper_id DESC, phoenix_questions.question_number ASC, phoenix_questions.question_random ASC";}
if($scramble == '2' && $order == '2'){
$sql1 = "SELECT phoenix_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_unit ASC, phoenix_questions.question_module ASC, phoenix_questions.question_number ASC, phoenix_questions.question_serie DESC, phoenix_questions.question_random ASC";}
if($scramble == '2' && $order == '3'){
$sql1 = "SELECT phoenix_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_serie DESC, phoenix_questions.question_unit ASC, phoenix_questions.question_module ASC, phoenix_questions.question_number ASC, phoenix_questions.question_random ASC";}
if($scramble == '2' && $order == '4'){
$sql1 = "SELECT phoenix_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_serie ASC, phoenix_questions.question_unit ASC, phoenix_questions.question_module ASC, phoenix_questions.question_number ASC, phoenix_questions.question_random ASC";}
if($scramble == '2' && $order == '5'){
$sql1 = "SELECT phoenix_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_rate DESC, phoenix_questions.question_random ASC";}
if($scramble == '2' && $order == '6'){
$sql1 = "SELECT phoenix_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY -phoenix_questions.question_rate DESC, phoenix_questions.question_random ASC";}
if($scramble == '2' && $order == '7'){
$sql1 = "SELECT phoenix_questions.question_id FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_questions.question_random ASC";}
$res1 = $mysqli -> query($sql1);
$i = 1;
while($data1 = mysqli_fetch_assoc($res1)){
$q_id = $data1['question_id'];
$sql2 = "UPDATE phoenix_assign_questions SET assign_question_number = '".$i."' WHERE assign_id = '".$assign_id."' AND question_id = '".$q_id."'";
$res2 = $mysqli -> query($sql2);
$sql3 = "UPDATE phoenix_assign_questions_originality SET originality = originality + 1 WHERE teacher_id = '".$user_id."' AND question_id = '".$q_id."'";
$res3 = $mysqli -> query($sql3);
$i++;}
if($hide == '1'){
$sql_up = "UPDATE phoenix_assign_questions SET assign_question_hide = '1' WHERE assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);}
header("Location: assign.php?r=1");}
?>