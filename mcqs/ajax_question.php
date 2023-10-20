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
$assign_game_pause = $_GET['assign_game_pause'];
if(empty($assign_id) || empty($assign_game_pause)){
$mysqli -> close();
exit();}
$sql_s = "SELECT assign_game_status, assign_game_pause, assign_game_remaining FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_s = $mysqli -> query($sql_s);
$data_s = mysqli_fetch_assoc($res_s);
$assign_game_status = $data_s['assign_game_status'];
$assign_game_pause_db = $data_s['assign_game_pause'];
$assign_game_remaining = $data_s['assign_game_remaining'];
if($assign_game_status != 1){
echo 'R';
exit();}
if($assign_game_status == 1 && !is_null($assign_game_remaining)){
if($assign_game_pause == 'ongoing' && $assign_game_pause_db == 1){
echo 'P '.$assign_game_remaining;}
if($assign_game_pause == 'pause' && $assign_game_pause_db == 0){
echo 'G '.$assign_game_remaining;}}
$mysqli -> close();
?>