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
$group_id = $_GET['group_id'];
if(empty($group_id)){
echo 'Missing info about the group you intend to view. Please go back and try again.';
exit();}
$sql2 = "SELECT * FROM phoenix_groups WHERE group_id ='".$group_id."'";
$res2 = $mysqli -> query($sql2);
$data2 = mysqli_fetch_assoc($res2);
$group_name = $data2['group_name'];
$group_teacher = $data2['group_teacher'];
if($user_id != $group_teacher){
echo 'You are not authorized to manage this group';
exit();}
$sql3 = "SELECT * FROM phoenix_group_users WHERE group_id ='".$group_id."'";
$res3 = $mysqli -> query($sql3);
$nr3 = mysqli_num_rows($res3);
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
</style>
<script language="JavaScript">
function toggle(source) {
checkboxes = document.getElementsByName('select_members[]');
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
<p style="font-size: x-large"><b><?php echo strtoupper($group_name)?></b></p>
<?php
if($nr3 == 0){
echo '<p><em>No students found</em></p>';}
?>
<p><input type="button" name="add" value="ADD MEMBERS" onclick="document.location.href='group_user_add.php?group_id=<?php echo $group_id; ?>';"></p>
<?php
if($nr3 > 0){
?>
<p><b><span style="color: #820000"><?php echo $nr3; ?></span> student(s) found</b></p>
<form method="post" action="group_user_delete.php?group_id=<?php echo $group_id; ?>">
<p><table width="33%" bgcolor="#000000" align="center"><tbody><tr>
<td height="50" width="20%" bgcolor="#647d57"><input type="checkbox" style="transform: scale(1.5);" onclick="toggle(this)"></td>
<td height="50" width="80%" bgcolor="#647d57"><b>NAME</b></td>
</tr>
<?php
$color = 1;
while($data3 = mysqli_fetch_assoc($res3)){
$student_id = $data3['user_id'];
$sql6 = "SELECT * FROM phoenix_users WHERE user_id = '".$student_id."'";
$res6 = $mysqli -> query($sql6);
$data6 = mysqli_fetch_assoc($res6);
if($color == 1){
echo '<tr>
<td height="50" width="10%" bgcolor="#769467"><input type="checkbox" style="transform: scale(1.5);" value="'.$student_id.'" name="select_members[]"></td>
<td height="50" width="60%" bgcolor="#769467">'.$data6['user_title'].' '.$data6['user_first_name'].' '.$data6['user_last_name'].'</td>
</tr>';
$color = 2;}
else {
echo '<tr>
<td height="50" bgcolor="#a0b595"><input type="checkbox" style="transform: scale(1.5);" value="'.$student_id.'" name="select_members[]"></td>
<td height="50" bgcolor="#a0b595">'.$data6['user_title'].' '.$data6['user_first_name'].' '.$data6['user_last_name'].'</td>
</tr>';
$color = 1;}}
?>
</tbody></table></p>
<p><input type="submit" value="DELETE"></p></form>
<p><a href="#top"><b>Back to the top</b></a></p>
<?php
}
?>
</body></html>