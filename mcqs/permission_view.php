<?php
$perm_id = $_GET['id'];
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
$tz = $data['user_timezone'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
$sql_perm = "SELECT * FROM phoenix_permissions WHERE permission_teacher = '".$user_id."' AND permission_active = '1' AND permission_id = '".$perm_id."'";
$res_perm = $mysqli -> query($sql_perm);
$nr_perm = mysqli_num_rows($res_perm);
if($nr_perm == 0){
echo 'This rule does not seem to be active or you are not allowed to manage it.';
exit();}
$data = mysqli_fetch_assoc($res_perm);
if($data['permission_syllabus'] == '3'){
$s_text = "A LEVEL";}
if($data['permission_syllabus'] == '2'){
$s_text = "AS LEVEL";}
if($data['permission_syllabus'] == '1'){
$s_text = "IGCSE";}
$expire = $data['permission_expire'];
if($expire == "0000-00-00 00:00:00"){
$expire_tz = "N/A";}
else {
$expire_date = date('Y-m-d H:i:s', strtotime(''.$expire.''));
if($tz >= 0){
$expire_tz = date('Y-m-d H:i:s', strtotime(''.$expire_date.' + '.$tz.' minutes'));}
if($tz < 0){
$expire_tz = date('Y-m-d H:i:s', strtotime(''.$expire_date.' - '.abs($tz).' minutes'));}}
$created = $data['permission_created'];
$created_date = date('Y-m-d H:i:s', strtotime(''.$created.''));
if($tz >= 0){
$created_tz = date('Y-m-d H:i:s', strtotime(''.$created_date.' + '.$tz.' minutes'));}
if($tz < 0){
$created_tz = date('Y-m-d H:i:s', strtotime(''.$created_date.' - '.abs($tz).' minutes'));}
$sql_stu = "SELECT * FROM phoenix_permissions_users WHERE permission_id = '".$perm_id."'";
$res_stu = $mysqli -> query($sql_stu);
$nr_stu = number_format(mysqli_num_rows($res_stu));
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
</style>
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
<p style="font-size: x-large"><b><?php echo $s_text ?> RULE</b></p><p>
<p><b>EXPIRATION<br>
<span style="color: #033909"><?php echo $expire_tz; ?></span></b><p>
<p><b>STUDENTS</b></p>
<p><b><span style="color: #820000"><?php echo $nr_stu; ?></span> student(s) found</b></p>
<table width = "33%" align="center" bgcolor="#000000">
<tbody><tr>
<td bgcolor = "#647d57" height="40"><b>NAME</b></td>
</tr>
<?php
$color = 1;
while($row = mysqli_fetch_assoc($res_stu)){
$student_id = $row['student_id'];
$sql_get = "SELECT * FROM phoenix_users WHERE user_id = '".$student_id."'";
$res_get = $mysqli -> query($sql_get);
$data_get = mysqli_fetch_assoc($res_get);
if($color == 1){
echo '<tr>
<td bgcolor = "#769467" height="40">'.$data_get['user_title'].' '.$data_get['user_first_name'].' '.$data_get['user_last_name'].'</td>
</tr>';
$color = 2;}
else {
echo '<tr>
<td bgcolor = "#a0b595" height="40">'.$data_get['user_title'].' '.$data_get['user_first_name'].' '.$data_get['user_last_name'].'</td>
</tr>';
$color = 1;}}
?>
</tbody></table><br>
<?php
if($data['permission_type'] == '1'){
$sql_papers = "SELECT * FROM phoenix_permissions_papers WHERE permission_id = '".$perm_id."'";
$res_papers = $mysqli -> query($sql_papers);
$nr_p = number_format(mysqli_num_rows($res_papers));
echo '<p><b>PAPERS HIDDEN</b></p>
<p><b><span style="color: #820000">'.$nr_p.'</span> paper(s) found</b></p>
<table width="50%" align="center" bgcolor="#000000"><tbody><tr>
<td height="40" bgcolor="#647d57"><b>YEAR</b></td>
<td height="40" bgcolor="#647d57"><b>SERIE</b></td>
<td height="40" bgcolor="#647d57"><b>VERSION</b></td>
</tr>';
$color = 1;
while($row_p = mysqli_fetch_assoc($res_papers)){
$paper_id = $row_p['paper_id'];
$sql_get_p = "SELECT * FROM phoenix_papers WHERE paper_id = '".$paper_id."'";
$res_get_p = $mysqli -> query($sql_get_p);
$data_get_p = mysqli_fetch_assoc($res_get_p);
$paper_serie = $data_get_p['paper_serie'];
if($paper_serie == 1){
$paper_serie_text = "m - February / March";}
if($paper_serie == 2){
$paper_serie_text = "s - May / June";}
if($paper_serie == 3){
$paper_serie_text = "w - October / November";}
if($paper_serie == 4){
$paper_serie_text = "y - Specimen";}
$paper_version = $data_get_p['paper_version'];
if($paper_version == 0){
$paper_version = "/";}
if($color == 1){
echo '<tr>
<td bgcolor = "#769467" height="40">'.$data_get_p['paper_year'].'</td>
<td bgcolor = "#769467" height="40">'.$paper_serie_text.'</td>
<td bgcolor = "#769467" height="40">'.$paper_version.'</td>
</tr>';
$color = 2;}
else {
echo '<tr>
<td bgcolor = "#a0b595" height="40">'.$data_get_p['paper_year'].'</td>
<td bgcolor = "#a0b595" height="40">'.$paper_serie_text.'</td>
<td bgcolor = "#a0b595" height="40">'.$paper_version.'</td>
</tr>';
$color = 1;}}
echo'</tbody></table>';}
if($data['permission_type'] == '2'){
$sql_topics = "SELECT * FROM phoenix_permissions_topics WHERE permission_id = '".$perm_id."'";
$res_topics = $mysqli -> query($sql_topics);
$nr_t = number_format(mysqli_num_rows($res_topics));
echo '<p><b>TOPICS HIDDEN</b></p>
<p><b><span style="color: #820000">'.$nr_t.'</span> topic(s) found</b></p>
<table width="50%" align="center" bgcolor="#000000"><tbody>';
$color = 1;
$previous = '';
while($row_t = mysqli_fetch_assoc($res_topics)){
$topic_id = $row_t['topic_id'];
$sql_get_t = "SELECT * FROM phoenix_topics WHERE topic_id = '".$topic_id."'";
$res_get_t = $mysqli -> query($sql_get_t);
$data_topic = mysqli_fetch_assoc($res_get_t);
$current = $data_topic['topic_unit_id'];
if($current != $previous){
$title_raw = $data_topic['topic_unit'];
$title = strtoupper($title_raw);
echo'<tr><td height="40" bgcolor = "#647d57"><b>'.$title.'</b></td></tr>';
$color = 1;}
if($color == 1){
echo '<tr><td height="40" bgcolor="#769467">'.$data_topic['topic_module'].'</td></tr>';
$color = 2;}
else {
echo '<tr><td height="40" bgcolor="#a0b595">'.$data_topic['topic_module'].'</td></tr>';
$color = 1;}
$previous = $current;}
echo'</tbody></table>';}
?>
<p><a href="#top"><b>Back to the top</b></a></p>
</body></html>