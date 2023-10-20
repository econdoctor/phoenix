<?php
session_start();
if(empty($_SESSION['phoenix_reset_id'])){
header("Location: reset_password.php?m=1&error=5");
exit();}
$user_id = $_SESSION['phoenix_reset_id'];
$password_new = $_POST['password_new'];
$password_new_confirm = $_POST['password_new_confirm'];
if(empty($password_new) || empty($password_new_confirm)){
header("Location: reset_password_rc_change.php?error=1");
exit();}
if($password_new != $password_new_confirm){
header("Location: reset_password_rc_change.php?error=2");
exit();}
if(strlen($password_new) < 8 || strlen($password_new_confirm) < 8){
header("Location: reset_password_rc_change.php?error=3");
exit();}
if($password_new == $password_new_confirm){
include "connectdb.php";
if($mysqli -> connect_errno){
header("Location: reset_password_rc_change.php?error=4");
exit();}
$sql = "SELECT user_password FROM phoenix_users WHERE user_id = '".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$password_db = $data['user_password'];
if(md5($password_new) == $password_db){
header("Location: reset_password_rc_change.php?error=5");}
if(md5($password_new) != $password_db){
$rc1 = substr(str_shuffle('123456789ABCDEFGHIJKLMNPQRSTUVWXYZ'),1,4);
$rc2 = substr(str_shuffle('123456789ABCDEFGHIJKLMNPQRSTUVWXYZ'),1,4);
$rc3 = substr(str_shuffle('123456789ABCDEFGHIJKLMNPQRSTUVWXYZ'),1,4);
$rc4 = substr(str_shuffle('123456789ABCDEFGHIJKLMNPQRSTUVWXYZ'),1,4);
$rc = $rc1.' - '.$rc2.' - '.$rc3.' - '.$rc4;
$sql2 = "SELECT * FROM phoenix_users WHERE user_recovery = '".$rc."'";
$res2 = $mysqli -> query($sql2);
$nr2 = mysqli_num_rows($res2);
if($nr2 > 0){
header("Location: reset_password_rc_change.php");
exit();}
$sql3 = "UPDATE phoenix_users SET user_password = '".md5($password_new)."', user_recovery = '".$rc."' WHERE user_id ='".$user_id."'";
$res3 = $mysqli -> query($sql3);
session_destroy();
?>
<!doctype html>
<html>
<head>
<title>Phoenix</title>
<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<style type="text/css">
a:link {
text-decoration: none;
color: #000000;
}
a:visited {
text-decoration: none;
color: #000000;
}
a:hover {
text-decoration: none;
color: #000000;
}
a:active {
text-decoration: none;
color: #000000;
}
body,td,th {
color: #000000;
font-family: "Segoe", "Segoe UI", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;
}
body {
background-color: #8BA57E;
text-align: center;
font-size: large;
}
input[type=button]{
-webkit-appearance: none;
-moz-appearance: none;
appearance: none;
background-color: #033909;
border: 2px solid black;
padding: 10px 15px;
color: white;
font-weight: bold;
font-size: 18px;
cursor: pointer;
width: 15%;
border-radius: 4px;
}
</style>
</head>
<body>
<br><a href="./mcqs/main.php"><img src="home.png" width="150"></a>
<p style="font-size: x-large;"><b>RESET PASSWORD</b></p>
<p><b style="color: #033909;">Your password has been changed.</b></p>
<p>Here's your new recovery key:</p>
<p><b style="font-size: x-large; color: #033909;"><?php echo $rc; ?></b></p>
<p>Your new recovery key can be used to recover your account in the event you forget your password (again!).</p>
<p><b style="color: #820000;">Write it down or take a screenshot before leaving this page.</b></p>
<p><input type="button" value="SIGN IN" onclick="document.location.href='login.php';"></p>
</body>
</html>
<?php
}}
?>