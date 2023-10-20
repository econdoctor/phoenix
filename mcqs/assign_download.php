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
$sql = "SELECT user_type, user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id ='".$user_id."'";
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
$sql2 = "SELECT assign_name, assign_type, assign_teacher, assign_time_allowed, assign_key FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res2 = $mysqli -> query($sql2);
$data2 = mysqli_fetch_assoc($res2);
$assign_name = $data2['assign_name'];
$assign_type = $data2['assign_type'];
$assign_teacher = $data2['assign_teacher'];
$assign_time_allowed = $data2['assign_time_allowed'];
$assign_key = $data2['assign_key'];
if($assign_teacher != $user_id){
echo 'You are not authorized to managed this assignment.';
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
width: 40%;
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
<input type="button" name="browse" value="BROWSE" style="font-size: x-large; width: 30%; background-color: #ffc000;  color: #3b2c00; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='browse.php';"/>&nbsp;&nbsp;
<input type="button" name="manage" value="MANAGE" style="font-size: x-large; width: 30%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='manage.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 30%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='assign.php';"/>
</td></tr></tbody></table>
<p style="font-size: x-large;"><b><?php echo strtoupper($assign_name); ?></b></p>
<form action="assign_download_preview.php" method="get">
<?php
if($_GET['error'] == 1){
echo '<p><b style="color: #820000;">Please fill in the information required</b></p>';}
?>
<p><b>Do you want to show the answer of each question?</b><br>
<input type="radio" name="answers" value='1' required <?php if($_GET['answers'] == '1') { echo 'checked'; } ?>> Yes - <input type="radio" name="answers" value='2' required <?php if($_GET['answers'] == '2' || empty($_GET['answers'])) { echo 'checked'; } ?>> No</p>
<p><b>Do you want to show the reference of each question?</b><br>
<input type="radio" name="ref" value='1' required <?php if($_GET['ref'] == '1') { echo 'checked'; } ?>> Yes - <input type="radio" name="ref" value='2' required <?php if($_GET['ref'] == '2' || empty($_GET['ref'])) { echo 'checked'; } ?>> No</p>
<p><b>Do you want to show the classification of each question?</b><br>
<input type="radio" name="topic" value='1' required <?php if($_GET['topic'] == '1') { echo 'checked'; } ?>> Yes - <input type="radio" name="topic" value='2' required <?php if($_GET['topic'] == '2' || empty($_GET['topic'])) { echo 'checked'; } ?>> No</p>
<p><b>Which of the following do you want to include in the document header?</b><br>
<?php
if($assign_type != '4' && $assign_type != '5'){
?>
<input type="checkbox" style="transform: scale(1.5);" name="ddl" value="ddl_yes" <?php if($_GET['ddl'] == 'ddl_yes') { echo 'checked'; } ?>> Deadline -
<?php
if($assign_time_allowed > 0){
?>
<input type="checkbox" style="transform: scale(1.5);" name="ta" value="ta_yes" <?php if($_GET['ta'] == 'ta_yes') { echo 'checked'; } ?>> Time allowed -
<?php
}}
?>
<input type="checkbox" style="transform: scale(1.5);" name="name" value="name_yes" <?php if($_GET['sn'] == 'name_yes') { echo 'checked'; } ?>> Student name -
<input type="checkbox" style="transform: scale(1.5);" name="date" value="date_yes" <?php if($_GET['sd'] == 'date_yes') { echo 'checked'; } ?>> Date -
<input type="checkbox" style="transform: scale(1.5);" name="score" value="score_yes" <?php if($_GET['ss'] == 'score_yes') { echo 'checked'; } ?>> Score</p>
<input type="hidden" name="assign_id" value="<?php echo $assign_id; ?>">
<input type="hidden" name="k" value="<?php echo $assign_key; ?>">
<input type="submit" value="CONFIRM">
<p>&nbsp;</p></form></body></html>