<?php
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
$sql_s = "SELECT assign_game_status FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_s = $mysqli -> query($sql_s);
$data_s = mysqli_fetch_assoc($res_s);
$assign_game_status = $data_s['assign_game_status'];
if($assign_game_status != 2){
echo 'R';}
$mysqli -> close();
?>