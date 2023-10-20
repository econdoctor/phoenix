<?php
session_start();
if(empty($_SESSION['phoenix_reset_id'])){
header("Location: reset_password.php?m=1&error=5");
exit();}
$user_id = $_SESSION['phoenix_reset_id'];
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
input[type=password] {
padding: 3px 5px;
margin: 2px 0;
box-sizing: border-box;
text-align: center;
font-weight: bold;
font-size: 18px;
border: 2px solid black;
border-radius: 4px;
width: 20%;
}
input[type=submit]{
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
width: 20%;
border-radius: 4px;
}
</style>
</head>
<body>
<br><a href="./mcqs/main.php"><img src="home.png" width="150"></a>
<p style="font-size: x-large;"><b>RESET PASSWORD</b></p>
<?php
if($_GET['error'] == 1){
echo '<p><b style="color: #820000;">Please fill in the information required</b></p>';}
if($_GET['error'] == 2){
echo '<p><b style="color: #820000;">You entered two different new passwords</b></p>';}
if($_GET['error'] == 5){
echo '<p><b style="color: #820000;">Your new password is identical to your current password</b></p>';}
if($_GET['error'] == 3){
echo '<p><b style="color: #820000;">Your new password is too short<br>It should be at least 8 characters long</b></p>';}
if($_GET['error'] == 4){
echo '<p><b style="color: #820000;">Failed to connect to database</b></p>';}
if($_GET['error'] == 6){
echo '<p><b style="color: #820000;">Your current password is incorrect</b></p>';}
?>
<form method="post" action="reset_password_rc_db.php">
<p><input name="password_new" type="password" required="required" placeholder="Your new password"></p>
<p><input name="password_new_confirm" type="password" required="required" placeholder="Confirm your new password"></p>
<p><input type="submit" value="CONFIRM"></p>
</form>
</body>
</html>