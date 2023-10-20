<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$perm_id = $_GET['id'];
if(empty($perm_id)){
echo 'Missing information';
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_id ='".$user_id."'";
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
$sqlcheck = "SELECT * FROM phoenix_permissions WHERE permission_id = '".$perm_id."'";
$rescheck = $mysqli -> query($sqlcheck);
$datacheck = mysqli_fetch_assoc($rescheck);
$teacher_id = $datacheck['permission_teacher'];
$active = $datacheck['permission_active'];
$type = $datacheck['permission_type'];
if($active == 1){
echo 'This rule is already active.';
exit();}
if ($teacher_id != $user_id) {
echo 'You are not authorized to manage this rule.';
exit();}
if($type == '1'){
$sql_del = "DELETE FROM phoenix_permissions_papers WHERE permission_id = '".$perm_id."'";
$res_del = $mysqli -> query($sql_del);}
$sql_type = "UPDATE phoenix_permissions SET permission_type = '2' WHERE permission_id = '".$perm_id."'";
$res_type = $mysqli -> query($sql_type);
if(empty($_POST['permission_topic_1']) && empty($_POST['permission_topic_2']) && empty($_POST['permission_topic_3']) && empty($_POST['permission_topic_4']) && empty($_POST['permission_topic_5']) && empty($_POST['permission_topic_6'])){
echo 'Please select at least one topic.';
exit();}
if(!empty($_POST['permission_topic_1'])){
foreach($_POST['permission_topic_1'] as $check1){
$sql1 = "INSERT INTO phoenix_permissions_topics (permission_id, topic_id) VALUES ('".$perm_id."', '".$check1."')";
$res1 = $mysqli -> query($sql1);}}
if(!empty($_POST['permission_topic_2'])){
foreach($_POST['permission_topic_2'] as $check2){
$sql2 = "INSERT INTO phoenix_permissions_topics (permission_id, topic_id) VALUES ('".$perm_id."', '".$check2."')";
$res2 = $mysqli -> query($sql2);}}
if(!empty($_POST['permission_topic_3'])){
foreach($_POST['permission_topic_3'] as $check3){
$sql3 = "INSERT INTO phoenix_permissions_topics (permission_id, topic_id) VALUES ('".$perm_id."', '".$check3."')";
$res3 = $mysqli -> query($sql3);}}
if(!empty($_POST['permission_topic_4'])){
foreach($_POST['permission_topic_4'] as $check4){
$sql4 = "INSERT INTO phoenix_permissions_topics (permission_id, topic_id) VALUES ('".$perm_id."', '".$check4."')";
$res4 = $mysqli -> query($sql4);}}
if(!empty($_POST['permission_topic_5'])){
foreach($_POST['permission_topic_5'] as $check5){
$sql5 = "INSERT INTO phoenix_permissions_topics (permission_id, topic_id) VALUES ('".$perm_id."', '".$check5."')";
$res5 = $mysqli -> query($sql5);}}
if(!empty($_POST['permission_topic_6'])){
foreach($_POST['permission_topic_6'] as $check6){
$sql6 = "INSERT INTO phoenix_permissions_topics (permission_id, topic_id) VALUES ('".$perm_id."', '".$check6."')";
$res6 = $mysqli -> query($sql6);}}
header("Location: permission_new_rule_options.php?id=$perm_id");
?>
