<?php
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
$member_id = $_GET['member_id'];
if(empty($member_id)){
echo 'Missing information';
exit();}
$s = $_GET['s'];
if(empty($s)){
$s = $_POST['course'];}
if(empty($s)){
echo 'Missing information';
exit();}
$sql2 = "SELECT user_teacher, user_title, user_first_name, user_last_name FROM phoenix_users WHERE user_id = '".$member_id."'";
$res2 = $mysqli -> query($sql2);
$data2 = mysqli_fetch_assoc($res2);
$user_refresh = $data2['user_refresh'];
if($user_id != $data2['user_teacher']){
echo 'You are not authorized to manage this account.';
exit();}
if($s == '1'){
$sql_count = "SELECT COUNT(*) FROM phoenix_questions WHERE question_new_syllabus = '1' AND question_answer <> '0'";
$sql_answers = "SELECT COUNT(DISTINCT question_id) FROM phoenix_answers WHERE user_id = '".$member_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '1')";
$sql_answers2 = "SELECT COUNT(*) FROM phoenix_answers WHERE user_id = '".$member_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '1')";
$sql_valid = "SELECT COUNT(*) FROM phoenix_answers WHERE user_id = '".$member_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '1') AND answer_valid = '1'";}
if($s == '2'){
$sql_count = "SELECT COUNT(*) FROM phoenix_questions WHERE question_new_syllabus = '2' AND question_answer <> '0'";
$sql_answers = "SELECT COUNT(DISTINCT question_id) FROM phoenix_answers WHERE user_id = '".$member_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '2')";
$sql_answers2 = "SELECT COUNT(*) FROM phoenix_answers WHERE user_id = '".$member_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '2')";
$sql_valid = "SELECT COUNT(*) FROM phoenix_answers WHERE user_id = '".$member_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '2') AND answer_valid = '1'";}
if($s == '3'){
$sql_count = "SELECT COUNT(*) FROM phoenix_questions WHERE question_new_syllabus = '3' AND question_answer <> '0'";
$sql_answers = "SELECT COUNT(DISTINCT question_id) FROM phoenix_answers WHERE user_id = '".$member_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '3')";
$sql_answers2 = "SELECT COUNT(*) FROM phoenix_answers WHERE user_id = '".$member_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '3')";
$sql_valid = "SELECT COUNT(*) FROM phoenix_answers WHERE user_id = '".$member_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '3') AND answer_valid = '1'";}
$res_count = $mysqli -> query($sql_count);
$data_count = mysqli_fetch_assoc($res_count);
$count = $data_count['COUNT(*)'];
$res_answers = $mysqli -> query($sql_answers);
$data_answers = mysqli_fetch_assoc($res_answers);
$answers = $data_answers['COUNT(DISTINCT question_id)'];
$rate = round($answers / $count * 100, 2);
$res_answers2 = $mysqli -> query($sql_answers2);
$data_answers2 = mysqli_fetch_assoc($res_answers2);
$answers2 = $data_answers2['COUNT(*)'];
if($answers2 == 0){
$student_score = '0.00';
$correct_display = 'N/A';}
if($answers2 > 0){
$res_valid = $mysqli -> query($sql_valid);
$data_valid = mysqli_fetch_assoc($res_valid);
$valid = $data_valid['COUNT(*)'];
$correct = round($valid / $answers2 * 100, 2);
$correct_display = round($valid / $answers2 * 100, 0);
$student_score = round($correct * $rate / 100, 2);}
if($s == '1'){
$sql_up = "UPDATE phoenix_users SET user_score_igcse = '".$student_score."' WHERE user_id = '".$member_id."'";}
if($s == '2'){
$sql_up = "UPDATE phoenix_users SET user_score_as = '".$student_score."' WHERE user_id = '".$member_id."'";}
if($s == '3'){
$sql_up = "UPDATE phoenix_users SET user_score_a2 = '".$student_score."' WHERE user_id = '".$member_id."'";}
$res_up = $mysqli -> query($sql_up);
if($student_score < 5){
$student_rank = 'Newbie';}
if($student_score >= 5 && $student_score < 10){
$student_rank = 'Novice';}
if($student_score >= 10 && $student_score < 15){
$student_rank = 'Amateur';}
if($student_score >= 15 && $student_score < 20){
$student_rank = 'Apprentice';}
if($student_score >= 20 && $student_score < 25){
$student_rank = 'Adept';}
if($student_score >= 25 && $student_score < 30){
$student_rank = 'Junior Economist';}
if($student_score >= 30 && $student_score < 35){
$student_rank = 'Senior Economist';}
if($student_score >= 35 && $student_score < 40){
$student_rank = 'Chief Economist';}
if($student_score >= 40 && $student_score < 45){
$student_rank = 'Expert';}
if($student_score >= 45 && $student_score < 50){
$student_rank = 'Scholar';}
if($student_score >= 50 && $student_score < 55){
$student_rank = 'Chairman';}
if($student_score >= 55 && $student_score < 60){
$student_rank = 'Veteran';}
if($student_score >= 60 && $student_score < 65){
$student_rank = 'Master';}
if($student_score >= 65 && $student_score < 70){
$student_rank = 'Grand Master';}
if($student_score >= 70 && $student_score < 75){
$student_rank = 'Supreme Master';}
if($student_score >= 75 && $student_score < 80){
$student_rank = 'Overlord';}
if($student_score >= 80 && $student_score < 85){
$student_rank = 'Hero';}
if($student_score >= 85 && $student_score < 90){
$student_rank = 'Legend';}
if($student_score >= 90 && $student_score < 95){
$student_rank = 'Immortal';}
if($student_score >= 95 && $student_score < 100){
$student_rank = 'God';}
if($student_score == 100){
$student_rank = 'Beyonder';}
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
.brmedium {
display: block;
margin-bottom: 2em;
}
.brsmall {
display: block;
margin-bottom: 0.5em;
}
</style>
<script language="JavaScript">
function toggle_papers(source){
checkboxes = document.getElementsByName('reset_papers[]');
for(var i=0, n=checkboxes.length;i<n;i++){
checkboxes[i].checked = source.checked;}}
</script>
<script language="JavaScript">
function toggle_topics(source){
checkboxes = document.getElementsByName('reset_topics[]');
for(var i=0, n=checkboxes.length;i<n;i++){
checkboxes[i].checked = source.checked;}}
</script>
<script language="JavaScript">
function papers(){
document.getElementById('papers').style.display = "block";
document.getElementById('topics').style.display = "none";
document.getElementById('button_papers').setAttribute('onclick','');
document.getElementById('button_papers').setAttribute('style','width: 15%; cursor: default; background-color: black;');
document.getElementById('button_topics').setAttribute('style','');
document.getElementById('button_topics').setAttribute('onclick','topics();');}
</script>
<script language="JavaScript">
function topics(){
document.getElementById('topics').style.display = "block";
document.getElementById('papers').style.display = "none";
document.getElementById('button_topics').setAttribute('onclick','');
document.getElementById('button_topics').setAttribute('style','width: 15%; cursor: default; background-color: black;');
document.getElementById('button_papers').setAttribute('style','');
document.getElementById('button_papers').setAttribute('onclick','papers();');}
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
<p style="font-size: x-large"><b><?php echo strtoupper($data2['user_title'].' '.$data2['user_first_name'].' '.$data2['user_last_name']); ?></b></p>
<p><input type="button" name="practice" value="PRACTICE" style="width: 15%; cursor: default; background-color: black;">&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="width: 15%;" onclick="document.location.href='user_view_assignments.php?member_id=<?php echo $member_id; ?>'">&nbsp;&nbsp;
<input type="button" name="permissions" value="PERMISSIONS" style="width: 15%;" onclick="document.location.href='user_view_permissions.php?member_id=<?php echo $member_id; ?>'"></p>
<span class="brsmall"></span>
<form method="post" action="user_view_practice.php?member_id=<?php echo $member_id; ?>">
<p><b>Course:</b><span class="brsmall"></span>
<select name="course" style="width: 20%;" onchange="this.form.submit();"><optgroup>
<option value="1" <?php if($s == '1') { echo 'selected'; } ?>>IGCSE</option>
<option value="2" <?php if($s == '2') { echo 'selected'; } ?>>AS Level</option>
<option value="3" <?php if($s == '3') { echo 'selected'; } ?>>A Level</option>
</optgroup></select></p></form>
<span class="brsmall"></span>
<p><input type="button" name="ranking" value="RANKING" onclick="document.location.href='view_ranking.php?s=<?php echo $s; ?>';"/></p>
<table width="50%" align="center" bgcolor="#000000"><tbody><tr>
<td height="40" bgcolor="647d57"><b>PROGRESS RATE</b></td>
<td height="40" bgcolor="647d57"><b>SUCCESS RATE</b></td>
<td height="40" bgcolor="647d57"><b>LEVEL & RANK</b></td>
</tr><tr>
<td height="40" bgcolor="769467"><?php echo round($rate, 0); ?>%</td>
<td height="40" bgcolor="769467"><?php echo $correct_display; ?>%</td>
<td height="40" bgcolor="769467">LV <?php echo floor($student_score); ?> - <?php echo $student_rank; ?></td>
</tr></tbody></table>
<span class="brmedium"></span>
<p><input type="button" id="button_papers" name="papers" value="PAPERS" onclick="papers();"/>&nbsp;&nbsp;
<input type="button" id="button_topics" name="topics" value="TOPICS" onclick="topics();"/></p>
<span class="brmedium"></span>
<div id="papers" style="display: none;">
<?php
$sql_papers = "SELECT * FROM phoenix_papers WHERE paper_syllabus = '".$s."' AND paper_id IN (SELECT DISTINCT paper_id FROM phoenix_answers WHERE user_id = '".$member_id."' AND answer_type = '1') ORDER BY paper_year DESC, paper_serie ASC, paper_version ASC";
$res_papers = $mysqli -> query($sql_papers);
$nr_papers = mysqli_num_rows($res_papers);
if($nr_papers == 0){
echo '<p><em>No papers found</em></p>';}
if($nr_papers > 0){
?>
<form method="post" action="reset_paper_all.php?member_id=<?php echo $member_id; ?>&s=<?php echo $s; ?>">
<table width="67%" align="center" bgcolor="#000000"><tbody>
<tr>
<td height="40" width="5%" bgcolor="#647d57"><p><input type="checkbox" style="transform: scale(1.5);" onclick="toggle_papers(this)"></p></td>
<td height="40" width="15%" bgcolor="#647d57"><p><b>YEAR</b></p></td>
<td height="40" width="25%" bgcolor="#647d57"><p><b>SERIE</b></p></td>
<td height="40" width="15%" bgcolor="#647d57"><p><b>VERSION</b></p></td>
<td height="40" width="20%" bgcolor="#647d57"><p><b>STATUS</b></p></td>
<td height="40" width="20%" bgcolor="#647d57"><p><b>ACTIONS</b></p></td>
</tr>
<?php
$color = 1;
while($data_papers = mysqli_fetch_assoc($res_papers)){
$paper_id = $data_papers['paper_id'];
$paper_serie = $data_papers['paper_serie'];
if($paper_serie == 1){
$paper_serie_text = "m - February / March";}
if($paper_serie == 2){
$paper_serie_text = "s - May / June";}
if($paper_serie == 3){
$paper_serie_text = "w - October / November";}
if($paper_serie == 4){
$paper_serie_text = "y - Specimen";}
$paper_version = $data_papers['paper_version'];
if($paper_version == 0){
$paper_version = "/";}
$min_a = $data_papers['paper_a'];
$min_b = $data_papers['paper_b'];
$min_c = $data_papers['paper_c'];
$min_d = $data_papers['paper_d'];
$min_e = $data_papers['paper_e'];
if($s == '1'){
$min_f = $data_papers['paper_f'];
$min_g = $data_papers['paper_g'];}
$sql_count = "SELECT COUNT(*) FROM phoenix_questions WHERE question_paper_id = '".$paper_id."' AND question_answer <> '0'";
$res_count = $mysqli -> query($sql_count);
$data_count = mysqli_fetch_assoc($res_count);
$nr_nq = $data_count['COUNT(*)'];
$sql_na = "SELECT COUNT(*) FROM phoenix_answers WHERE paper_id = '".$paper_id."' AND user_id  = '".$member_id."'";
$res_na = $mysqli -> query($sql_na);
$data_na = mysqli_fetch_assoc($res_na);
$nr_na = $data_na['COUNT(*)'];
if($nr_na == $nr_nq){
$sql_correct = "SELECT COUNT(*) FROM phoenix_answers WHERE paper_id = '".$paper_id."' AND user_id = '".$member_id."' AND answer_valid = '1'";
$res_correct = $mysqli -> query($sql_correct);
$data_correct = mysqli_fetch_assoc($res_correct);
$score = $data_correct['COUNT(*)'];}
if($score >= $min_a){
$letter = "A";}
if($score < $min_a && $score >= $min_b){
$letter = "B";}
if($score < $min_b && $score >= $min_c){
$letter = "C";}
if($score < $min_c && $score >= $min_d){
$letter = "D";}
if($score < $min_d && $score >= $min_e){
$letter = "E";}
if($s != '1' && $score < $min_e){
$letter = "U";}
if($s == '1' && $score < $min_e && $score >= $min_f){
$letter = "F";}
if($s == '1' && $score < $min_f && $score >= $min_g){
$letter = "G";}
if($s == '1' && $score < $min_g){
$letter = "U";}
$sql_date = "SELECT MAX(answer_date) FROM phoenix_answers WHERE user_id = '".$member_id."' AND paper_id = '".$paper_id."'";
$res_date = $mysqli -> query($sql_date);
$data_date = mysqli_fetch_assoc($res_date);
$answer_date = date("Y-m-d H:i:s", strtotime($data_date['MAX(answer_date)']));
if($user_timezone >= 0){
$answer_date_display = date('Y-m-d', strtotime(''.$answer_date.' + '.$user_timezone.' minutes'));}
if($user_timezone < 0){
$answer_date_display = date('Y-m-d', strtotime(''.$answer_date.' - '.abs($user_timezone).' minutes'));}
if($color == 1){
echo '<tr>
<td height="40" bgcolor="769467"><input type="checkbox" style="transform: scale(1.5);" value="'.$paper_id.'" name="reset_papers[]"></td>
<td height="40" bgcolor="769467"><p>'.$data_papers['paper_year'].'</p></td>
<td height="40" bgcolor="769467"><p>'.$paper_serie_text.'</p></td>
<td height="40" bgcolor="769467"><p>'.$paper_version.'</p></td>
<td height="40" bgcolor="769467"><p>';
if($nr_na < $nr_nq){
echo '<p><b style="color: #0D3151">IN PROGRESS</b><br><b>'.$answer_date_display.'<br>'.round($nr_na / $nr_nq * 100, 0).'%</b></p>';}
if($nr_na == $nr_nq){
echo '<p><b style="color: #033909">FINISHED</b><br><b>'.$answer_date_display.'<br>'.round($score / $nr_nq * 100, 0).'% - '.$letter.'</b></p>';}
echo '</td><td height="40" bgcolor="769467"><p>';
echo '<a href="qp_report.php?paper_id='.$paper_id.'&member_id='.$member_id.'&s='.$s.'"><img src="mg.png" class="mid" width="30" title="View Report"></a>&nbsp;&nbsp;&nbsp;';
echo '<a href="user_gt_view.php?paper_id='.$paper_id.'&member_id='.$member_id.'&s='.$s.'"><img src="gt.png" class="mid" width="30" title="Grade Thresholds"></a>&nbsp;&nbsp;
<a href="reset_paper_teacher.php?paper_id='.$paper_id.'&member_id='.$member_id.'&s='.$s.'"><img src="reset.png" class="mid" width="30" title="Reset Answers"></a></p>
</td></tr>';
$color = 2;}
else {
echo '<tr>
<td height="40" bgcolor="a0b595"><input type="checkbox" style="transform: scale(1.5);" value="'.$paper_id.'" name="reset_papers[]"></td>
<td height="40" bgcolor="a0b595"><p>'.$data_papers['paper_year'].'</p></td>
<td height="40" bgcolor="a0b595"><p>'.$paper_serie_text.'</p></td>
<td height="40" bgcolor="a0b595"><p>'.$paper_version.'</p></td>
<td height="40" bgcolor="a0b595"><p>';
if($nr_na < $nr_nq){
echo '<p><b style="color: #0D3151">IN PROGRESS</b><br><b>'.$answer_date_display.'<br>'.round($nr_na / $nr_nq * 100, 0).'%</b></p>';}
if($nr_na == $nr_nq){
echo '<p><b style="color: #033909">FINISHED</b><br><b>'.$answer_date_display.'<br>'.round($score / $nr_nq * 100, 0).'% - '.$letter.'</b></p>';}
echo '</td><td height="40" bgcolor="a0b595"><p>';
echo '<a href="qp_report.php?paper_id='.$paper_id.'&member_id='.$member_id.'&s='.$s.'"><img src="mg.png" class="mid" width="30" title="View Report"></a>&nbsp;&nbsp;&nbsp;';
echo '<a href="user_gt_view.php?paper_id='.$paper_id.'&member_id='.$member_id.'&s='.$s.'"><img src="gt.png" class="mid" width="30" title="Grade Thresholds"></a>&nbsp;&nbsp;
<a href="reset_paper_teacher.php?paper_id='.$paper_id.'&member_id='.$member_id.'&s='.$s.'"><img src="reset.png" class="mid" width="30" title="Reset Answers"></a></p>
</td></tr>';
$color = 1;}}
?>
</tbody>
</table><br>
<input type="submit" value="RESET" onclick="return confirm('Are you sure you want to reset the papers you selected?')">
</form>
<?php
}
?>
</div>
<div id="topics" style="display:none;">
<?php
$sql_th = "SELECT * FROM phoenix_thresholds WHERE teacher_id = '".$user_id."' AND syllabus = '".$s."'";
$res_th = $mysqli -> query($sql_th);
$data_th = mysqli_fetch_assoc($res_th);
$min_a = $data_th['min_a'];
$min_b = $data_th['min_b'];
$min_c = $data_th['min_c'];
$min_d = $data_th['min_d'];
$min_e = $data_th['min_e'];
if($s == '1'){
$min_f = $data_th['min_f'];
$min_g = $data_th['min_g'];}
$sql_topics = "SELECT * FROM phoenix_topics WHERE topic_id IN (SELECT DISTINCT topic_id FROM phoenix_answers WHERE user_id = '".$member_id."' AND answer_type = '2' AND answer_syllabus = '".$s."') ORDER BY topic_unit_id ASC, topic_module_id ASC";
$res_topics = $mysqli -> query($sql_topics);
$nr_topics = number_format(mysqli_num_rows($res_topics));
if($nr_topics == 0){
echo '<p><em>No topics found</em></p>';}
if($nr_topics > 0){
?>
<form method="post" action="reset_topic_all.php?member_id=<?php echo $member_id; ?>&s=<?php echo $s; ?>">
<table width="67%" align="center" bgcolor="#000000"><tbody>
<tr>
<td height="40" width="5%" bgcolor="#647d57"><p><input type="checkbox" style="transform: scale(1.5);" onclick="toggle_topics(this)"></p></td>
<td height="40" width="50%" bgcolor="#647d57"><p><b>TOPIC</b></p></td>
<td height="40" width="20%" bgcolor="#647d57"><p><b>STATUS</b></p></td>
<td height="40" width="15%" bgcolor="#647d57"><p><b>ACTIONS</b></p></td>
</tr>
<?php
$color = 1;
while($data_topics = mysqli_fetch_assoc($res_topics)){
$topic_id = $data_topics['topic_id'];
$sql_nq = "SELECT COUNT(*) FROM phoenix_questions WHERE question_topic_id = '".$topic_id."' AND question_repeat = '0' AND question_obsolete = '0' AND question_answer <> '0'";
$res_nq = $mysqli -> query($sql_nq);
$data_nq = mysqli_fetch_assoc($res_nq);
$nr_nq = $data_nq['COUNT(*)'];
$sql_na = "SELECT COUNT(*) FROM phoenix_answers WHERE topic_id = '".$topic_id."' AND user_id = '".$member_id."' AND answer_type = '2'";
$res_na = $mysqli -> query($sql_na);
$data_na = mysqli_fetch_assoc($res_na);
$nr_na = $data_na['COUNT(*)'];
$sql_correct = "SELECT COUNT(*) FROM phoenix_answers WHERE topic_id = '".$topic_id."' AND user_id = '".$member_id."' AND answer_type = '2' AND answer_valid = '1'";
$res_score = $mysqli -> query($sql_correct);
$data_score = mysqli_fetch_assoc($res_score);
$score = $data_score['COUNT(*)'];
$percent = round($score / $nr_nq * 100, 0);
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
if($s != '1' && $percent < $min_e){
$letter = "U";}
if($s == '1'){
if($percent < $min_e && $percent >= $min_f){
$letter = "F";}
if($percent < $min_f && $percent >= $min_g){
$letter = "G";}
if($percent < $min_g){
$letter = "U";}}
$sql_date = "SELECT MAX(answer_date) FROM phoenix_answers WHERE user_id = '".$member_id."' AND topic_id = '".$topic_id."'";
$res_date = $mysqli -> query($sql_date);
$data_date = mysqli_fetch_assoc($res_date);
$answer_date = date("Y-m-d H:i:s", strtotime($data_date['MAX(answer_date)']));
if($user_timezone >= 0){
$answer_date_display = date('Y-m-d', strtotime(''.$answer_date.' + '.$user_timezone.' minutes'));}
if($user_timezone < 0){
$answer_date_display = date('Y-m-d', strtotime(''.$answer_date.' - '.abs($user_timezone).' minutes'));}
if($color == 1){
echo '<tr>
<td height="40" bgcolor="#769467"><input type="checkbox" style="transform: scale(1.5);" value="'.$topic_id.'" name="reset_topics[]"></td>
<td height="40" bgcolor="#769467"><p>'.$data_topics['topic_module'].'</p></td>
<td height="40" bgcolor="#769467"><p>';
if($nr_na < $nr_nq){
echo '<b style="color: #0D3151">IN PROGRESS</b><br><b>'.$answer_date_display.'<br>'.round($nr_na / $nr_nq * 100, 0).'%</b>';}
if($nr_na == $nr_nq){
echo '<b style="color: #033909">FINISHED</b><br><b>'.$answer_date_display.'<br>'.round($score / $nr_nq * 100, 0).'% - '.$letter.'</b>';}
echo '</p></td>
<td height="40" bgcolor="#769467"><p>';
echo '<a href="t_report.php?topic_id='.$topic_id.'&member_id='.$member_id.'&s='.$s.'"><img src="mg.png" class="mid" width="30" title="View Report"></a>&nbsp;&nbsp;&nbsp;';
echo '<a href="reset_topic_teacher.php?topic_id='.$topic_id.'&member_id='.$member_id.'&s='.$s.'"><img src="reset.png" class="mid" width="30" title="Reset Answers"></a>
</p></td></tr>';
$color = 2;}
else {
echo '<tr>
<td height="40" bgcolor="#a0b595"><input type="checkbox" style="transform: scale(1.5);" value="'.$topic_id.'" name="reset_topics[]"></td>
<td height="40" bgcolor="#a0b595"><p>'.$data_topics['topic_module'].'</p></td>
<td height="40" bgcolor="#a0b595"><p>';
if($nr_na < $nr_nq){
echo '<b style="color: #0D3151">IN PROGRESS</b><br><b>'.$answer_date_display.'<br>'.round($nr_na / $nr_nq * 100, 0).'%</b>';}
if($nr_na == $nr_nq){
echo '<b style="color: #033909">FINISHED</b><br><b>'.$answer_date_display.'<br>'.round($score / $nr_nq * 100, 0).'% - '.$letter.'</b>';}
echo '</p></td>
<td height="40" bgcolor="#a0b595"><p>';
echo '<a href="t_report.php?topic_id='.$topic_id.'&member_id='.$member_id.'&s='.$s.'"><img src="mg.png" class="mid" width="30" title="View Report"></a>&nbsp;&nbsp;&nbsp;';
echo '<a href="reset_topic_teacher.php?topic_id='.$topic_id.'&member_id='.$member_id.'&s='.$s.'"><img src="reset.png" class="mid" width="30" title="Reset Answers"></a>
</p></td></tr>';
$color = 1;}}
echo'</tbody></table><br>';
?>
<input type="submit" value="RESET" onclick="return confirm('Are you sure you want to reset the topics you selected?')">
</form>
<?php
}
?>
</div>
<span class="brmedium"></span>
<p><a href="#top"><b>Back to the top</b></a></p>
</body></html>