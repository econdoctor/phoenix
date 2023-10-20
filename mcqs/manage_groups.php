<?php
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
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
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
.mid {
vertical-align: middle;
}
</style>
<script language="JavaScript">
function toggle(source){
checkboxes = document.getElementsByName('select_group[]');
for(var i=0, n=checkboxes.length;i<n;i++){
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
<p style="font-size: x-large"><b>GROUPS</b></p>
<p><input type="button" name="new" value="NEW GROUP" onclick="document.location.href='group_create.php';"/></p>
<?php
$sql2 = "SELECT * FROM phoenix_groups WHERE group_teacher = '".$user_id."'  ORDER BY group_curriculum DESC, group_name ASC";
$res2 = $mysqli -> query($sql2);
$nr2 = mysqli_num_rows($res2);
if($nr2 == 0){
echo '<p><em>No groups found</em></p>';}
if($nr2 > 0){
echo '<p><b><span style="color: #820000">'.number_format($nr2).'</span> group(s) found</b></p>
<form method="post" action="group_delete.php">
<p><table width="67%" bgcolor="#000000" align="center"><tbody><tr>
<td height="50" width="10%" bgcolor="#647d57"><input type="checkbox" style="transform: scale(1.5);" onclick="toggle(this);"></td>
<td height="50" width="45%" bgcolor="#647d57"><b>NAME</b></td>
<td height="50" width="15%" bgcolor="#647d57"><b>COURSE</b></td>
<td height="50" width="15%" bgcolor="#647d57"><b>MEMBERS</b></td>
<td height="50" width="15%" bgcolor="#647d57"><b>ACTIONS</b></td>
</tr>';
$color = 1;
while($data2 = mysqli_fetch_assoc($res2)){
$group_id = $data2['group_id'];
$s = $data2['group_curriculum'];
if($s == '3'){
$s_text = "A LEVEL";}
if($s == '2'){
$s_text = "AS LEVEL";}
if($s == '1'){
$s_text = "IGCSE";}
$sql4 = "SELECT * FROM phoenix_group_users WHERE group_id = '".$group_id."'";
$res4 = $mysqli -> query($sql4);
$nr4 = mysqli_num_rows($res4);
$data4 = mysqli_fetch_assoc($res4);
if($color == 1){
echo  '<tr>
<td height="50" bgcolor="#769467"><input type="checkbox" style="transform: scale(1.5);" name="select_group[]" value="'.$data2['group_id'].'"</td>
<td height="50" bgcolor="#769467">'.$data2['group_name'].'</td>
<td height="50" bgcolor="#769467"><b>'.$s_text.'</b></td>
<td height="50" bgcolor="#769467">'.number_format($nr4).'</td>
<td height="50" bgcolor="#769467"><a href="group_view.php?group_id='.$data2['group_id'].'"><img src="mg.png" width="25" height="25" title="View" class="mid"></a>&nbsp;&nbsp;
<a href="group_edit.php?group_id='.$data2['group_id'].'"><img src="mod.png" width="25" height="25" title="Edit" class="mid"></a></td>
</tr>';
$color = 2;}
else {
echo  '<tr>
<td height="50" bgcolor="#a0b595"><input type="checkbox" style="transform: scale(1.5);" name="select_group[]" value="'.$data2['group_id'].'"</td>
<td height="50" bgcolor="#a0b595">'.$data2['group_name'].'</td>
<td height="50" bgcolor="#a0b595"><b>'.$s_text.'</b></td>
<td height="50" bgcolor="#a0b595">'.number_format($nr4).'</td>
<td height="50" bgcolor="#a0b595"><a href="group_view.php?group_id='.$data2['group_id'].'"><img src="mg.png" width="25" height="25" title="View" class="mid"></a>&nbsp;&nbsp;
<a href="group_edit.php?group_id='.$data2['group_id'].'"><img src="mod.png" width="25" height="25" title="Edit" class="mid"></a></td>
</tr>';
$color = 1;}}
?>
</tbody></table></p>
<p><input type="submit" value="DELETE" onclick="return confirm('Are you sure you want to delete the group(s) you selected?')" formaction="group_delete.php"></p>
</form>
<?php
}
?>
<p><a href="#top"><b>Back to the top</b></a></p>
</body></html>
