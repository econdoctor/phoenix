<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$assign_id = $_GET['assign_id'];
if(empty($assign_id)){
echo 'Missing information about the assignment.';
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT user_type, user_active, user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_active = $data['user_active'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
$sql_assign = "SELECT assign_type, assign_name, assign_teacher, assign_syllabus, assign_pt FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
$assign_type = $data_assign['assign_type'];
$assign_name = $data_assign['assign_name'];
$assign_teacher = $data_assign['assign_teacher'];
$assign_pt = $data_assign['assign_pt'];
$s = $data_assign['assign_syllabus'];
if($assign_teacher != $user_id){
echo 'You are not authorized to manage this assignment.';
exit();}
if($assign_pt == '1'){
echo 'You cannot select questions by topic.';
exit();}
$sql_check_start = "SELECT COUNT(*) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_start IS NOT NULL";
$res_check_start = $mysqli -> query($sql_check_start);
$data_check_start = mysqli_fetch_assoc($res_check_start);
if($data_check_start['COUNT(*)'] > 0){
echo 'You can no longer add questions to this assignment.';
exit();}
$search_unit = $_POST['topic_unit'];
$search_module = $_POST['topic_module'];
if(empty($search_unit) && empty($search_module)){
$sql_get = "SELECT topic_unit_id, topic_hidden, topic_unit, topic_id, topic_module FROM phoenix_topics WHERE topic_syllabus = '".$s."' ORDER BY topic_unit_id ASC, topic_module_id ASC";}
if(isset($search_unit) && isset($search_module)){
$sql_get = "SELECT topic_unit_id, topic_hidden, topic_unit, topic_id, topic_module FROM phoenix_topics WHERE topic_syllabus = '".$s."' AND topic_unit_id = (CASE WHEN '".$search_unit."' != 'Any' THEN '".$search_unit."' ELSE topic_unit_id END) AND topic_module_id = (CASE WHEN '".$search_module."' != 'Any' THEN '".$search_module."' ELSE topic_module_id END) ORDER BY topic_unit_id ASC, topic_module_id ASC";}
$res_get = $mysqli -> query($sql_get);
$nr_get = mysqli_num_rows($res_get);
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
vertical-align:middle
}
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script>
function submitform(){
setTimeout(function(){
document.form1.submit(); }, 100);}
</script>
<script>
$(document).ready(function(){
$('#topic_unit').change(function() {
$("#topic_module").val('Any');})});
</script>
<script language="JavaScript">
function toggle1(source){
checkboxes = document.getElementsByName('assign_topic_1[]');
for(var i=0, n=checkboxes.length;i<n;i++){
checkboxes[i].checked = source.checked;}}
function toggle2(source){
checkboxes = document.getElementsByName('assign_topic_2[]');
for(var i=0, n=checkboxes.length;i<n;i++){
checkboxes[i].checked = source.checked;}}
function toggle3(source){
checkboxes = document.getElementsByName('assign_topic_3[]');
for(var i=0, n=checkboxes.length;i<n;i++){
checkboxes[i].checked = source.checked;}}
function toggle4(source){
checkboxes = document.getElementsByName('assign_topic_4[]');
for(var i=0, n=checkboxes.length;i<n;i++){
checkboxes[i].checked = source.checked;}}
function toggle5(source){
checkboxes = document.getElementsByName('assign_topic_5[]');
for(var i=0, n=checkboxes.length;i<n;i++){
checkboxes[i].checked = source.checked;}}
function toggle6(source){
checkboxes = document.getElementsByName('assign_topic_6[]');
for(var i=0, n=checkboxes.length;i<n;i++){
checkboxes[i].checked = source.checked;}}
</script>
<script language="JavaScript">
function preload(id){
var x = document.getElementById(id)
document.getElementById("preload").style.display = "block";
document.getElementById("content").style.display = "none";
<?php
if($assign_type != '4'){
?>
document.getElementById("students").setAttribute('style', 'width: 15%;')
document.getElementById("students").setAttribute('onclick', "document.location.href='assign_students_stats.php?assign_id=<?php echo $assign_id; ?>'");
<?php
}
?>
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
<?php
if($assign_type != '4'){
?>
<input type="button" id="students" name="students" value="STUDENTS" style="width: 15%;" onclick="preload('students');">&nbsp;&nbsp;
<?php
}
?>
<input type="button" id="questions" name="questions" value="QUESTIONS" style="width: 15%;" onclick="preload('questions');"></p>
<span class="brmedium"></span>
<div id="preload" style="display: none;">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><b style="font-size: larger; color: #033909;">LOADING...</b><br>
<img src="preload.gif" width="100"></p>
</div>
<div id="content" style="display: block;">
<p>Please select the topic(s) you want to choose questions from.</p>
<?php
if($_GET['error'] == 1){
echo '<p><b style="color: #820000;">Please select at least one topic</b></p>';}
?>
<form name="form1" method="post" action="assign_add_questions_topic.php?assign_id=<?php echo $assign_id; ?>">
<p><select name="topic_unit" id="topic_unit" onChange="submitform();"><optgroup>
<option value="Any">All units</option>
<?php
$sql1 = "SELECT DISTINCT topic_unit_id, topic_unit  FROM phoenix_topics WHERE topic_syllabus = '".$s."' ORDER BY topic_unit_id ASC";
$res1 = $mysqli -> query($sql1);
while($data1 = mysqli_fetch_assoc($res1)){
$topic_unit_id = $data1['topic_unit_id'];
$topic_unit = $data1['topic_unit'];
?>
<option value="<?php echo $topic_unit_id; ?>" <?php echo is_selected($topic_unit_id, $search_unit); ?>><?php echo $topic_unit; ?></option>
<?php
}
?>
</optgroup></select></p>
<p><select id="topic_module" name="topic_module" onchange="submitform();"><optgroup>
<?php
if(empty($search_module)){
echo '<option value="Any">All topics</option>';}
if(isset($search_module) && isset($search_unit)){
$sql2 ="SELECT topic_module_id, topic_module FROM phoenix_topics WHERE topic_syllabus = '".$s."' AND topic_unit_id = '".$search_unit."' ORDER BY topic_module_id ASC";
$res2 = $mysqli->query($sql2);
echo '<option value="Any">All topics</option>';
while($data2 = mysqli_fetch_assoc($res2)){
$topic_module_id = $data2['topic_module_id'];
$topic_module = $data2['topic_module'];
?>
<option value="<?php echo $topic_module_id; ?>" <?php echo is_selected($topic_module_id, $search_module); ?>><?php echo $topic_module; ?></option>';
<?php
}}
?>
</optgroup></select></p></form>
<?php
if($nr_get == 0){
echo '<p><em>No topics found</em></p>';}
if($nr_get > 0){
echo '<p><b><span style="color: #820000">'.$nr_get.'</span> topic(s) found</b></p>
<form method="post" action="assign_add_questions_insertt.php?assign_id='.$assign_id.'">
<table width="67%" align="center" bgcolor="#000000"><tbody>';
$color = 1;
$previous = '';
$i = 0;
while($data_get = mysqli_fetch_assoc($res_get)){
$current = $data_get['topic_unit_id'];
$topic_hidden = $data_get['topic_hidden'];
$sql_count = "SELECT COUNT(*) FROM phoenix_questions WHERE question_topic_id = '".$data_get['topic_id']."' AND question_id NOT IN (SELECT question_id FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."') AND question_repeat = '0' AND question_obsolete = '0'";
$res_count = $mysqli -> query($sql_count);
$data_count = mysqli_fetch_assoc($res_count);
$count_q = $data_count['COUNT(*)'];
if($current != $previous){
$i++;
$title = strtoupper($data_get['topic_unit']);
if($user_active == 0 && $i > 1){
echo '<tr>
<td height="50" width="10%" bgcolor="#647d57"><a href="upgrade.php"><img src="unlock.png" width="30" width="30" class="mid" title="Unlock"></a></td>
<td height="50" colspan="2" bgcolor="#647d57"><b><p>'.$title.'</p></b></td>
</tr>';}
if($user_active == 1 || ($user_active == 0 && $i == 1)){
echo '<tr>
<td height="50" width="10%" bgcolor="#647d57"><input type="checkbox" style="transform: scale(1.5);" onclick="toggle'.$i.'(this)"></td>
<td height="50" colspan="2" bgcolor="#647d57"><b><p>'.$title.'</p></b></td>
</tr>';}
$color = 1;}
if($color == 1){
if($user_active == 0 && $topic_hidden == 1){
echo '<tr>
<td height="50" width="10%" bgcolor="#769467"><a href="upgrade.php"><img src="unlock.png" width="30" width="30" class="mid" title="Unlock"></a></td>
<td height="50" bgcolor="#769467"><p>'.$data_get['topic_module'].'</p></td>
<td height="50" width="15%" bgcolor="#769467"><p><b>'.$count_q.' MCQs</b></p></td>
</tr>';}
if($user_active == 1 || ($user_active == 0 && $topic_hidden == 0)){
echo '<tr>
<td height="50" width="10%" bgcolor="#769467"><input type="checkbox" style="transform: scale(1.5);" value="'.$data_get['topic_id'].'" name="assign_topic_'.$i.'[]"></td>
<td height="50" bgcolor="#769467"><p>'.$data_get['topic_module'].'</p></td>
<td height="50" width="15%" bgcolor="#769467"><p><b>'.$count_q.' MCQs</b></p></td>
</tr>';}
$color = 2;}
else {
if($user_active == 0 && $topic_hidden == 1){
echo '<tr>
<td height="50" width="10%" bgcolor="#a0b595"><a href="upgrade.php"><img src="unlock.png" width="30" width="30" class="mid" title="Unlock"></a></td>
<td height="50" bgcolor="#a0b595"><p>'.$data_get['topic_module'].'</p></td>
<td height="50" width="15%" bgcolor="#a0b595"><p><b>'.$count_q.' MCQs</b></p></td>
</tr>';}
if($user_active == 1 || ($user_active == 0 && $topic_hidden == 0)){
echo '<tr>
<td height="50" width="10%" bgcolor="#a0b595"><input type="checkbox" style="transform: scale(1.5);" value="'.$data_get['topic_id'].'" name="assign_topic_'.$i.'[]"></td>
<td height="50" bgcolor="#a0b595"><p>'.$data_get['topic_module'].'</p></td>
<td height="50" width="15%" bgcolor="#a0b595"><p><b>'.$count_q.' MCQs</b></p></td>
</tr>';}
$color = 1;}
$previous = $current;}
echo'</tbody></table>
<p><input type="submit" value="NEXT"></p></form>
<p><a href="#top"><b>Back to the top</b></a></p>';}
?>
</div></body></html>