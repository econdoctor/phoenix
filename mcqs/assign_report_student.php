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
$sql = "SELECT user_type, user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id = '".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 2){
echo 'What are you doing here? This page is for students only.';
exit();}
$assign_id = $_GET['assign_id'];
if(empty($assign_id)){
echo 'Missing information about the assignment.';
exit();}
$sql_assign = "SELECT assign_name, assign_scramble, assign_release, assign_syllabus, assign_deadline  FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
$assign_name = $data_assign['assign_name'];
$assign_scramble = $data_assign['assign_scramble'];
$assign_release = $data_assign['assign_release'];
$s = $data_assign['assign_syllabus'];
$assign_deadline = date("Y-m-d H:i:s", strtotime($data_assign['assign_deadline']));
$sql_check = "SELECT assign_student_start, assign_student_end FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND student_id = '".$user_id."'";
$res_check = $mysqli -> query($sql_check);
$nr_check = mysqli_num_rows($res_check);
if($nr_check == 0){
echo 'You are not authorized to complete this assignment.';
exit();}
$data_check = mysqli_fetch_assoc($res_check);
if(($assign_release == '2' && $now < $assign_deadline) || ($assign_release == '1' && $now < $data_check['assign_student_end'])){
echo 'The report is not yet available';
exit();}
$sql_refresh = "SELECT question_id FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."' AND assign_question_refresh = '1'";
$res_refresh = $mysqli -> query($sql_refresh);
while($data_refresh = mysqli_fetch_assoc($res_refresh)){
$question_id = $data_refresh['question_id'];
$sql_a = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer = '1'";
$res_a = $mysqli -> query($sql_a);
$data_a = mysqli_fetch_assoc($res_a);
$answers_a = $data_a['COUNT(*)'];
$sql_b = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer = '2'";
$res_b = $mysqli -> query($sql_b);
$data_b = mysqli_fetch_assoc($res_b);
$answers_b = $data_b['COUNT(*)'];
$sql_c = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer = '3'";
$res_c = $mysqli -> query($sql_c);
$data_c = mysqli_fetch_assoc($res_c);
$answers_c = $data_c['COUNT(*)'];
$sql_d = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer = '4'";
$res_d = $mysqli -> query($sql_d);
$data_d = mysqli_fetch_assoc($res_d);
$answers_d = $data_d['COUNT(*)'];
$total_answers = $answers_a + $answers_b + $answers_c + $answers_d;
if($total_answers  >0){
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
if($assign_scramble == '1'){
$sql_get = "SELECT phoenix_assign_questions.question_id, topic_unit, topic_module, question_paper_id, question_answer, question_syllabus, question_number, assign_question_rate_a, assign_question_rate_b, assign_question_rate_c, assign_question_rate_d FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id INNER JOIN phoenix_topics ON phoenix_questions.question_topic_id = phoenix_topics.topic_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY SIN(phoenix_questions.question_id * $user_id) ASC";}
if($assign_scramble != '1'){
$sql_get = "SELECT phoenix_assign_questions.question_id, topic_unit, topic_module, question_paper_id, question_answer, question_syllabus, question_number, assign_question_rate_a, assign_question_rate_b, assign_question_rate_c, assign_question_rate_d FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id INNER JOIN phoenix_topics ON phoenix_questions.question_topic_id = phoenix_topics.topic_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_assign_questions.assign_question_number ASC";}
$res_get = $mysqli -> query($sql_get);
$count = mysqli_num_rows($res_get);
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
.tooltip2 {
position: relative;
display: inline-block;
}
.tooltip2 .tooltiptext2 {
visibility: hidden;
width: 300px;
background-color: black;
color: #fff;
text-align: center;
padding: 10px 10px 10px 10px;
bottom: 150%;
border-radius: 6px;
margin-left: -175px;
margin-top: -175px;
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
<p><input type="button" name="practice" value="PRACTICE" style="font-size: x-large; width: 45%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='practice.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 45%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='complete.php';"/>
</p></td></tr></tbody></table>
<p style="font-size: x-large"><b><?php echo strtoupper($assign_name); ?></b></p>
<?php
$i = 1;
while($data_get = mysqli_fetch_assoc($res_get)){
$video = 0;
$filename = './v/'.$data_get['question_paper_id'].$data_get['question_number'].'.mp4';
if(file_exists($filename)){
$video = 1;}
$question_id = $data_get['question_id'];
$unit_name = $data_get['topic_unit'];
$module_name = $data_get['topic_module'];
$question_paper = $data_get['question_paper_id'];
$answer_question = $data_get['question_answer'];
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
$sql8 = "SELECT answer FROM phoenix_answers WHERE user_id = '".$user_id."' AND question_id = '".$question_id."' AND assign_id = '".$assign_id."'";
$res8 = $mysqli -> query($sql8);
$data8 = mysqli_fetch_assoc($res8);
$answer_student = $data8['answer'];
if($data_get['question_syllabus'] != '3'){
$paper = 'P1';}
if($data_get['question_syllabus'] == '3'){
$paper = 'P3';}
$display_info = $paper.' | '.$year.'<br>'.$serie.'<br>Version '.$version.' | Question '.$data_get['question_number'].'<br><br>'.$unit_name.'<br><br>'.$module_name;
echo '<table width="72%" align="center" bgcolor="#000000"><tbody><tr>
<td colspan="4" height="40" bgcolor="#6a855c">
<table align="center" width="100%" bgcolor="6a855c"><tbody><tr>';
if($answer_student == '' || $answer_student == '0'){
echo '<td height="40" valign="middle" width="5%"><img src="cross.png" height="25" valign="middle" title="No answer"></td>';}
if($answer_student != '' && $answer_student != '0'){
echo '<td height="40" valign="middle" width="5%"><img src="tick.png" height="25" valign="middle" title="Answer saved"></td>';}
echo '<td height="40" width="90%"><b>QUESTION '.$i.'</b></td>
<td height="40" width="5%" valign="middle">
<div class="tooltip2"><img src="info.png" valign="middle" height="30">
<span class="tooltiptext2"><b>'.$display_info.'</b></span></div></td>
</tr></tbody></table>
</td></tr>
<tr>
<td colspan="4" bgcolor="#FFFFFF"><img src="q/'.$data_get['question_syllabus'].'/'.$data_get['question_paper_id'].'/'.$data_get['question_number'].'.png" width="95%"></td></tr>';
if($data_get['assign_question_rate_a'] == NULL && $data_get['assign_question_rate_b'] == NULL && $data_get['assign_question_rate_c'] == NULL && $data_get['assign_question_rate_d'] == NULL){
?>
<tr>
<td width="25%" height="40" <?php if(($answer_student == '1' && $answer_question == '1') || ($answer_student != '1' && $answer_question == '1')){ echo 'bgcolor = "#246648"'; } if($answer_student == '1' && $answer_question != '1'){ echo 'bgcolor = "#9C3039"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>A</b></td>
<td width="25%" height="40" <?php if(($answer_student == '2' && $answer_question == '2') || ($answer_student != '2' && $answer_question == '2')){ echo 'bgcolor = "#246648"'; } if($answer_student == '2' && $answer_question != '2'){ echo 'bgcolor = "#9C3039"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>B</b></td>
<td width="25%" height="40" <?php if(($answer_student == '3' && $answer_question == '3') || ($answer_student != '3' && $answer_question == '3')){ echo 'bgcolor = "#246648"'; } if($answer_student == '3' && $answer_question != '3'){ echo 'bgcolor = "#9C3039"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>C</b></td>
<td width="25%" height="40" <?php if(($answer_student == '4' && $answer_question == '4') || ($answer_student != '4' && $answer_question == '4')){ echo 'bgcolor = "#246648"'; } if($answer_student == '4' && $answer_question != '4'){ echo 'bgcolor = "#9C3039"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>D</b></td>
</tr>
<?php
}
if($data_get['assign_question_rate_a'] != NULL && $data_get['assign_question_rate_b'] != NULL && $data_get['assign_question_rate_c'] != NULL && $data_get['assign_question_rate_d'] != NULL){
$question_rate_a = round($data_get['assign_question_rate_a'], 0);
$question_rate_b = round($data_get['assign_question_rate_b'], 0);
$question_rate_c = round($data_get['assign_question_rate_c'], 0);
$question_rate_d = round($data_get['assign_question_rate_d'], 0);
?>
<tr>
<td height="40" width="25%" <?php if(($answer_student == '1' && $answer_question == '1') || ($answer_student != '1' && $answer_question == '1')){ echo 'bgcolor = "#246648"'; } if($answer_student == '1' && $answer_question != '1'){ echo 'bgcolor = "#9C3039"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>A<?php echo ' ('.$question_rate_a.'%)'; ?></b></td>
<td height="40" width="25%" <?php if(($answer_student == '2' && $answer_question == '2') || ($answer_student != '2' && $answer_question == '2')){ echo 'bgcolor = "#246648"'; } if($answer_student == '2' && $answer_question != '2'){ echo 'bgcolor = "#9C3039"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>B<?php echo ' ('.$question_rate_b.'%)'; ?></b></td>
<td height="40" width="25%" <?php if(($answer_student == '3' && $answer_question == '3') || ($answer_student != '3' && $answer_question == '3')){ echo 'bgcolor = "#246648"'; } if($answer_student == '3' && $answer_question != '3'){ echo 'bgcolor = "#9C3039"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>C<?php echo ' ('.$question_rate_c.'%)'; ?></b></td>
<td height="40" width="25%" <?php if(($answer_student == '4' && $answer_question == '4') || ($answer_student != '4' && $answer_question == '4')){ echo 'bgcolor = "#246648"'; } if($answer_student == '4' && $answer_question != '4'){ echo 'bgcolor = "#9C3039"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>D<?php echo ' ('.$question_rate_d.'%)'; ?></b></td>
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
echo '</tbody></table><br><br>';
$i++;}
?>
<a href="#top"><b>Back to the top</b></a>
<p>&nbsp;</p>
</body></html>