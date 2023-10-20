<?php
$r = $_GET['r'];
if($r == 1){
sleep(1);}
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
$assign_type = $_POST['assign_type'];
$assign_course = $_POST['assign_course'];
if(empty($assign_type)){
$assign_type = "any";}
if(empty($assign_course)){
$assign_course = "any";}
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
$sql = "SELECT user_type, user_timezone, user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
$tz = $data['user_timezone'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
if($assign_type == "any" && $assign_course == "any"){
$sql_as = "SELECT assign_id, assign_name, assign_type, assign_syllabus, assign_deadline, assign_start, assign_game_status FROM phoenix_assign WHERE assign_teacher = '".$user_id."' AND assign_active = '1' ORDER BY assign_deadline DESC";}
if($assign_type != "any" && $assign_course != "any"){
$sql_as = "SELECT assign_id, assign_name, assign_type, assign_syllabus, assign_deadline, assign_start, assign_game_status FROM phoenix_assign WHERE assign_teacher = '".$user_id."' AND assign_type = '".$assign_type."' AND assign_syllabus = '".$assign_course."' AND assign_active = '1' ORDER BY assign_deadline DESC";}
if($assign_type != "any" && $assign_course == "any"){
$sql_as = "SELECT assign_id, assign_name, assign_type, assign_syllabus, assign_deadline, assign_start, assign_game_status FROM phoenix_assign WHERE assign_teacher = '".$user_id."' AND assign_type = '".$assign_type."' AND assign_active = '1' ORDER BY assign_deadline DESC";}
if($assign_type == "any" && $assign_course != "any"){
$sql_as = "SELECT assign_id, assign_name, assign_type, assign_syllabus, assign_deadline, assign_start, assign_game_status FROM phoenix_assign WHERE assign_teacher = '".$user_id."' AND assign_syllabus = '".$assign_course."' AND assign_active = '1' ORDER BY assign_deadline DESC";}
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
width: 15%;
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
vertical-align:middle
}
</style>
<script language="JavaScript">
function toggle(source){
checkboxes = document.getElementsByName('assign[]');
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
<p style="font-size: x-large"><b>ASSIGNMENTS</b></p><p>
<input type="button" name="assign_new" style="width: 20%" value="NEW ASSIGNMENT" onclick="document.location.href='assign_new.php';"/>&nbsp;
<form method="post" action="assign.php">
<p><select name="assign_course" style="width: 20%" onchange="this.form.submit();"><optgroup>
<option value="any">All courses</option>
<option value="1" <?php if($assign_course == '1') { echo 'selected'; } ?>>IGCSE</option>
<option value="2" <?php if($assign_course == '2') { echo 'selected'; } ?>>AS Level</option>
<option value="3" <?php if($assign_course == '3') { echo 'selected'; } ?>>A Level</option>
</optgroup></select>&nbsp;
<select name="assign_type" style="width: 20%" onchange="this.form.submit();"><optgroup>
<option value="any">All types</option>
<option value="1" <?php if($assign_type == '1') { echo 'selected'; } ?>>1</option>
<option value="2" <?php if($assign_type == '2') { echo 'selected'; } ?>>2</option>
<option value="3" <?php if($assign_type == '3') { echo 'selected'; } ?>>3</option>
<option value="4" <?php if($assign_type == '4') { echo 'selected'; } ?>>4</option>
<option value="5" <?php if($assign_type == '5') { echo 'selected'; } ?>>5</option>


</optgroup></select></p></form>
<?php
if($nr_as == 0){
echo '<p><em>No assignments found.</em></p>';}
if($nr_as > 0){
echo '<p><b><span style="color: #820000">'.$nr_as.'</span> assignment(s) found</b></p>
<form action="assign_delete.php" method="post">
<p><table width="80%" bgcolor="#000000" align="center"><tbody><tr>
<td height="40" width="5%" bgcolor="647d57"><p><input type="checkbox" style="transform: scale(1.5);" onClick="toggle(this);"></p></td>
<td height="40" width="10%" bgcolor="647d57"><p><b>COURSE</b></p></td>
<td height="40" width="15%" bgcolor="647d57"><p><b>TYPE</b></p></td>
<td height="40" width="30%" bgcolor="647d57"><p><b>NAME</b></p></td>
<td height="40" width="20%" bgcolor="647d57"><p><b>STATUS</b></p></td>
<td height="40" width="20%" bgcolor="647d57"><p><b>ACTIONS</b></p></td>
</tr>';
$color = 1;
while($data_as = mysqli_fetch_assoc($res_as)){
$assign_id = $data_as['assign_id'];
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
$assign_syllabus = $data_as['assign_syllabus'];
$assign_game_status = $data_as['assign_game_status'];
if($assign_syllabus == '3'){
$s_text = "A LEVEL";}
if($assign_syllabus == '2'){
$s_text = "AS LEVEL";}
if($assign_syllabus == '1'){
$s_text = 'IGCSE';}
$assign_deadline = date("Y-m-d H:i:s", strtotime($data_as['assign_deadline']));
$assign_start = date("Y-m-d H:i:s", strtotime($data_as['assign_start']));
if($now < $assign_start){
$status = '<b>NOT STARTED YET</b>';}
if($now > $assign_start && $now < $assign_deadline){
$status = '<span style="color: #0D3151"><b>IN PROGRESS</b></span>';}
if($now > $assign_deadline){
$status = '<span style="color: #033909"><b>FINISHED</b></span>';}
if($assign_type == '4'){
$status = '<b>OFFLINE</b>';}
if($assign_type == '5' && $assign_game_status == '0'){
$status = '<b>NOT STARTED YET</b>';}
if($assign_type == '5' && $assign_game_status != '0' && $assign_game_status != '4'){
$status = '<span style="color: #0D3151"><b>IN PROGRESS</b></span>';}
if($assign_type == '5' && $assign_game_status == '4'){
$status = '<span style="color: #033909"><b>FINISHED</b></span>';}
if($color == 1){
echo '<tr>
<td height="40" bgcolor="#769467"><p><input type="checkbox" style="transform: scale(1.5);" value="'.$data_as['assign_id'].'" name="assign[]"></td>
<td height="40" bgcolor="#769467"><p><b>'.$s_text.'</b></p></td>
<td height="40" bgcolor="#769467"><p>'.$assign_type_text.'</p></td>
<td height="40" bgcolor="#769467"><p>'.$data_as['assign_name'].'</p></td>
<td height="40" bgcolor="#769467"><p><b>'.$status.'</b></p></td>
<td height="40" bgcolor="#769467"><p>
<a href="assign_info.php?assign_id='.$data_as['assign_id'].'"><img src="mg.png" width="30" height="30" class="mid" title="View"></a>&nbsp;';
if($assign_type == '5' && $assign_game_status == '0'){
echo '<a href="assign_game_start.php?assign_id='.$data_as['assign_id'].'"><img src="start.png" width="30" height="30" class="mid" title="Start"></a>&nbsp;';}
if($assign_type == '5' && $assign_game_status != '0' && $assign_game_status != '4'){
echo '<a href="assign_game_question.php?assign_id='.$data_as['assign_id'].'"><img src="start.png" width="30" height="30" class="mid" title="Continue"></a>&nbsp;
<a href="assign_game_terminate.php?assign_id='.$data_as['assign_id'].'"><img src="terminate.png" width="30" height="30" class="mid" title="Terminate"></a>&nbsp;';}
if($assign_type == '5' && $assign_game_status == '4'){
echo '&nbsp;<a href="assign_game_final.php?assign_id='.$data_as['assign_id'].'"><img src="gt.png" width="25" height="30" class="mid" title="Final ranking"></a>&nbsp;&nbsp;';}
if($assign_type != '4'){
echo '<a href="assign_copy.php?assign_id='.$data_as['assign_id'].'"><img src="copy.png" width="30" height="30" class="mid" title="Copy"></a>&nbsp;&nbsp;';}
if($now < $assign_start){
?>
<a href="assign_start_now.php?assign_id=<?php echo $data_as['assign_id']; ?>" onclick="return confirm('Are you sure you want to start this assignment?')"><img src="start.png" width="30" height="30" class="mid" title="Start"></a>&nbsp;
<?php
}
if($now > $assign_start && $now < $assign_deadline){
?>
<a href="assign_terminate.php?assign_id=<?php echo $data_as['assign_id']; ?>" onclick="return confirm('Are you sure you want to terminate this assignment?')"><img src="terminate.png" width="30" height="30" class="mid" title="Terminate"></a>&nbsp;
<?php
}
if($assign_type == '4'){
echo '&nbsp;&nbsp;';}
echo '<a href="assign_download.php?assign_id='.$data_as['assign_id'].'"><img src="download.png" width="30" height="30" class="mid" title="Download"></a></p></td></tr>';
$color = 2;}
else {
echo '<tr>
<td height="40" bgcolor="#a0b595"><p><input type="checkbox" style="transform: scale(1.5);" value="'.$data_as['assign_id'].'" name="assign[]"></td>
<td height="40" bgcolor="#a0b595"><p><b>'.$s_text.'</b></p></td>
<td height="40" bgcolor="#a0b595"><p>'.$assign_type_text.'</p></td>
<td height="40" bgcolor="#a0b595"><p>'.$data_as['assign_name'].'</p></td>
<td height="40" bgcolor="#a0b595"><p><b>'.$status.'</b></p></td>
<td height="40" bgcolor="#a0b595"><p>
<a href="assign_info.php?assign_id='.$data_as['assign_id'].'"><img src="mg.png" width="30" height="30" class="mid" title="View"></a>&nbsp;';
if($assign_type == '5' && $assign_game_status == '0'){
echo '<a href="assign_game_start.php?assign_id='.$data_as['assign_id'].'"><img src="start.png" width="30" height="30" class="mid" title="Start"></a>&nbsp;';}
if($assign_type == '5' && $assign_game_status != '0' && $assign_game_status != '4'){
echo '<a href="assign_game_question.php?assign_id='.$data_as['assign_id'].'"><img src="start.png" width="30" height="30" class="mid" title="Continue"></a>&nbsp;
<a href="assign_game_terminate.php?assign_id='.$data_as['assign_id'].'"><img src="terminate.png" width="30" height="30" class="mid" title="Terminate"></a>&nbsp;';}
if($assign_type == '5' && $assign_game_status == '4'){
echo '&nbsp;<a href="assign_game_final.php?assign_id='.$data_as['assign_id'].'"><img src="gt.png" width="25" height="30" class="mid" title="Final ranking"></a>&nbsp;&nbsp;';}
if($assign_type != '4'){
echo '<a href="assign_copy.php?assign_id='.$data_as['assign_id'].'"><img src="copy.png" width="30" height="30" class="mid" title="Copy"></a>&nbsp;&nbsp;';}
if($now < $assign_start){
?>
<a href="assign_start_now.php?assign_id=<?php echo $data_as['assign_id']; ?>" onclick="return confirm('Are you sure you want to start this assignment?')"><img src="start.png" width="30" height="30" class="mid" title="Start"></a>&nbsp;
<?php
}
if($now > $assign_start && $now < $assign_deadline){
?>
<a href="assign_terminate.php?assign_id=<?php echo $data_as['assign_id']; ?>" onclick="return confirm('Are you sure you want to terminate this assignment?')"><img src="terminate.png" width="30" height="30" class="mid" title="Terminate"></a>&nbsp;
<?php
}
if($assign_type == '4'){
echo '&nbsp;&nbsp;';}
echo '<a href="assign_download.php?assign_id='.$data_as['assign_id'].'"><img src="download.png" width="30" height="30" class="mid" title="Download"></a></p></td></tr>';
$color = 1;}}
?>
</tbody></table></p>
<input type="submit" value="DELETE" onclick="return confirm('Are you sure you want to delete the assignment(s) you selected?')"></p>
<?php
}
?>
<p><a href="#top"><b>Back to the top</b></a></p>
</body></html>