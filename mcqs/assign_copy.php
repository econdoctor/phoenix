<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$assign_id = $_GET['assign_id'];
if(empty($assign_id)){
echo 'Missing information';
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
$sql_info = "SELECT assign_name, assign_syllabus, assign_time_allowed, assign_type, assign_teacher FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
$assign_name = $data_info['assign_name'];
$assign_syllabus = $data_info['assign_syllabus'];
if($assign_syllabus == '1'){
$assign_syllabus_display = 'IGCSE';}
if($assign_syllabus == '2'){
$assign_syllabus_display = 'AS LEVEL';}
if($assign_syllabus == '3'){
$assign_syllabus_display = 'A LEVEL';}
$assign_time_allowed = $data_info['assign_time_allowed'];
$assign_type = $data_info['assign_type'];
if($assign_type == '4'){
echo 'You cannot copy offline assignments';
exit();}
$assign_teacher = $data_info['assign_teacher'];
if($user_id != $assign_teacher){
echo 'You are not authorized to manage this assignment.';
exit();}
function is_selected($db_value, $html_value){
if($db_value == $html_value){
return "selected";}
else{
return "";}}
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
width: 15%;
border-radius: 4px;
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
input[type=datetime-local] {
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
input[type=date] {
padding: 3px 5px;
margin: 2px 0;
box-sizing: border-box;
text-align: center;
font-weight: bold;
font-size: 18px;
border: 2px solid black;
border-radius: 4px;
width: 40%;
}
input[type=time] {
padding: 3px 5px;
margin: 2px 0;
box-sizing: border-box;
text-align: center;
font-weight: bold;
font-size: 18px;
border: 2px solid black;
border-radius: 4px;
width: 40%;
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
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script>
$(function () {
$("#type").change(function() {
var val = $(this).val();
if(val == "5") {
$("#bottom").hide();
$("#time_allowed").prop('required', false);
$("#deadline").prop('required', false);
$("#deadline_date").prop('required', false);
$("#deadline_time").prop('required', false);
$('#time_allowed').val('');
$('#deadline').prop('type', 'text').val('');
$('#deadline_date').prop('type', 'text').val('');
$('#deadline_time').prop('type', 'text').val('');
$('#start').prop('type', 'text').val('');
$('#start_date').prop('type', 'text').val('');
$('#start_time').prop('type', 'text').val('');}
else if(val != "5"){
$("#bottom").show();
$("#deadline").prop('required', true);
$("#deadline_date").prop('required', true);
$("#deadline_time").prop('required', true);}});});
</script>
<script>
function statusDiv(){
var sel = document.getElementById("type");
var text = sel.options[sel.selectedIndex].text;
if(text == "Game" || text == "Offline") {
$("#bottom").hide();
$("#time_allowed").prop('required', false);
$("#deadline").prop('required', false);
$("#deadline_date").prop('required', false);
$("#deadline_time").prop('required', false);
$('#time_allowed').val('');
$('#deadline').prop('type', 'text').val('');
$('#deadline_date').prop('type', 'text').val('');
$('#deadline_time').prop('type', 'text').val('');
$('#start').prop('type', 'text').val('');
$('#start_date').prop('type', 'text').val('');
$('#start_time').prop('type', 'text').val('');}
else if(text != "Game" && text != "Offline"){
$("#bottom").show();
$("#deadline").prop('required', true);
$("#deadline_date").prop('required', true);
$("#deadline_time").prop('required', true);}}
</script>
</head>
<body onload="statusDiv();"><a name="top"></a>
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
<p style="font-size: x-large"><b>COPY OF "<?php echo strtoupper($assign_name); ?>"</b></p>
<form method="post" action="assign_copy_insert1.php?copy_id=<?php echo $assign_id; ?>">
<?php
if($_GET['error'] == 1){
echo '<p><b style="color: #820000;">Missing information</b></p>';}
if($_GET['error'] == 2){
echo '<p><b style="color: #820000;">The assignment cannot be released after it is due</b></p>';}
if($_GET['error'] == 3){
echo '<p><b style="color: #820000;">The time allowed must be strictly positive</b></p>';}
if($_GET['error'] == 4){
echo '<p><b style="color: #820000;">The time allowed must be an integer</b></p>';}
if($_GET['error'] == 5){
echo '<p><b style="color: #820000;">The start date has already passed</b></p>';}
if($_GET['error'] == 6){
echo '<p><b style="color: #820000;">The deadline has already passed</b></p>';}
if($_GET['error'] == 7){
echo '<p><b style="color: #820000;">You cannot have more than 10 online assignments in progress</b></p>';}
?>
<p><select name="type" id="type" style="width: 20%" required ><optgroup>
<option value="" disabled>Type</option>
<option value="1" <?php if((empty($_GET['t']) && $assign_type == '1') || $_GET['t'] == "1") { echo 'selected'; } ?>>Homework</option>
<option value="2" <?php if((empty($_GET['t']) && $assign_type == '2') ||  $_GET['t'] == "2") { echo 'selected'; } ?>>Class exercise</option>
<option value="3" <?php if((empty($_GET['t']) && $assign_type == '3') || $_GET['t'] == "3") { echo 'selected'; } ?>>Test</option>
<option value="5" <?php if((empty($_GET['t']) && $assign_type == '5') || $_GET['t'] == "5") { echo 'selected'; } ?>>Game</option>
</optgroup></select></p>
<p><input type="text" name="name" maxlength="64" required size="32" placeholder="Assignment name" <?php if(empty($_GET['n'])) { echo 'value="'.$assign_name.' (Copy)"'; } else { echo 'value="'.$_GET['n'],'"'; } ?>></p>

<p><b>Course:</b><br><b style="color: #033909;"><?php echo $assign_syllabus_display; ?></b></p>
<input type="hidden" name="syllabus" value="<?php echo $assign_syllabus; ?>">




<div id="bottom" style="display: <?php if($_GET['t'] == '5' || (empty($_GET['t']) && $assign_type == '5')) { echo 'none'; } else { echo 'block'; } ?>;">
<p><input type="text" id="time_allowed" name="time_allowed" size="6" maxlength="3" placeholder="Time allowed (minutes)" <?php if(empty($_GET['ta']) && $assign_time_allowed > 0) { echo 'value="'.$assign_time_allowed.'"'; }  if(!empty($_GET['ta'])) { echo 'value="'.$_GET['ta'].'"'; } ?>><br>
<b style="font-size: medium; color: #820000">Leave blank for no time limit</b></p>
<?php
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE){
if(empty($_GET['start'])){
?>
<p><input id="start" name="start" type="text" placeholder="Start" onfocus="(this.type='datetime-local')">
<?php
}
if(!empty($_GET['start'])){
?>
<p><input id="start" name="start" type="datetime-local" value="<?php echo $_GET['start']; ; ?>">
<?php
}
?>
<br><b style="font-size: medium; color: #820000">Leave blank for an immediate start</b></p>
<?php
if(empty($_GET['deadline'])){
?>
<p><input id="deadline" name="deadline" type="text" placeholder="Deadline" onfocus="(this.type='datetime-local')" required>
<?php
}
if(!empty($_GET['deadline'])){
?>
<p><input id="deadline" name="deadline" type="datetime-local" value="<?php echo $_GET['deadline']; ; ?>">
<?php
}}
elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE){
if(empty($_GET['start_d']) && empty($_GET['start_t'])){
?>
<table width="42%" align="center"><tbody><tr>
<td width="50%"><input id="start_date" name="start_date" type="text" title="Start (Date)" style="width: 100%;" placeholder="Start (Date)" onfocus="(this.type='date')"></td>
<td width="1%"></td>
<td width="50%"><input id="start_time" name="start_time" style="width: 100%;" type="text" title="Start (Time)" placeholder="Start (Time)" onfocus="(this.type='time')"></td>
</tr></tbody></table>
<?php
}
if(!empty($_GET['start_d']) && !empty($_GET['start_t'])){
?>
<table width="42%" align="center"><tbody><tr>
<td width="50%"><input id="start_date" name="start_date" type="date" title="Start (Date)" style="width: 100%;" value="<?php echo $_GET['start_d']; ?>"></td>
<td width="1%"></td>
<td width="50%"><input id="start_time" name="start_time" type="time" title="Start (Time)" style="width: 100%;" value="<?php echo $_GET['start_t']; ?>"></td>
</tr></tbody></table>
<?php
}
?>
<b style="font-size: medium; color: #820000">Leave blank for an immediate start</b></p>
<?php
if(empty($_GET['deadline_d']) && empty($_GET['deadline_t'])){
?>
<table width="42%" align="center"><tbody><tr>
<td width="50%"><input id="deadline_date" name="deadline_date" type="text" title="Deadline (Date)" style="width: 100%;" placeholder="Deadline (Date)" onfocus="(this.type='date')" required></td>
<td width="1%"></td>
<td width="50%"><input id="deadline_time" name="deadline_time" style="width: 100%;" type="text" title="Deadline (Time)" placeholder="Deadline (Time)" onfocus="(this.type='time')" required></td>
</tr></tbody></table>
<?php
}
if(!empty($_GET['deadline_d']) && !empty($_GET['deadline_t'])){
?>
<table width="42%" align="center"><tbody><tr>
<td width="50%"><input id="deadline_date" name="deadline_date" type="date" title="Deadline (Date)" style="width: 100%;" value="<?php echo $_GET['deadline_d']; ?>" required></td>
<td width="1%"></td>
<td width="50%"><input id="deadline_time" name="deadline_time" type="time" title="Deadline (Time)" style="width: 100%;" value="<?php echo $_GET['deadline_t']; ?>" required></td>
</tr></tbody></table>
<?php
}}


elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE){
?>
<p><input id="start_date" name="start_date" type="text" placeholder="Start (Date: yyyy-mm-dd)" maxlength="10" <?php if(!empty($_GET['start_d'])) { echo 'value="'.$_GET['start_d'].'"'; } ?>>&nbsp;&nbsp;
<input id="start_time" name="start_time" type="text" placeholder="Start (Time: hh:mm)" maxlength="5" <?php if(!empty($_GET['start_t'])) { echo 'value="'.$_GET['start_t'].'"'; } ?>>
<br><b style="font-size: medium; color: #820000">Leave blank for an immediate start</b></p>
<p><input id="deadline_date" name="deadline_date" type="text" placeholder="Deadline (Date: yyyy-mm-dd)" maxlength="10" <?php if(!empty($_GET['deadline_d'])) { echo 'value="'.$_GET['deadline_d'].'"'; } ?> required>&nbsp;&nbsp;
<input id="deadline_time" name="deadline_time" type="text" placeholder="Deadline (Time: hh:mm)" maxlength="5" required <?php if(!empty($_GET['deadline_t'])) { echo 'value="'.$_GET['deadline_t'].'"'; } ?>>
<?php
}


else {
if(empty($_GET['start'])){
?>
<p><input id="start" name="start" type="text" placeholder="Start" onfocus="(this.type='datetime-local')">
<?php
}
if(!empty($_GET['start'])){
?>
<p><input id="start" name="start" type="datetime-local" value="<?php echo $_GET['start']; ; ?>">
<?php
}
?>
<br><b style="font-size: medium; color: #820000">Leave blank for an immediate start</b></p>
<?php
if(empty($_GET['deadline'])){
?>
<p><input id="deadline" name="deadline" type="text" placeholder="Deadline" onfocus="(this.type='datetime-local')" required>
<?php
}
if(!empty($_GET['deadline'])){
?>
<p><input id="deadline" name="deadline" type="datetime-local" value="<?php echo $_GET['deadline']; ; ?>">
<?php
}}
?>
</p></div>
<input type="submit" value="NEXT" style="width: 20%"><br><br>
</form></body></html>