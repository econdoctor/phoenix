<?php
$group_id = $_POST['group'];
if(empty($group_id)){
$group_id = "Any";}
$order = $_POST['order'];
if(empty($order)){
$order = "course";}
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
$sql = "SELECT * FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$tz = $data['user_timezone'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
if($group_id == "Any"){
if($order == "course"){
$sql2 = "SELECT * FROM phoenix_users WHERE user_teacher = '".$user_id."' ORDER BY user_syllabus DESC";}
if($order == "ll"){
$sql2 = "SELECT * FROM phoenix_users WHERE user_teacher = '".$user_id."' ORDER BY user_last_login DESC";}
if($order == "level"){
$sql2 = "SELECT * FROM phoenix_users WHERE user_teacher = '".$user_id."' ORDER BY user_score_gen DESC";}}
if($group_id != "Any"){
if($order == "course"){
$sql2 = "SELECT * FROM phoenix_users WHERE user_teacher = '".$user_id."' AND user_id IN (SELECT user_id FROM phoenix_group_users WHERE group_id = '".$group_id."') ORDER BY user_syllabus DESC";}
if($order == "ll"){
$sql2 = "SELECT * FROM phoenix_users WHERE user_teacher = '".$user_id."' AND user_id IN (SELECT user_id FROM phoenix_group_users WHERE group_id = '".$group_id."') ORDER BY user_last_login DESC";}
if($order == "level"){
$sql2 = "SELECT * FROM phoenix_users WHERE user_teacher = '".$user_id."' AND user_id IN (SELECT user_id FROM phoenix_group_users WHERE group_id = '".$group_id."') ORDER BY user_score_gen DESC";}}
$res2 = $mysqli -> query($sql2);
$nr2 = mysqli_num_rows($res2);
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
.mid {
vertical-align: middle;
}
input[type=text] {
padding: 3px 5px;
margin: 2px 0;
box-sizing: border-box;
text-align: center;
font-weight: bold;
font-size: 18px;
border: 2px solid black;
border-radius: 4px;
width: 20%;
}
</style>
<script language="JavaScript">
function toggle(source) {
checkboxes = document.getElementsByName('manage_user[]');
for(var i=0, n=checkboxes.length;i<n;i++) {
checkboxes[i].checked = source.checked;}}
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
<p style="font-size: x-large"><b>STUDENTS</b></p>
<?php
if($nr2 == 0){
?>
<p><em>No students found</em></p>
<p>&nbsp;</p>
<form method="post" action="add_student.php">
<p><b style="font-size: larger;">ADD A STUDENT</b></p>
<p><input type="text" name="student_code" size="12" maxlength="8" placeholder="Student's ID code"></p>
<p><input type="submit" value="CONFIRM"></p>
</form>
<?php
}
if($nr2 > 0){
?>
<form action="manage_students.php" method="post">
<?php
$sqly = "SELECT * FROM phoenix_groups WHERE group_teacher = '".$user_id."' ORDER BY group_curriculum";
$resy = $mysqli -> query($sqly);
$nr_y = mysqli_num_rows($resy);
if($nr_y > 0){
echo '<select name="group" style="width: 20%" id="group" onchange="this.form.submit();"><optgroup>
<option value="Any">All groups</option>';
while($datay = mysqli_fetch_assoc($resy)){
?>
<option value="<?php echo $datay['group_id'] ?>" <?php echo is_selected($datay['group_id'], $group_id); ?>><?php echo $datay['group_name']; ?></option>
<?php
}
echo '</optgroup></select>&nbsp;&nbsp;';}
?>
<select name="order" style="width: 20%" onchange="this.form.submit();"><optgroup>
<option value="course" <?php echo is_selected("course", $order); ?>>Sort by course</option>
<option value="ll" <?php echo is_selected("ll", $order); ?>>Sort by last visit</option>
<option value="level" <?php echo is_selected("level", $order); ?>>Sort by level</option>
</optgroup></select></p></form>
<form method="post">
<p><b><span style="color: #820000"><?php echo number_format($nr2); ?></span> student(s) found</b></p>
<p><table width="80%" bgcolor="#000000" align="center"><tbody><tr>
<td height="40" bgcolor="#647d57" width="5%"><p><input type="checkbox" style="transform: scale(1.5);" onclick="toggle(this)"></p></td>
<td height="40" bgcolor="#647d57" width="30%"><p><b>NAME</b></p></td>
<td height="40" bgcolor="#647d57" width="10%"><p><b>COURSE</b></p></td>
<td height="40" bgcolor="#647d57" width="20%"><p><b>LAST VISIT</b></p></td>
<td height="40" bgcolor="#647d57" width="25%"><p><b>LEVEL & RANK</b></p></td>
<td height="40" bgcolor="#647d57" width="10%"><p><b>DETAILS</b></p></td>
</tr>
<?php
$color = 1;
while($row = mysqli_fetch_assoc($res2)){
$student_id = $row['user_id'];
$score = $row['user_score_gen'];
$s = $row['user_syllabus'];
if($s == '3'){
$s_text = "A LEVEL";}
if($s == '2'){
$s_text = "AS LEVEL";}
if($s == '1'){
$s_text = "IGCSE";}
$ll = date("Y-m-d H:i:s", strtotime($row['user_last_login']));
if($tz >= 0){
$ll_display = date('Y-m-d H:i:s', strtotime(''.$ll.' + '.$tz.' minutes'));}
if($tz < 0){
$ll_display = date('Y-m-d H:i:s', strtotime(''.$ll.' - '.abs($tz).' minutes'));}
if($score < 5){
$rank = 'Newbie';}
if($score >= 5 && $score < 10){
$rank = 'Novice';}
if($score >= 10 && $score < 15){
$rank = 'Amateur';}
if($score >= 15 && $score < 20){
$rank = 'Apprentice';}
if($score >= 20 && $score < 25){
$rank = 'Adept';}
if($score >= 25 && $score < 30){
$rank = 'Junior Economist';}
if($score >= 30 && $score < 35){
$rank = 'Senior Economist';}
if($score >= 35 && $score < 40){
$rank = 'Chief Economist';}
if($score >= 40 && $score < 45){
$rank = 'Expert';}
if($score >= 45 && $score < 50){
$rank = 'Scholar';}
if($score >= 50 && $score < 55){
$rank = 'Chairman';}
if($score >= 55 && $score < 60){
$rank = 'Veteran';}
if($score >= 60 && $score < 65){
$rank = 'Master';}
if($score >= 65 && $score < 70){
$rank = 'Grand Master';}
if($score >= 70 && $score < 75){
$rank = 'Supreme Master';}
if($score >= 75 && $score < 80){
$rank = 'Overlord';}
if($score >= 80 && $score < 85){
$rank = 'Hero';}
if($score >= 85 && $score < 90){
$rank = 'Legend';}
if($score >= 90 && $score < 95){
$rank = 'Immortal';}
if($score >= 95 && $score < 100){
$rank = 'God';}
if($score == 100){
$rank = 'Beyonder';}
if($color == 1){
echo '<tr>
<td height="40" bgcolor="#769467"><p><input type="checkbox" style="transform: scale(1.5);" value="'.$student_id.'" name="manage_user[]"></p></td>
<td height="40" bgcolor="#769467"><p>'.$row['user_title'].' '.$row['user_first_name'].' '.$row['user_last_name'].'</p></td>
<td height="40" bgcolor="#769467"><p><b>'.$s_text.'</b></p></td>
<td height="40" bgcolor="#769467"><p>'.$ll_display.'</p></td>
<td height="40" bgcolor="#769467"><p><b style="color: #0E2366">LV '.floor($score).' - '.$rank.'</b></p></td>
<td height="40" bgcolor="#769467"><p><i><b><a href="user_view.php?member_id='.$row['user_id'].'"><img src="mg.png" width="30" height="30" title="View" class="mid"></a></b></i></p></td>
</tr>';
$color = 2;}
else {
echo '<tr>
<td height="40" bgcolor="#a0b595"><p><input type="checkbox" style="transform: scale(1.5);" value="'.$student_id.'" name="manage_user[]"></p></td>
<td height="40" bgcolor="#a0b595"><p>'.$row['user_title'].' '.$row['user_first_name'].' '.$row['user_last_name'].'</p></td>
<td height="40" bgcolor="#a0b595"><p><b>'.$s_text.'</b></p></td>
<td height="40" bgcolor="#a0b595"><p>'.$ll_display.'</p></td>
<td height="40" bgcolor="#a0b595"><p><b style="color: #0E2366">LV '.floor($score).' - '.$rank.'</b></p></td>
<td height="40" bgcolor="#a0b595"><p><i><b><a href="user_view.php?member_id='.$row['user_id'].'"><img src="mg.png" width="30" height="30" title="View" class="mid"></a></b></i></p></td>
</tr>';
$color = 1;}}
?>
</tbody></table></p>
<table align="center" width="80%" bgcolor="#000000"><tbody><tr>
<td height="40" width="25%" bgcolor="#647d57"><b><p>DELETE / RESET</b></p></td>
<td height="40" width="25%" bgcolor="#647d57"><b><p>UPDATE</b></p></td>
<td height="40" width="25%" bgcolor="#647d57"><b><p>TRANSFER</b></p></td>
<td height="40" width="25%" bgcolor="#647d57"><b><p>ADD</b></p></td>
</tr><tr>
<td height="40" bgcolor="#769467"><p><input type="submit" style="width: 80%" value="DELETE" formaction="user_assoc_delete.php" onclick="return confirm('Are you sure you want to unlink your teacher account from the student accounts you selected? This decision is irreversible.')"></p>
<p><input type="submit" style="width: 80%" value="RESET" formaction="user_assoc_reset.php" onclick="return confirm('Are you sure you want to reset the accounts you selected? This decision is irreversible. Resetting an account permanently erases all of the information linked to it.')"></p></td>
<td height="40" bgcolor="#769467"><p><select name="new_syllabus" style="width: 80%"><optgroup>
<option value="" selected disabled>Select a course</option><optgroup>
<option value="1">IGCSE</option>
<option value="2">AS Level</option>
<option value="3">A Level</option>
</optgroup></select></p>
<p><input type="submit" style="width: 80%" value="CONFIRM" formaction="update_syllabus.php" onclick="return confirm('Are you sure you want to update the course taken by the students you selected? Please note that they will be automatically removed from any group that does not match their new course.')"></p></td>
<td height="40" bgcolor="#769467"><p><input type="text" style="width: 80%" name="teacher_code" size="12" maxlength="8" placeholder="Teacher's ID code"></p>
<p><input type="submit" style="width: 80%" value="CONFIRM" formaction="update_teacher.php" onclick="return confirm('Are you sure you want to transfer the accounts you selected? Once the transfer is confirmed, you will no longer be able to manage these accounts.')"></p></td>
<td height="40" bgcolor="#769467"><p><input type="text" style="width: 80%" name="student_code" size="12" maxlength="8" placeholder="Student's ID code"></p>
<p><input type="submit" style="width: 80%" value="CONFIRM" formaction="add_student.php"></p></td>
</tr></tbody></table>
<p><a href="#top"><b>Back to the top</b></a></p>
<?php
}
?>
</form>
</body></html>