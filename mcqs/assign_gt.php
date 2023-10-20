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
$sql = "SELECT user_type, user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id = '".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 2){
echo 'What are you doing here? This page is for students only.';
exit();}
if(empty($_GET['assign_id'])){
echo 'Missing information about the assignment.';
exit();}
$sql_check = "SELECT COUNT(*) FROM phoenix_assign_users WHERE assign_id = '".$_GET['assign_id']."' AND student_id = '".$user_id."'";
$res_check = $mysqli -> query($sql_check);
$data_check = mysqli_fetch_assoc($res_check);
$nr_check = $data_check['COUNT(*)'];
if($nr_check == 0){
echo 'You are not authorized to complete this assignment.';
exit();}
$sql_assign = "SELECT * FROM phoenix_assign WHERE assign_id = '".$_GET['assign_id']."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
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
  width: 20%;
  border-radius: 4px;
}
</style>
</head>
<body>
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
<input type="button" name="practice" value="PRACTICE" style="font-size: x-large; width: 45%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='practice.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 45%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='complete.php';"/>
</p></td></tr></tbody></table>
<p style="font-size: x-large"><b><?php echo strtoupper($data_assign['assign_name']); ?></b></p>
<table align="center" bgcolor="#000000" width="50%"><tbody><tr>
<td height="40" bgcolor="#6a855c"><b>A</b></td>
<td height="40" bgcolor="#6a855c"><b>B</b></td>
<td height="40" bgcolor="#6a855c"><b>C</b></td>
<td height="40" bgcolor="#6a855c"><b>D</b></td>
<td height="40" bgcolor="#6a855c"><b>E</b></td>
<?php
if($data_assign['assign_syllabus'] != '1'){
echo '<td height="40" bgcolor="#6a855c"><b>U</b></td>';}
if($data_assign['assign_syllabus'] == '1'){
echo '<td height="40" bgcolor="#6a855c"><b>F</b></td>
<td height="40" bgcolor="#6a855c"><b>G</b></td>
<td height="40" bgcolor="#6a855c"><b>U</b></td>';}
?>
</tr><tr>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data_assign['assign_a']; ?>%</td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data_assign['assign_b']; ?>%</td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data_assign['assign_c']; ?>%</td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data_assign['assign_d']; ?>%</td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data_assign['assign_e']; ?>%</td>
<?php
if($data_assign['assign_syllabus'] != '1'){
echo '<td height="40" bgcolor="#769467">< '.$data_assign['assign_e'].'%</td>';}
if($data_assign['assign_syllabus'] == '1'){
echo '<td height="40" bgcolor="#769467">&#8805; '.$data_assign['assign_f'].'%</td>
<td height="40" bgcolor="#769467">&#8805; '.$data_assign['assign_g'].'%</td>
<td height="40" bgcolor="#769467">< '.$data_assign['assign_g'].'%</td>';}
?>
</tr></tbody></table>
<p>&nbsp;</p>
</body>
</html>
