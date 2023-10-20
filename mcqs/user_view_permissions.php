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
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
$sql_refresh = "SELECT permission_id FROM phoenix_permissions WHERE permission_expire != '0000-00-00 00:00:00' AND permission_expire <= '".$now."'";
$res_refresh = $mysqli -> query($sql_refresh);
while($data_refresh = mysqli_fetch_assoc($res_refresh)){
$perm_id = $data_refresh['permission_id'];
$sql_del1 = "DELETE FROM phoenix_permissions WHERE permission_id = '".$perm_id."'";
$res_del1 = $mysqli -> query($sql_del1);
$sql_del2 = "DELETE FROM phoenix_permissions_users WHERE permission_id = '".$perm_id."'";
$res_del2 = $mysqli -> query($sql_del2);
$sql_del3 = "DELETE FROM phoenix_permissions_papers WHERE permission_id = '".$perm_id."'";
$res_del3 = $mysqli -> query($sql_del3);
$sql_del4 = "DELETE FROM phoenix_permissions_topics WHERE permission_id = '".$perm_id."'";
$res_del4 = $mysqli -> query($sql_del4);}
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
<p><input type="button" name="practice" value="PRACTICE" style="width: 15%;" onclick="document.location.href='user_view_practice.php?member_id=<?php echo $member_id; ?>&s=<?php echo $data2['user_syllabus']; ?>'">&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="width: 15%;" onclick="document.location.href='user_view_assignments.php?member_id=<?php echo $member_id; ?>'">&nbsp;&nbsp;
<input type="button" name="permissions" value="PERMISSIONS" style="width: 15%; cursor: default; background-color: black;"></p>
<span class="brmedium"></span>
<p><input type="button" id="button_papers" name="papers" value="PAPERS" onclick="papers();"/>&nbsp;&nbsp;
<input type="button" id="button_topics" name="topics" value="TOPICS" onclick="topics();"/></p>
<span class="brmedium"></span>
<div id="papers" style="display:none;">
<?php
$sql_papers = "SELECT phoenix_permissions_users.permission_id, paper_id, permission_expire FROM phoenix_permissions_users INNER JOIN phoenix_permissions ON phoenix_permissions_users.permission_id = phoenix_permissions.permission_id INNER JOIN phoenix_permissions_papers ON phoenix_permissions_users.permission_id = phoenix_permissions_papers.permission_id WHERE phoenix_permissions_users.student_id = '".$member_id."' AND phoenix_permissions.permission_type = '1'";
$res_papers = $mysqli -> query($sql_papers);
$nr_papers = mysqli_num_rows($res_papers);
if($nr_papers == 0){
echo '<p><em>No papers found</em></p>';}
if($nr_papers > 0){
echo '<table width="50%" bgcolor="#000000" align="center"><tbody>
<tr>
<td height="40" bgcolor="#647d57" width="15%"><b>YEAR</b></td>
<td height="40" bgcolor="#647d57" width="40%"><b>SERIE</b></td>
<td height="40" bgcolor="#647d57" width="15%"><b>VERSION</b></td>
<td height="40" bgcolor="#647d57" width="30%"><b>EXPIRATION</b></td>
</tr>';
$color = 1;
while($data_papers = mysqli_fetch_assoc($res_papers)){
$paper_id = $data_papers['paper_id'];
if($data_papers['permission_expire'] == '0000-00-00 00:00:00'){
$expiration_display = "N/A";}
if($data_papers['permission_expire'] != '0000-00-00 00:00:00'){
$expiration = date("Y-m-d H:i", strtotime($data_papers['permission_expire']));
if($user_timezone >= 0){
$expiration_display = date('Y-m-d H:i', strtotime(''.$expiration.' + '.$user_timezone.' minutes'));}
if($user_timezone < 0){
$expiration_display = date('Y-m-d H:i', strtotime(''.$expiration.' - '.abs($user_timezone).' minutes'));}}
$sql_info = "SELECT paper_year, paper_serie, paper_version FROM phoenix_papers WHERE paper_id = '".$paper_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
$paper_serie = $data_info['paper_serie'];
if($paper_serie == 1){
$paper_serie_text = "m - February / March";}
if($paper_serie == 2){
$paper_serie_text = "s - May / June";}
if($paper_serie == 3){
$paper_serie_text = "w - October / November";}
if($paper_serie == 4){
$paper_serie_text = "y - Specimen";}
$paper_version = $data_info['paper_version'];
if($paper_version == 0){
$paper_version = "/";}
if($color == 1){
echo '<tr>
<td height="40" bgcolor="#769467">'.$data_info['paper_year'].'</td>
<td height="40" bgcolor="#769467">'.$paper_serie_text.'</td>
<td height="40" bgcolor="#769467">'.$paper_version.'</td>
<td height="40" bgcolor="#769467"><b>'.$expiration_display.'</b></td>
</tr>';
$color = 2;}
else {
echo '<tr>
<td height="40" bgcolor="#a0b595">'.$data_info['paper_year'].'</td>
<td height="40" bgcolor="#a0b595">'.$paper_serie_text.'</td>
<td height="40" bgcolor="#a0b595">'.$paper_version.'</td>
<td height="40" bgcolor="#a0b595"><b>'.$expiration_display.'</b></td>
</tr>';
$color = 1;}}
echo '</tbody></table>';
?>
<p><a href="#top"><b>Back to the top</b></a></p>
<?php
}
?>
</div>
<div id="topics" style="display:none;">
<?php
$sql_topics = "SELECT phoenix_permissions_users.permission_id, topic_id, permission_expire FROM phoenix_permissions_users INNER JOIN phoenix_permissions ON phoenix_permissions_users.permission_id = phoenix_permissions.permission_id INNER JOIN phoenix_permissions_topics ON phoenix_permissions_users.permission_id = phoenix_permissions_topics.permission_id WHERE phoenix_permissions_users.student_id = '".$member_id."' AND phoenix_permissions.permission_type = '2'";
$res_topics = $mysqli -> query($sql_topics);
$nr_topics = number_format(mysqli_num_rows($res_topics));
if($nr_topics == 0){
echo '<p><em>No topics found</em></p>';}
if($nr_topics > 0) {
echo '<table width = "60%" align="center" bgcolor="#000000"><tbody>';
$color = 1;
echo '<tr>
<td height="40" width="75%" bgcolor="#647d57"><b>TOPIC</b></td>
<td height="40" width="25%" bgcolor="#647d57"><b>EXPIRATION</b></td>
</tr>';
while($data_topics = mysqli_fetch_assoc($res_topics)){
$topic_id = $data_topics['topic_id'];
if($data_topics['permission_expire'] == '0000-00-00 00:00:00'){
$expiration_display = "N/A";}
if($data_topics['permission_expire'] != '0000-00-00 00:00:00'){
$expiration = date("Y-m-d H:i", strtotime($data_topics['permission_expire']));
if($user_timezone >= 0){
$expiration_display = date('Y-m-d H:i', strtotime(''.$expiration.' + '.$user_timezone.' minutes'));}
if($user_timezone < 0){
$expiration_display = date('Y-m-d H:i', strtotime(''.$expiration.' - '.abs($user_timezone).' minutes'));}}
$sql_info = "SELECT topic_unit, topic_module FROM phoenix_topics WHERE topic_id = '".$topic_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
if($color == 1){
echo '<tr>
<td height="40" bgcolor="#769467">'.$data_info['topic_module'].'</td>
<td height="40" bgcolor="#769467"><b>'.$expiration_display.'</b></td>
</tr>';
$color = 2;}
else {
echo '<tr>
<td height="40" bgcolor="#a0b595">'.$data_info['topic_module'].'</td>
<td height="40" bgcolor="#a0b595"><b>'.$expiration_display.'</b></td>
</tr>';
$color = 1;}}
echo'</tbody></table>';
?>
<p><a href="#top"><b>Back to the top</b></a></p>
<?php
}
?>
</div>
</body></html>