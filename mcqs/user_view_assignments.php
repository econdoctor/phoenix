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
$sql2 = "SELECT user_title, user_first_name, user_last_name, user_teacher, user_syllabus FROM phoenix_users WHERE user_id = '".$member_id."'";
$res2 = $mysqli -> query($sql2);
$data2 = mysqli_fetch_assoc($res2);
if($user_id != $data2['user_teacher']){
echo 'You are not authorized to manage this account.';
exit();}
$sql_refresh = "SELECT assign_id FROM phoenix_assign_users WHERE student_id = '".$member_id."' AND student_refresh = '1'";
$res_refresh = $mysqli -> query($sql_refresh);
while($data_refresh = mysqli_fetch_assoc($res_refresh)){
$assign_id = $data_refresh['assign_id'];
$sql1 = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND user_id = '".$member_id."'";
$res1 = $mysqli -> query($sql1);
$data1 = mysqli_fetch_assoc($res1);
$num_answers = $data1['COUNT(*)'];
$sql3 = "SELECT COUNT(*) FROM phoenix_answers WHERE answer_type = '3' AND assign_id = '".$assign_id."' AND user_id = '".$member_id."' AND answer_valid = '1'";
$res3 = $mysqli -> query($sql3);
$data3 = mysqli_fetch_assoc($res3);
$num_correct = $data3['COUNT(*)'];
$sql4 = "UPDATE phoenix_assign_users SET num_answers = '".$num_answers."', score = '".$num_correct."', student_refresh = '0' WHERE assign_id = '".$assign_id."' AND student_id = '".$member_id."'";
$res4 = $mysqli -> query($sql4);}
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
<input type="button" name="browse" value="BROWSE" style="font-size: x-large; width: 30%; background-color: #ffc000;  color: #3b2c00; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='browse.php';"/>&nbsp;&nbsp;
<input type="button" name="manage" value="MANAGE" style="font-size: x-large; width: 30%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='manage.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 30%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='assign.php';"/>
</td></tr></tbody></table>
<p style="font-size: x-large"><b><?php echo strtoupper($data2['user_title'].' '.$data2['user_first_name'].' '.$data2['user_last_name']); ?></b></p>
<p><input type="button" name="practice" value="PRACTICE" style="width: 15%;" onclick="document.location.href='user_view_practice.php?member_id=<?php echo $member_id; ?>&s=<?php echo $data2['user_syllabus']; ?>';">&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="width: 15%; cursor: default; background-color: black;">&nbsp;&nbsp;
<input type="button" name="permissions" value="PERMISSIONS" style="width: 15%;" onclick="document.location.href='user_view_permissions.php?member_id=<?php echo $member_id; ?>'"></p>
<?php
$sql_assign = "SELECT phoenix_assign.assign_id, assign_deadline, assign_start, assign_name, assign_type, assign_syllabus, assign_a, assign_b, assign_c, assign_d, assign_e, assign_f, assign_g, assign_release, assign_nq, num_answers, score FROM phoenix_assign_users INNER JOIN phoenix_assign ON phoenix_assign.assign_id = phoenix_assign_users.assign_id WHERE student_id = '".$member_id."' AND assign_active = '1' ORDER BY assign_deadline DESC";
$res_assign = $mysqli -> query($sql_assign);
$nr_assign = mysqli_num_rows($res_assign);
if($nr_assign == 0){
echo '<span class="brmedium"></span><p><em>No assignments found</em></p>';}
if($nr_assign > 0){
echo '<span class="brmedium"></span>
<table align="center" bgcolor="#000000" width="72%"><tbody>
<tr>
<td height="40" width="15%" bgcolor="#647d57"><b><p>TYPE</p></b></td>
<td height="40" width="40%" bgcolor="#647d57"><b><p>NAME</p></b></td>
<td height="40" width="30%" bgcolor="#647d57"><b><p>STATUS</p></b></td>
<td height="40" width="15%" bgcolor="#647d57"><b><p>REPORT</p></b></td>
</tr>';
$color = 1;
while($data_assign = mysqli_fetch_assoc($res_assign)){
$assign_id = $data_assign['assign_id'];
$assign_deadline = date("Y-m-d H:i:s", strtotime($data_assign['assign_deadline']));
if($user_timezone >= 0){
$assign_deadline_display = date('Y-m-d H:i:s', strtotime(''.$assign_deadline.' + '.$user_timezone.' minutes'));}
if($user_timezone < 0){
$assign_deadline_display = date('Y-m-d H:i:s', strtotime(''.$assign_deadline.' - '.abs($user_timezone).' minutes'));}
$assign_start = date("Y-m-d H:i:s", strtotime($data_assign['assign_start']));
if($user_timezone >= 0){
$assign_start_display = date('Y-m-d H:i:s', strtotime(''.$assign_start.' + '.$user_timezone.' minutes'));}
if($user_timezone < 0){
$assign_start_display = date('Y-m-d H:i:s', strtotime(''.$assign_start.' - '.abs($user_timezone).' minutes'));}
$assign_name = $data_assign['assign_name'];
$assign_type = $data_assign['assign_type'];
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
$assign_syllabus = $data_assign['assign_syllabus'];
$assign_min_a = $data_assign['assign_a'];
$assign_min_b = $data_assign['assign_b'];
$assign_min_c = $data_assign['assign_c'];
$assign_min_d = $data_assign['assign_d'];
$assign_min_e = $data_assign['assign_e'];
if($assign_syllabus == '1'){
$assign_min_f = $data_assign['assign_f'];
$assign_min_g = $data_assign['assign_g'];}
$release = $data_assign['assign_release'];
$assign_nq = $data_assign['assign_nq'];
$num_answers = $data_assign['num_answers'];
$sql_end = "SELECT assign_student_end FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND student_id = '".$member_id."'";
$res_end = $mysqli -> query($sql_end);
$data_end = mysqli_fetch_assoc($res_end);
$assign_end = $data_end['assign_student_end'];
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
if($now > $assign_end){
$score = $data_assign['score'];
$percent = round($score / $assign_nq * 100, 0);
if($percent >= $assign_min_a){
$letter_assign = "A";}
if($percent < $assign_min_a && $percent >= $assign_min_b){
$letter_assign = "B";}
if($percent < $assign_min_b && $percent >= $assign_min_c){
$letter_assign = "C";}
if($percent < $assign_min_c && $percent >= $assign_min_d){
$letter_assign = "D";}
if($percent < $assign_min_d && $percent >= $assign_min_e){
$letter_assign = "E";}
if($assign_syllabus != '1' && $percent < $assign_min_e){
$letter_assign = "U";}
if($assign_syllabus == '1' && $percent < $assign_min_e && $percent >= $assign_min_f){
$letter_assign = "F";}
if($assign_syllabus == '1' && $percent < $assign_min_f && $percent >= $assign_min_g){
$letter_assign = "G";}
if($assign_syllabus == '1' && $percent < $assign_min_g){
$letter_assign = "U";}}
if($color == 1){
echo '<tr>
<td height="40" bgcolor="#769467"><p>'.$assign_type_text.'</p></td>
<td height="40" bgcolor="#769467"><p><a href="assign_info.php?assign_id='.$assign_id.'">'.$assign_name.'</a></p></td>
<td height="40" bgcolor="#769467"><p>';
if($now < $assign_start){
echo '<b>NOT STARTED YET</b>';}
if($now >= $assign_start && $now < $assign_end){
echo '<b style="color: #0D3151">IN PROGRESS</b><br><b>('.round($num_answers / $assign_nq * 100, 0).'%)</b>';}
if($now >= $assign_end){
echo '<b style="color: #033909">FINISHED</b><br>
<b>'.$percent.'% - '.$letter_assign.'</b>';}
echo '</p></td>
<td height="40" bgcolor="#769467"><p>';
if($now < $assign_start){
echo '<img src="cross.png" width="30" class="mid">';}
if($now >= $assign_start && $now < $assign_end){
echo '<img src="cross.png" width="30" class="mid">';}
if($now >= $assign_end){
echo '<a href="assign_report_teacher.php?assign_id='.$assign_id.'&student_id='.$member_id.'"><img src="mg.png" width="30" title="View" class="mid"></a>';}
echo '</p></td></tr>';
$color = 2;}
else {
echo '<tr>
<td height="40" bgcolor="#a0b595"><p>'.$assign_type_text.'</p></td>
<td height="40" bgcolor="#a0b595"><p><a href="assign_info.php?assign_id='.$assign_id.'">'.$assign_name.'</a></p></td>
<td height="40" bgcolor="#a0b595"><p>';
if($now < $assign_start){
echo '<b>NOT STARTED YET</b>';}
if($now >= $assign_start && $now < $assign_end){
echo '<b style="color: #0D3151">IN PROGRESS</b><br><b>('.round($num_answers / $assign_nq * 100, 0).'%)</b>';}
if($now >= $assign_end){
echo '<b style="color: #033909">FINISHED</b><br>
<b>'.$percent.'% - '.$letter_assign.'</b>';}
echo '</p></td>
<td height="40" bgcolor="#a0b595"><p>';
if($now < $assign_start){
echo '<img src="cross.png" width="30" class="mid">';}
if($now >= $assign_start && $now < $assign_end){
echo '<img src="cross.png" width="30" class="mid">';}
if($now >= $assign_end){
echo '<a href="assign_report_teacher.php?assign_id='.$assign_id.'&student_id='.$member_id.'"><img src="mg.png" width="30" title="View" class="mid"></a>';}
echo '</p></td>
</tr>';
$color = 1;}}
echo '</tbody></table>';}
?>
<p><b><a href="#top">Back to the top</a></b></p>
</body></html>