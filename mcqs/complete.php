<?php
$r = $_GET['r'];
if($r == 1){
sleep(1);}
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
$sql_tz = "SELECT user_timezone FROM phoenix_users WHERE user_id = '".$user_teacher."'";
$res_tz = $mysqli -> query($sql_tz);
$data_tz = mysqli_fetch_assoc($res_tz);
$tz = $data_tz['user_timezone'];
$sql_as = "SELECT * FROM phoenix_assign INNER JOIN phoenix_assign_users ON phoenix_assign_users.assign_id = phoenix_assign.assign_id WHERE phoenix_assign_users.student_id = '".$user_id."' AND phoenix_assign.assign_active = '1' ORDER BY phoenix_assign.assign_deadline DESC";
$res_as = $mysqli -> query($sql_as);
$nr_as = mysqli_num_rows($res_as);
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
<meta name="theme-color" content="#ffffff"><style type="text/css">
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
<input type="button" name="practice" value="PRACTICE" style="font-size: x-large; width: 45%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='practice.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 45%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='complete.php';"/>
</p></td></tr></tbody></table>
<p style="font-size: x-large"><b>ASSIGNMENTS</b></p>
<?php
if($nr_as == 0){
echo '<p><em>No assignments found</em></p>';}
if($nr_as > 0){
?>
<form method="post"><p>
<table width="90%" bgcolor="#000000" align="center">
<tbody><tr>
<td height = "40" width="10%" bgcolor="#6a855c"><b><p>TYPE</p></b></td>
<td height = "40" width="35%" bgcolor="#6a855c"><b><p>NAME</p></b></td>
<td height = "40" width="15%" bgcolor="#6a855c"><b><p>START</p></b></td>
<td height = "40" width="15%" bgcolor="#6a855c"><b><p>DEADLINE</p></b></td>
<td height = "40" width="15%" bgcolor="#6a855c"><b><p>STATUS</p></b></td>
<td height = "40" width="10%" bgcolor="#6a855c"><b><p>ACTIONS</p></b></td>
</tr>
<?php
$color = 1;
while($data_as = mysqli_fetch_assoc($res_as)){
$assign_id = $data_as['assign_id'];
$assign_ddl = $data_as['assign_student_end'];
$release = $data_as['assign_release'];
$assign_game_status = $data_as['assign_game_status'];
$assign_deadline = date("Y-m-d H:i:s", strtotime($data_as['assign_deadline']));
if($tz >= 0){
$assign_deadline_display = date('Y-m-d H:i', strtotime(''.$assign_deadline.' + '.$tz.' minutes'));}
if($tz < 0){
$assign_deadline_display = date('Y-m-d H:i', strtotime(''.$assign_deadline.' - '.abs($tz).' minutes'));}
$assign_start = date("Y-m-d H:i:s", strtotime($data_as['assign_start']));
if($tz >= 0){
$assign_start_display = date('Y-m-d H:i', strtotime(''.$assign_start.' + '.$tz.' minutes'));}
if($tz < 0){
$assign_start_display = date('Y-m-d H:i', strtotime(''.$assign_start.' - '.abs($tz).' minutes'));}
$assign_type = $data_as['assign_type'];
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
if($assign_type == '5'){
$assign_start_display = 'N/A';
$assign_deadline_display = 'N/A';}
$s = $data_as['assign_syllabus'];
$count_nq = $data_as['assign_nq'];
$sql_na = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND user_id = '".$user_id."'";
$res_na = $mysqli -> query($sql_na);
$data_na = mysqli_fetch_assoc($res_na);
$count_na = $data_na['COUNT(*)'];
if($color == 1){
echo '<tr>
<td height="40" bgcolor="#769467"><p>'.$assign_type_text.'</p></td>
<td height="40" bgcolor="#769467"><p>'.$data_as['assign_name'].'</p></td>
<td height="40" bgcolor="#769467"><p>'.$assign_start_display.'</p></td>
<td height="40" bgcolor="#769467"><p>'.$assign_deadline_display.'</p></td>
<td height="40" bgcolor="#769467"><p>';
if($assign_type != '5'){
if($now < $assign_start){
echo '<b>NOT STARTED YET</b>';}
if($now > $assign_start && $now < $assign_ddl){
echo '<b style = "color: #0D3151">IN PROGRESS</b><br><b>('.round($count_na / $count_nq * 100, 0).'%)</b>';}
if($now > $assign_ddl){
echo '<b style="color: #033909">FINISHED</b>';
if($now > $assign_deadline || ($now < $assign_deadline && $release == '1')){
$sql_score = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND user_id = '".$user_id."' AND answer_valid ='1'";
$res_score = $mysqli -> query($sql_score);
$data_score = mysqli_fetch_assoc($res_score);
$score = $data_score['COUNT(*)'];
$percent = round($score / $count_nq * 100,0);
$min_a = $data_as['assign_a'];
$min_b = $data_as['assign_b'];
$min_c = $data_as['assign_c'];
$min_d = $data_as['assign_d'];
$min_e = $data_as['assign_e'];
if($s == '1'){
$min_f = $data_as['assign_f'];
$min_g = $data_as['assign_g'];}
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
echo '<br><b>'.$percent.'% - '.$letter.'</b>';}}}
if($assign_type == '5'){
if($assign_game_status == 0){
echo '<b>NOT STARTED YET</b>';}
if($assign_game_status != 0 && $assign_game_status != 4){
echo '<b style="color: #0D3151;">IN PROGRESS</b><br><b>'.round($count_na / $count_nq * 100, 0).'%</b>';}
if($assign_game_status == 4){
$sql_score = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND user_id = '".$user_id."' AND answer_valid ='1'";
$res_score = $mysqli -> query($sql_score);
$data_score = mysqli_fetch_assoc($res_score);
$score = $data_score['COUNT(*)'];
$percent = round($score / $count_nq * 100,0);
$min_a = $data_as['assign_a'];
$min_b = $data_as['assign_b'];
$min_c = $data_as['assign_c'];
$min_d = $data_as['assign_d'];
$min_e = $data_as['assign_e'];
if($s == '1'){
$min_f = $data_as['assign_f'];
$min_g = $data_as['assign_g'];}
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
echo '<b style="color: #033909;">FINISHED</b><br><b>'.$percent.'% - '.$letter.'</b>';}}
echo '</p></td>
<td height="40" bgcolor="#769467"><p>';
if($assign_type != '5'){
if($now < $assign_start){
echo '<a href="assign_gt.php?assign_id='.$assign_id.'"><img src="gt.png" width="30" height="30" class="mid" title="Grade Thresholds"></a>';}
if($now > $assign_start && $now < $assign_ddl){
echo '<a href="complete_start.php?assign_id='.$assign_id.'"><img src="start.png" width="30" height="30" class="mid" title="Complete"></a>&nbsp;&nbsp;';
echo '<a href="assign_gt.php?assign_id='.$assign_id.'"><img src="gt.png" width="30" height="30" class="mid" title="Grade Thresholds"></a>';}
if($now > $assign_ddl){
if($now > $assign_deadline || ($now < $assign_deadline && $release == '1')){
echo '<a href="assign_report_student.php?assign_id='.$assign_id.'"><img src="mg.png" width="30" height="30" class="mid" title="Report"></a>&nbsp;&nbsp;';}
echo '<a href="assign_gt.php?assign_id='.$assign_id.'"><img src="gt.png" width="30" height="30" class="mid" title="Grade Thresholds"></a>';}}
if($assign_type == '5'){
if($assign_game_status == 0){
echo '<a href="complete_game_start.php?assign_id='.$assign_id.'"><img src="start.png" width="30" height="30" class="mid" title="Start"></a>';}
if($assign_game_status != 0 && $assign_game_status != 4){
echo '<a href="complete_game_question.php?assign_id='.$assign_id.'"><img src="start.png" width="30" height="30" class="mid" title="Continue"></a>';}
if($assign_game_status == 4){
echo '<a href="assign_report_student.php?assign_id='.$assign_id.'"><img src="mg.png" width="30" height="30" class="mid" title="Report"></a>&nbsp;&nbsp;
<a href="complete_game_final.php?assign_id='.$assign_id.'"><img src="gt.png" width="30" height="30" class="mid" title="Ranking"></a>';}}
echo '</p></td>';
$color = 2;}


else {
echo '<tr>
<td height="40" bgcolor="#A0B595"><p>'.$assign_type_text.'</p></td>
<td height="40" bgcolor="#A0B595"><p>'.$data_as['assign_name'].'</p></td>
<td height="40" bgcolor="#A0B595"><p>'.$assign_start_display.'</p></td>
<td height="40" bgcolor="#A0B595"><p>'.$assign_deadline_display.'</p></td>
<td height="40" bgcolor="#A0B595"><p>';
if($assign_type != '5'){
if($now < $assign_start){
echo '<b>NOT STARTED YET</b>';}
if($now > $assign_start && $now < $assign_ddl){
echo '<b style = "color: #0D3151">IN PROGRESS</b><br><b>('.round($count_na / $count_nq * 100, 0).'%)</b>';}
if($now > $assign_ddl){
echo '<b style="color: #033909">FINISHED</b>';
if($now > $assign_deadline || ($now < $assign_deadline && $release == '1')){
$sql_score = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND user_id = '".$user_id."' AND answer_valid ='1'";
$res_score = $mysqli -> query($sql_score);
$data_score = mysqli_fetch_assoc($res_score);
$score = $data_score['COUNT(*)'];
$percent = round($score / $count_nq * 100,0);
$min_a = $data_as['assign_a'];
$min_b = $data_as['assign_b'];
$min_c = $data_as['assign_c'];
$min_d = $data_as['assign_d'];
$min_e = $data_as['assign_e'];
if($s == '1'){
$min_f = $data_as['assign_f'];
$min_g = $data_as['assign_g'];}
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
echo '<br><b>'.$percent.'% - '.$letter.'</b>';}}}
if($assign_type == '5'){
if($assign_game_status == 0){
echo '<b>NOT STARTED YET</b>';}
if($assign_game_status != 0 && $assign_game_status != 4){
echo '<b style="color: #0D3151;">IN PROGRESS</b><br><b>'.round($count_na / $count_nq * 100, 0).'%</b>';}
if($assign_game_status == 4){
$sql_score = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND user_id = '".$user_id."' AND answer_valid ='1'";
$res_score = $mysqli -> query($sql_score);
$data_score = mysqli_fetch_assoc($res_score);
$score = $data_score['COUNT(*)'];
$percent = round($score / $count_nq * 100,0);
$min_a = $data_as['assign_a'];
$min_b = $data_as['assign_b'];
$min_c = $data_as['assign_c'];
$min_d = $data_as['assign_d'];
$min_e = $data_as['assign_e'];
if($s == '1'){
$min_f = $data_as['assign_f'];
$min_g = $data_as['assign_g'];}
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
echo '<b style="color: #033909;">FINISHED</b><br><b>'.$percent.'% - '.$letter.'</b>';}}
echo '</p></td>
<td height="40" bgcolor="#A0B595"><p>';
if($assign_type != '5'){
if($now < $assign_start){
echo '<a href="assign_gt.php?assign_id='.$assign_id.'"><img src="gt.png" width="30" height="30" class="mid" title="Grade Thresholds"></a>';}
if($now > $assign_start && $now < $assign_ddl){
echo '<a href="complete_start.php?assign_id='.$assign_id.'"><img src="start.png" width="30" height="30" class="mid" title="Complete"></a>&nbsp;&nbsp;';
echo '<a href="assign_gt.php?assign_id='.$assign_id.'"><img src="gt.png" width="30" height="30" class="mid" title="Grade Thresholds"></a>';}
if($now > $assign_ddl){
if($now > $assign_deadline || ($now < $assign_deadline && $release == '1')){
echo '<a href="assign_report_student.php?assign_id='.$assign_id.'"><img src="mg.png" width="30" height="30" class="mid" title="Report"></a>&nbsp;&nbsp;';}
echo '<a href="assign_gt.php?assign_id='.$assign_id.'"><img src="gt.png" width="30" height="30" class="mid" title="Grade Thresholds"></a>';}}
if($assign_type == '5'){
if($assign_game_status == 0){
echo '<a href="complete_game_start.php?assign_id='.$assign_id.'"><img src="start.png" width="30" height="30" class="mid" title="Start"></a>';}
if($assign_game_status != 0 && $assign_game_status != 4){
echo '<a href="complete_game_question.php?assign_id='.$assign_id.'"><img src="start.png" width="30" height="30" class="mid" title="Continue"></a>';}
if($assign_game_status == 4){
echo '<a href="assign_report_student.php?assign_id='.$assign_id.'"><img src="mg.png" width="30" height="30" class="mid" title="Report"></a>&nbsp;&nbsp;
<a href="complete_game_final.php?assign_id='.$assign_id.'"><img src="gt.png" width="30" height="30" class="mid" title="Ranking"></a>';}}
echo '</p></td>';
$color = 1;}}
echo '</tbody></table>';}
?>
<p><b><a href="#top">Back to the top</a></b></p>
</body></html>