<?php
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
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
$paper_id = $_GET['paper_id'];
$member_id = $_GET['member_id'];
$s = $_GET['s'];
if(empty($paper_id) || empty($member_id) || empty($s)){
echo 'Missing information';
exit();}
$sql_check = "SELECT user_teacher, user_title, user_first_name, user_last_name, user_syllabus FROM phoenix_users WHERE user_id = '".$member_id."'";
$res_check = $mysqli -> query($sql_check);
$data_check = mysqli_fetch_assoc($res_check);
if($user_id != $data_check['user_teacher']){
echo 'You are not authorized to manage this student';
exit();}
$sql2 = "SELECT paper_id, paper_year, paper_serie, paper_version FROM phoenix_papers WHERE paper_id = '".$paper_id."'";
$res2 = $mysqli -> query($sql2);
$data2 = mysqli_fetch_assoc($res2);
if($s == '1'){
$s_text = 'IGCSE';}
if($s == '2'){
$s_text = 'AS Level';}
if($s == '3'){
$s_text = 'A Level';}
$paper_serie = $data2['paper_serie'];
if($paper_serie == 1){
$paper_serie_text = "m - February / March";}
if($paper_serie == 2){
$paper_serie_text = "s - May / June";}
if($paper_serie == 3){
$paper_serie_text = "w - October / November";}
if($paper_serie == 4){
$paper_serie_text = "y - Specimen";}
$paper_version = $data2['paper_version'];
if($paper_version == 0){
$paper_version = "/";}
$sql3 = "SELECT * FROM phoenix_questions INNER JOIN phoenix_topics ON phoenix_questions.question_topic_id = phoenix_topics.topic_id WHERE question_paper_id = '".$paper_id."'";
$res3 = $mysqli -> query($sql3);
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
<p><input type="button" name="browse" value="BROWSE" style="font-size: x-large; width: 30%; background-color: #ffc000;  color: #3b2c00; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='browse.php';"/>&nbsp;&nbsp;
<input type="button" name="manage" value="MANAGE" style="font-size: x-large; width: 30%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='manage.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 30%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='assign.php';"/>
</td></tr></tbody></table>
<p style="font-size: x-large"><b><?php echo strtoupper($data_check['user_title'].' '.$data_check['user_first_name'].' '.$data_check['user_last_name']); ?></b></p>
<p><input type="button" name="practice" value="PRACTICE" style="width: 15%;" onclick="document.location.href='user_view_practice.php?member_id=<?php echo $member_id; ?>&s=<?php echo $data_check['user_syllabus']; ?>';">&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="width: 15%;" onclick="document.location.href='user_view_assignments.php?member_id=<?php echo $member_id; ?>'">&nbsp;&nbsp;
<input type="button" name="permissions" value="PERMISSIONS" style="width: 15%;" onclick="document.location.href='user_view_permissions.php?member_id=<?php echo $member_id; ?>'"></p>
<span class="brsmall"></span>
<p style="font-size: x-large"><b>PAPER REFERENCE</b></p>
<table width="67%" align="center" bgcolor="#000000"><tbody><tr>
<td height="40" bgcolor="647d57"><b>COURSE</b></td>
<td height="40" bgcolor="647d57"><b>YEAR</b></td>
<td height="40" bgcolor="647d57"><b>SERIE</b></td>
<td height="40" bgcolor="647d57"><b>VERSION</b></td>
</tr><tr>
<td height="40" bgcolor="769467"><b><?php echo strtoupper($s_text); ?></b></td>
<td height="40" bgcolor="769467"><?php echo $data2['paper_year']; ?></td>
<td height="40" bgcolor="769467"><?php echo $paper_serie_text; ?></td>
<td height="40" bgcolor="769467"><?php echo $paper_version; ?></td>
</tr></tbody></table>
<p style="font-size: x-large;"><b>QUESTIONS</b></p>
<?php
while($data3 = mysqli_fetch_assoc($res3)){
$video = 0;
$filename = './v/'.$data2['paper_id'].$data3['question_number'].'.mp4';
if(file_exists($filename)){
$video = 1;}
$question_id = $data3['question_id'];
$answer_question = $data3['question_answer'];
if($answer_question == '0'){
?>
<table width="72%" align="center" bgcolor="#000000">
<tbody>
<tr valign="middle">
<td height="40" colspan ="4" bgcolor="#647d57" valign="middle">
<table align="center" bgcolor="647d57" width="100%">
<tbody><tr valign="middle">
<td width="5%" valign="middle">&nbsp;</td>
<td width="90%" align="center"><b>QUESTION <?php echo $data3['question_number']; ?></b></td>
<td width="5%" valign="middle">&nbsp;</td>
</tr></tbody></table></td></tr>
<tr><td height="40" bgcolor="#a0b595"><b>This question was removed</b></td>
</tr></tbody></table><br><br>
<?php
}
if($answer_question != '0'){
$topic_info = $data3['topic_unit'].'<br><br>'.$data3['topic_module'];
echo '<table width="72%" align="center" bgcolor="#000000"><tbody>';
echo '<tr valign="middle"><td height="40" colspan ="4" bgcolor="#647d57" valign="middle">
<table align="center" bgcolor="647d57" width="100%">
<tbody><tr valign="middle">';
$sql8 = "SELECT answer FROM phoenix_answers WHERE user_id = '".$member_id."' AND question_id = '".$question_id."' AND answer_type = '1'";
$res8 = $mysqli -> query($sql8);
$num_ans = mysqli_num_rows($res8);
if($num_ans == 0){
echo '<td height="40" valign="middle" width="5%"><img src="cross.png" height="25" valign="middle" title="No answer"></td>';}
if($num_ans > 0){
$data8 = mysqli_fetch_assoc($res8);
$answer_student = $data8['answer'];
echo '<td height="40" valign="middle" width="5%"><img src="tick.png" height="25" valign="middle" title="Answer saved"></td>';}
echo  '<td width="90%" align="center"><b>QUESTION '.$data3['question_number'].'</b></td>
<td width="5%" valign="middle">
<div class="tooltip2"><img src="info.png" valign="middle" height="30">
<span class="tooltiptext2"><b>'.$topic_info.'</b></span></div></td>
</tr></tbody></table></td></tr>
<tr><td colspan = "4" bgcolor="#FFFFFF"><img src="q/'.$s.'/'.$paper_id.'/'.$data3['question_number'].'.png" width="95%"></td></tr>';
if($num_ans > 0){
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
if($num_ans == 0){
?>
<tr>
<td width="25%" height="40" bgcolor = "#769467"><b>A</b></td>
<td width="25%" height="40" bgcolor = "#769467"><b>B</b></td>
<td width="25%" height="40" bgcolor = "#769467"><b>C</b></td>
<td width="25%" height="40" bgcolor = "#769467"><b>D</b></td>
</tr>
<?php
}
if($video == 1){
?>
<tr><td colspan="4" bgcolor="#a0b595">
<p><input id="btn_<?php echo $data3['question_number']; ?>" type="button" style="width: 30%;" value="VIDEO EXPLANATION" onclick="showVideo('<?php echo $data3['question_number']; ?>');"></p>
<div id="video_<?php echo $data3['question_number']; ?>" style="display:none;">
<p><video id="player_<?php echo $data3['question_number']; ?>" width="95%" controls preload="none" style="border: black solid 2px;">
<source src="./v/<?php echo $data2['paper_id'].$data3['question_number']; ?>.mp4" type="video/mp4">
Your browser does not support the video tag.
</video>
</div></p>
</td></tr>
<?php
}
echo '</tbody></table><br><br>';}}
?>
<p><a href="#top"><b>Back to the top</b></a></p>
</body></html>