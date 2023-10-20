<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
$s = $_GET['s'];
if(empty($s)){
echo 'Missing information about the course.';
exit();}
if($s == '1'){
$s_text = 'IGCSE';}
if($s == '2'){
$s_text = 'AS LEVEL';}
if($s == '3'){
$s_text = 'A LEVEL';}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT user_type, user_teacher, user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_teacher = $data['user_teacher'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 2){
echo 'What are you doing here? This page is for students only.';
exit();}
if($user_teacher != 0){
$now = date("Y-m-d H:i:s");
$sql_id = "SELECT permission_id FROM phoenix_permissions WHERE permission_teacher = '".$user_teacher."' AND permission_expire != '0000-00-00 00:00:00' AND permission_expire <= '".$now."' AND permission_syllabus = '".$s."'";
$res_id = $mysqli -> query($sql_id);
while($row = mysqli_fetch_assoc($res_id)){
$perm_id = $row['permission_id'];
$sql_del1 = "DELETE FROM phoenix_permissions WHERE permission_id = '".$perm_id."'";
$res_del1 = $mysqli -> query($sql_del1);
$sql_del2 = "DELETE FROM phoenix_permissions_users WHERE permission_id = '".$perm_id."'";
$res_del2 = $mysqli -> query($sql_del2);
$sql_del3 = "DELETE FROM phoenix_permissions_papers WHERE permission_id = '".$perm_id."'";
$res_del3 = $mysqli -> query($sql_del3);
$sql_del4 = "DELETE FROM phoenix_permissions_topics WHERE permission_id = '".$perm_id."'";
$res_del4 = $mysqli -> query($sql_del4);}}
if($s == '1'){
$sql_count = "SELECT COUNT(*) FROM phoenix_questions WHERE question_new_syllabus = '1' AND question_answer <> '0'";
$sql_answers = "SELECT COUNT(DISTINCT question_id) FROM phoenix_answers WHERE user_id = '".$user_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '1')";
$sql_answers2 = "SELECT COUNT(*) FROM phoenix_answers WHERE user_id = '".$user_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '1')";
$sql_valid = "SELECT COUNT(*) FROM phoenix_answers WHERE user_id = '".$user_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '1') AND answer_valid = '1'";}
if($s == '2'){
$sql_count = "SELECT COUNT(*) FROM phoenix_questions WHERE question_new_syllabus = '2' AND question_answer <> '0'";
$sql_answers = "SELECT COUNT(DISTINCT question_id) FROM phoenix_answers WHERE user_id = '".$user_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '2')";
$sql_answers2 = "SELECT COUNT(*) FROM phoenix_answers WHERE user_id = '".$user_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '2')";
$sql_valid = "SELECT COUNT(*) FROM phoenix_answers WHERE user_id = '".$user_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '2') AND answer_valid = '1'";}
if($s == '3'){
$sql_count = "SELECT COUNT(*) FROM phoenix_questions WHERE question_new_syllabus = '3' AND question_answer <> '0'";
$sql_answers = "SELECT COUNT(DISTINCT question_id) FROM phoenix_answers WHERE user_id = '".$user_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '3')";
$sql_answers2 = "SELECT COUNT(*) FROM phoenix_answers WHERE user_id = '".$user_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '3')";
$sql_valid = "SELECT COUNT(*) FROM phoenix_answers WHERE user_id = '".$user_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '3') AND answer_valid = '1'";}
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
$sql_up = "UPDATE phoenix_users SET user_score_igcse = '".$student_score."' WHERE user_id = '".$user_id."'";}
if($s == '2'){
$sql_up = "UPDATE phoenix_users SET user_score_as = '".$student_score."' WHERE user_id = '".$user_id."'";}
if($s == '3'){
$sql_up = "UPDATE phoenix_users SET user_score_a2 = '".$student_score."' WHERE user_id = '".$user_id."'";}
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
</style>
</head>
<body>
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
</td></tr></tbody></table>
<p style="font-size: x-large"><b><?php echo $s_text; ?> QUESTIONS</b></p>
<p><input type="button" name="paper" value="PAPERS" onclick="document.location.href='practice_paper.php?s=<?php echo $s; ?>';"/>&nbsp;&nbsp;
<input type="button" name="topic" value="TOPICS" onclick="document.location.href='practice_topic.php?s=<?php echo $s; ?>';"/>&nbsp;&nbsp;
<input type="button" name="mistakes" value="MY MISTAKES" onclick="document.location.href='practice_mistakes.php?s=<?php echo $s; ?>';"/></p>
<p>&nbsp;</p>
<table width="50%" align="center" bgcolor="#000000"><tbody><tr>
<td height="40" bgcolor="647d57"><b>PROGRESS RATE</b></td>
<td height="40" bgcolor="647d57"><b>SUCCESS RATE</b></td>
<td height="40" bgcolor="647d57"><b>LEVEL & RANK</b></td>
</tr><tr>
<td height="40" bgcolor="769467"><?php echo round($rate, 0); ?>%</td>
<td height="40" bgcolor="769467"><?php echo $correct_display; ?>%</td>
<td height="40" bgcolor="769467">LV <?php echo floor($student_score); ?> - <?php echo $student_rank; ?></td>
</tr></tbody></table>
<p><input type="button" name="ranking" value="RANKING" onclick="document.location.href='ranking.php?s=<?php echo $s; ?>';"/></p>
</body></html>