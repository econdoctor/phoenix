<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];}
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$time = $_POST['time'];
if(!isset($time)){
$time = 1440;}
$limit = date('Y-m-d H:i:s', strtotime(''.$now.' - '.$time.' minutes'));
$course = $_POST['course'];
if(!isset($course)){
$course = 'all';}
$type = $_POST['type'];
if(!isset($type)){
$type = 'all';}
$validity = $_POST['validity'];
if(!isset($validity)){
$validity = 'all';}
$stu = $_POST['students'];
if(!isset($stu)){
$stu = 'all';}
$order = $_POST['order'];
if(!isset($order)){
$order = 'time';}
$ar = $_POST['ar'];
if(!isset($ar)){
$ar = 0;}
$sql = "SELECT * FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_tz = $data['user_timezone'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
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
.tooltip {
position: relative;
display: inline-block;
}
.tooltip .tooltiptext {
width: 800px;
bottom: 135%;
left: 50%;
margin-left: -400px;
visibility: hidden;
background-color: black;
color: #fff;
text-align: center;
padding: 0px 0;
border-radius: 6px;
position: absolute;
z-index: 1;
}
.tooltip .tooltiptext::after {
content: " ";
position: absolute;
top: 100%;
left: 50%;
margin-left: -5px;
border-width: 5px;
border-style: solid;
border-color: black transparent transparent transparent;
}
.tooltip:hover .tooltiptext {
visibility: visible;
}
.mid {
vertical-align:middle
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
bottom: 145%;
border-radius: 6px;
margin-left: -140px;
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
</style>
<?php
if($ar == 1){
echo '<script type="text/javascript">
window.onload=function(){
window.setTimeout(function() { document.main.submit(); }, 10000);
};</script>';}
?>
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
<p style="font-size: x-large"><b>LIVE</b></p><p>
<form method="post" action="live.php" id="main" name="main">
<select name="time" onchange="this.form.submit();">
<optgroup>
<option value="60" <?php if($time == 60) { echo 'selected'; } ?>>Last hour</option>
<option value="1440" <?php if($time == 1440) { echo 'selected'; } ?>>Last day</option>
<option value="10080" <?php if($time == 10080) { echo 'selected'; } ?>>Last week</option>
</optgroup></select>
&nbsp;&nbsp;<select name="students" onchange="this.form.submit();">
<optgroup>
<option value="all" <?php if($stu == 'all') { echo 'selected'; } ?>>All students</option>
<?php
$sql_gs = "SELECT DISTINCT user_id FROM phoenix_answers INNER JOIN phoenix_questions ON phoenix_answers.question_id = phoenix_questions.question_id WHERE phoenix_answers.user_id IN (SELECT user_id FROM phoenix_users WHERE user_teacher = '".$user_id."') AND phoenix_answers.answer_date >= '".$limit."' AND phoenix_questions.question_new_syllabus = (CASE WHEN '".$course."' <> 'all' THEN '".$course."' ELSE question_new_syllabus END) AND phoenix_answers.answer_type = (CASE WHEN '".$type."' <> 'all' THEN '".$type."' ELSE answer_type END) AND phoenix_answers.answer_valid = (CASE WHEN '".$validity."' = 'incorrect' THEN '0' WHEN '".$validity."' = 'correct' THEN '1' ELSE answer_valid END) ORDER BY phoenix_answers.user_id ASC";
$res_gs = $mysqli -> query($sql_gs);
while($data_gs = mysqli_fetch_assoc($res_gs)){
$student_id = $data_gs['user_id'];
$sql_gsn = "SELECT * FROM phoenix_users WHERE user_id = '".$student_id."'";
$res_gsn = $mysqli -> query($sql_gsn);
$data_gsn = mysqli_fetch_assoc($res_gsn);
$st = $data_gsn['user_title'];
$sfn = $data_gsn['user_first_name'];
$sln = $data_gsn['user_last_name'];
if($stu == $student_id){
echo '<option value="'.$student_id.'" selected>'.$st.' '.$sfn.' '.$sln.'</option>';}
if($stu != $student_id){
echo '<option value="'.$student_id.'" >'.$st.' '.$sfn.' '.$sln.'</option>';}}
?>
</optgroup></select>
&nbsp;&nbsp;<select name="course" onchange="this.form.submit();">
<optgroup>
<option value="all" <?php if($course == 'all') { echo 'selected'; } ?>>All courses</option>
<option value="1" <?php if($course == '1') { echo 'selected'; } ?>>IGCSE</option>
<option value="2" <?php if($course == '2') { echo 'selected'; } ?>>AS</option>
<option value="3" <?php if($course == '3') { echo 'selected'; } ?>>A2</option>
</optgroup></select>
&nbsp;&nbsp;<select name="type" onchange="this.form.submit();">
<optgroup>
<option value="all" <?php if($type == 'all') { echo 'selected'; } ?>>All types</option>
<option value="3" <?php if($type == '3') { echo 'selected'; } ?>>Assignments (A)</option>
<option value="1" <?php if($type == '1') { echo 'selected'; } ?>>Papers (P)</option>
<option value="2" <?php if($type == '2') { echo 'selected'; } ?>>Topics (T)</option>
</optgroup></select>
&nbsp;&nbsp;<select name="validity" onchange="this.form.submit();">
<optgroup>
<option value="all" <?php if($validity == 'all') { echo 'selected'; } ?>>All validity</option>
<option value="incorrect" <?php if($validity == 'incorrect') { echo 'selected'; } ?>>Incorrect</option>
<option value="correct" <?php if($validity == 'correct') { echo 'selected'; } ?>>Correct</option>
</optgroup></select>
&nbsp;&nbsp;<select name="order" onchange="this.form.submit();">
<optgroup>
<option value="time" <?php if($order == 'time') { echo 'selected'; } ?>>Sort by time</option>
<option value="students" <?php if($order == 'students') { echo 'selected'; } ?>>Sort by students</option>
<option value="course" <?php if($order == 'course') { echo 'selected'; } ?>>Sort by course</option>
<option value="type" <?php if($order == 'type') { echo 'selected'; } ?>>Sort by type</option>
<option value="validity" <?php if($order == 'validity') { echo 'selected'; } ?>>Sort by validity</option>
</optgroup></select></b>
<p><input type="hidden" name="ar" value="0">
<input type="checkbox" style="transform: scale(1.5);" name="ar" value="1" onchange="this.form.submit();" <?php if($ar == 1) { echo 'checked'; } ?>>&nbsp;<b>Auto refresh</b></p>
<p><input type="submit" value="REFRESH"></p>
</form>
<?php
if($order == 'time'){
$sql_list = "SELECT * FROM phoenix_answers INNER JOIN phoenix_questions ON phoenix_answers.question_id = phoenix_questions.question_id WHERE phoenix_answers.answer <> '0' AND phoenix_answers.user_id IN (SELECT user_id FROM phoenix_users WHERE user_teacher = '".$user_id."') AND phoenix_answers.answer_date >= '".$limit."' AND phoenix_answers.user_id = (CASE WHEN '".$stu."' <> 'all' THEN '".$stu."' ELSE user_id END) AND phoenix_questions.question_new_syllabus = (CASE WHEN '".$course."' <> 'all' THEN '".$course."' ELSE question_new_syllabus END) AND phoenix_answers.answer_type = (CASE WHEN '".$type."' <> 'all' THEN '".$type."' ELSE answer_type END) AND phoenix_answers.answer_valid = (CASE WHEN '".$validity."' = 'incorrect' THEN '0' WHEN '".$validity."' = 'correct' THEN '1' ELSE answer_valid END) ORDER BY phoenix_answers.answer_date DESC";}
if($order == 'students'){
$sql_list = "SELECT * FROM phoenix_answers INNER JOIN phoenix_questions ON phoenix_answers.question_id = phoenix_questions.question_id WHERE phoenix_answers.answer <> '0' AND phoenix_answers.user_id IN (SELECT user_id FROM phoenix_users WHERE user_teacher = '".$user_id."') AND phoenix_answers.answer_date >= '".$limit."' AND phoenix_answers.user_id = (CASE WHEN '".$stu."' <> 'all' THEN '".$stu."' ELSE user_id END) AND phoenix_questions.question_new_syllabus = (CASE WHEN '".$course."' <> 'all' THEN '".$course."' ELSE question_new_syllabus END) AND phoenix_answers.answer_type = (CASE WHEN '".$type."' <> 'all' THEN '".$type."' ELSE answer_type END) AND phoenix_answers.answer_valid = (CASE WHEN '".$validity."' = 'incorrect' THEN '0' WHEN '".$validity."' = 'correct' THEN '1' ELSE answer_valid END) ORDER BY phoenix_answers.user_id ASC, phoenix_answers.answer_date DESC ";}
if($order == 'type'){
$sql_list = "SELECT * FROM phoenix_answers INNER JOIN phoenix_questions ON phoenix_answers.question_id = phoenix_questions.question_id WHERE phoenix_answers.answer <> '0' AND phoenix_answers.user_id IN (SELECT user_id FROM phoenix_users WHERE user_teacher = '".$user_id."') AND phoenix_answers.answer_date >= '".$limit."' AND phoenix_answers.user_id = (CASE WHEN '".$stu."' <> 'all' THEN '".$stu."' ELSE user_id END) AND phoenix_questions.question_new_syllabus = (CASE WHEN '".$course."' <> 'all' THEN '".$course."' ELSE question_new_syllabus END) AND phoenix_answers.answer_type = (CASE WHEN '".$type."' <> 'all' THEN '".$type."' ELSE answer_type END) AND phoenix_answers.answer_valid = (CASE WHEN '".$validity."' = 'incorrect' THEN '0' WHEN '".$validity."' = 'correct' THEN '1' ELSE answer_valid END) ORDER BY phoenix_answers.answer_type ASC, phoenix_answers.answer_date DESC";}
if($order == 'course'){
$sql_list = "SELECT * FROM phoenix_answers INNER JOIN phoenix_questions ON phoenix_answers.question_id = phoenix_questions.question_id WHERE phoenix_answers.answer <> '0' AND phoenix_answers.user_id IN (SELECT user_id FROM phoenix_users WHERE user_teacher = '".$user_id."') AND phoenix_answers.answer_date >= '".$limit."' AND phoenix_answers.user_id = (CASE WHEN '".$stu."' <> 'all' THEN '".$stu."' ELSE user_id END) AND phoenix_questions.question_new_syllabus = (CASE WHEN '".$course."' <> 'all' THEN '".$course."' ELSE question_new_syllabus END) AND phoenix_answers.answer_type = (CASE WHEN '".$type."' <> 'all' THEN '".$type."' ELSE answer_type END) AND phoenix_answers.answer_valid = (CASE WHEN '".$validity."' = 'incorrect' THEN '0' WHEN '".$validity."' = 'correct' THEN '1' ELSE answer_valid END) ORDER BY phoenix_questions.question_new_syllabus DESC, phoenix_answers.answer_date DESC";}
if($order == 'validity'){
$sql_list = "SELECT * FROM phoenix_answers INNER JOIN phoenix_questions ON phoenix_answers.question_id = phoenix_questions.question_id WHERE phoenix_answers.answer <> '0' AND phoenix_answers.user_id IN (SELECT user_id FROM phoenix_users WHERE user_teacher = '".$user_id."') AND phoenix_answers.answer_date >= '".$limit."'AND phoenix_answers.user_id = (CASE WHEN '".$stu."' <> 'all' THEN '".$stu."' ELSE user_id END) AND phoenix_questions.question_new_syllabus = (CASE WHEN '".$course."' <> 'all' THEN '".$course."' ELSE question_new_syllabus END) AND phoenix_answers.answer_type = (CASE WHEN '".$type."' <> 'all' THEN '".$type."' ELSE answer_type END) AND phoenix_answers.answer_valid = (CASE WHEN '".$validity."' = 'incorrect' THEN '0' WHEN '".$validity."' = 'correct' THEN '1' ELSE answer_valid END) ORDER BY phoenix_answers.answer_valid ASC, phoenix_answers.answer_date DESC";}
$res_list = $mysqli -> query($sql_list);
$num_list = mysqli_num_rows($res_list);
if($num_list == 0){
echo '<p><em>No answers found</em></p>';}
if($num_list >= 1000){
echo '<p><em>I have found over 1000 answers matching your search criteria.<br> That\'s too much, try to narrow them down.</em></p>';}
if($num_list > 0 && $num_list < 1000){
echo '<p><b><span style="color: #820000">'.$num_list.'</span> answer(s) found</b></p>
<table width="80%" align="center" bgcolor="#000000"><tbody><tr>
<td height="40" bgcolor="#647d57"><b>DATE & TIME</b></td>
<td height="40" bgcolor="#647d57"><b>NAME</b></td>
<td height="40" bgcolor="#647d57"><b>QUESTION</b></td>
<td height="40" bgcolor="#647d57"><b>COURSE</b></td>
<td height="40" bgcolor="#647d57"><b>TYPE</b></td>
<td height="40" bgcolor="#647d57"><b>ANSWER</b></td>
</tr>';
$color = 1;
while($data_list = mysqli_fetch_assoc($res_list)){
$student_id = $data_list['user_id'];
$question_id = $data_list['question_id'];
$answer_given = $data_list['answer'];
if($answer_given == 1){
$answer_given = 'A';}
if($answer_given == 2){
$answer_given = 'B';}
if($answer_given == 3){
$answer_given = 'C';}
if($answer_given == 4){
$answer_given = 'D';}
$answer_type = $data_list['answer_type'];
$answer_date = $data_list['answer_date'];
if($user_tz >= 0){
$answer_date_display = date('Y-m-d H:i:s', strtotime(''.$answer_date.' + '.$user_tz.' minutes'));}
if($user_tz < 0){
$answer_date_display = date('Y-m-d H:i:s', strtotime(''.$answer_date.' - '.abs($user_tz).' minutes'));}
$sql_info = "SELECT * FROM phoenix_users WHERE user_id = '".$student_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
$student_t = $data_info['user_title'];
$student_fn = $data_info['user_first_name'];
$student_ln = $data_info['user_last_name'];
$sql_q = "SELECT question_answer, question_syllabus, question_new_syllabus, question_paper_id, question_number FROM phoenix_questions WHERE question_id = '".$question_id."'";
$res_q = $mysqli -> query($sql_q);
$data_q = mysqli_fetch_assoc($res_q);
$correct_answer = $data_q['question_answer'];
if($correct_answer == 1){
$correct_answer = 'A';}
if($correct_answer == 2){
$correct_answer = 'B';}
if($correct_answer == 3){
$correct_answer = 'C';}
if($correct_answer == 4){
$correct_answer = 'D';}
$question_syllabus = $data_q['question_syllabus'];
$question_new_syllabus = $data_q['question_new_syllabus'];
if($question_new_syllabus == '3'){
$s_text = "A LEVEL";}
if($question_new_syllabus == '2'){
$s_text = "AS LEVEL";}
if($question_new_syllabus == '1'){
$s_text = $question_new_syllabus;}
$q_paper = $data_q['question_paper_id'];
$q_number = $data_q['question_number'];
if($answer_type == '1'){
$question_paper = $data_q['question_paper_id'];
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
if(substr($question_paper, 0, 1) < 3){
$paper = 'P1';}
if(substr($question_paper, 0, 1) == 3){
$paper = 'P3';}
$label = $paper.' | '.$year.'<br>'.$serie.'<br>Version '.$version.' | Question '.$data_q['question_number'];}
if($answer_type == '2'){
$topic_id = $data_list['topic_id'];
$sql_topic = "SELECT * FROM phoenix_topics WHERE topic_id = '".$topic_id."'";
$res_topic = $mysqli -> query($sql_topic);
$data_topic = mysqli_fetch_assoc($res_topic);
$label = $data_topic['topic_module'];}
if($answer_type == '3'){
$assign_id = $data_list['assign_id'];
$sql_assign = "SELECT * FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
$label = $data_assign['assign_name'];}
if($answer_type == 1){
$answer_type = "P";}
if($answer_type == 2){
$answer_type = "T";}
if($answer_type == 3){
$answer_type = "A";}
if($color == 1){
echo '<tr>
<td height="40" bgcolor="#769467">'.$answer_date_display.'</td>
<td height="40" bgcolor="#769467">'.$student_t.' '.$student_fn.' '.$student_ln.'</td>
<td height="40" bgcolor="#769467" class="mid"><em><b><div class="tooltip"><img src="mg.png" width="22" height="22" class="mid">
<span class="tooltiptext"><table align="center" width="100%" bgcolor="#000000"><tbody><tr><td bgcolor="#FFFFFF"><img src="./q/'.$question_syllabus.'/'.$q_paper.'/'.$q_number.'.png" width="100%"></td></tr></tbody></table></span>
</div></b></em></td>
<td height="40" bgcolor="#769467"><b>'.$s_text.'</b></td>
<td height="40" bgcolor="#769467"><b><div class="tooltip2">'.$answer_type.'
<span class="tooltiptext2">'.$label.'</span>
</div></b></td>';
if($answer_given == $correct_answer){
echo '<td height="40" bgcolor="#246648"><b>'.$answer_given.'</b></td>';}
if($answer_given != $correct_answer){
echo '<td height="40" bgcolor="#9C3039"><b>'.$answer_given.' (Key: '.$correct_answer.')</b></td>';}
echo '</tr>';
$color = 2;}
else {
echo '<tr>
<td height="40" bgcolor="#a0b595">'.$answer_date_display.'</td>
<td height="40" bgcolor="#a0b595">'.$student_t.' '.$student_fn.' '.$student_ln.'</td>
<td height="40" bgcolor="#a0b595" class="mid"><em><b><div class="tooltip"><img src="mg.png" width="22" height="22" class="mid">
<span class="tooltiptext"><table align="center" width="100%" bgcolor="#000000"><tbody><tr><td bgcolor="#FFFFFF"><img src="./q/'.$question_syllabus.'/'.$q_paper.'/'.$q_number.'.png" width="100%"></td></tr></tbody></table></span>
</div></b></em></td>
<td height="40" bgcolor="#a0b595"><b>'.$s_text.'</b></td>
<td height="40" bgcolor="#a0b595"><b><div class="tooltip2">'.$answer_type.'
<span class="tooltiptext2">'.$label.'</span>
</div></b></td>';
if($answer_given == $correct_answer){
echo '<td height="40" bgcolor="#246648"><b>'.$answer_given.'</b></td>';}
if($answer_given != $correct_answer){
echo '<td height="40" bgcolor="#9C3039"><b>'.$answer_given.' (Key: '.$correct_answer.')</b></td>';}
echo '</tr>';
$color = 1;}}
echo '</tbody></table>';}
?>
<p><a href="#top"><b>Back to the top</b></a></p>
</body></html>