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
$sql_A2 = "SELECT * FROM phoenix_thresholds WHERE teacher_id = '".$user_id."' AND syllabus = '3'";
$res_A2 = $mysqli -> query($sql_A2);
$data_A2 = mysqli_fetch_assoc($res_A2);
$sql_AS = "SELECT * FROM phoenix_thresholds WHERE teacher_id = '".$user_id."' AND syllabus = '2'";
$res_AS = $mysqli -> query($sql_AS);
$data_AS = mysqli_fetch_assoc($res_AS);
$sql_IGCSE = "SELECT * FROM phoenix_thresholds WHERE teacher_id = '".$user_id."' AND syllabus = '1'";
$res_IGCSE = $mysqli -> query($sql_IGCSE);
$data_IGCSE = mysqli_fetch_assoc($res_IGCSE);
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
width: 15%;
border-radius: 4px;
}
.mid {
vertical-align:middle
}
</style></head>
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
<td width="80%"><p style="font-size: xx-large;"><b>PHOENIX</b>
<p>
<input type="button" name="browse" value="BROWSE" style="font-size: x-large; width: 30%; background-color: #ffc000;  color: #3b2c00; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='browse.php';"/>&nbsp;&nbsp;
<input type="button" name="manage" value="MANAGE" style="font-size: x-large; width: 30%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='manage.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 30%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='assign.php';"/>
</td></tr></tbody></table>
<p style="font-size: x-large"><b>GRADE THRESHOLDS</b>
<p><b style="color: #820000;">The grade thresholds below are used to calculate your students' letter grade when they practice MCQs organised by topic.</b></p>
<p><table align="center" bgcolor="#000000" width="67%"><tbody><tr>
<td height="40" bgcolor="647d57"><b>COURSE</b></td>
<td height="40" bgcolor="647d57"><b>A</b></td>
<td height="40" bgcolor="647d57"><b>B</b></td>
<td height="40" bgcolor="647d57"><b>C</b></td>
<td height="40" bgcolor="647d57"><b>D</b></td>
<td height="40" bgcolor="647d57"><b>E</b></td>
<td height="40" bgcolor="647d57"><b>F</b></td>
<td height="40" bgcolor="647d57"><b>G</b></td>
<td height="40" bgcolor="647d57"><b>U</b></td>
<td height="40" bgcolor="647d57"><b>EDIT</b></td>
</tr>
<tr>
<td height="40" bgcolor="769467"><b>IGCSE</b></td>
<td height="40" bgcolor="769467">&#8805; <?php echo $data_IGCSE['min_a']; ?>%</td>
<td height="40" bgcolor="769467">&#8805; <?php echo $data_IGCSE['min_b']; ?>%</td>
<td height="40" bgcolor="769467">&#8805; <?php echo $data_IGCSE['min_c']; ?>%</td>
<td height="40" bgcolor="769467">&#8805; <?php echo $data_IGCSE['min_d']; ?>%</td>
<td height="40" bgcolor="769467">&#8805; <?php echo $data_IGCSE['min_e']; ?>%</td>
<td height="40" bgcolor="769467">&#8805; <?php echo $data_IGCSE['min_f']; ?>%</td>
<td height="40" bgcolor="769467">&#8805; <?php echo $data_IGCSE['min_g']; ?>%</td>
<td height="40" bgcolor="769467">< <?php echo $data_IGCSE['min_g']; ?>%</td>
<td height="40" bgcolor="769467"><a href="edit_gt.php?s=1"><img src="mod.png" width="25" height="25" class="mid"></a></td>
</tr><tr>
<td height="40" bgcolor="a0b595"><b>AS LEVEL</b></td>
<td height="40" bgcolor="a0b595">&#8805; <?php echo $data_AS['min_a']; ?>%</td>
<td height="40" bgcolor="a0b595">&#8805; <?php echo $data_AS['min_b']; ?>%</td>
<td height="40" bgcolor="a0b595">&#8805; <?php echo $data_AS['min_c']; ?>%</td>
<td height="40" bgcolor="a0b595">&#8805; <?php echo $data_AS['min_d']; ?>%</td>
<td height="40" bgcolor="a0b595">&#8805; <?php echo $data_AS['min_e']; ?>%</td>
<td height="40" bgcolor="a0b595">-</td>
<td height="40" bgcolor="a0b595">-</td>
<td height="40" bgcolor="a0b595">< <?php echo $data_AS['min_e']; ?>%</td>
<td height="40" bgcolor="a0b595"><a href="edit_gt.php?s=2"><img src="mod.png" width="25" height="25" class="mid"></a></td>
</tr><tr>
<td height="40" bgcolor="769467"><b>A LEVEL</b></td>
<td height="40" bgcolor="769467">&#8805; <?php echo $data_A2['min_a']; ?>%</td>
<td height="40" bgcolor="769467">&#8805; <?php echo $data_A2['min_b']; ?>%</td>
<td height="40" bgcolor="769467">&#8805; <?php echo $data_A2['min_c']; ?>%</td>
<td height="40" bgcolor="769467">&#8805; <?php echo $data_A2['min_d']; ?>%</td>
<td height="40" bgcolor="769467">&#8805; <?php echo $data_A2['min_e']; ?>%</td>
<td height="40" bgcolor="769467">-</td>
<td height="40" bgcolor="769467">-</td>
<td height="40" bgcolor="769467">< <?php echo $data_A2['min_e']; ?>%</td>
<td height="40" bgcolor="769467"><a href="edit_gt.php?s=3"><img src="mod.png" width="25" height="25" class="mid"></a></td>
</tr></tbody></table></p>
<p>&nbsp;</p>
</body></html>