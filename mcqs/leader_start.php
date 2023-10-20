<?php
date_default_timezone_set('UTC');
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
echo 'die';
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
exit();}
$assign_id = $_GET['assign_id'];
if(empty($assign_id)){
$mysqli -> close();
exit();}
$sql_students = "SELECT assign_student_ping, student_id FROM phoenix_assign_users WHERE assign_id = '".$assign_id."'";
$res_students = $mysqli -> query($sql_students);
$now = date("Y-m-d H:i:s");
$limit = date("Y-m-d H:i:s", strtotime(''.$now.'- 10 seconds'));
while($data_students = mysqli_fetch_assoc($res_students)){
if($data_students['assign_student_ping'] < $limit){
echo ' '.$data_students['student_id'].'_0';}
if($data_students['assign_student_ping'] >= $limit){
echo ' '.$data_students['student_id'].'_1';}}
$mysqli -> close();
?>