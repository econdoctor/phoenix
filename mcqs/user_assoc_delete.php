<?php
if(!array_filter($_POST['manage_user'])){
echo 'Please select at least one student.';
exit();}
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
$sql = "SELECT user_type FROM phoenix_users WHERE user_id = '".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
if(!empty($_POST['manage_user'])){
foreach($_POST['manage_user'] as $check){
$sql_1 = "DELETE FROM phoenix_answers WHERE user_id = '".$check."' AND answer_type < '3'";
$res_1 = $mysqli -> query($sql_1);
$sql_3 = "DELETE FROM phoenix_assign_users WHERE student_id = '".$check."'";
$res_3 = $mysqli -> query($sql_3);
$sql_4 = "DELETE FROM phoenix_group_users WHERE user_id = '".$check."'";
$res_4 = $mysqli -> query($sql_4);
$sql_5 = "DELETE FROM phoenix_permissions_users WHERE student_id = '".$check."'";
$res_5 = $mysqli -> query($sql_5);
$sql_6 = "DELETE FROM phoenix_thresholds WHERE teacher_id = '".$check."'";
$res_6 = $mysqli -> query($sql_6);
$sql_7 = "INSERT INTO phoenix_thresholds (teacher_id, syllabus, min_a, min_b, min_c, min_d, min_e, min_f, min_g) VALUES ('".$check."','3','80','70','60','50','40', '0', '0')";
$res_7 = $mysqli -> query($sql_7);
$sql_8 = "INSERT INTO phoenix_thresholds (teacher_id, syllabus, min_a, min_b, min_c, min_d, min_e, min_f, min_g) VALUES ('".$check."','2','80','70','60','50','40', '0', '0')";
$res_8 = $mysqli -> query($sql_8);
$sql_9 = "INSERT INTO phoenix_thresholds (teacher_id, syllabus, min_a, min_b, min_c, min_d, min_e, min_f, min_g) VALUES ('".$check."','1','75','65','55','45','40', '35', '30')";
$res_9 = $mysqli -> query($sql_9);
$sql_10 = "UPDATE phoenix_users SET user_teacher = '0', user_refresh = '1' WHERE user_id = '".$check."'";
$res_10 = $mysqli -> query($sql_10);}
$sql_11 = "SELECT permission_id FROM phoenix_permissions WHERE permission_teacher = '".$user_id."'";
$res_11 = $mysqli -> query($sql_11);
while($data_11 = mysqli_fetch_assoc($res_11)){
$permission_id = $data_11['permission_id'];
$sql_12 = "SELECT COUNT(*) FROM phoenix_permissions_users WHERE permission_id = '".$permission_id."'";
$res_12 = $mysqli -> query($sql_12);
$data_12 = mysqli_fetch_assoc($res_12);
$nr_stu = $data_12['COUNT(*)'];
if($nr_stu == 0){
$sql13 = "DELETE FROM phoenix_permissions WHERE permission_id = '".$permission_id."'";
$res13 = $mysqli -> query($sql13);
$sql14 = "DELETE FROM phoenix_permissions_papers WHERE permission_id = '".$permission_id."'";
$res14 = $mysqli -> query($sql14);
$sql15 = "DELETE FROM phoenix_permissions_topics WHERE permission_id = '".$permission_id."'";
$res15 = $mysqli -> query($sql15);}}
$sql_16 = "SELECT assign_id FROM phoenix_assign WHERE assign_teacher = '".$user_id."'";
$res_16 = $mysqli -> query($sql_16);
while($data_16 = mysqli_fetch_assoc($res_16)){
$assign_id = $data_16['assign_id'];
$sql_17 = "SELECT COUNT(*) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."'";
$res_17 = $mysqli -> query($sql_17);
$data_17 = mysqli_fetch_assoc($res_17);
$nr_stu = $data_17['COUNT(*)'];
if($nr_stu == 0){
$sql18 = "SELECT question_id FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."'";
$res18 = $mysqli -> query($sql18);
while($data18 = mysqli_fetch_assoc($res18)){
$question_id = $data18['question_id'];
$sql19 = "UPDATE phoenix_assign_questions_originality SET originality = originality - 1 WHERE teacher_id = '".$user_id."' AND question_id = '".$question_id."'";
$res19 = $mysqli -> query($sql19);}
$sql20 = "DELETE FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res20 = $mysqli -> query($sql20);
$sql21 = "DELETE FROM phoenix_assign_qp WHERE assign_id = '".$assign_id."'";
$res21 = $mysqli -> query($sql21);
$sql22 = "DELETE FROM phoenix_assign_t WHERE assign_id = '".$assign_id."'";
$res22 = $mysqli -> query($sql22);
$sql23 = "DELETE FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."'";
$res23 = $mysqli -> query($sql23);
$sql25 = "DELETE FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."'";
$res25 = $mysqli -> query($sql25);}}
$sql26 = "SELECT assign_id FROM phoenix_assign WHERE assign_teacher = '".$user_id."' AND assign_type = '5' AND assign_active = '1' AND assign_teams = '1'";
$res26 = $mysqli -> query($sql26);
while($data26 = mysqli_fetch_assoc($res26)){
$assign_id = $data26['assign_id'];
$sql27 = "SELECT team FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."'";
$res27 = $mysqli -> query($sql27);
while($data27 = mysqli_fetch_assoc($res27)){
$team = $data27['team'];
$sql28 = "SELECT COUNT(*) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_team = '".$team."'";
$res28 = $mysqli -> query($sql28);
$data28 = mysqli_fetch_assoc($res28);
$nr_stu = $data28['COUNT(*)'];
if($nr_stu == 0){
$sql29 = "DELETE FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."' AND team = '".$team."'";
$res29 = $mysqli -> query($sql29);}
if($nr_stu > 0){
$sql30 = "UPDATE phoenix_assign_teams SET members = '".$nr_stu."' WHERE assign_id = '".$assign_id."' AND team = '".$team."'";
$res30 = $mysqli -> query($sql30);}}
$sql31 = "SELECT COUNT(*) FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."'";
$res31 = $mysqli -> query($sql31);
$data31 = mysqli_fetch_assoc($res31);
$nr_teams = $data31['COUNT(*)'];
if($nr_teams < 2){
$sql32 = "UPDATE phoenix_assign_users SET assign_student_team = '0', assign_student_coeff = '100' WHERE assign_id = '".$assign_id."'";
$res32 = $mysqli -> query($sql32);
$sql33 = "UPDATE phoenix_assign SET assign_teams = '0' WHERE assign_id = '".$assign_id."'";
$res33 = $mysqli -> query($sql33);}
if($nr_teams >= 2){
$sql34 = "SELECT team, members FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."' ORDER BY members DESC";
$res34 = $mysqli -> query($sql34);
$i = 1;
while($data34 = mysqli_fetch_assoc($res34)){
$members = $data34['members'];
if($i == 1){
$max = $members;}
$team = $data34['team'];
$coeff = round($max / $members * 100, 0);
$sql35 = "UPDATE phoenix_assign_users SET assign_student_coeff = '".$coeff."' WHERE assign_id = '".$assign_id."' AND assign_student_team = '".$team."'";
$res35 = $mysqli -> query($sql35);
$i++;}
for($i = 1; $i <= $nr_teams; $i++){
$sql36 = "SELECT assign_student_coeff FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_team = '".$i."' LIMIT 1";
$res36 = $mysqli -> query($sql36);
$data36 = mysqli_fetch_assoc($res36);
$coeff = $data36['assign_student_coeff'];
$sql36 = "SELECT SUM(answer_points) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND user_id IN (SELECT student_id FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_team = '".$i."')";
$res36 = $mysqli -> query($sql36);
$data36 = mysqli_fetch_assoc($res36);
$total = $data36['SUM(answer_points)'];
$total_w = $total * $coeff;
$sql37 = "UPDATE phoenix_assign_teams SET score = '".$total_w."' WHERE assign_id = '".$assign_id."' AND team = '".$i."'";
$res37 = $mysqli -> query($sql37);}}}}
header("Location: manage_students.php");
?>