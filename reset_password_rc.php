<?php
session_start();
$id = $_POST['id'];
$rc1 = $_POST['rc1'];
$rc2 = $_POST['rc2'];
$rc3 = $_POST['rc3'];
$rc4 = $_POST['rc4'];
if(empty($id) || empty($rc1) || empty($rc2) || empty($rc3) || empty($rc4)){
header("Location: reset_password.php?m=1&error=1&id=$id&rc1=$rc1&rc2=$rc2&rc3=$rc3&rc4=$rc4");
exit();}
if(!empty($id) && !empty($rc1) && !empty($rc2) && !empty($rc3) && !empty($rc4)){
include "connectdb.php";
if($mysqli -> connect_errno){
header("Location: reset_password.php?m=1&error=4&id=$id&rc1=$rc1&rc2=$rc2&rc3=$rc3&rc4=$rc4");
exit();}
$sql = "SELECT user_recovery, user_id FROM phoenix_users WHERE user_name = '".$id."' OR user_email = '".$id."'";
$res = $mysqli -> query($sql);
$nr = mysqli_num_rows($res);
if($nr == 0){
header("Location: reset_password.php?m=1&error=2&rc1=$rc1&rc2=$rc2&rc3=$rc3&rc4=$rc4");
exit();}
if($nr > 0){
$rc = strtoupper($rc1.' - '.$rc2.' - '.$rc3.' - '.$rc4);
$data = mysqli_fetch_assoc($res);
$user_recovery = $data['user_recovery'];
if($user_recovery != $rc){
header("Location: reset_password.php?m=1&error=3&id=$id");
exit();}
if($user_recovery == $rc){
$_SESSION['phoenix_reset_id'] = $data['user_id'];
header("Location: reset_password_rc_change.php");
exit();
}}}
?>