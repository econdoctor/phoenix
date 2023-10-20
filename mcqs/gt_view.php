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
$user_teacher = $data['user_teacher'];
$user_active = $data['user_active'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
$s = $_GET['s'];
if(empty($s)){
echo 'Please go back and choose a syllabus.';
exit();}
if($s == '3'){
$s_text = "A Level";}
if($s == '2'){
$s_text = "AS Level";}
if($s == '1'){
$s_text = "IGCSE";}
$paper_id = $_GET['paper_id'];
if(empty($paper_id)){
echo 'Missing information about the paper.';
exit();}
$sql2 = "SELECT * FROM phoenix_papers WHERE paper_id = '".$paper_id."'";
$res2 = $mysqli -> query($sql2);
$data2 = mysqli_fetch_assoc($res2);
$paper_hidden = $data2['paper_hidden'];
if($user_active == 0 && $paper_hidden == 1){
echo 'You are not authorized to access this grade threshold.';
exit();}
$paper_serie = $data2['paper_serie'];
if($paper_serie == 1){
$paper_serie_text = "m - February / March";}
if($paper_serie == 2){
$paper_serie_text = "s - May / June";}
if($paper_serie == 3){
$paper_serie_text = "w - October / November";}
if($paper_serie == 4){
$paper_serie_text = "y - Specimen";}
$paper_version = $data2['paper_version'];
if($paper_version == 0){
$paper_version = "/";}
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
<?php
if($user_type == 2){
?>
<input type="button" name="browse" value="BROWSE" style="font-size: x-large; width: 30%; background-color: #ffc000;  color: #3b2c00; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='browse.php';"/>&nbsp;
<input type="button" name="manage" value="MANAGE" style="font-size: x-large; width: 30%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='manage.php';"/>&nbsp;
<input type="button" name="assign" value="ASSIGNMENTS" style="font-size: x-large; width: 30%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='assign.php';"/>&nbsp;
<?php
}
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
echo '</td></tr></tbody></table>';
if($user_type == 1){
?>
<p><input type="button" style="width: 20%" name="title" value="<?php echo strtoupper($s_text); ?> PAPERS" onclick="document.location.href='practice_paper.php?s=<?php echo $s; ?>';"/>
<?php
}
if($user_type == 2){
?>
<p><input type="button" style="width: 20%" name="title" value="<?php echo strtoupper($s_text); ?> PAPERS" onclick="document.location.href='browse_paper.php?s=<?php echo $s; ?>';"/>
<?php
}
?>
<p><b style="font-size: x-large;">PAPER REFERENCE</b></p>
<p><table width="67%" align="center" bgcolor="#000000"><tbody><tr>
<td height="40" bgcolor="647d57"><b>COURSE</b></td>
<td height="40" bgcolor="647d57"><b>YEAR</b></td>
<td height="40" bgcolor="647d57"><b>SERIE</b></td>
<td height="40" bgcolor="647d57"><b>VERSION</b></td>
</tr><tr>
<td height="40" bgcolor="769467"><?php echo $s_text; ?></td>
<td height="40" bgcolor="769467"><?php echo $data2['paper_year']; ?></td>
<td height="40" bgcolor="769467"><?php echo $paper_serie_text; ?></td>
<td height="40" bgcolor="769467"><?php echo $paper_version; ?></td>
</tr></tbody></table></p>
<p><b style="font-size: x-large;">GRADE THRESHOLDS</b></p>
<?php
if($paper_id == 5 || $paper_id == 6 || $paper_id == 7 || $paper_id == 51 || $paper_id == 52 || $paper_id == 53 || $paper_id == 89 || $paper_id == 90 || $paper_id == 91){
echo '<p><b style="color: #820000">Cambridge International did not release grade thresholds for the May / June 2020 examination serie.
<br>The data below is an average of the grade thresholds of the last 5 years.</b></p>';}
if($paper_id == 136 || $paper_id == 139 || $paper_id == 140 || $paper_id == 141 || $paper_id == 142){
echo '<p><b style="color: #820000">Cambridge International did not release grade thresholds for the Specimen papers.
<br>The data below is an average of the grade thresholds of the last 5 years.</b></p>';}
if($s != '1'){
echo '<p><table width = "50%" bgcolor = "#000000" align="center"><tbody><tr>
<td height="40" width="16.67%" bgcolor="#647d57"><b>A</b></td>
<td height="40" width="16.67%" bgcolor="#647d57"><b>B</b></td>
<td height="40" width="16.67%" bgcolor="#647d57"><b>C</b></td>
<td height="40" width="16.67%" bgcolor="#647d57"><b>D</b></td>
<td height="40" width="16.67%" bgcolor="#647d57"><b>E</b></td>
<td height="40" width="16.67%" bgcolor="#647d57"><b>U</b></td>';}
if($s == '1'){
echo '<p><table width = "67%" bgcolor = "#000000" align="center"><tbody><tr>
<td height="40" width="12.5%" bgcolor="#647d57"><b>A</b></td>
<td height="40" width="12.5%" bgcolor="#647d57"><b>B</b></td>
<td height="40" width="12.5%" bgcolor="#647d57"><b>C</b></td>
<td height="40" width="12.5%" bgcolor="#647d57"><b>D</b></td>
<td height="40" width="12.5%" bgcolor="#647d57"><b>E</b></td>
<td height="40" width="12.5%" bgcolor="#647d57"><b>F</b></td>
<td height="40" width="12.5%" bgcolor="#647d57"><b>G</b></td>
<td height="40" width="12.5%" bgcolor="#647d57"><b>U</b></td>';}
?>
</tr><tr>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data2['paper_a']; ?></td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data2['paper_b']; ?></td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data2['paper_c']; ?></td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data2['paper_d']; ?></td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $data2['paper_e']; ?></td>
<?php
if($s != '1'){
echo '<td height="40" bgcolor="#769467">< '.$data2['paper_e'].'</td>';}
if($s == "1"){
echo '<td height="40" bgcolor="#769467">&#8805; '.$data2['paper_f'].'</td>
<td height="40" bgcolor="#769467">&#8805; '.$data2['paper_g'].'</td>
<td height="40" bgcolor="#769467">< '.$data2['paper_g'].'</td>';}
?>
</tr></tbody></table></p></body></html>