<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];}
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
$order = $_POST['order'];
if(empty($order)){
$order = $_SESSION['phoenix_order'];
if(empty($order)){
$order = '3';}}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT user_type, user_teacher, user_active, user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_teacher = $data['user_teacher'];
$user_active = $data['user_active'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 2){
echo 'What are you doing here? This page is for students only.';
exit();}
$topic_id = $_GET['topic_id'];
if(empty($topic_id)){
echo 'Missing information about the topic.';
exit();}
$sql2 = "SELECT * FROM phoenix_topics WHERE topic_id = '".$topic_id."'";
$res2 = $mysqli -> query($sql2);
$data2 = mysqli_fetch_assoc($res2);
$unit = $data2['topic_unit_id'];
$module = $data2['topic_module_id'];
$unit_name = $data2['topic_unit'];
$module_name = $data2['topic_module'];
$topic_hidden = $data2['topic_hidden'];
$s = $data2['topic_syllabus'];
if($s == '1'){
$s_text = 'IGCSE';}
if($s == '2'){
$s_text = 'AS Level';}
if($s == '3'){
$s_text = 'A Level';}
if($user_active == 0 && $topic_hidden == 1){
echo 'You are not authorized to access this topic.';
exit();}
$sql_perm = "SELECT COUNT(*) FROM phoenix_permissions_topics INNER JOIN phoenix_permissions_users ON phoenix_permissions_users.permission_id = phoenix_permissions_topics.permission_id WHERE phoenix_permissions_topics.topic_id = '".$topic_id."' AND phoenix_permissions_users.student_id = '".$user_id."'";
$res_perm = $mysqli -> query($sql_perm);
$data_perm = mysqli_fetch_assoc($res_perm);
$nr_perm = $data_perm['COUNT(*)'];
if($nr_perm > 0){
echo 'You are not authorized to access this topic.';
exit();}
if($order == '7'){
$sql3 = "SELECT * FROM phoenix_questions WHERE question_topic_id = '".$topic_id."' AND question_repeat = '0' AND question_obsolete = '0' ORDER BY SIN(question_id * $user_id) ASC";}
if($order == '3'){
$sql3 = "SELECT * FROM phoenix_questions WHERE question_topic_id = '".$topic_id."' AND question_repeat = '0' AND question_obsolete = '0' ORDER BY question_serie DESC";}
if($order == '4'){
$sql3 = "SELECT * FROM phoenix_questions WHERE question_topic_id = '".$topic_id."' AND question_repeat = '0' AND question_obsolete = '0' ORDER BY question_serie ASC";}
if($order == '5'){
$sql3 = "SELECT * FROM phoenix_questions WHERE question_topic_id = '".$topic_id."' AND question_repeat = '0' AND question_obsolete = '0' ORDER BY question_rate DESC";}
if($order == '6'){
$sql3 = "SELECT * FROM phoenix_questions WHERE question_topic_id = '".$topic_id."' AND question_repeat = '0' AND question_obsolete = '0' ORDER BY -question_rate DESC";}
$res3 = $mysqli -> query($sql3);
$count = number_format(mysqli_num_rows($res3));
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
.custom-clickable-row {
cursor: pointer;
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
video {
  margin-bottom: -1px;
}
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script>
setInterval(function refresh(){
$.ajax({
url: "refresh_session.php",
success: function(data){
if(data == 'die'){
window.location.href = "../login.php";}}});}, 60000);
</script>
<script>
function rep(id, r_s, h, video){
document.getElementById('row'+id).removeAttribute('onclick');
document.getElementById('1'+id).removeAttribute('onclick');
document.getElementById('2'+id).removeAttribute('onclick');
document.getElementById('3'+id).removeAttribute('onclick');
document.getElementById('4'+id).removeAttribute('onclick');
document.getElementById('1'+id).removeAttribute('class');
document.getElementById('2'+id).removeAttribute('class');
document.getElementById('3'+id).removeAttribute('class');
document.getElementById('4'+id).removeAttribute('class');
if(video == 1){
document.getElementById('tr_vid_'+id).style.display = 'table-row';}
var h_r = h.split("").reverse().join("");
if(h_r % 4 == 0){
r_c = '1';}
if(h_r % 4 == 1){
r_c = '2';}
if(h_r % 4 == 2){
r_c = '3';}
if(h_r % 4 == 3){
r_c = '4';}
if(r_c == r_s){
document.getElementById(r_s+id).style.backgroundColor = '#246648';
var audio = document.getElementById("audio_right");}
if(r_c != r_s){
document.getElementById(r_c+id).style.backgroundColor = '#246648';
document.getElementById(r_s+id).style.backgroundColor = '#9C3039';
var audio = document.getElementById("audio_wrong");}
audio.play();}
</script>
<script>
function answer(question_id, topic_id, answer, question_number, order){
$.ajax({
url: "answer_topic.php?question_id="+question_id+"&topic_id="+topic_id+"&answer="+answer+"&order="+order,
success: function(data){
if(data == 'die'){
window.location.href = "../login.php";}
if(data != 'die'){
$( "#tick" + question_number ).show();
$( '#1' + question_number ).html('<b>A (' + data.split(" ")[0] + '%)</b>');
$( '#2' + question_number ).html('<b>B (' + data.split(" ")[1] + '%)</b>');
$( '#3' + question_number ).html('<b>C (' + data.split(" ")[2] + '%)</b>');
$( '#4' + question_number ).html('<b>D (' + data.split(" ")[3] + '%)</b>');}}});}
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
<body><a name="top"></a>
<table align="center" width="90%">
<tr>
<td>
<p style="text-align: right;"><img src="online.png" width="15">&nbsp;&nbsp;<b><a href="../profile.php"><?php echo $user_title.' '.$user_first_name.' '.$user_last_name.' ('.$user_name.')'; ?></a> - <a href="logout.php">Log out</a></b></p>
</td>
</tr>
</table>
<table width="80%" bgcolor="#6a855c" align="center" style="border: solid black 4px; border-radius:24px;"><tr width="95%">
<td width="20%"><a href="main.php"><img src="home_phoenix.png" width="150" height="150"></a></td>
<td width="80%"><p style="font-size: xx-large;"><b>PHOENIX</b></p>
<p>
<?php
if($user_type == 1 && $user_teacher == 0){
?>
<input type="button" name="practice" value="PRACTICE" style="font-size: x-large; width: 45%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='practice.php';"/>
<?php
}
if($user_type == 1 && $user_teacher != 0){
?>
<input type="button" name="practice" value="PRACTICE" style="font-size: x-large; width: 45%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='practice.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 45%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='complete.php';"/>
<?php
}
?>
</td></tr></table>
<p><input type="button" name="title" value="<?php echo strtoupper($s_text); ?> TOPICS" onclick="document.location.href='practice_topic.php?s=<?php echo $s; ?>';"/>
<p style="font-size: x-large;"><b>TOPIC REFERENCE</b></p>
<table width="80%" align="center" bgcolor="#000000"><tr>
<td height="40" bgcolor="647d57"><b>COURSE</b></td>
<td height="40" bgcolor="647d57"><b>UNIT</b></td>
<td height="40" bgcolor="647d57"><b>TOPIC</b></td>
</tr><tr>
<td height="40" bgcolor="769467"><?php echo $s_text; ?></td>
<td height="40" bgcolor="769467"><?php echo $unit_name; ?></td>
<td height="40" bgcolor="769467"><?php echo $module_name; ?></td>
</tr></table>
<p style="font-size: x-large;"><b>QUESTIONS</b></p>
<?php
if($count == 0){
echo '<p><em>No MCQs found</em></p>';}
if($count > 0){
echo '<p><b><span style="color: #820000;">'.$count.'</span> MCQs found</b></p>';
?>
<form method="post" action="t_practice.php?topic_id=<?php echo $topic_id; ?>">
<p><select name="order" onchange="this.form.submit()">
<option value='3' <?php echo is_selected('3', $order); ?>>Most recent first</option>
<option value='4' <?php echo is_selected('4', $order); ?>>Oldest first</option>
<option value='5' <?php echo is_selected('5', $order); ?>>Easiest first</option>
<option value='6' <?php echo is_selected('6', $order); ?>>Hardest first</option>
<option value='7' <?php echo is_selected('7', $order); ?>>Random</option>
</select></p></form>
<?php
$i = "1";
while($data3 = mysqli_fetch_assoc($res3)){
$video = 0;
$filename = './v/'.$data3['question_paper_id'].$data3['question_number'].'.mp4';
if(file_exists($filename)){
$video = 1;}
$question_id = $data3['question_id'];
$question_paper = $data3['question_paper_id'];
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
if($data3['question_syllabus'] != '3'){
$paper = 'P1';}
if($data3['question_syllabus'] == '3'){
$paper = 'P3';}
$paper_info = $paper.' | '.$year.'<br>'.$serie.'<br>Version '.$version.'<br>Question '.$data3['question_number'];
$answer_question = $data3['question_answer'];
if($answer_question == '1'){
$int = 4*rand(2500000,25000000);}
if($answer_question == '2'){
$int = 4*rand(2500000,25000000)+1;}
if($answer_question == '3'){
$int = 4*rand(2500000,25000000)+2;}
if($answer_question == '4'){
$int = 4*rand(2500000,25000000)+3;}
$hash = strrev($int);
$sql8 = "SELECT answer FROM phoenix_answers WHERE user_id = '".$user_id."' AND question_id = '".$question_id."' AND answer_type = '2'";
$res8 = $mysqli -> query($sql8);
$num_as = mysqli_num_rows($res8);
$data8 = mysqli_fetch_assoc($res8);
$answer_student = $data8['answer'];
?>
<table width="72%" align="center" bgcolor="#000000">
<?php
$display = 1;
$sql_check = "SELECT COUNT(*) FROM phoenix_assign_questions WHERE question_id = '".$question_id."' AND assign_question_hide = '1' AND assign_id IN (SELECT assign_id FROM phoenix_assign_users WHERE student_id = '".$user_id."' AND assign_student_end > '".$now."')";
$res_check = $mysqli -> query($sql_check);
$data_check = mysqli_fetch_assoc($res_check);
if($data_check['COUNT(*)'] > 0){
$display = 0;}
if($display == 0){
?>
<tr valign="middle"><td height="40" colspan ="4" bgcolor="#647d57" valign="middle">
<table align="center" bgcolor="647d57" width="100%">
<tr valign="middle">
<td width="5%" valign="middle">&nbsp;</td>
<td width="90%" align="center"><b>QUESTION <?php echo $i; ?></b></td>
<td width="5%" valign="middle">&nbsp;</td>
</tr></table></td></tr>
<tr><td bgcolor="#FFFFFF" colspan ="4"><img src="na.png" width="95%"></td></tr>
<tr id="row<?php echo $i; ?>">
<td height="40" colspan="4" bgcolor = "#a0b595"><b>This question is part of an ongoing assignment.</b></td>
</td>
</tr>
<?php
}
if($display == 1){
?>
<tr valign="middle"><td height="40" colspan ="4" bgcolor="#647d57" valign="middle">
<table align="center" bgcolor="647d57" width="100%">
<tr valign="middle">
<td width="5%" valign="middle"><img src="tick.png" id="tick<?php echo $i; ?>" <?php if($num_as == 0) { echo 'style="display:none"'; } ?> height="25"></td>
<td width="90%" align="center"><b>QUESTION <?php echo $i; ?></b></td>
<td width="5%" valign="middle">
<div class="tooltip2"><img src="info.png" valign="middle" height="30">
<span class="tooltiptext2" <?php if(substr($question_paper, 3, 1) == '2' || substr($question_paper, 3, 1) == '4') { echo 'style="width: 150px;margin-left: -100px; margin-top: -100px;"'; } if(substr($question_paper, 3, 1) == '1') { echo 'style="width: 200px;margin-left: -125px; margin-top: -125px;"'; } if(substr($question_paper, 3, 1) == '3') { echo 'style="width: 225px;margin-left: -137px; margin-top: -137px;"'; } ?>>
<b><?php echo $paper_info; ?></b></span></div></td>
</tr></table></td></tr>
<tr><td bgcolor="#FFFFFF" colspan ="4"><img src="q/<?php echo $data3['question_syllabus']; ?>/<?php echo $data3['question_paper_id']; ?>/<?php echo $data3['question_number']; ?>.png" width="95%"></td></tr>
<?php
if($num_as == 0){
?>
<tr id="row<?php echo $i; ?>">
<td height="40" bgcolor = "#769467" width = "25%" id="1<?php echo $i; ?>" class="custom-clickable-row" onclick="rep('<?php echo $i; ?>', '1', '<?php echo $hash; ?>', '<?php echo $video; ?>');answer('<?php echo $data3['question_id']; ?>', '<?php echo $topic_id; ?>', '1', '<?php echo $i; ?>', '<?php echo $order; ?>')"><b>A</b></td>
<td height="40" bgcolor = "#769467" width = "25%" id="2<?php echo $i; ?>" class="custom-clickable-row" onclick="rep('<?php echo $i; ?>', '2', '<?php echo $hash; ?>', '<?php echo $video; ?>');answer('<?php echo $data3['question_id']; ?>', '<?php echo $topic_id; ?>', '2', '<?php echo $i; ?>', '<?php echo $order; ?>')"><b>B</b></td>
<td height="40" bgcolor = "#769467" width = "25%" id="3<?php echo $i; ?>" class="custom-clickable-row" onclick="rep('<?php echo $i; ?>', '3', '<?php echo $hash; ?>', '<?php echo $video; ?>');answer('<?php echo $data3['question_id']; ?>', '<?php echo $topic_id; ?>', '3', '<?php echo $i; ?>', '<?php echo $order; ?>')"><b>C</b></td>
<td height="40" bgcolor = "#769467" width = "25%" id="4<?php echo $i; ?>" class="custom-clickable-row" onclick="rep('<?php echo $i; ?>', '4', '<?php echo $hash; ?>', '<?php echo $video; ?>');answer('<?php echo $data3['question_id']; ?>', '<?php echo $topic_id; ?>', '4', '<?php echo $i; ?>', '<?php echo $order; ?>')"><b>D</b></td>
</tr>
<?php
}
if($num_as > 0){
if($data3['question_rate_a'] == NULL && $data3['question_rate_b'] == NULL && $data3['question_rate_c'] == NULL && $data3['question_rate_d'] == NULL){
?>
<tr>
<td width="25%" height="40" <?php if(($answer_student == '1' && $answer_question == '1') || ($answer_student != '1' && $answer_question == '1')){ echo 'bgcolor = "#246648"'; } if($answer_student == '1' && $answer_question != '1'){ echo 'bgcolor = "#9C3039"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>A</b></td>
<td width="25%" height="40" <?php if(($answer_student == '2' && $answer_question == '2') || ($answer_student != '2' && $answer_question == '2')){ echo 'bgcolor = "#246648"'; } if($answer_student == '2' && $answer_question != '2'){ echo 'bgcolor = "#9C3039"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>B</b></td>
<td width="25%" height="40" <?php if(($answer_student == '3' && $answer_question == '3') || ($answer_student != '3' && $answer_question == '3')){ echo 'bgcolor = "#246648"'; } if($answer_student == '3' && $answer_question != '3'){ echo 'bgcolor = "#9C3039"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>C</b></td>
<td width="25%" height="40" <?php if(($answer_student == '4' && $answer_question == '4') || ($answer_student != '4' && $answer_question == '4')){ echo 'bgcolor = "#246648"'; } if($answer_student == '4' && $answer_question != '4'){ echo 'bgcolor = "#9C3039"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>D</b></td>
</tr>
<?php
}
if($data3['question_rate_a'] != NULL && $data3['question_rate_b'] != NULL && $data3['question_rate_c'] != NULL && $data3['question_rate_d'] != NULL){
$question_rate_a = round($data3['question_rate_a'], 0);
$question_rate_b = round($data3['question_rate_b'], 0);
$question_rate_c = round($data3['question_rate_c'], 0);
$question_rate_d = round($data3['question_rate_d'], 0);
?>
<tr>
<td height="40" width="25%" <?php if(($answer_student == '1' && $answer_question == '1') || ($answer_student != '1' && $answer_question == '1')){ echo 'bgcolor = "#246648"'; } if($answer_student == '1' && $answer_question != '1'){ echo 'bgcolor = "#9C3039"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>A<?php echo ' ('.$question_rate_a.'%)'; ?></b></td>
<td height="40" width="25%" <?php if(($answer_student == '2' && $answer_question == '2') || ($answer_student != '2' && $answer_question == '2')){ echo 'bgcolor = "#246648"'; } if($answer_student == '2' && $answer_question != '2'){ echo 'bgcolor = "#9C3039"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>B<?php echo ' ('.$question_rate_b.'%)'; ?></b></td>
<td height="40" width="25%" <?php if(($answer_student == '3' && $answer_question == '3') || ($answer_student != '3' && $answer_question == '3')){ echo 'bgcolor = "#246648"'; } if($answer_student == '3' && $answer_question != '3'){ echo 'bgcolor = "#9C3039"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>C<?php echo ' ('.$question_rate_c.'%)'; ?></b></td>
<td height="40" width="25%" <?php if(($answer_student == '4' && $answer_question == '4') || ($answer_student != '4' && $answer_question == '4')){ echo 'bgcolor = "#246648"'; } if($answer_student == '4' && $answer_question != '4'){ echo 'bgcolor = "#9C3039"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>D<?php echo ' ('.$question_rate_d.'%)'; ?></b></td>
</tr>
<?php
}}
if($video == 1){
?>
<tr id="tr_vid_<?php echo $i; ?>" <?php if($num_as == 0) { echo 'style="display: none;"'; } ?>>
<td colspan="4" bgcolor="#a0b595">
<p><input id="btn_<?php echo $i; ?>" type="button" style="width: 30%;" value="VIDEO EXPLANATION" onclick="showVideo('<?php echo $i; ?>');"></p>
<div id="div_<?php echo $i; ?>" style="display:none;">
<p><video id="player_<?php echo $i; ?>" width="95%" controls preload="none" style="border: black solid 2px;">
<source src="./v/<?php echo $data3['question_paper_id'].$data3['question_number']; ?>.mp4" type="video/mp4">
Your browser does not support the video tag.
</video>
</div></p>
</td></tr>
<?php
}}
echo '</table><br><br>';
$i++;}}
?>
<p><a href="#top"><b>Back to the top</b></a><p>
<audio id="audio_right" src="right.mp3" preload="auto">
<audio id="audio_wrong" src="wrong.mp3" preload="auto">
</body></html>
