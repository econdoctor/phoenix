<?php
session_start();
if(empty($_SESSION['phoenix_user_id'])){
header("Location: login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
include "connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_id ='".$user_id."'";
$result = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($result);
$user_code = $data['user_code'];
$user_syllabus = $data['user_syllabus'];
if($user_syllabus == '3'){
$s_text = "A Level";}
if($user_syllabus == '2'){
$s_text = "AS Level";}
if($user_syllabus == '1'){
$s_text = "IGCSE";}
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_type = $data['user_type'];
$user_email = $data['user_email'];
$user_teacher = $data['user_teacher'];
$user_reg_date = $data['user_reg_date'];
$user_recovery = $data['user_recovery'];
$user_name = $data['user_name'];
$user_timezone = $data['user_timezone'];
$user_alias = $data['user_alias'];
if($user_alias == ''){
$user_alias = 'Not set';}
$user_avatar = $data['user_avatar'];
$sql_tz = "SELECT * FROM phoenix_timezones WHERE value = '".$user_timezone."'";
$result_tz = $mysqli -> query($sql_tz);
$data_tz = mysqli_fetch_assoc($result_tz);
$user_timezone_label = $data_tz['label'];
if($user_type == 1){
$user_type_text = "Student";
$sql2 = "SELECT * FROM phoenix_users WHERE user_id ='".$user_teacher."'";
$result2 = $mysqli -> query($sql2);
$data2 = mysqli_fetch_assoc($result2);
$user_teacher_title = $data2['user_title'];
$user_teacher_first_name = $data2['user_first_name'];
$user_teacher_last_name = $data2['user_last_name'];}
if($user_type == 2){
$user_type_text = "Teacher";
$sql3 = "SELECT * FROM phoenix_users WHERE user_teacher = '".$user_id."'";
$result3 = $mysqli -> query($sql3);
$num_rows_3 = mysqli_num_rows($result3);
$user_current_accounts = number_format($num_rows_3);}
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
width: 20%;
border-radius: 4px;
}
.brsmall {
display: block;
margin-bottom: 0.5em;
}
</style>
</head>
<body><a name="top"></a>
<table align="center" width="90%"><tbody>
<tr>
<td>
<p style="text-align: right;"><img src="online.png" width="15">&nbsp;&nbsp;<b><a href="profile.php"><?php echo $user_title.' '.$user_first_name.' '.$user_last_name.' ('.$user_name.')'; ?></a> - <a href="logout.php">Log out</a></b></p>
</td>
</tr>
</tbody></table>
<a href="./mcqs/main.php"><img src="home.png" width="150"></a>
<p style="font-size: x-large"><b>PROFILE</b></p>
<p><b>Account type</b><br>
<b style="color: #033909"><?php echo $user_type_text; ?></b></p>
<?php
if($user_type == 1){
echo '<p><b>Identification code</b><br>
<b style="color: #033909">'.$user_code.'</b>
</p>';
if($user_teacher != 0){
echo '<p><b>Referent teacher</b><br>
<b style="color: #033909">'.$user_teacher_title.' '.$user_teacher_first_name.' '.$user_teacher_last_name.'</b>
<br>
</p>';}
echo '<p><b>Course</b><br>
<b style="color: #033909">'.$s_text.'</b><br>';
if($user_teacher == 0){
echo '<b><a href="syllabus_change.php" style="font-size: medium; color: #820000;">Edit</a></b>';}
echo '</p>';}
if($user_type == 2){
echo '<p><b>Identification code</b><br>
<b style="color: #033909">'.$user_code.'</b>
</p>
<p><b>Associated student accounts</b><br>
<b style="color: #033909"> '.$user_current_accounts.'</b><br>
</p>
<p><b>Timezone</b><br>
<b style="color: #033909">'.$user_timezone_label.'</b><br>
<b><a href="edit_tz.php" style="font-size: medium; color: #820000;">Edit</a></b></p>';}
?>
<p><b>Username</b><br>
<b style="color: #033909"><?php echo $user_name; ?></b></p>
<?php
if($user_type == 1){
?>
<p><b>Alias</b><br>
<b style="color: #033909"><?php echo $user_alias; ?></b><br>
<b><a href="alias_change.php" style="font-size: medium; color: #820000;">Click here to change your alias</a></b></p>
<p><b>Avatar</b></p>
<p>
<?php
if($user_avatar != 0){
?>
<img src="./mcqs/avatars/Character%20<?php echo $user_avatar; ?>.png" width="200">
<?php
}
if($user_avatar == 0){
?>
<b style="color: #033909;">Not set</b>
<?php
}
?>
<br><b><a href="avatar_change.php" style="font-size: medium; color: #820000;">Click here to change your avatar</a></b></p>
<?php
}
?>
<p><b>Name</b><br>
<b style="color: #033909"><?php echo ''.$user_title.' '.$user_first_name.' '.$user_last_name.''; ?></b></p>
<p><b>Email address</b><br>
<b style="color: #033909"><?php echo $user_email; ?></b><br>
<b><a href="email_change.php" style="font-size: medium; color: #820000;">Edit</a></b><br>
</p>
<p><b>Registration date</b><br>
<b style="color: #033909"><?php echo $user_reg_date; ?></b><br>
</p>
<p><b>Recovery key</b>
<div id="div1">
<input type="button" style="width: 10%;" value="SHOW" onclick="document.getElementById('div2').style.display = 'block'; document.getElementById('div1').style.display = 'none';">
</div>
<div id="div2" style="display: none;">
<input type="button" style="width: 10%;" value="HIDE" onclick="document.getElementById('div1').style.display = 'block'; document.getElementById('div2').style.display = 'none'; document.getElementById('btn_hide').style.display = 'none';">
<span class="brsmall"></span><b style="color: #033909;"><?php echo $user_recovery; ?></b>
</div>
</p>
<p style="font-size: medium;"><a href="password_change.php" style="color: #820000;"><b>Change your password</b></a></p>
<?php
if($user_type == 2 || ($user_type == 1 && $user_teacher == 0)){
echo '<p style="font-size: medium; color: #820000;"><a href="user_delete.php" style="color: #820000"><b>Delete your account</b></a></p>';}
?>
<p><a href="#top"><b>Back to the top</b></a></p>
</body></html>
