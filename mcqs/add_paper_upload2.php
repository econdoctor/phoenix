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
$sql = "SELECT user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_id != 1){
echo 'Access restricted';
exit();}
$paper_reference = $_GET['paper_reference'];
if(empty($paper_reference)){
echo 'Missing information about the paper';
exit();}
$sql_info = "SELECT * FROM phoenix_papers WHERE paper_reference = '".$paper_reference."'";
$res_info = $mysqli -> query($sql_info);
$num_rows_info = mysqli_num_rows($res_info);
if($num_rows_info == 0){
header("Location: add_paper.php");
exit();}
$data_info = mysqli_fetch_assoc($res_info);
$paper_id = $data_info['paper_id'];
$paper_syllabus = $data_info['paper_syllabus'];
$paper_a = $data_info['paper_a'];
$paper_b = $data_info['paper_b'];
$paper_c = $data_info['paper_c'];
$paper_d = $data_info['paper_d'];
$paper_e = $data_info['paper_e'];
$paper_f = $data_info['paper_f'];
$paper_g = $data_info['paper_g'];
if($paper_syllabus != 1 && ($paper_a == '0' || $paper_b == '0' || $paper_c == '0' || $paper_d == '0' || $paper_e == '0')){
header("Location: add_paper_gt.php?paper_reference=$paper_reference");
exit();}
if($paper_syllabus == 1 && ($paper_a == '0' || $paper_b == '0' || $paper_c == '0' || $paper_d == '0' || $paper_e == '0' || $paper_f == '0' || $paper_g == '0')){
header("Location: add_paper_gt.php?paper_reference=$paper_reference");
exit();}
$sql_q = "SELECT * FROM phoenix_questions WHERE question_paper_id = '".$paper_id."'";
$res_q = $mysqli -> query($sql_q);
$num_rows_q = mysqli_num_rows($res_q);
if($num_rows_q == 0){
header("Location: add_paper_ms.php?paper_reference=$paper_reference");
exit();}
$sql_ans = "SELECT * FROM phoenix_questions WHERE question_paper_id = '".$paper_id."'";
$res_ans = $mysqli -> query($sql_ans);
while($data_ans = mysqli_fetch_assoc($res_ans)){
$answer = $data_ans['question_answer'];
if($answer != 1 && $answer != 2 && $answer != 3 && $answer != 4 && $answer != 0){
header("Location: add_paper_ms.php?paper_reference=$paper_reference");
exit();}}
$target_dir = './q/' . $paper_syllabus . '/' . $paper_id . '/';
$files_in_directory = scandir($target_dir);
$items_count = count($files_in_directory);
if($items_count != 22){
echo 'Please upload questions 1 to 20.';
exit();}
for($i=1; $i<=20; $i++){
$filename = $_SERVER['DOCUMENT_ROOT'] . '/q/'.$paper_syllabus.'/'.$paper_id . '/'.$i.'.png';
if(!file_exists($filename)){
echo 'Error. Please check the file names.';
exit();}}
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
width: 30%;
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
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script>
window.onload = function(){
setInterval(function(){$.post('refresh_session.php');}, 60000);};
</script>
</head>
<body><a name="top"></a>
<table align="center" width="90%"><tbody>
<tr>
<td>
<p style="text-align: right;"><img src="online.png" width="15">&nbsp;&nbsp;<b><a href="profile.php"><?php echo $user_title.' '.$user_first_name.' '.$user_last_name.' ('.$user_name.')'; ?></a> - <a href="logout.php">Log out</a></b></p>
</td>
</tr>
</tbody></table>
<table width="80%" bgcolor="#6a855c" align="center" style="border: solid black 4px; border-radius:24px;"><tbody><tr width="95%">
<td width="20%"><a href="main.php"><img src="home.png" width="150" height="150"></a></td>
<td width="80%"><p style="font-size: xx-large;"><b>PHOENIX</b></p>
<p>
<input type="button" name="browse" value="BROWSE" style="font-size: x-large; width: 30%; background-color: #ffc000;  color: #3b2c00; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='browse.php';"/>&nbsp;&nbsp;
<input type="button" name="manage" value="MANAGE" style="font-size: x-large; width: 30%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='manage.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 30%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='assign.php';"/>
</td></tr></tbody></table>
<p style="font-size: x-large"><b>QUESTIONS</b></p>
<p><b>Paper reference:</b><br>
<b style="color: #033909"><?php echo $paper_reference; ?></b></p>
<p><b style="color: #820000;">Now, let's upload questions 21 to 40.</b></p>
<form action='add_paper_upload_go2.php?paper_reference=<?php echo $paper_reference; ?>' method='post' enctype="multipart/form-data">
<input type="file" name="file[]" id="file" multiple="20"><br><br>
<input type="submit" name="submit" value="UPLOAD">
</form>
<p>&nbsp;</p>
</body>
</html>