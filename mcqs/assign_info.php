<?php
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT user_type, user_timezone, user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id ='".$user_id."'";
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
$assign_id = $_GET['assign_id'];
if(!isset($assign_id)){
echo 'Missing information about the assignment.';
exit();}
$sql_info = "SELECT * FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
$assign_name = $data_info['assign_name'];
$assign_type = $data_info['assign_type'];
if($assign_type == '1'){
$assign_type_text = 'Homework';}
if($assign_type == '2'){
$assign_type_text = 'Class exercise';}
if($assign_type == '3'){
$assign_type_text = 'Test';}
if($assign_type == '4'){
$assign_type_text = 'Offline';}
if($assign_type == '5'){
$assign_type_text = 'Game';}
$nr_questions = $data_info['assign_nq'];
$assign_time_limit = $data_info['assign_time_limit'];
$assign_game_status = $data_info['assign_game_status'];
$assign_syllabus = $data_info['assign_syllabus'];
if($assign_syllabus == '3'){
$s_text = "A LEVEL";}
if($assign_syllabus == '2'){
$s_text = "AS LEVEL";}
if($assign_syllabus == '1'){
$s_text = "IGCSE";}
$assign_start = date("Y-m-d H:i:s", strtotime($data_info['assign_start']));
if($user_timezone >= 0){
$assign_start_display = date('Y-m-d H:i', strtotime(''.$assign_start.' + '.$user_timezone.' minutes'));}
if($user_timezone < 0){
$assign_start_display = date('Y-m-d H:i', strtotime(''.$assign_start.' - '.abs($user_timezone).' minutes'));}
$assign_deadline = date("Y-m-d H:i:s", strtotime($data_info['assign_deadline']));
if($user_timezone >= 0){
$assign_deadline_display = date('Y-m-d H:i', strtotime(''.$assign_deadline.' + '.$user_timezone.' minutes'));}
if($user_timezone < 0){
$assign_deadline_display = date('Y-m-d H:i', strtotime(''.$assign_deadline.' - '.abs($user_timezone).' minutes'));}
$end_f = date("M d, Y H:i:s", strtotime($assign_deadline));
$assign_time_allowed = $data_info['assign_time_allowed'];
$assign_teacher = $data_info['assign_teacher'];
if($assign_teacher != $user_id){
echo 'You are not authorized to manage this assignment.';
exit();}
if($now < $assign_start){
$status = "NOT STARTED YET";}
if($now > $assign_start && $now < $assign_deadline){
$status = "<span style='color: #0D3151'>IN PROGRESS</span>";}
if($now > $assign_deadline){
$status = "<span style='color: #033909'>FINISHED</span>";}
if($assign_type == '4'){
$status = "OFFLINE";}
if($assign_type == '5'){
if($assign_game_status == 0)
$status = "NOT STARTED YET";}
if($assign_game_status == 4){
$status = "<span style='color: #033909'>FINISHED</span>";}
if($assign_game_status != 0 && $assign_game_status != 4){
$status = "<span style='color: #0D3151'>IN PROGRESS</span>";}
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
.brmedium {
display: block;
margin-bottom: 2em;
}
</style>
<script language="JavaScript">
function preload(id){
var x = document.getElementById(id)
document.getElementById("preload").style.display = "block";
document.getElementById("content").style.display = "none";
<?php
if($assign_type != '4'){
?>
document.getElementById("students").setAttribute('style', 'width: 15%;')
document.getElementById("students").setAttribute('onclick', "document.location.href='assign_students_stats.php?assign_id=<?php echo $assign_id; ?>'");
<?php
}
?>
document.getElementById("questions").setAttribute('style', 'width: 15%;')
document.getElementById("questions").setAttribute('onclick', "document.location.href='assign_questions_stats.php?assign_id=<?php echo $assign_id; ?>'");
document.getElementById("details").setAttribute('style', 'width: 15%;')
document.getElementById("details").setAttribute('onclick', "document.location.href='assign_info.php?assign_id=<?php echo $assign_id; ?>'");
x.setAttribute('style', 'width: 15%; cursor: default; background-color: black;"')
x.setAttribute('onclick', '');
if(id == 'details'){
document.location.href='assign_info.php?assign_id=<?php echo $assign_id; ?>';}
if(id == 'students'){
document.location.href='assign_students_stats.php?assign_id=<?php echo $assign_id; ?>';}
if(id == 'questions'){
document.location.href='assign_questions_stats.php?assign_id=<?php echo $assign_id; ?>';}}
</script>
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
<p style="font-size: x-large"><b><?php echo strtoupper($assign_name); ?></b></p><p>
<p><input type="button" id="details" name="details" value="DETAILS" style="width: 15%; cursor: default; background-color: black;">&nbsp;&nbsp;
<?php
if($assign_type != '4'){
?>
<input type="button" id="students" name="students" value="STUDENTS" style="width: 15%;" onclick="preload('students');">&nbsp;&nbsp;
<?php
}
?>
<input type="button" id="questions" name="questions" value="QUESTIONS" style="width: 15%;" onclick="preload('questions');"></p>
<span class="brmedium"></span>
<div id="preload" style="display: none;">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><b style="font-size: larger; color: #033909;">LOADING...</b><br>
<img src="preload.gif" width="100"></p>
</div>
<div id="content" style="display: block;">
<p><table align="center" width="50%" bgcolor="#000000"><tbody><tr>
<td height="40" bgcolor="#647d57"><b>COURSE</b></td>
<td height="40" bgcolor="#647d57"><b>TYPE</b></td>
<td height="40" bgcolor="#647d57"><b>STATUS</b></td>
</tr><tr>
<td height="40" bgcolor="#769467"><b><?php echo $s_text; ?></b></td>
<td height="40" bgcolor="#769467"><?php echo $assign_type_text; ?></td>
<td height="40" bgcolor="#769467"><b><?php echo $status; ?></b></td>
</tr></tbody></table></p>
<?php
if($assign_type == '4'){
?>
<span class="brmedium"></span>
<p><table align="center" width="15%" bgcolor="#000000"><tbody><tr>
<td height="40" bgcolor="#647d57"><b>QUESTIONS</b></td>
</tr><tr>
<td width="25%" height="40" bgcolor="#769467"><p><b><?php echo $nr_questions; ?></b> MCQs</p>
&nbsp;&nbsp;<input type="button" name="update_questions" value="UPDATE" style="width: 80%;" onclick="document.location.href='update_questions.php?assign_id=<?php echo $assign_id; ?>';"></p></td>
</td></tr></tbody></table>
<p>&nbsp;</p>
<?php
}
if($assign_type == '5'){
?>
<span class="brmedium"></span>
<p><table align="center" width="50%" bgcolor="#000000"><tbody><tr>
<td height="40" bgcolor="#647d57"><b>QUESTIONS</b></td>
<td height="40" bgcolor="#647d57"><b>TIMER</b></td>
</tr><tr>
<td width="25%" height="40" bgcolor="#769467"><p><b><?php echo $nr_questions; ?></b> MCQs</p>
<?php
if($assign_game_status == 0){
?>
&nbsp;&nbsp;<input type="button" name="update_questions" value="UPDATE" style="width: 40%;" onclick="document.location.href='update_questions.php?assign_id=<?php echo $assign_id; ?>';"></p></td>
<?php
}
?>
</td>
<td height="40" width="25%" bgcolor="#769467"><p><b><?php echo $assign_time_limit; ?></b> seconds</p>
<?php
if($assign_game_status == 0){
?>
<p><input type="button" name="update_tl" value="UPDATE" style="width: 40%;" onclick="document.location.href='update_time_limit.php?assign_id=<?php echo $assign_id; ?>';"></p>
<?php
}
?>
</td>
</tr></tbody></table></p><span class="brmedium"></span>
<p><b style="font-size: larger;">GRADE THRESHOLDS</b></p>
<?php
if($assign_syllabus != '1'){
echo '<p><table align="center" bgcolor="#000000" width="50%">';}
if($assign_syllabus == '1'){
echo '<p><table align="center" bgcolor="#000000" width="67%">';}
?>
<tbody><tr>
<td height="40" bgcolor="#647d57"><b>A</b></td>
<td height="40" bgcolor="#647d57"><b>B</b></td>
<td height="40" bgcolor="#647d57"><b>C</b></td>
<td height="40" bgcolor="#647d57"><b>D</b></td>
<td height="40" bgcolor="#647d57"><b>E</b></td>
<?php
if($assign_syllabus != '1'){
echo '<td height="40" bgcolor="#647d57"><b>U</b></td>';}
if($assign_syllabus == '1'){
echo '<td height="40" bgcolor="#647d57"><b>F</b></td>
<td height="40" bgcolor="#647d57"><b>G</b></td>
<td height="40" bgcolor="#647d57"><b>U</b></td>';}
?>
</tr><tr>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data_info['assign_a']; ?>%</td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data_info['assign_b']; ?>%</td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data_info['assign_c']; ?>%</td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data_info['assign_d']; ?>%</td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data_info['assign_e']; ?>%</td>
<?php
if($assign_syllabus != '1'){
echo '<td height="40" bgcolor="#769467">< '.$data_info['assign_e'].'%</td>';}
if($assign_syllabus == '1'){
echo '<td height="40" bgcolor="#769467">&#8805; '.$data_info['assign_f'].'%</td>
<td height="40" bgcolor="#769467">&#8805; '.$data_info['assign_g'].'%</td>
<td height="40" bgcolor="#769467">< '.$data_info['assign_g'].'%</td>';}
?>
</tr></tbody></table></p>
<p><input type="button" name="update_gt" value="UPDATE" style="width: 10%;" onclick="document.location.href='update_gt.php?assign_id=<?php echo $assign_id; ?>';"></p></div>
<p>&nbsp;</p>
<?php
}
if($assign_type != '4' && $assign_type != '5'){
?>
<span class="brmedium"></span>
<p><table align="center" width="80%" bgcolor="#000000"><tbody><tr>
<td height="40" bgcolor="#647d57"><b>START</b></td>
<td height="40" bgcolor="#647d57"><b>DEADLINE</b></td>
<td height="40" bgcolor="#647d57"><b>QUESTIONS</b></td>
<td height="40" bgcolor="#647d57"><b>TIME ALLOWED</b></td>
</tr><tr>
<td width="25%" height="40" bgcolor="#769467"><p><?php echo $assign_start_display; ?></p>
<?php
if($now < $assign_start){
?>
<p><input type="button" name="update_start" value="UPDATE" style="width: 40%;" onclick="document.location.href='update_start.php?assign_id=<?php echo $assign_id; ?>';"></p>
<?php
}
?>
</td>
<td width="25%" height="40" bgcolor="#769467"><p><?php echo $assign_deadline_display; ?></p>
<p><input type="button" name="update_deadline" value="UPDATE" style="width: 40%;" onclick="document.location.href='update_deadline.php?assign_id=<?php echo $assign_id; ?>';"></p></td>
<td width="25%" height="40" bgcolor="#769467"><p><b><?php echo $nr_questions; ?></b> MCQs</p>
<?php
$sql_check_start = "SELECT COUNT(*) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_start IS NOT NULL";
$res_check_start = $mysqli -> query($sql_check_start);
$data_check_start = mysqli_fetch_assoc($res_check_start);
if($data_check_start['COUNT(*)'] == 0 && $now < $assign_deadline){
?>
&nbsp;&nbsp;<input type="button" name="update_questions" value="UPDATE" style="width: 40%;" onclick="document.location.href='update_questions.php?assign_id=<?php echo $assign_id; ?>';"></p></td>
<?php
}
?>
</td>
<?php
if($assign_time_allowed > 0){
echo '<td height="40" width="25%" bgcolor="#769467"><p><b>'.$assign_time_allowed.'</b> minutes</p>';
if($data_check_start['COUNT(*)'] == 0){
?>
<p><input type="button" name="update_ta" value="UPDATE" style="width: 40%;" onclick="document.location.href='update_time_allowed.php?assign_id=<?php echo $assign_id; ?>';"></p>
<?php
}
echo '</td>';}
if($assign_time_allowed == 0){
echo '<td height="40" width="25%" bgcolor="#769467"><p>N/A</p>';
if($data_check_start['COUNT(*)'] == 0){
?>
<p><input type="button" name="update_ta" value="UPDATE" style="width: 40%;" onclick="document.location.href='update_time_allowed.php?assign_id=<?php echo $assign_id; ?>';"></p>
<?php
}
echo '</td>';}
?>
</tr></tbody></table></p><span class="brmedium"></span>
<p><b style="font-size: larger;">GRADE THRESHOLDS</b></p>
<?php
if($assign_syllabus != '1'){
echo '<p><table align="center" bgcolor="#000000" width="50%">';}
if($assign_syllabus == '1'){
echo '<p><table align="center" bgcolor="#000000" width="67%">';}
?>
<tbody><tr>
<td height="40" bgcolor="#647d57"><b>A</b></td>
<td height="40" bgcolor="#647d57"><b>B</b></td>
<td height="40" bgcolor="#647d57"><b>C</b></td>
<td height="40" bgcolor="#647d57"><b>D</b></td>
<td height="40" bgcolor="#647d57"><b>E</b></td>
<?php
if($assign_syllabus != '1'){
echo '<td height="40" bgcolor="#647d57"><b>U</b></td>';}
if($assign_syllabus == '1'){
echo '<td height="40" bgcolor="#647d57"><b>F</b></td>
<td height="40" bgcolor="#647d57"><b>G</b></td>
<td height="40" bgcolor="#647d57"><b>U</b></td>';}
?>
</tr><tr>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data_info['assign_a']; ?>%</td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data_info['assign_b']; ?>%</td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data_info['assign_c']; ?>%</td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data_info['assign_d']; ?>%</td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data_info['assign_e']; ?>%</td>
<?php
if($assign_syllabus != '1'){
echo '<td height="40" bgcolor="#769467">< '.$data_info['assign_e'].'%</td>';}
if($assign_syllabus == '1'){
echo '<td height="40" bgcolor="#769467">&#8805; '.$data_info['assign_f'].'%</td>
<td height="40" bgcolor="#769467">&#8805; '.$data_info['assign_g'].'%</td>
<td height="40" bgcolor="#769467">< '.$data_info['assign_g'].'%</td>';}
?>
</tr></tbody></table></p>
<p><input type="button" name="update_gt" value="UPDATE" style="width: 10%;" onclick="document.location.href='update_gt.php?assign_id=<?php echo $assign_id; ?>';"></p></div>
<p>&nbsp;</p>
<?php
}
?>
</div></body></html>