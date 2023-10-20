<?php
session_start();
if(empty($_SESSION['phoenix_user_id'])){
header("Location: login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
$m = $_GET['m'];
$game = $_GET['game'];
$avatar = $_GET['avatar'];
if(empty($avatar)){
header("Location: avatar_change.php?error=1&m=$m&game=$game");
exit();}
include "connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$avatar_clear = $mysqli -> real_escape_string($avatar);
$sql2 = "UPDATE phoenix_users SET user_avatar = '".$avatar_clear."' WHERE user_id = '".$user_id."'";
$res2 = $mysqli -> query($sql2);
if($m == 1){
header("Location: ./mcqs/main.php");
exit();}
if($game != ''){
header("Location: ./mcqs/complete_game_start.php?assign_id=$game");
exit();}
else {
header("Location: profile.php");
exit();}
?>