<?php
echo 'Restricted';
exit();
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
$user_code = rand(10000000,99999999);
$rc1 = substr(str_shuffle('123456789ABCDEFGHIJKLMNPQRSTUVWXYZ'),1,4);
$rc2 = substr(str_shuffle('123456789ABCDEFGHIJKLMNPQRSTUVWXYZ'),1,4);
$rc3 = substr(str_shuffle('123456789ABCDEFGHIJKLMNPQRSTUVWXYZ'),1,4);
$rc4 = substr(str_shuffle('123456789ABCDEFGHIJKLMNPQRSTUVWXYZ'),1,4);
$rc = $rc1.' - '.$rc2.' - '.$rc3.' - '.$rc4;
$user_name_raw = $_POST['user_name'];
$user_title_raw = $_POST['user_title'];
$user_first_name_raw = $_POST['user_first_name'];
$user_last_name_raw = $_POST['user_last_name'];
$user_email_raw = $_POST['user_email'];
$antispam = strtolower($_POST['antispam']);
$user_reg_date = date("Y/m/d");
$user_password  = $_POST['user_password'];
$user_password_confirm  = $_POST['user_password_confirm'];
$timezone = $_POST['timezone'];
if(empty($user_name_raw) || empty($user_email_raw) || empty($user_first_name_raw) || empty($user_last_name_raw) || empty($user_password) || empty($user_password_confirm) || empty($user_title_raw) || empty($antispam) || $timezone == ""){
header("Location: reg_teacher.php?error=1&user_name=$user_name_raw&user_title=$user_title_raw&user_first_name=$user_first_name_raw&user_last_name=$user_last_name_raw&user_email=$user_email_raw&timezone=$timezone&antispam=$antispam");
exit();}
if($antispam != "6"){
header("Location: reg_teacher.php?error=3&user_name=$user_name_raw&user_title=$user_title_raw&user_first_name=$user_first_name_raw&user_last_name=$user_last_name_raw&user_email=$user_email_raw&timezone=$timezone&antispam=$antispam");
exit();}
if(strlen($user_password) < 8 || strlen($user_password_confirm) < 8){
header("Location: reg_teacher.php?error=6&user_name=$user_name_raw&user_title=$user_title_raw&user_first_name=$user_first_name_raw&user_last_name=$user_last_name_raw&user_email=$user_email_raw&timezone=$timezone&antispam=$antispam");
exit();}
if($user_password != $user_password_confirm){
header("Location: reg_teacher.php?error=7&user_name=$user_name_raw&user_title=$user_title_raw&user_first_name=$user_first_name_raw&user_last_name=$user_last_name_raw&user_email=$user_email_raw&timezone=$timezone&antispam=$antispam");
exit();}
include "connectdb.php";
if($mysqli -> connect_errno){
header("Location: reg_teacher.php?error=4&user_name=$user_name_raw&user_title=$user_title_raw&user_first_name=$user_first_name_raw&user_last_name=$user_last_name_raw&user_email=$user_email_raw&timezone=$timezone&antispam=$antispam");
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_name = '".$user_name_raw."' OR user_email = '".$user_email_raw."'";
$result = $mysqli -> query($sql);
$num_rows = mysqli_num_rows($result);
if($num_rows > 0){
header("Location: reg_teacher.php?error=8&user_name=$user_name_raw&user_title=$user_title_raw&user_first_name=$user_first_name_raw&user_last_name=$user_last_name_raw&user_email=$user_email_raw&timezone=$timezone&antispam=$antispam");
exit();}
$sql_code = "SELECT * FROM phoenix_users WHERE user_code = '".$user_code."' OR user_recovery = '".$rc."'";
$result_code = $mysqli -> query($sql_code);
$num_rows_code = mysqli_num_rows($result_code);
if($num_rows_code > 0){
header("Location: reg_teacher.php?user_name=$user_name_raw&user_title=$user_title_raw&user_first_name=$user_first_name_raw&user_last_name=$user_last_name_raw&user_email=$user_email_raw&timezone=$timezone&antispam=$antispam");
exit();}
$user_name = $mysqli->real_escape_string($user_name_raw);
$user_email = $mysqli->real_escape_string($user_email_raw);
$user_first_name = $mysqli->real_escape_string($user_first_name_raw);
$user_last_name = $mysqli->real_escape_string($user_last_name_raw);
$user_title = $mysqli->real_escape_string($user_title_raw);
$timezone_db = $mysqli->real_escape_string($timezone);
$sql3 = "INSERT INTO phoenix_users (user_reg_date, user_password, user_name, user_email, user_first_name, user_last_name, user_type, user_title, user_active, user_last_login, user_code, user_timezone, user_recovery) VALUES ('".$user_reg_date."', '".md5($user_password)."', '".$user_name."', '".$user_email."', '".$user_first_name."', '".$user_last_name."', '2', '".$user_title."', '1', '".$now."', '".$user_code."', '".$timezone_db."', '".$rc."')";
$result3 = $mysqli -> query($sql3);
$sql_get = "SELECT * FROM phoenix_users WHERE user_name = '".$user_name."'";
$result_get = $mysqli -> query($sql_get);
$data_get = mysqli_fetch_assoc($result_get);
$teacher_id = $data_get['user_id'];
$sql_A2 = "INSERT INTO phoenix_thresholds (teacher_id, syllabus, min_a, min_b, min_c, min_d, min_e, min_f, min_g) VALUES ('".$teacher_id."','3','80','70','60','50','40', '0', '0')";
$result_A2 = $mysqli -> query($sql_A2);
$sql_AS = "INSERT INTO phoenix_thresholds (teacher_id, syllabus, min_a, min_b, min_c, min_d, min_e, min_f, min_g) VALUES ('".$teacher_id."','2','80','70','60','50','40', '0', '0')";
$result_AS = $mysqli -> query($sql_AS);
$sql_IGCSE = "INSERT INTO phoenix_thresholds (teacher_id, syllabus, min_a, min_b, min_c, min_d, min_e, min_f, min_g) VALUES ('".$teacher_id."','1','75','65','55','45','40', '35', '30')";
$result_IGCSE = $mysqli -> query($sql_IGCSE);
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
<p style="font-size: x-large;"><b>REGISTER</b></p>
<p><b>Your account has been created.</b></p>
<p>Here's your recovery key:</p>
<p><b style="font-size: x-large; color: #033909;"><?php echo $rc; ?></b></p>
<p>Your recovery key can be used to recover your account in the event you forget your password.</p>
<p><b style="color: #820000;">Write it down or take a screenshot before leaving this page.</b></p>
<p><input type="button" value="SIGN IN" onclick="document.location.href='login.php';"></p>
</body>
</html>