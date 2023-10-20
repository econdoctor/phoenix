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
if($assign_pt == '2'){
echo 'You cannot select questions by paper.';
exit();}
$sql_check_start = "SELECT COUNT(*) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_start IS NOT NULL";
$res_check_start = $mysqli -> query($sql_check_start);
$data_check_start = mysqli_fetch_assoc($res_check_start);
if($data_check_start['COUNT(*)'] > 0){
echo 'You can no longer add questions to this assignment.';
exit();}
$search_year = $_POST['paper_year'];
$search_serie = $_POST['paper_serie'];
$search_version = $_POST['paper_version'];
if(empty($search_year) && empty($search_serie) && empty($search_version)){
$sql2 = "SELECT * FROM phoenix_papers WHERE paper_syllabus = '".$s."' ORDER BY paper_year DESC, paper_serie DESC, paper_version ASC";}
if(isset($search_year) && isset($search_serie) && isset($search_version)){
$sql2 = "SELECT * FROM phoenix_papers WHERE paper_syllabus = '".$s."' AND paper_year = (CASE WHEN '".$search_year."' != 'Any' THEN '".$search_year."' ELSE paper_year END) AND paper_serie = (CASE WHEN '".$search_serie."' != 'Any' THEN '".$search_serie."' ELSE paper_serie END) AND paper_version = (CASE WHEN '".$search_version."' != 'Any' THEN '".$search_version."' ELSE paper_version END) ORDER BY paper_year DESC, paper_serie DESC, paper_version ASC";}
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
vertical-align:middle
}
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
$('#paper_year').change(function(){
$("#paper_serie").val('Any');})});
</script><script>
$(document).ready(function(){
$('#paper_year').change(function(){
$("#paper_version").val('Any');})});
</script><script>
$(document).ready(function(){
$('#paper_serie').change(function(){
$("#paper_version").val('Any');})});
</script><script>
function submitform(){
setTimeout(function(){
document.form1.submit();}, 100);}
</script>
<script language="JavaScript">
function toggle(source) {
checkboxes = document.getElementsByName('assign_paper[]');
for(var i=0, n=checkboxes.length;i<n;i++) {
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
<?php
if($_GET['error'] == 1){
echo '<p><b style="color: #820000;">Please select at least one paper</b></p>';}
?>
<p>Please select the paper(s) you want to choose questions from.</p>
<form name="form1" method="post" action="assign_add_questions_paper.php?assign_id=<?php echo $assign_id; ?>"><p>
<select name="paper_year" id="paper_year" onChange="submitform();"><optgroup>
<option value="Any">All years</option>
<?php
$sql3 = "SELECT DISTINCT paper_year FROM phoenix_papers WHERE paper_syllabus = '".$s."' ORDER BY paper_year DESC";
$res3 = $mysqli -> query($sql3);
while($data3 = mysqli_fetch_assoc($res3)){
$paper_year = $data3['paper_year'];
?>
<option value="<?php echo $paper_year; ?>"  <?php echo is_selected($paper_year, $search_year); ?>><?php echo $paper_year; ?></option>
<?php
}
?>
</optgroup></select>
&nbsp;<select name="paper_serie" id="paper_serie" onChange="submitform();"><optgroup>
<?php
if(empty($search_serie)){
echo '<option value="Any">All series</option>';}
if(isset($search_serie) && isset($search_year)){
$sql4 = "SELECT DISTINCT paper_serie FROM phoenix_papers WHERE paper_syllabus = '".$s."' AND paper_year = '".$search_year."' ORDER BY paper_serie ASC";
$res4 = $mysqli -> query($sql4);
echo '<option value="Any">All series</option>';
while($data4 = mysqli_fetch_assoc($res4)){
$paper_serie = $data4['paper_serie'];
if($paper_serie == 1){
$paper_serie_text = "m - February / March";}
if($paper_serie == 2){
$paper_serie_text = "s - May / June";}
if($paper_serie == 3){
$paper_serie_text = "w - October / November";}
if($paper_serie == 4){
$paper_serie_text = "y - Specimen";}
?>
<option value="<?php echo $paper_serie; ?>" <?php echo is_selected($paper_serie, $search_serie); ?>><?php echo $paper_serie_text; ?></option>';
<?php
}}
?>
</optgroup></select>
&nbsp;<select name="paper_version" id="paper_version" onchange="submitform();"><optgroup>
<?php
if(empty($search_version)){
echo '<option value="Any">All versions</option>';}
if(isset($search_serie) && isset($search_year) && isset($search_version)){
$sql5 = "SELECT paper_version FROM phoenix_papers WHERE paper_serie = '".$search_serie."' AND paper_year ='".$search_year."' AND paper_syllabus ='".$s."'";
$res5 = $mysqli -> query($sql5);
echo '<option value="Any">All versions</option>';
while($data5 = mysqli_fetch_assoc($res5)){
$paper_version = $data5['paper_version'];
if($paper_version > 0){
$paper_version_text = $paper_version;}
if($paper_version == 0){
$paper_version_text = "/";}
?>
<option value="<?php echo $paper_version; ?>" <?php echo is_selected($paper_version, $search_version); ?>><?php echo $paper_version_text; ?></option>';
<?php
}}
?>
</optgroup></select></form>
<?php
if($nr2 == 0){
echo '<p><em>No papers found</em></p>';}
if($nr2 > 0){
?>
<p><b><span style="color: #820000"><?php echo number_format($nr2); ?></span> paper(s) found</b></p>
<form method="post" action="assign_add_questions_insertp.php?assign_id=<?php echo $assign_id; ?>">
<table width = "50%" align="center" bgcolor="#000000"><tbody><tr>
<td height="50" width="10%" bgcolor="647d57"><input type="checkbox" style="transform: scale(1.5);" onClick="toggle(this)"></td>
<td height="50" bgcolor="#647d57"><p><b>YEAR</b></p></td>
<td height="50" bgcolor="#647d57"><p><b>SERIE</b></p></td>
<td height="50" bgcolor="#647d57"><p><b>VERSION</b></p></td>
</tr>
<?php
$color = 1;
while($data2 = mysqli_fetch_assoc($res2)){
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

if($color == 1){
echo '<tr>';
if($user_active == 0 && $data2['paper_hidden'] == 1){
echo '<td height="40" width="10%" bgcolor="#769467"><a href="upgrade.php"><img src="unlock.png" width="30" width="30" class="mid" title="Unlock"></a></td>';}
if($user_active == 1 || ($user_active == 0 && $data2['paper_hidden'] == 0)){
echo '<td height="40" width="10%" bgcolor="#769467"><input type="checkbox" style="transform: scale(1.5);" value="'.$data2['paper_id'].'" name="assign_paper[]"></td>';}
echo '<td height="40" width="20%" bgcolor="#769467"><p>'.$data2['paper_year'].'</p></td>
<td height="40" bgcolor="#769467"><p>'.$paper_serie_text.'</p></td>
<td height="40" width="20%" bgcolor="#769467"><p>'.$paper_version.'</p></td>
</tr>';
$color = 2;}
else {
echo '<tr>';
if($user_active == 0 && $data2['paper_hidden'] == 1){
echo '<td height="40" width="10%" bgcolor="#a0b595"><a href="upgrade.php"><img src="unlock.png" width="30" width="30" class="mid" title="Unlock"></a></td>';}
if($user_active == 1 || ($user_active == 0 && $data2['paper_hidden'] == 0)){
echo '<td height="40" width="10%" bgcolor="#a0b595"><input type="checkbox" style="transform: scale(1.5);" value="'.$data2['paper_id'].'" name="assign_paper[]"></td>';}
echo '<td height="40" width="20%" bgcolor="#a0b595"><p>'.$data2['paper_year'].'</p></td>
<td height="40" bgcolor="#a0b595"><p>'.$paper_serie_text.'</p></td>
<td height="40" width="20%" bgcolor="#a0b595"><p>'.$paper_version.'</p></td>
</tr>';
$color = 1;}}
echo'</tbody></table>
<p><input type="submit" value="NEXT"></p>
</form>
<p><a href="#top"><b>Back to the top</b></a></p>';}
?>
</div></body></html>