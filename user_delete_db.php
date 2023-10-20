<?php
session_start();
if(empty($_SESSION['phoenix_user_id'])){
header("Location: login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
include "connectdb.php";
if($mysqli -> connect_errno){
header("Location: user_delete.php?error=2");
exit();}
$sql = "SELECT user_password, user_type, user_teacher FROM phoenix_users WHERE user_id = '".$user_id."'";
$result = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($result);
$user_password = $data['user_password'];
$user_type = $data['user_type'];
$user_teacher = $data['user_teacher'];
if($user_type == 1 && $user_teacher != 0){
echo 'Your account is managed by your teacher. You cannot delete it.';
exit();}
$password = $_POST['password'];
if(empty($password)){
header("Location: user_delete.php?error=3");
exit();}
if(!empty($password) && md5($password) != $user_password){
header("Location: user_delete.php?error=4");
exit();}
if(md5($password) == $user_password){
if($user_type == 1 && $user_teacher == 0){
$sql1 = "DELETE FROM phoenix_answers WHERE user_id = '".$user_id."'";
$res1 = $mysqli -> query($sql1);
$sql2 = "DELETE FROM phoenix_thresholds WHERE teacher_id = '".$user_id."'";
$res2 = $mysqli -> query($sql2);
$sql3 = "DELETE FROM phoenix_users WHERE user_id = '".$user_id."'";
$res3 = $mysqli -> query($sql3);
$sql4 = "DELETE FROM mentor_register WHERE user_id = '".$user_id."'";
$res4 = $mysqli -> query($sql4);}
if($user_type == 2){
$sql1 = "DELETE FROM phoenix_answers WHERE user_id IN (SELECT user_id FROM phoenix_users WHERE user_teacher = '".$user_id."')";
$res1 = $mysqli -> query($sql1);
$sql2 = "DELETE FROM phoenix_assign_qp WHERE assign_id IN (SELECT assign_id FROM phoenix_assign WHERE assign_teacher = '".$user_id."')";
$res2 = $mysqli -> query($sql2);
$sql3 = "DELETE FROM phoenix_assign_t WHERE assign_id IN (SELECT assign_id FROM phoenix_assign WHERE assign_teacher = '".$user_id."')";
$res3 = $mysqli -> query($sql3);
$sql4 = "DELETE FROM phoenix_assign_questions WHERE assign_id IN (SELECT assign_id FROM phoenix_assign WHERE assign_teacher = '".$user_id."')";
$res4 = $mysqli -> query($sql4);
$sql5 = "DELETE FROM phoenix_assign_users WHERE assign_id IN (SELECT assign_id FROM phoenix_assign WHERE assign_teacher = '".$user_id."')";
$res5 = $mysqli -> query($sql5);
$sql6 = "DELETE FROM phoenix_assign_teams WHERE assign_id IN (SELECT assign_id FROM phoenix_assign WHERE assign_teacher = '".$user_id."')";
$res6 = $mysqli -> query($sql6);
$sql7 = "DELETE FROM phoenix_assign_questions_originality WHERE teacher_id = '".$user_id."'";
$res7 = $mysqli -> query($sql7);
$sql8 = "DELETE FROM phoenix_assign WHERE assign_teacher = '".$user_id."'";
$res8 = $mysqli -> query($sql8);
$sql9 = "DELETE FROM phoenix_group_users WHERE group_id IN (SELECT group_id FROM phoenix_groups WHERE group_teacher = '".$user_id."')";
$res9 = $mysqli -> query($sql9);
$sql10 = "DELETE FROM phoenix_groups WHERE group_teacher = '".$user_id."'";
$res10 = $mysqli -> query($sql10);
$sql11 = "DELETE FROM phoenix_permissions_papers WHERE permission_id IN (SELECT permission_id FROM phoenix_permissions WHERE permission_teacher = '".$user_id."')";
$res11 = $mysqli -> query($sql11);
$sql12 = "DELETE FROM phoenix_permissions_topics WHERE permission_id IN (SELECT permission_id FROM phoenix_permissions WHERE permission_teacher = '".$user_id."')";
$res12 = $mysqli -> query($sql12);
$sql13 = "DELETE FROM phoenix_permissions_users WHERE permission_id IN (SELECT permission_id FROM phoenix_permissions WHERE permission_teacher = '".$user_id."')";
$res13 = $mysqli -> query($sql13);
$sql14 = "DELETE FROM phoenix_permissions WHERE permission_teacher = '".$user_id."'";
$res14 = $mysqli -> query($sql14);
$sql15 = "DELETE FROM phoenix_thresholds WHERE teacher_id = '".$user_id."'";
$res15 = $mysqli -> query($sql15);
$sqlT = "SELECT user_id FROM phoenix_users WHERE user_teacher = '".$user_id."'";
$resT = $mysqli -> query($sqlT);
while($dataT = mysqli_fetch_assoc($resT)){
$student_id = $dataT['user_id'];
$sqlU0 = "DELETE FROM phoenix_thresholds WHERE teacher_id = '".$student_id."'";
$resU0 = $mysqli -> query($sqlU0);
$sqlU1 = "INSERT INTO phoenix_thresholds (teacher_id, syllabus, min_a, min_b, min_c, min_d, min_e, min_f, min_g) VALUES ('".$student_id."','3','80','70','60','50','40', '0', '0')";
$resU1 = $mysqli -> query($sqlU1);
$sqlU2 = "INSERT INTO phoenix_thresholds (teacher_id, syllabus, min_a, min_b, min_c, min_d, min_e, min_f, min_g) VALUES ('".$student_id."','2','80','70','60','50','40', '0', '0')";
$resU2 = $mysqli -> query($sqlU2);
$sqlU3 = "INSERT INTO phoenix_thresholds (teacher_id, syllabus, min_a, min_b, min_c, min_d, min_e, min_f, min_g) VALUES ('".$student_id."','1','75','65','55','45','40', '35', '30')";
$resU3 = $mysqli -> query($sqlU3);}
$sqlV = "DELETE FROM mentor_register WHERE user_id = '".$user_id."'";
$resV = $mysqli -> query($sqlV);
$sqlW = "UPDATE phoenix_users SET user_teacher = '0', user_refresh = '1' WHERE user_teacher = '".$user_id."'";
$resW = $mysqli -> query($sqlW);
$sqlX = "DELETE FROM phoenix_users WHERE user_id = '".$user_id."'";
$resX = $mysqli -> query($sqlX);}
session_destroy();
header("Location: login.php?delete=1");}
?>