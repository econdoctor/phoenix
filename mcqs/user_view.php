<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_timezone = $data['user_timezone'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
$member_id = $_GET['member_id'];
$sql2 = "SELECT * FROM phoenix_users WHERE user_id = '".$member_id."'";
$res2 = $mysqli -> query($sql2);
$data2 = mysqli_fetch_assoc($res2);
$s = $data2['user_syllabus'];
if($s == '3'){
$s_text = "A LEVEL";}
if($s == '2'){
$s_text = "AS LEVEL";}
if($s == '1'){
$s_text = "IGCSE";}
$log_date = date("Y-m-d H:i:s", strtotime($data2['user_last_login']));
if($user_timezone >= 0){
$log_date_display = date('Y-m-d H:i:s', strtotime(''.$log_date.' + '.$user_timezone.' minutes'));}
if($user_timezone < 0){
$log_date_display = date('Y-m-d H:i:s', strtotime(''.$log_date.' - '.abs($user_timezone).' minutes'));}
if($user_id != $data2['user_teacher']){
echo 'You are not authorized to manage this account.';
exit();}
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
<meta name="theme-color" content="#ffffff"><style type="text/css">
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
font-size: large;
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
.brsmall {
display: block;
margin-bottom: 1.5em;
}
</style>
</head>
<body>
<table align="center" width="90%"><tbody>
<tr>
<td>
<p style="text-align: right;"><img src="online.png" width="15">&nbsp;&nbsp;<b><a href="../profile.php"><?php echo $user_title.' '.$user_first_name.' '.$user_last_name.' ('.$user_name.')'; ?></a> - <a href="logout.php">Log out</a></b></p>
</td>
</tr>
</tbody></table>
<table width="80%" bgcolor="#6a855c" align="center" style="border: solid black 4px; border-radius:24px;"><tbody><tr width="95%">
<td width="20%"><a href="main.php"><img src="home_phoenix.png" width="150" height="150"></a></td>
<td width="80%"><p style="font-size: xx-large;"><b>PHOENIX</b></p>
<p>
<input type="button" name="browse" value="BROWSE" style="font-size: x-large; width: 30%; background-color: #ffc000;  color: #3b2c00; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='browse.php';"/>&nbsp;&nbsp;
<input type="button" name="manage" value="MANAGE" style="font-size: x-large; width: 30%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='manage.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 30%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='assign.php';"/>
</td></tr></tbody></table>
<p style="font-size: x-large"><b><?php echo ''.strtoupper($data2['user_title']).' '.strtoupper($data2['user_first_name']).' '.strtoupper($data2['user_last_name']).''; ?></b></p>
<?php
if($_GET['password_change'] == 1){
echo '<p><b style="color: #033909;">The password was changed successfully</b></p>';}
?>
<?php
if($data2['user_alias'] != '' && $data2['user_avatar'] != 0){
?>
<p><img src="./avatars/Character%20<?php echo $data2['user_avatar']; ?>.png" width="200"><br><b><?php echo $data2['user_alias']; ?></b></p>
<?php
}
?>
<p><input type="button" style="width: 20%" name="name" value="EDIT NAME" onclick="document.location.href='update_name.php?student_id=<?php echo $member_id;?>';"/>&nbsp;&nbsp;
<input type="button" style="width: 20%" name="login" value="ACCESS ACCOUNT" onclick="document.location.href='override_student.php?student_id=<?php echo $member_id; ?>';"/>&nbsp;&nbsp;
<input type="button" style="width: 20%" name="password" value="CHANGE PASSWORD" onclick="document.location.href='reset_password.php?student_id=<?php echo $member_id; ?>';"/></p>
<span class="brsmall"></span>
<p><table align="center" width="80%" bgcolor="#000000"><tbody><tr>
<td height="40" bgcolor="#647d57"><b>USERNAME</b></td>
<td height="40" bgcolor="#647d57"><b>COURSE</b></td>
<td height="40" bgcolor="#647d57"><b>EMAIL</b></td>
<td height="40" bgcolor="#647d57"><b>REGISTRATION</b></td>
<td height="40" bgcolor="#647d57"><b>LAST VISIT</b></td>
</tr><tr>
<td height="40" bgcolor="#769467"><?php echo $data2['user_name']; ?></td>
<td height="40" bgcolor="#769467"><b><?php echo $s_text ?></b></td>
<td height="40" bgcolor="#769467"><?php echo $data2['user_email']; ?></td>
<td height="40" bgcolor="#769467"><?php echo $data2['user_reg_date']; ?></td>
<td height="40" bgcolor="#769467"><?php echo $log_date_display; ?></td>
</tr></tbody></table></p>
<span class="brsmall"></span>
<p><input type="button" style="width: 25%" name="practice" value="PRACTICE" onclick="document.location.href='user_view_practice.php?member_id=<?php echo $member_id; ?>&s=<?php echo $data2['user_syllabus']; ?>';"/>&nbsp;&nbsp;
<input type="button" style="width: 25%" name="assignments" value="ASSIGNMENTS" onclick="document.location.href='user_view_assignments.php?member_id=<?php echo $member_id; ?>';"/>&nbsp;&nbsp;
<input type="button" style="width: 25%" name="permissions" value="PERMISSIONS" onclick="document.location.href='user_view_permissions.php?member_id=<?php echo $member_id; ?>';"/></p>
</body></html>