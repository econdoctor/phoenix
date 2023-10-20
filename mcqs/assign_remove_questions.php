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
$assign_id = $_GET['assign_id'];
if(empty($assign_id)){
echo 'Missing information about the assignment.';
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
$sql_assign = "SELECT assign_deadline, assign_game_status, assign_type, assign_name, assign_teacher, assign_syllabus FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
$assign_name = $data_assign['assign_name'];
$assign_teacher = $data_assign['assign_teacher'];
$assign_type = $data_assign['assign_type'];
$assign_game_status = $data_assign['assign_game_status'];
$assign_deadline = $data_assign['assign_deadline'];
$s = $data_assign['assign_syllabus'];
if($assign_teacher != $user_id){
echo 'You are not authorized to manage this assignment.';
exit();}
$sql_check_start = "SELECT COUNT(*) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_start IS NOT NULL";
$res_check_start = $mysqli -> query($sql_check_start);
$data_check_start = mysqli_fetch_assoc($res_check_start);
if($data_check_start['COUNT(*)'] > 0){
echo 'You can no longer remove questions from this assignment.';
exit();}
if($assign_type != '5' && $assign_type != '4' && $now > $assign_deadline){
echo 'The assignment is already over';
exit();}
if($assign_type == '5' && $assign_game_status > 0){
echo 'The game has already started';
exit();}
$sql_get = "SELECT * FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id INNER JOIN phoenix_topics ON phoenix_questions.question_topic_id = phoenix_topics.topic_id WHERE assign_id = '".$assign_id."' ORDER BY phoenix_assign_questions.assign_question_number ASC";
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
width: 40%;
border-radius: 4px;
}
.tooltip2 {
position: relative;
display: inline-block;
}
.tooltip2 .tooltiptext2 {
visibility: hidden;
width: 250px;
background-color: black;
color: #fff;
text-align: center;
padding: 10px 10px 10px 10px;
bottom: 150%;
border-radius: 6px;
margin-left: -150px;
margin-top: -150px;
position: absolute;
z-index: 1;
}
.tooltip2 .tooltiptext2::after {
content: " ";
position: absolute;
top: 100%;
left: 50%;
margin-left: -5px;
border-width: 5px;
border-style: solid;
border-color: black transparent transparent transparent;
}
.tooltip2:hover .tooltiptext2 {
visibility: visible;
}
.brmedium {
display: block;
margin-bottom: 2em;
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
video {
  margin-bottom: -1px;
}
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script language="JavaScript">
function toggle(source){
checkboxes = document.getElementsByName('question_id[]');
for(var i=0, n=checkboxes.length;i<n;i++){
checkboxes[i].checked = source.checked;}}
</script>
<script type="text/javascript">
var updateCount = function() {
var x = $(".z:checked").length;
document.getElementById("y").innerHTML = '<p><b>You have selected <span style="color: #820000;">'+x+'</span> question(s)</b></p>';
document.title = x + " MCQs selected - Phoenix";};
</script>
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
<script type="text/javascript">
function showVideo(number) {
var div_video = document.getElementById('div_'+number);
var btn_video = document.getElementById('btn_'+number);
var player_video = document.getElementById('player_'+number);
div_video.style.display = 'block';
btn_video.style.display = 'none';
player_video.play();}
</script>
</head>
<body onload="updateCount()"><a name="top"></a>
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
<p><input type="button" id="details" name="details" value="DETAILS" style="width: 15%;" onclick="preload('details');">&nbsp;&nbsp;
<?php
if($assign_type != '4'){
?>
<input type="button" id="students" name="students" value="STUDENTS" style="width: 15%;" onclick="preload('students');">&nbsp;&nbsp;
<?php
}
?><input type="button" id="questions" name="questions" value="QUESTIONS" style="width: 15%;" onclick="preload('questions');"></p>
<span class="brmedium"></span>
<div id="preload" style="display: none;">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><b style="font-size: larger; color: #033909;">LOADING...</b><br>
<img src="preload.gif" width="100"></p>
</div>
<div id="content" style="display: block;">
<?php
if($_GET['error'] == 1){
echo '<p><b style="color: #820000;">Please select at least one question</b></p>';}
?>
<p>Please select the questions you want to remove from the assignment.</p>
<p><b><input type="checkbox" style="transform: scale(1.5);" style="transform: scale(1.5);" onclick="toggle(this);updateCount()">&nbsp;&nbsp;Select all</b></p>
<div id="y"></div>
<form method="post" action="assign_remove_questions_db.php?assign_id=<?php echo $assign_id; ?>">
<?php
$i = 1;
while($data_get = mysqli_fetch_assoc($res_get)){
$video = 0;
$filename = './v/'.$data_get['question_paper_id'].$data_get['question_number'].'.mp4';
if(file_exists($filename)){
$video = 1;}
$unit = $data_get['topic_unit'];
$module = $data_get['topic_module'];
$question_paper = $data_get['question_paper_id'];
if(substr($question_paper, 3, 1) == '1'){
$serie = 'm - February / March';}
if(substr($question_paper, 3, 1) == '2'){
$serie = 's - May / June';}
if(substr($question_paper, 3, 1) == '3'){
$serie = 'w - October / November';}
if(substr($question_paper, 3, 1) == '4'){
$serie = 'y - Specimen';}
$year = '20'.substr($question_paper, 1, 2);
$version = substr($question_paper, -1, 1);
if($version == 0){
$version = "/";}
if($data_get['question_syllabus'] != "3"){
$paper = 'P1';}
if($data_get['question_syllabus'] == "3"){
$paper = 'P3';}
$display_info = $paper.' | '.$year.'<br>'.$serie.'<br>Version '.$version.' | Question '.$data_get['question_number'].'<br><br>'.$unit.'<br><br>'.$module;
?>
<table width="72%" align="center" bgcolor="#000000"><tbody><tr>
<td colspan="4" height="40" bgcolor="#6a855c">
<table align="center" width="100%" bgcolor="6a855c"><tbody><tr>
<td height="40" width="5%"><input type="checkbox" style="transform: scale(1.5);" height="20" style="transform: scale(1.5);" class="z" name="question_id[]" value="<?php echo $data_get['question_id']; ?>" onclick="updateCount()"></td>
<td height="40" width="80%"><b>QUESTION <?php echo $i; ?></b></td>
<td height="40" width="5%" valign="middle">
<div class="tooltip2"><img src="info.png" valign="middle" height="30">
<span class="tooltiptext2"><b><?php echo $display_info; ?></b></span></div></td>
</tr></tbody></table>
</td></tr>
<tr>
<td colspan="4" bgcolor="#FFFFFF"><img src="q/<?php echo $data_get['question_syllabus']; ?>/<?php echo $data_get['question_paper_id']; ?>/<?php echo $data_get['question_number']; ?>.png" width="95%"></td></tr>
<?php
if($data_get['question_rate_a'] == NULL && $data_get['question_rate_b'] == NULL && $data_get['question_rate_c'] == NULL && $data_get['question_rate_d'] == NULL){
?>
<tr>
<td height="40" width="25%" <?php if($data_get['question_answer'] == '1') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>A</b></td>
<td height="40" width="25%" <?php if($data_get['question_answer'] == '2') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>B</b></td>
<td height="40" width="25%" <?php if($data_get['question_answer'] == '3') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>C</b></td>
<td height="40" width="25%" <?php if($data_get['question_answer'] == '4') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>D</b></td>
</tr>
<?php
}
if($data_get['question_rate_a'] != NULL && $data_get['question_rate_b'] != NULL && $data_get['question_rate_c'] != NULL && $data_get['question_rate_d'] != NULL){
$question_rate_a = round($data_get['question_rate_a'], 0);
$question_rate_b = round($data_get['question_rate_b'], 0);
$question_rate_c = round($data_get['question_rate_c'], 0);
$question_rate_d = round($data_get['question_rate_d'], 0);
?>
<tr>
<td height="40" width="25%" <?php if($data_get['question_answer'] == '1') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>A<?php echo ' ('.$question_rate_a.'%)'; ?></b></td>
<td height="40" width="25%" <?php if($data_get['question_answer'] == '2') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>B<?php echo ' ('.$question_rate_b.'%)'; ?></b></td>
<td height="40" width="25%" <?php if($data_get['question_answer'] == '3') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>C<?php echo ' ('.$question_rate_c.'%)'; ?></b></td>
<td height="40" width="25%" <?php if($data_get['question_answer'] == '4') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>D<?php echo ' ('.$question_rate_d.'%)'; ?></b></td>
</tr>
<?php
}
if($video == 1){
?>
<tr><td colspan="4" bgcolor="#a0b595">
<p><input id="btn_<?php echo $i; ?>" type="button" style="width: 30%;" value="VIDEO EXPLANATION" onclick="showVideo('<?php echo $i; ?>');"></p>
<div id="div_<?php echo $i; ?>" style="display:none;">
<p><video id="player_<?php echo $i; ?>" width="95%" controls preload="none" style="border: black solid 2px;">
<source src="./v/<?php echo $data_get['question_paper_id'].$data_get['question_number']; ?>.mp4" type="video/mp4">
Your browser does not support the video tag.
</video>
</div></p>
</td></tr>
<?php
}
?>
</tbody></table><br><br>
<?php
$i++;
}
?>
<p><input type="submit" value="REMOVE"></p>
<p><a href="#top"><b>Back to the top</b></a></p></div>
</body></html>