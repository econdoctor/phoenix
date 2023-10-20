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
$sql_assign = "SELECT assign_type, assign_name, assign_teacher, assign_syllabus, assign_scramble FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
$assign_type = $data_assign['assign_type'];
$assign_name = $data_assign['assign_name'];
$assign_teacher = $data_assign['assign_teacher'];
$s = $data_assign['assign_syllabus'];
$assign_scramble = $data_assign['assign_scramble'];
if($assign_scramble == '0'){
$assign_scramble = '2';}
if($assign_teacher != $user_id){
echo 'You are not authorized to manage this assignment.';
exit();}
$sql_refresh = "SELECT question_id FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."' AND assign_question_refresh = '1'";
$res_refresh = $mysqli -> query($sql_refresh);
while($data_refresh = mysqli_fetch_assoc($res_refresh)){
$question_id = $data_refresh['question_id'];
$sql_a = "SELECT COUNT(*) FROM phoenix_answers WHERE answer_type = '3' AND assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer = '1'";
$res_a = $mysqli -> query($sql_a);
$data_a = mysqli_fetch_assoc($res_a);
$answers_a = $data_a['COUNT(*)'];
$sql_b = "SELECT COUNT(*) FROM phoenix_answers WHERE answer_type = '3' AND assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer = '2'";
$res_b = $mysqli -> query($sql_b);
$data_b = mysqli_fetch_assoc($res_b);
$answers_b = $data_b['COUNT(*)'];
$sql_c = "SELECT COUNT(*) FROM phoenix_answers WHERE answer_type = '3' AND assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer = '3'";
$res_c = $mysqli -> query($sql_c);
$data_c = mysqli_fetch_assoc($res_c);
$answers_c = $data_c['COUNT(*)'];
$sql_d = "SELECT COUNT(*) FROM phoenix_answers WHERE answer_type = '3' AND assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer = '4'";
$res_d = $mysqli -> query($sql_d);
$data_d = mysqli_fetch_assoc($res_d);
$answers_d = $data_d['COUNT(*)'];
$total_answers = $answers_a + $answers_b + $answers_c + $answers_d;
if($total_answers > 0){
$rate_a = round($answers_a / $total_answers * 100, 2);
$rate_b = round($answers_b / $total_answers * 100, 2);
$rate_c = round($answers_c / $total_answers * 100, 2);
$rate_d = round($answers_d / $total_answers * 100, 2);
$sql_correct = "SELECT COUNT(*) FROM phoenix_answers WHERE answer_type = '3' AND assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer_valid = '1'";
$res_correct = $mysqli -> query($sql_correct);
$data_correct = mysqli_fetch_assoc($res_correct);
$answers_correct = $data_correct['COUNT(*)'];
$rate_correct = round($answers_correct / $total_answers * 100, 2);
$sql_up = "UPDATE phoenix_assign_questions SET assign_question_rate_a = '".$rate_a."',  assign_question_rate_b = '".$rate_b."', assign_question_rate_c = '".$rate_c."', assign_question_rate_d = '".$rate_d."', assign_question_rate = '".$rate_correct."', assign_question_refresh = '0' WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."'";
$res_up = $mysqli -> query($sql_up);}}
function is_selected($db_value, $html_value){
if($db_value == $html_value){
return "selected";}
else {
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
.brsmall {
display: block;
margin-bottom: 0.5em;
}
.brxsmall {
display: block;
margin-bottom: 0.4em;
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
.tooltip3 {
position: relative;
display: inline-block;
}
.tooltip3 .tooltiptext3 {
visibility: hidden;
width: 100%;
background-color: black;
color: #fff;
text-align: center;
padding: 10px 10px 10px 10px;
bottom: 120%;
border-radius: 6px;
margin-left: -156px;
margin-top: -150px;
position: absolute;
z-index: 1;
}
.tooltip3 .tooltiptext3::after {
content: " ";
position: absolute;
top: 100%;
left: 50%;
margin-left: -5px;
border-width: 5px;
border-style: solid;
border-color: black transparent transparent transparent;
}
.tooltip3:hover .tooltiptext3 {
visibility: visible;
}
video {
margin-bottom: -1px;
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
?>
<input type="button" id="questions" name="questions" value="QUESTIONS" style="width: 15%; cursor: default; background-color: black;"></p>
<span class="brmedium"></span>
<div id="preload" style="display: none;">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><b style="font-size: larger; color: #033909;">LOADING...</b><br>
<img src="preload.gif" width="100"></p>
</div>
<div id="content" style="display: block;">
<?php
$questions_order = $_POST['questions_order'];
if(!isset($questions_order)){
if($assign_scramble == '2'){
$questions_order = "assignment";}
if($assign_scramble == '1'){
$questions_order = '6';}}
if($assign_type != '4'){
?>
<form method="post" action="assign_questions_stats.php?assign_id=<?php echo $assign_id; ?>">
<b>Sort by:</b><span class="brsmall"></span>
<select name="questions_order" onchange="this.form.submit();"><optgroup>
<?php
if($assign_scramble != '1'){
?>
<option value="assignment" <?php echo is_selected("assignment", $questions_order); ?>>Original</option>
<?php
}
?>
<option value='5' <?php echo is_selected('5', $questions_order); ?>>Difficulty (Easiest first)</option>
<option value='6' <?php echo is_selected('6', $questions_order); ?>>Difficulty (Hardest first)</option>
</optgroup></select></form>
<?php
}
?>
<span class="brmedium"></span>
<?php
if($questions_order == "assignment"){
$sql_questions = "SELECT phoenix_questions.question_id, topic_unit, topic_module, question_paper_id, question_number, assign_question_number, question_answer, assign_question_rate, assign_question_rate_a, assign_question_rate_b, assign_question_rate_c, assign_question_rate_d, question_syllabus FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id INNER JOIN phoenix_topics ON phoenix_questions.question_topic_id = phoenix_topics.topic_id WHERE assign_id = '".$assign_id."' ORDER BY phoenix_assign_questions.assign_question_number ASC";}
if($questions_order == '5'){
$sql_questions = "SELECT phoenix_questions.question_id, topic_unit, topic_module, question_paper_id, question_number, assign_question_number, question_answer, assign_question_rate, assign_question_rate_a, assign_question_rate_b, assign_question_rate_c, assign_question_rate_d, question_syllabus  FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id INNER JOIN phoenix_topics ON phoenix_questions.question_topic_id = phoenix_topics.topic_id WHERE assign_id = '".$assign_id."' ORDER BY phoenix_assign_questions.assign_question_rate DESC";}
if($questions_order == '6'){
$sql_questions = "SELECT phoenix_questions.question_id, topic_unit, topic_module, question_paper_id, question_number, assign_question_number, question_answer, assign_question_rate, assign_question_rate_a, assign_question_rate_b, assign_question_rate_c, assign_question_rate_d, question_syllabus FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id INNER JOIN phoenix_topics ON phoenix_questions.question_topic_id = phoenix_topics.topic_id WHERE assign_id = '".$assign_id."' ORDER BY -phoenix_assign_questions.assign_question_rate DESC";}
$res_questions = $mysqli -> query($sql_questions);
$color = 1;
$i = 1;
while($data_questions = mysqli_fetch_assoc($res_questions)){
$video = 0;
$filename = './v/'.$data_questions['question_paper_id'].$data_questions['question_number'].'.mp4';
if(file_exists($filename)){
$video = 1;}
$question_id = $data_questions['question_id'];
$question_paper = $data_questions['question_paper_id'];
$question_syllabus = $data_questions['question_syllabus'];
$question_number = $data_questions['question_number'];
$assign_question_number = $data_questions['assign_question_number'];
$question_answer = $data_questions['question_answer'];
$assign_question_rate = $data_questions['assign_question_rate'];
$assign_question_rate_a = $data_questions['assign_question_rate_a'];
$assign_question_rate_b = $data_questions['assign_question_rate_b'];
$assign_question_rate_c = $data_questions['assign_question_rate_c'];
$assign_question_rate_d = $data_questions['assign_question_rate_d'];
$unit = $data_questions['topic_unit'];
$module = $data_questions['topic_module'];
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
if($question_syllabus != '3'){
$paper = 'P1';}
if($question_syllabus == '3'){
$paper = 'P3';}
$display_info = $paper.' | '.$year.'<br>'.$serie.'<br>Version '.$version.' | Question '.$question_number.'<br><br>'.$unit.'<br><br>'.$module;
if($assign_question_rate_a != NULL && $assign_question_rate_b != NULL && $assign_question_rate_c != NULL && $assign_question_rate_d != NULL){
$listA = '';
$listB = '';
$listC = '';
$listD = '';
$sql_ans = "SELECT user_title, user_first_name, user_last_name, answer FROM phoenix_users INNER JOIN phoenix_answers ON phoenix_users.user_id = phoenix_answers.user_id WHERE assign_id ='".$assign_id."' AND question_id = '".$question_id."'";
$res_ans = $mysqli -> query($sql_ans);
while($data_ans = mysqli_fetch_assoc($res_ans)){
$user_title = $data_ans['user_title'];
$user_first_name = $data_ans['user_first_name'];
$user_last_name = $data_ans['user_last_name'];
$answer = $data_ans['answer'];
if($answer == '1'){
$listA .= $data_ans['user_title'].' '.$data_ans['user_first_name'].' '.$data_ans['user_last_name'].'<br>';}
if($answer == '2'){
$listB .= $data_ans['user_title'].' '.$data_ans['user_first_name'].' '.$data_ans['user_last_name'].'<br>';}
if($answer == '3'){
$listC .= $data_ans['user_title'].' '.$data_ans['user_first_name'].' '.$data_ans['user_last_name'].'<br>';}
if($answer == '4'){
$listD .= $data_ans['user_title'].' '.$data_ans['user_first_name'].' '.$data_ans['user_last_name'].'<br>';}}
if($listA == ''){
$listA = 'No answer';}
if($listB == ''){
$listB = 'No answer';}
if($listC == ''){
$listC = 'No answer';}
if($listD == ''){
$listD = 'No answer';}}
?>
<table width="72%" align="center" bgcolor="#000000"><tbody><tr>
<td colspan="4" height="40" bgcolor="#6a855c">
<table align="center" width="100%" bgcolor="6a855c"><tbody><tr>
<td height="40" width="5%">&nbsp;</td>
<td height="40" width="80%"><b>QUESTION <?php echo $assign_question_number; ?></b></td>
<td height="40" width="5%" valign="middle">
<div class="tooltip2"><img src="info.png" valign="middle" height="30">
<span class="tooltiptext2"><b><?php echo $display_info; ?></b></span></div></td>
</tr></tbody></table>
</td></tr>
<tr>
<td colspan="4" bgcolor="#FFFFFF"><img src="q/<?php echo $question_syllabus; ?>/<?php echo $question_paper; ?>/<?php echo $question_number; ?>.png" width="95%"></td></tr>
<?php
if($assign_question_rate_a == NULL && $assign_question_rate_b == NULL && $assign_question_rate_b == NULL && $assign_question_rate_d == NULL){
?>
<tr>
<td height="40" width="25%" <?php if($question_answer == '1') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><div style="height: 100%; width: 100%;" class="tooltip3"><span class="brxsmall"></span><b>A</b><span class="tooltiptext3"><b>No answer</b></span></div></td>
<td height="40" width="25%" <?php if($question_answer == '2') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><div style="height: 100%; width: 100%;" class="tooltip3"><span class="brxsmall"></span><b>B</b><span class="tooltiptext3"><b>No answer</b></span></div></td>
<td height="40" width="25%" <?php if($question_answer == '3') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><div style="height: 100%; width: 100%;" class="tooltip3"><span class="brxsmall"></span><b>C</b><span class="tooltiptext3"><b>No answer</b></span></div></td>
<td height="40" width="25%" <?php if($question_answer == '4') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><div style="height: 100%; width: 100%;" class="tooltip3"><span class="brxsmall"></span><b>D</b><span class="tooltiptext3"><b>No answer</b></span></div></td>
</tr>
<?php
}
if($assign_question_rate_a != NULL && $assign_question_rate_b != NULL && $assign_question_rate_c != NULL && $assign_question_rate_d != NULL){
$question_rate_a = round($assign_question_rate_a, 0);
$question_rate_b = round($assign_question_rate_b, 0);
$question_rate_c = round($assign_question_rate_c, 0);
$question_rate_d = round($assign_question_rate_d, 0);
?>
<tr>
<td height="40" width="25%" <?php if($question_answer == '1') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><div style="height: 100%; width: 100%;" class="tooltip3"><span class="brxsmall"></span><b>A<?php echo ' ('.$question_rate_a.'%)'; ?></b><span class="tooltiptext3"><b><?php echo $listA; ?></b></span></div></td>
<td height="40" width="25%" <?php if($question_answer == '2') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><div style="height: 100%; width: 100%;" class="tooltip3"><span class="brxsmall"></span><b>B<?php echo ' ('.$question_rate_b.'%)'; ?></b><span class="tooltiptext3"><b><?php echo $listB; ?></b></span></div></td>
<td height="40" width="25%" <?php if($question_answer == '3') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><div style="height: 100%; width: 100%;" class="tooltip3"><span class="brxsmall"></span><b>C<?php echo ' ('.$question_rate_c.'%)'; ?></b><span class="tooltiptext3"><b><?php echo $listC; ?></b></span></div></td>
<td height="40" width="25%" <?php if($question_answer == '4') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><div style="height: 100%; width: 100%;" class="tooltip3"><span class="brxsmall"></span><b>D<?php echo ' ('.$question_rate_d.'%)'; ?></b><span class="tooltiptext3"><b><?php echo $listD; ?></b></span></div></td>
</tr>
<?php
}
if($video == 1){
?>
<tr><td colspan="4" bgcolor="#a0b595">
<p><input id="btn_<?php echo $i; ?>" type="button" style="width: 30%;" value="VIDEO EXPLANATION" onclick="showVideo('<?php echo $i; ?>');"></p>
<div id="div_<?php echo $i; ?>" style="display:none;">
<p><video id="player_<?php echo $i; ?>" width="95%" controls preload="none" style="border: black solid 2px;">
<source src="./v/<?php echo $data_questions['question_paper_id'].$data_questions['question_number']; ?>.mp4" type="video/mp4">
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
<p><a href="#top"><b>Back to the top</b></a></p></div>
</body></html>