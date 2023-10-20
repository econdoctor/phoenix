<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$assign_id = $_GET['assign_id'];
$copy_id = $_GET['copy_id'];
if(empty($assign_id) || empty($copy_id)){
echo 'Missing information.';
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT user_type, user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
$sqlcheck = "SELECT assign_teacher, assign_syllabus, assign_active, assign_type, assign_name FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$rescheck = $mysqli -> query($sqlcheck);
$datacheck = mysqli_fetch_assoc($rescheck);
$teacher_id = $datacheck['assign_teacher'];
$s = $datacheck['assign_syllabus'];
$assign_active = $datacheck['assign_active'];
$assign_type = $datacheck['assign_type'];
$assign_name = $datacheck['assign_name'];
if($assign_active == 1){
echo 'This assignment is already active.';
exit();}
if($teacher_id != $user_id){
echo 'You are not authorized to manage this assignment';
exit();}
$sql_info = "SELECT assign_teacher, assign_active, assign_type FROM phoenix_assign WHERE assign_id = '".$copy_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
if($data_info['assign_type'] == '4'){
echo 'You cannot copy offline assignments';
exit();}
if($data_info['assign_teacher'] != $user_id){
echo 'You are not authorized to copy this assignment';
exit();}
if($data_info['assign_active'] == 0){
echo 'The original assignment is inactive';
exit();}
if($assign_type != '5'){
echo 'This assignment is not a game.';
exit();}
$sql_count = "SELECT COUNT(*) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."'";
$res_count = $mysqli -> query($sql_count);
$data_count = mysqli_fetch_assoc($res_count);
$students_number = $data_count['COUNT(*)'];
if($students_number < 2){
echo 'You cannot make teams';
exit();}
$teams_number = $_POST['teams_number'];
$pre_assign = $_POST['pre_assign'];
if($teams_number == '' || empty($teams_number) || $pre_assign == '' || empty($pre_assign)){
header("Location: Location: assign_copy_teams_set.php?copy_id=$copy_id&assign_id=$assign_id");
exit();}
$sql_del = "DELETE FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."'";
$res_del = $mysqli -> query($sql_del);
$team_shield_array = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
for($i=1;$i<=$teams_number;$i++){
$team_shield_key = array_rand($team_shield_array, 1);
$team_shield = $team_shield_array[$team_shield_key];
$sql_ins = "INSERT INTO phoenix_assign_teams (assign_id, team, team_shield) VALUES ('".$assign_id."', '".$i."', '".$team_shield."')";
$res_ins = $mysqli -> query($sql_ins);
unset($team_shield_array[$team_shield_key]);}
$sql_up = "UPDATE phoenix_assign_users SET assign_student_team = '0' WHERE assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);
if($pre_assign == '1'){
$team_max = floor($students_number / $teams_number);
for($i = 1; $i <= $teams_number; $i++){
$sql_pa = "SELECT student_id FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_team = '0' ORDER BY RAND() LIMIT $team_max";
$res_pa = $mysqli -> query($sql_pa);
while($data_pa = mysqli_fetch_assoc($res_pa)){
$student_id = $data_pa['student_id'];
$sql_up = "UPDATE phoenix_assign_users SET assign_student_team = '".$i."' WHERE assign_id = '".$assign_id."' AND student_id = '".$student_id."'";
$res_up = $mysqli -> query($sql_up);}}
if($students_number % $teams_number != 0){
$array = array();
for($i = 1; $i <= $teams_number; $i++){
$array[] = $i;}
$sql_lo = "SELECT student_id FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_team = '0'";
$res_lo = $mysqli -> query($sql_lo);
while($data_lo = mysqli_fetch_assoc($res_lo)){
$student_id = $data_lo['student_id'];
$key = array_rand($array, 1);
$team = $array[$key];
$sql_up = "UPDATE phoenix_assign_users SET assign_student_team = '".$team."' WHERE student_id = '".$student_id."' AND assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);
unset($array[$key]);}}}
$sql_get = "SELECT user_id, user_title, user_first_name, user_last_name, assign_student_team FROM phoenix_users INNER JOIN phoenix_assign_users ON phoenix_users.user_id = phoenix_assign_users.student_id WHERE assign_id = '".$assign_id."' ORDER BY assign_student_team";
$res_get = $mysqli -> query($sql_get);
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
optgroup {
font-size:18px;
}
select {
padding: 3px 5px;
margin: 2px 0;
box-sizing: border-box;
text-align: center;
font-weight: bold;
font-size: 18px;
border: 2px solid black;
 border-radius: 4px;
}
input, select{
box-sizing: border-box;
-moz-box-sizing: border-box;
-webkit-box-sizing: border-box;
}
.mid {
vertical-align: middle;
}
input[type=text] {
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
<p style="font-size: x-large"><b><?php echo strtoupper($assign_name); ?></b></p>
<form method="post" action="assign_copy_insert_teams.php?copy_id=<?php echo $copy_id; ?>&assign_id=<?php echo $assign_id; ?>">
<table width="40%" align="center" bgcolor="#000000"><tbody>
<?php
if($pre_assign == '2'){
?>
<tr>
<td height="40" bgcolor="647d57"><b>NAME</b></td>
<td height="40" bgcolor="647d57"><b>TEAM</b></td>
</tr>
<?php
}
$color = 1;
while($data_get = mysqli_fetch_assoc($res_get)){
$user_id = $data_get['user_id'];
$user_title = $data_get['user_title'];
$user_first_name = $data_get['user_first_name'];
$user_last_name = $data_get['user_last_name'];
$assign_student_team = $data_get['assign_student_team'];
if($assign_student_team != $previous && $pre_assign == '1'){
?>
<tr>
<td height="40" colspan="2" bgcolor="647d57"><b>TEAM <?php echo $assign_student_team; ?></b></td>
</tr>
<?php
$color = 1;}
$previous = $assign_student_team;
if($color == 1){
?>
<tr>
<td height="40" bgcolor="769467"><?php echo $user_title.' '.$user_first_name.' '.$user_last_name; ?></td>
<td height="40" bgcolor="769467">
<select name="team[]" style="width: 80%;"><optgroup>
<option value="" disabled <?php if($assign_student_team == 0) { echo 'selected'; } ?>>Choose a team</option>
<?php
for($i = 1; $i <= $teams_number; $i++){
?>
<option value="<?php echo $user_id.'_'.$i; ?>" <?php if($assign_student_team == $i) { echo 'selected'; } ?>>Team <?php echo $i; ?></option>
<?php
}
?>
</optgroup></select>
</td>
</tr>
<?php
$color = 2;}
else {
?>
<tr>
<td height="40" bgcolor="a0b595"><?php echo $user_title.' '.$user_first_name.' '.$user_last_name; ?></td>
<td height="40" bgcolor="a0b595">
<select name="team[]" style="width: 80%;"><optgroup>
<option value="" disabled <?php if($assign_student_team == 0) { echo 'selected'; } ?>>Choose a team</option>
<?php
for($i = 1; $i <= $teams_number; $i++){
?>
<option value="<?php echo $user_id.'_'.$i; ?>" <?php if($assign_student_team == $i) { echo 'selected'; } ?>>Team <?php echo $i; ?></option>
<?php
}
?>
</optgroup></select>
</td>
</tr>
<?php
$color = 1;}}
?>
</tbody></table>
<p><input type="submit" value="NEXT"></p>
</form></body></html>