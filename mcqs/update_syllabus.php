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
$new_syllabus = $_POST['new_syllabus'];
if(empty($new_syllabus)){
echo 'Missing information about the new course';
exit();}
if(!array_filter($_POST['manage_user'])){
echo 'Please select at least one student.';
exit();}
if(!empty($_POST['manage_user'])){
foreach($_POST['manage_user'] as $check){
$sql_update = "UPDATE phoenix_users SET user_syllabus = '".$new_syllabus."' WHERE user_id = '".$check."'";
$res_update = $mysqli -> query($sql_update);
$sql_group = "SELECT * FROM phoenix_groups WHERE group_teacher = '".$user_id."' AND group_curriculum != '".$new_syllabus."'";
$res_group = $mysqli -> query($sql_group);
while($data_group = mysqli_fetch_assoc($res_group)){
$group_id = $data_group['group_id'];
$sql_delete = "DELETE FROM phoenix_group_users WHERE group_id = '".$group_id."' AND user_id = '".$check."'";
$res_delete = $mysqli -> query($sql_delete);}}
header("Location: manage_students.php");
exit();}
?>