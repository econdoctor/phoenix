<?php
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
$sql_del = "SELECT permission_id FROM phoenix_permissions WHERE permission_expire != '0000-00-00 00:00:00' AND permission_expire <= '".$now."'";
$res_del = $mysqli -> query($sql_del);
while($data_del = mysqli_fetch_assoc($res_del)){
$permission_id = $data_del['permission_id'];
$sql_del1 = "DELETE FROM phoenix_permissions WHERE permission_id = '".$permission_id."'";
$res_del1 = $mysqli -> query($sql_del1);
$sql_del2 = "DELETE FROM phoenix_permissions_users WHERE permission_id = '".$permission_id."'";
$res_del2 = $mysqli -> query($sql_del2);
$sql_del3 = "DELETE FROM phoenix_permissions_papers WHERE permission_id = '".$permission_id."'";
$res_del3 = $mysqli -> query($sql_del3);
$sql_del4 = "DELETE FROM phoenix_permissions_topics WHERE permission_id = '".$permission_id."'";
$res_del4 = $mysqli -> query($sql_del4);}
$sql_perm = "SELECT * FROM phoenix_permissions WHERE permission_teacher = '".$user_id."' AND permission_active = '1' ORDER BY permission_id DESC";
$res_perm = $mysqli -> query($sql_perm);
$count = mysqli_num_rows($res_perm);
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
checkboxes = document.getElementsByName('rule[]');
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
<p style="font-size: x-large"><b>PERMISSIONS</b></p><p>
<p><b style="color: #820000">Permissions allow you to hide questions from your students.</b></p>
<input type="button" name="rule_new" width="25%" value="NEW RULE" onclick="document.location.href='permission_new_rule_course.php';"/>&nbsp;
<?php
if($count == 0){
echo '<p><em>No rules found</em></p>';}
if($count > 0){
echo '<p><b><span style="color: #820000">'.$count.'</span> rule(s) found</b></p>
<form method="post" action="permission_delete.php?id='.$perm_id.'">
<table width="50%" bgcolor="#000000" align="center"><tbody><tr>
<td bgcolor="647d57" height="50"><input type="checkbox" style="transform: scale(1.5);" onClick="toggle(this)"></td>
<td bgcolor="647d57" height="50"><b>COURSE</b></td>
<td bgcolor="647d57" height="50"><b>TYPE</b></td>
<td bgcolor="647d57" height="50"><b>EXPIRATION</b></td>
<td bgcolor="647d57" height="50"><b>VIEW</b></td>
</tr>';
$color = 1;
while($data_perm = mysqli_fetch_assoc($res_perm)){
$expire = $data_perm['permission_expire'];
$expire_date = date('Y-m-d H:i:s', strtotime(''.$expire.''));
if($tz >= 0){
$expire_tz = date('Y-m-d H:i:s', strtotime(''.$expire_date.' + '.$tz.' minutes'));}
if($tz < 0){
$expire_tz = date('Y-m-d H:i:s', strtotime(''.$expire_date.' - '.abs($tz).' minutes'));}
$created = $data_perm['permission_created'];
$created_date = date('Y-m-d H:i:s', strtotime(''.$created.''));
if($tz >= 0){
$created_tz = date('Y-m-d H:i:s', strtotime(''.$created_date.' + '.$tz.' minutes'));}
if($tz < 0){
$created_tz = date('Y-m-d H:i:s', strtotime(''.$created_date.' - '.abs($tz).' minutes'));}
if($expire == "0000-00-00 00:00:00"){
$expire_tz = "N/A";}
if($data_perm['permission_syllabus'] == '3'){
$s_text = "A LEVEL";}
if($data_perm['permission_syllabus'] == '2'){
$s_text = "AS LEVEL";}
if($data_perm['permission_syllabus'] == '1'){
$s_text = "IGCSE";}
if($data_perm['permission_type'] == '1'){
$type_text = "Papers";}
if($data_perm['permission_type'] == '2'){
$type_text = "Topics";}
if($color == 1){
echo '<tr>
<td height="50" bgcolor="#769467"><input type="checkbox" style="transform: scale(1.5);" value="'.$data_perm['permission_id'].'" name="rule[]"></td>
<td height="50" bgcolor="#769467"><b>'.$s_text.'</b></td>
<td height="50" bgcolor="#769467">'.$type_text.'</td>
<td height="50" bgcolor="#769467">'.$expire_tz.'</td>
<td height="50" bgcolor="#769467"><a href="permission_view.php?id='.$data_perm['permission_id'].'"><img src="mg.png" width="30" height="30" class="mid"></td>
</tr>';
$color = 2;}
else {
echo '<tr>
<td height="50" bgcolor="#a0b595"><input type="checkbox" style="transform: scale(1.5);" value="'.$data_perm['permission_id'].'" name="rule[]"></td>
<td height="50" bgcolor="#a0b595"><b>'.$s_text.'</b></td>
<td height="50" bgcolor="#a0b595">'.$type_text.'</td>
<td height="50" bgcolor="#a0b595">'.$expire_tz.'</td>
<td height="50" bgcolor="#a0b595"><a href="permission_view.php?id='.$data_perm['permission_id'].'"><img src="mg.png" width="30" height="30" class="mid"></td>
</tr>';
$color = 1;}}
?>
</tbody></table>
<p><input type="submit" value="DELETE" onclick="return confirm('Are you sure you want to delete the rule(s) you selected?')"></p>
</form>
<?php
}
?>
<p><a href="#top"><b>Back to the top</b></a></p>
</body></html>