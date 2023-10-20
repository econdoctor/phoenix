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
$s = $_GET['s'];
$sql = "SELECT * FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_teacher = $data['user_teacher'];
$user_alias = $data['user_alias'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 2){
echo 'What are you doing here? This page is for students only.';
exit();}
if($user_alias == ''){
header("Location: set_alias.php?s=$s");
exit();}
$division  = $_POST['division'];
if(empty($s) && empty($division)){
echo 'Missing information about the course';
exit();}
if(empty($division)){
$division = $s;}
if($division == '1'){
$sql_get = "SELECT * FROM phoenix_users WHERE user_type = '1' AND user_score_igcse > 0 ORDER BY user_score_igcse DESC";}
if($division == '2'){
$sql_get = "SELECT * FROM phoenix_users WHERE user_type = '1'AND user_score_as > 0 ORDER BY user_score_as DESC";}
if($division == '3'){
$sql_get = "SELECT * FROM phoenix_users WHERE user_type = '1'AND user_score_a2 > 0 ORDER BY user_score_a2 DESC";}
$res_get = $mysqli -> query($sql_get);
$nr_get = mysqli_num_rows($res_get);
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
<?php
if($user_type == 1 && $user_teacher == 0){
?>
<input type="button" name="practice" value="PRACTICE" style="font-size: x-large; width: 45%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='practice.php';"/>
<?php
}
if($user_type == 1 && $user_teacher != 0){
?>
<input type="button" name="practice" value="PRACTICE" style="font-size: x-large; width: 45%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='practice.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 45%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='complete.php';"/>
<?php
}
?>
</p></td></tr></tbody></table>
<p style="font-size: x-large"><b>RANKING</b></p>
<form method="post" action="ranking.php">
<p><select name="division" onchange="this.form.submit();">
<optgroup>
<option value="1" <?php if($division == '1') { echo 'selected'; } ?>>IGCSE</option>
<option value="2" <?php if($division == '2') { echo 'selected'; } ?>>AS Level</option>
<option value="3" <?php if($division == '3') { echo 'selected'; } ?>>A Level</option>
</optgroup></select></p></form>
<p><b style="color: #820000"><?php echo number_format($nr_get); ?></b> <b>students found</b></p>
<table align="center" width="67%" bgcolor="#000000"><tbody><tr>
<td height="40" bgcolor="#647d57"><b>RANK</b></td>
<td height="40" bgcolor="#647d57"><b>ALIAS</b></td>
<td height="40" bgcolor="#647d57"><b>LEVEL</b></td>
<td height="40" bgcolor="#647d57"><b>STATUS</b></td>
</tr>
<?php
$i = 1;
$color = 0;
while($data_get = mysqli_fetch_assoc($res_get)){
$student_id = $data_get['user_id'];
if($division == '1'){
$student_score = $data_get['user_score_igcse'];}
if($division == '2'){
$student_score = $data_get['user_score_as'];}
if($division == '3'){
$student_score = $data_get['user_score_a2'];}
$student_alias = $data_get['user_alias'];
if($student_alias == ''){
$student_alias = "Student #$student_id";}
if($student_score < 5){
$student_rank = 'Newbie';}
if($student_score >= 5 && $student_score < 10){
$student_rank = 'Novice';}
if($student_score >= 10 && $student_score < 15){
$student_rank = 'Amateur';}
if($student_score >= 15 && $student_score < 20){
$student_rank = 'Apprentice';}
if($student_score >= 20 && $student_score < 25){
$student_rank = 'Adept';}
if($student_score >= 25 && $student_score < 30){
$student_rank = 'Junior Economist';}
if($student_score >= 30 && $student_score < 35){
$student_rank = 'Senior Economist';}
if($student_score >= 35 && $student_score < 40){
$student_rank = 'Chief Economist';}
if($student_score >= 40 && $student_score < 45){
$student_rank = 'Expert';}
if($student_score >= 45 && $student_score < 50){
$student_rank = 'Scholar';}
if($student_score >= 50 && $student_score < 55){
$student_rank = 'Chairman';}
if($student_score >= 55 && $student_score < 60){
$student_rank = 'Veteran';}
if($student_score >= 60 && $student_score < 65){
$student_rank = 'Master';}
if($student_score >= 65 && $student_score < 70){
$student_rank = 'Grand Master';}
if($student_score >= 70 && $student_score < 75){
$student_rank = 'Supreme Master';}
if($student_score >= 75 && $student_score < 80){
$student_rank = 'Overlord';}
if($student_score >= 80 && $student_score < 85){
$student_rank = 'Hero';}
if($student_score >= 85 && $student_score < 90){
$student_rank = 'Legend';}
if($student_score >= 90 && $student_score < 95){
$student_rank = 'Immortal';}
if($student_score >= 95 && $student_score < 100){
$student_rank = 'God';}
if($student_score == 100){
$student_rank = 'Beyonder';}
if($student_id == $user_id){
echo '<tr>
<td height="40" bgcolor="#ffc000"><b>'.$i.'</b></td>
<td height="40" bgcolor="#ffc000"><b>'.$student_alias.'</b></td>
<td height="40" bgcolor="#ffc000"><b>LV '.floor($student_score).'</b></td>
<td height="40" bgcolor="#ffc000"><b>'.$student_rank.'</b></td>
</tr>';
$i++;}
if($student_id != $user_id){
if($color == 0){
echo '<tr>
<td height="40" bgcolor="#769467">'.$i.'</td>
<td height="40" bgcolor="#769467">'.$student_alias.'</td>
<td height="40" bgcolor="#769467"><b>LV '.floor($student_score).'</b></td>
<td height="40" bgcolor="#769467"><b>'.$student_rank.'</b></td>
</tr>';
$color = 1;
$i++;}
else{
echo '<tr>
<td height="40" bgcolor="#a0b595">'.$i.'</td>
<td height="40" bgcolor="#a0b595">'.$student_alias.'</td>
<td height="40" bgcolor="#a0b595"><b>LV '.floor($student_score).'</b></td>
<td height="40" bgcolor="#a0b595"><b>'.$student_rank.'</b></td>
</tr>';
$color = 0;
$i++;}}}
echo '</tbody></table><br>';
?>
</td></tr></tbody></table>
<p><a href="#top"><b>Back to the top</b></a></p>
</body>
</html>