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
$assign_id = $_GET['assign_id'];
if(!isset($assign_id)){
echo 'Missing information';
exit();}
$sql_as = "SELECT * FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_as = $mysqli -> query($sql_as);
$data_as = mysqli_fetch_assoc($res_as);
$nr_questions = $data_as['assign_nq'];
$assign_name = $data_as['assign_name'];
$assign_start = date("Y-m-d H:i:s", strtotime($data_as['assign_start']));
$assign_deadline = date("Y-m-d H:i:s", strtotime($data_as['assign_deadline']));
$assign_syllabus = $data_as['assign_syllabus'];
$assign_teacher = $data_as['assign_teacher'];
$assign_game_status = $data_as['assign_game_status'];
if($assign_teacher != $user_id){
echo 'You are not authorized to manage this assignment.';
exit();}
$assign_type = $data_as['assign_type'];
if($assign_type == '4'){
echo 'Not available for offline assignments';
exit();}
$min_a = $data_as['assign_a'];
$min_b = $data_as['assign_b'];
$min_c = $data_as['assign_c'];
$min_d = $data_as['assign_d'];
$min_e = $data_as['assign_e'];
if($assign_syllabus == '1'){
$min_f = $data_as['assign_f'];
$min_g = $data_as['assign_g'];}
$sql_refresh = "SELECT student_id FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND student_refresh = '1'";
$res_refresh = $mysqli -> query($sql_refresh);
while($data_refresh = mysqli_fetch_assoc($res_refresh)){
$student_id = $data_refresh['student_id'];
$sql1 = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND user_id = '".$student_id."'";
$res1 = $mysqli -> query($sql1);
$data1 = mysqli_fetch_assoc($res1);
$num_answers = $data1['COUNT(*)'];
$sql3 = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND user_id = '".$student_id."' AND answer_valid = '1'";
$res3 = $mysqli -> query($sql3);
$data3 = mysqli_fetch_assoc($res3);
$num_correct = $data3['COUNT(*)'];
$sql4 = "UPDATE phoenix_assign_users SET num_answers = '".$num_answers."', score = '".$num_correct."', student_refresh = '0' WHERE assign_id = '".$assign_id."' AND student_id = '".$student_id."'";
$res4 = $mysqli -> query($sql4);}
$sql_null = "SELECT COUNT(*) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND student_rank IS NULL";
$res_null = $mysqli -> query($sql_null);
$data_null = mysqli_fetch_assoc($res_null);
$num_null = $data_null['COUNT(*)'];
if((($assign_type != '5' && $now > $assign_deadline) || ($assign_type == '5' && $assign_game_status == 4)) && $num_null > 0){
$sql_list = "SELECT student_id, score FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' ORDER BY score DESC";
$res_list = $mysqli -> query($sql_list);
$rank = 0;
$previous = '';
$same = 0;
while($data_list = mysqli_fetch_assoc($res_list)){
$stu_id = $data_list['student_id'];
$stu_score = $data_list['score'];
if($stu_score != $previous){
$rank = $rank + $same + 1;
$same = 0;}
if($stu_score == $previous){
$same++;}
$previous = $stu_score;
$sql_up = "UPDATE phoenix_assign_users SET student_rank = '".$rank."' WHERE assign_id = '".$assign_id."' AND student_id = '".$stu_id."'";
$res_up = $mysqli -> query($sql_up);}}

function is_selected($db_value, $html_value){
if($db_value == $html_value){
return "selected";}
else {
return "";}}
$order = $_POST['order'];
if(!isset($order)){
$order = "default";}
if($assign_type != '5'){
if($order == "default"){
$sql_students = "SELECT user_id, user_title, user_first_name, user_last_name, score, student_rank, num_answers FROM phoenix_users INNER JOIN phoenix_assign_users ON phoenix_users.user_id = phoenix_assign_users.student_id WHERE phoenix_assign_users.assign_id = '".$assign_id."' ORDER BY phoenix_users.user_id ASC";}
if($now > $assign_start && $now < $assign_deadline && $order == "status"){
$sql_students = "SELECT user_id, user_title, user_first_name, user_last_name, score, student_rank, num_answers FROM phoenix_users INNER JOIN phoenix_assign_users ON phoenix_users.user_id = phoenix_assign_users.student_id WHERE phoenix_assign_users.assign_id = '".$assign_id."' ORDER BY phoenix_assign_users.num_answers DESC";}
if($now > $assign_deadline && $order == "score_top"){
$sql_students = "SELECT user_id, user_title, user_first_name, user_last_name, score, student_rank, num_answers FROM phoenix_users INNER JOIN phoenix_assign_users ON phoenix_users.user_id = phoenix_assign_users.student_id WHERE phoenix_assign_users.assign_id = '".$assign_id."' ORDER BY phoenix_assign_users.score DESC";}
if($now > $assign_deadline && $order == "score_bottom"){
$sql_students = "SELECT user_id, user_title, user_first_name, user_last_name, score, student_rank, num_answers FROM phoenix_users INNER JOIN phoenix_assign_users ON phoenix_users.user_id = phoenix_assign_users.student_id WHERE phoenix_assign_users.assign_id = '".$assign_id."' ORDER BY phoenix_assign_users.score ASC";}
$res_students = $mysqli -> query($sql_students);
$nr_students = mysqli_num_rows($res_students);
if($now > $assign_deadline){
$sql_stat = "SELECT MIN(score), MAX(score), AVG(score) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."'";
$res_stat = $mysqli -> query($sql_stat);
$data_stat = mysqli_fetch_assoc($res_stat);
$min_score = $data_stat['MIN(score)'];
$min_percent = round($min_score / $nr_questions * 100, 0);
$max_score = $data_stat['MAX(score)'];
$max_percent = round($max_score / $nr_questions * 100, 0);
$avg_score = $data_stat['AVG(score)'];
$avg_percent = round($avg_score / $nr_questions * 100, 0);}}
if($assign_type == '5'){
if($order == "default"){
$sql_students = "SELECT user_id, user_title, user_first_name, user_last_name, score, student_rank, num_answers FROM phoenix_users INNER JOIN phoenix_assign_users ON phoenix_users.user_id = phoenix_assign_users.student_id WHERE phoenix_assign_users.assign_id = '".$assign_id."' ORDER BY phoenix_users.user_id ASC";}
if($assign_game_status != 0 && $assign_game_status != 4 && $order == "status"){
$sql_students = "SELECT user_id, user_title, user_first_name, user_last_name, score, student_rank, num_answers FROM phoenix_users INNER JOIN phoenix_assign_users ON phoenix_users.user_id = phoenix_assign_users.student_id WHERE phoenix_assign_users.assign_id = '".$assign_id."' ORDER BY phoenix_assign_users.num_answers DESC";}
if($assign_game_status == 4  && $order == "score_top"){
$sql_students = "SELECT user_id, user_title, user_first_name, user_last_name, score, student_rank, num_answers FROM phoenix_users INNER JOIN phoenix_assign_users ON phoenix_users.user_id = phoenix_assign_users.student_id WHERE phoenix_assign_users.assign_id = '".$assign_id."' ORDER BY phoenix_assign_users.score DESC";}
if($assign_game_status == 4 && $order == "score_bottom"){
$sql_students = "SELECT user_id, user_title, user_first_name, user_last_name, score, student_rank, num_answers FROM phoenix_users INNER JOIN phoenix_assign_users ON phoenix_users.user_id = phoenix_assign_users.student_id WHERE phoenix_assign_users.assign_id = '".$assign_id."' ORDER BY phoenix_assign_users.score ASC";}
$res_students = $mysqli -> query($sql_students);
$nr_students = mysqli_num_rows($res_students);
if($assign_game_status == 4){
$sql_stat = "SELECT MIN(score), MAX(score), AVG(score) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."'";
$res_stat = $mysqli -> query($sql_stat);
$data_stat = mysqli_fetch_assoc($res_stat);
$min_score = $data_stat['MIN(score)'];
$min_percent = round($min_score / $nr_questions * 100, 0);
$max_score = $data_stat['MAX(score)'];
$max_percent = round($max_score / $nr_questions * 100, 0);
$avg_score = $data_stat['AVG(score)'];
$avg_percent = round($avg_score / $nr_questions * 100, 0);}}
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
vertical-align:middle
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
.brsmall {
display: block;
margin-bottom: 0.5em;
}
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script language="JavaScript">
function preload(id){
var x = document.getElementById(id)
document.getElementById("preload").style.display = "block";
document.getElementById("content").style.display = "none";
document.getElementById("students").setAttribute('style', 'width: 15%;')
document.getElementById("students").setAttribute('onclick', "document.location.href='assign_students_stats.php?assign_id=<?php echo $assign_id; ?>'");
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
<p style="font-size: x-large"><b><?php echo strtoupper($assign_name); ?></b></p>
<p><input type="button" id="details" name="details" value="DETAILS" style="width: 15%;" onclick="preload('details');">&nbsp;&nbsp;
<input type="button" id="students" name="students" value="STUDENTS" style="width: 15%; cursor: default; background-color: black;">&nbsp;&nbsp;
<input type="button" id="questions" name="questions" value="QUESTIONS" style="width: 15%;" onclick="preload('questions');"></p>
<span class="brmedium"></span>
<div id="preload" style="display: none;">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><b style="font-size: larger; color: #033909;">LOADING...</b><br>
<img src="preload.gif" width="100"></p>
</div>
<div id="content" style="display: block;">
<?php
if(($assign_type != '5' && $now > $assign_deadline) || ($assign_type == '5' && $assign_game_status == 4)){
?>
<table align="center" width="33.33%" bgcolor = "#000000"><tbody><tr>
<td height="40" width="33.33%" bgcolor="#647d57"><b>AVERAGE</b></td>
<td height="40" width="33.33%" bgcolor="#647d57"><b>MAXIMUM</b></td>
<td height="40" width="33.33%" bgcolor="#647d57"><b>MINIMUM</b></td>
</tr><tr>
<td height="40" bgcolor="#769467"><b style="color: #0D3151"><?php echo $avg_percent; ?>%</b></td>
<td height="40" bgcolor="#769467"><b style="color: #033909"><?php echo $max_percent; ?>%</b></td>
<td height="40" bgcolor="#769467"><b style="color: #820000"><?php echo $min_percent; ?>%</b></td>
</tr></tbody></table><br>
<?php
}
?>
<p><b><span style="color: #820000;"><?php echo $nr_students; ?></span> student(s) found</b></p>
<?php
if($assign_type != '5' || ($assign_type == '5' && $assign_game_status == 0)){
?>
<input type="button" name="update_students" value="ADD / REMOVE STUDENTS" style="width: 25%;" onclick="document.location.href='update_students.php?assign_id=<?php echo $assign_id; ?>';"></p>
<?php
}
?>
<form method='post' action="assign_students_stats.php?assign_id=<?php echo $assign_id; ?>">
<p><b>Sort by:</b>
<span class="brsmall"></span>
<select name="order" onchange="this.form.submit();" style="width: 15%"><optgroup>
<option value="default" <?php echo is_selected("default", $order); ?>>Default</option>
<?php
if(($assign_type != '5' && $now > $assign_start && $now < $assign_deadline) || ($assign_type == '5' && $assign_game_status != 0 && $assign_game_status != 4)){
?>
<option value="status" <?php echo is_selected("status", $order); ?>>Progress</option>
<?php
}
if(($assign_type != '5' && $now > $assign_deadline) || ($assign_type == '5' && $assign_game_status == 4)){
?>
<option value="score_top" <?php echo is_selected("score_top", $order); ?>>Score (Hgh to Low)</option>
<option value="score_bottom" <?php echo is_selected("score_bottom", $order); ?>>Score (Low to High)</option>
<?php
}
?>
</optgroup></select></form>
<table width="50%" align="center" bgcolor="#000000"><tbody><tr>
<?php
if(($assign_type != '5' && $now > $assign_deadline) || ($assign_type == '5' && $assign_game_status == 4)){
echo '<td bgcolor="647d57" width="15%" height="50"><b>RANK</b></td>
<td bgcolor="647d57" width="35%" height="50"><b>NAME</b></td>
<td bgcolor="647d57" width="35%" height="50"><b>STATUS</b></td>
<td bgcolor="647d57" width="15%" height="50"><b>REPORT</b></td>';}
if(($assign_type != '5' && $now < $assign_deadline) || ($assign_type == '5' && $assign_game_status != 4)){
echo '<td bgcolor="647d57" width="40%" height="50"><b>NAME</b></td>
<td bgcolor="647d57" width="40%" height="50"><b>STATUS</b></td>
<td bgcolor="647d57" width="20%" height="50"><b>REPORT</b></td>';}
?>
</tr>
<?php
$color = 1;
while($data_students = mysqli_fetch_assoc($res_students)){
$student_id = $data_students['user_id'];
$student_title = $data_students['user_title'];
$student_first_name = $data_students['user_first_name'];
$student_last_name = $data_students['user_last_name'];
$score = $data_students['score'];
$rank = $data_students['student_rank'];
$percent = round($score / $nr_questions * 100, 0);
$nr_answers = $data_students['num_answers'];
$nr_answers_percent = round($nr_answers / $nr_questions * 100, 0);
if($percent >= $min_a){
$letter = "A";}
if($percent < $min_a && $percent >= $min_b){
$letter = "B";}
if($percent < $min_b && $percent >= $min_c){
$letter = "C";}
if($percent < $min_c && $percent >= $min_d){
$letter = "D";}
if($percent < $min_d && $percent >= $min_e){
$letter = "E";}
if($assign_syllabus != '1' && $percent < $min_e){
$letter = "U";}
if($assign_syllabus == '1'){
if($percent < $min_e && $percent >= $min_f){
$letter = "F";}
if($percent < $min_f && $percent >= $min_g){
$letter = "G";}
if($percent < $min_g){
$letter = "U";}}
$sql_end = "SELECT assign_student_end FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND student_id = '".$student_id."'";
$res_end = $mysqli -> query($sql_end);
$data_end = mysqli_fetch_assoc($res_end);
$assign_student_end = $data_end['assign_student_end'];
if($color == 1){
echo '<tr>';
if(($assign_type != '5' && $now > $assign_deadline) || ($assign_type == '5' && $assign_game_status == 4)){
echo '<td height="40" bgcolor="769467"><b style="font-size: x-large;">'.$rank.'</b></td>';}
echo '<td height="40" bgcolor="769467"><a href="user_view.php?member_id='.$student_id.'">'.$student_title.' '.$student_first_name.' '.$student_last_name.'</a></td>
<td height="40" bgcolor="769467"><p>';
if(($assign_type != '5' && $now < $assign_start) || ($assign_type == '5' && $assign_game_status == 0)){
echo '<b>NOT STARTED YET</b>';}
if(($assign_type != '5' && $now > $assign_start && $now < $assign_student_end) || ($assign_type == '5' && $assign_game_status != 0 && $assign_game_status != 4)){
echo '<b style="color: #0D3151">IN PROGRESS</b><br><b>'.$nr_answers_percent.'%</b>';}
if(($assign_type != '5' && $now > $assign_student_end) || ($assign_type == '5' && $assign_game_status == 4)){
echo '<b style="color: #033909">FINISHED</b><br>
<b>'.$percent.'% - '.$letter.'</b>';}
echo '</p></td>
<td height="40" bgcolor="769467">';
if(($assign_type != '5' && $now < $assign_start) || ($assign_type == '5' && $assign_game_status == 0)){
echo '<img src="cross.png" width="30" height="30" class="mid">';}
if(($assign_type != '5' && $now > $assign_start && $now < $assign_student_end) || ($assign_type == '5' && $assign_game_status != 0 && $assign_game_status != 4)){
echo '<img src="cross.png" width="30" height="30" class="mid">';}
if(($assign_type != '5' && $now > $assign_student_end) || ($assign_type == '5' && $assign_game_status == 4)){
echo '<a href="assign_report_teacher.php?assign_id='.$assign_id.'&student_id='.$student_id.'"><img src="mg.png" width="30" height="30" class="mid" title="View"></a>';}
echo '</td></tr>';
$color = 2;}
else {
echo '<tr>';
if(($assign_type != '5' && $now > $assign_deadline) || ($assign_type == '5' && $assign_game_status == 4)){
echo '<td height="40" bgcolor="A0B595"><b style="font-size: x-large;">'.$rank.'</b></td>';}
echo '<td height="40" bgcolor="A0B595"><a href="user_view.php?member_id='.$student_id.'">'.$student_title.' '.$student_first_name.' '.$student_last_name.'</a></td>
<td height="40" bgcolor="A0B595"><p>';
if(($assign_type != '5' && $now < $assign_start) || ($assign_type == '5' && $assign_game_status == 0)){
echo '<b>NOT STARTED YET</b>';}
if(($assign_type != '5' && $now > $assign_start && $now < $assign_student_end) || ($assign_type == '5' && $assign_game_status != 0 && $assign_game_status != 4)){
echo '<b style="color: #0D3151">IN PROGRESS</b><br><b>'.$nr_answers_percent.'%</b>';}
if(($assign_type != '5' && $now > $assign_student_end) || ($assign_type == '5' && $assign_game_status == 4)){
echo '<b style="color: #033909">FINISHED</b><br>
<b>'.$percent.'% - '.$letter.'</b>';}
echo '</p></td>
<td height="40" bgcolor="A0B595">';
if(($assign_type != '5' && $now < $assign_start) || ($assign_type == '5' && $assign_game_status == 0)){
echo '<img src="cross.png" width="30" height="30" class="mid">';}
if(($assign_type != '5' && $now > $assign_start && $now < $assign_student_end) || ($assign_type == '5' && $assign_game_status != 0 && $assign_game_status != 4)){
echo '<img src="cross.png" width="30" height="30" class="mid">';}
if(($assign_type != '5' && $now > $assign_student_end) || ($assign_type == '5' && $assign_game_status == 4)){
echo '<a href="assign_report_teacher.php?assign_id='.$assign_id.'&student_id='.$student_id.'"><img src="mg.png" width="30" height="30" class="mid" title="View"></a>';}
echo '</td></tr>';
$color = 1;}}
?>
</tbody></table>
<p><a href="#top"><b>Back to the top</b></a></p>
<p>&nbsp;</p>
</div></body></html>