<?php
session_start();
if(empty($_SESSION['phoenix_user_id'])){
header("Location: login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
$alias_post = $_POST['alias'];
$game = $_GET['game'];
$m = $_GET['m'];
if(empty($alias_post)){
header("Location: alias_change.php?error=1&game=$game&m=$m");
exit();}
include "connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_id = '".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$alias_db = $data['user_alias'];
if($alias_post == $alias_db){
header("Location: alias_change.php?error=2&game=$game&m=$m");
exit();}
$sql_check = "SELECT * FROM phoenix_users WHERE user_type = '1' AND user_alias = '".$alias_post."'";
$res_check = $mysqli -> query($sql_check);
$nr_check = mysqli_num_rows($res_check);
if($nr_check > 0){
header("Location: alias_change.php?error=3&alias=$alias_post&game=$game&m=$m");
exit();}
$alias_clear = $mysqli -> real_escape_string($alias_post);
$sql2 = "UPDATE phoenix_users SET user_alias = '".$alias_clear."' WHERE user_id = '".$user_id."'";
$res2 = $mysqli -> query($sql2);
if($game != ''){
header("Location: ./mcqs/complete_game_start.php?assign_id=$game");
exit();}
if($m == 1){
header("Location: ./mcqs/main.php");
exit();}
else {
header("Location: profile.php");
exit();}
?>