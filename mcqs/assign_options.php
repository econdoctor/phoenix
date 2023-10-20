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
echo 'Missing information';
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
$sqlcheck = "SELECT assign_teacher, assign_name, assign_pt, assign_active, assign_type, assign_syllabus FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$rescheck = $mysqli -> query($sqlcheck);
$datacheck = mysqli_fetch_assoc($rescheck);
$teacher_id = $datacheck['assign_teacher'];
$assign_name = $datacheck['assign_name'];
$assign_pt = $datacheck['assign_pt'];
$assign_active = $datacheck['assign_active'];
$assign_type = $datacheck['assign_type'];
$s = $datacheck['assign_syllabus'];
if($assign_active == 1){
echo 'This assignment is already active.';
exit();}
if($teacher_id != $user_id){
echo 'You are not allowed to manage this assignment';
exit();}
$sql_get = "SELECT * FROM phoenix_thresholds WHERE teacher_id = '".$user_id."' AND syllabus = '".$s."'";
$res_get = $mysqli -> query($sql_get);
$data_get = mysqli_fetch_assoc($res_get);
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
.brmedium {
display: block;
margin-bottom: 0.5em;
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
width: 67%;
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
<script>
function showOrder(id){
var e = document.getElementById('select_order');
var x = document.getElementById('order');
if(id == 'hide'){
x.removeAttribute("required");
e.style.display = 'none';}
if(id == 'show'){
x.setAttribute("required", "");
e.style.display = 'block';}}
</script>
<script>
function clearOrder(){
document.getElementById('order').value = "";}
</script>
<script language="JavaScript">
function preload(){
document.getElementById("preload").style.display = "block";
document.getElementById("content").style.display = "none";}
</script>
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
<div id="preload" style="display: none;">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><b style="font-size: larger; color: #033909;">LOADING...</b><br>
<img src="preload.gif" width="100"></p>
</div>
<div id="content" style="display: block;">
<?php
if($_GET['error'] == 1){
echo '<p><b style="color: #820000;">Missing information</b></p>';}
if($_GET['error'] == 2){
echo '<p><b style="color: #820000;">The grade thresholds are inconsistent</b></p>';}
?>
<form method="post" action="assign_create.php?assign_id=<?php echo $assign_id; ?>">
<?php
if($assign_type == '4'){
?>
<p><select name="order" id="order"><optgroup>
<option value="" disabled <?php if(($_GET['scramble'] == '2' && $_GET['order'] == '') || $_GET['scramble'] == '1' || empty($_GET['scramble']) || empty($_GET['order'])) { echo 'selected'; } ?>>Sort MCQs by</option>
<?php
if($assign_pt == '1'){
?>
<option value='1' <?php if($_GET['scramble'] == '2' && $_GET['order'] == '1') { echo 'selected'; } ?>>Question paper</option>
<?php
}
?>
<option value='2' <?php if($_GET['scramble'] == '2' && $_GET['order'] == '2') { echo 'selected'; } ?>>Syllabus</option>
<option value='3' <?php if($_GET['scramble'] == '2' && $_GET['order'] == '3') { echo 'selected'; } ?>>Most recent first</option>
<option value='4' <?php if($_GET['scramble'] == '2' && $_GET['order'] == '4') { echo 'selected'; } ?>>Oldest first</option>
<option value='5' <?php if($_GET['scramble'] == '2' && $_GET['order'] == '5') { echo 'selected'; } ?>>Easiest first</option>
<option value='6' <?php if($_GET['scramble'] == '2' && $_GET['order'] == '6') { echo 'selected'; } ?>>Hardest first</option>
<option value='7' <?php if($_GET['scramble'] == '2' && $_GET['order'] == '7') { echo 'selected'; } ?>>Random</option>
<optgroup></select></p>
<?php
}
if($assign_type == '5'){
?>
<p><select name="order" id="order"><optgroup>
<option value="" disabled <?php if($_GET['order'] == '' || empty($_GET['order'])) { echo 'selected'; } ?>>Sort MCQs by</option>
<?php
if($assign_pt == '1'){
?>
<option value='1' <?php if($_GET['order'] == '1') { echo 'selected'; } ?>>Question paper</option>
<?php
}
?>
<option value='2' <?php if($_GET['order'] == '2') { echo 'selected'; } ?>>Syllabus</option>
<option value='3' <?php if($_GET['order'] == '3') { echo 'selected'; } ?>>Most recent first</option>
<option value='4' <?php if($_GET['order'] == '4') { echo 'selected'; } ?>>Oldest first</option>
<option value='5' <?php if($_GET['order'] == '5') { echo 'selected'; } ?>>Easiest first</option>
<option value='6' <?php if($_GET['order'] == '6') { echo 'selected'; } ?>>Hardest first</option>
<option value='7' <?php if($_GET['order'] == '7') { echo 'selected'; } ?>>Random</option>
<optgroup></select></p>
<p><select name="time_limit"><optgroup>
<option value="" disabled <?php if($_GET['time_limit'] == '' || empty($_GET['time_limit'])) { echo 'selected'; } ?>>Time limit per question</option>
<option value="30" <?php if($_GET['time_limit'] == '30') { echo 'selected'; } ?>>30 seconds</option>
<option value="45" <?php if($_GET['time_limit'] == '45') { echo 'selected'; } ?>>45 seconds</option>
<option value="60" <?php if($_GET['time_limit'] == '60') { echo 'selected'; } ?>>1 minute</option>
<option value="75" <?php if($_GET['time_limit'] == '75') { echo 'selected'; } ?>>1 minute 15 seconds</option>
<option value="90" <?php if($_GET['time_limit'] == '90') { echo 'selected'; } ?>>1 minute 30 seconds</option>
<option value="105" <?php if($_GET['time_limit'] == '105') { echo 'selected'; } ?>>1 minute 45 seconds</option>
<option value="120" <?php if($_GET['time_limit'] == '120') { echo 'selected'; } ?>>2 minutes</option>
<option value="135" <?php if($_GET['time_limit'] == '135') { echo 'selected'; } ?>>2 minutes 15 seconds</option>
<option value="150" <?php if($_GET['time_limit'] == '150') { echo 'selected'; } ?>>2 minutes 30 seconds</option>
<option value="165" <?php if($_GET['time_limit'] == '165') { echo 'selected'; } ?>>2 minutes 45 seconds</option>
<option value="180" <?php if($_GET['time_limit'] == '180') { echo 'selected'; } ?>>3 minutes</option>
<optgroup></select></p>
<p><select name="show_ranking">
<optgroup>
<option value="" disabled <?php if($_GET['show_ranking'] == '' || empty($_GET['show_ranking'])) { echo 'selected'; } ?>>Show ranking</option>
<option value="1" <?php if($_GET['show_ranking'] == '1') { echo 'selected'; } ?>>After every question</option>
<option value="5" <?php if($_GET['show_ranking'] == '5') { echo 'selected'; } ?>>After every 5 questions</option>
<option value="10" <?php if($_GET['show_ranking'] == '10') { echo 'selected'; } ?>>After every 10 questions</option>
<option value="end" <?php if($_GET['show_ranking'] == 'end') { echo 'selected'; } ?>>At the end only</option>
</optgroup>
</select></p>
<p><b style="font-size: larger;">GRADE THRESHOLDS</b></p>
<p><table align="center" bgcolor="#000000" width="50%"><tbody>
<tr>
<td height="40" bgcolor="#647d57"><b>A</b></td>
<td height="40" bgcolor="#647d57"><b>B</b></td>
<td height="40" bgcolor="#647d57"><b>C</b></td>
<td height="40" bgcolor="#647d57"><b>D</b></td>
<td height="40" bgcolor="#647d57"><b>E</b></td>
<?php
if($s == '1'){
echo '<td height="40" bgcolor="#647d57"><b>F</b></td>
<td height="40" bgcolor="#647d57"><b>G</b></td>';}
?>
</tr>
<tr>
<td height="40" bgcolor="#769467"><input type="text" name="min_a" maxlength="2" required value="<?php if(!isset($_GET['min_a'])) { echo $data_get['min_a']; } if(isset($_GET['min_a'])) { echo $_GET['min_a']; } ?>"></td>
<td height="40" bgcolor="#769467"><input type="text" name="min_b" maxlength="2" required value="<?php if(!isset($_GET['min_b'])) { echo $data_get['min_b']; } if(isset($_GET['min_b'])) { echo $_GET['min_b']; } ?>"></td>
<td height="40" bgcolor="#769467"><input type="text" name="min_c" maxlength="2" required value="<?php if(!isset($_GET['min_c'])) { echo $data_get['min_c']; } if(isset($_GET['min_c'])) { echo $_GET['min_c']; } ?>"></td>
<td height="40" bgcolor="#769467"><input type="text" name="min_d" maxlength="2" required value="<?php if(!isset($_GET['min_d'])) { echo $data_get['min_d']; } if(isset($_GET['min_d'])) { echo $_GET['min_d']; } ?>"></td>
<td height="40" bgcolor="#769467"><input type="text" name="min_e" maxlength="2" required value="<?php if(!isset($_GET['min_e'])) { echo $data_get['min_e']; } if(isset($_GET['min_e'])) { echo $_GET['min_e']; } ?>"></td>
<?php
if($s == '1'){
?>
<td height="40" bgcolor="#769467"><input type="text" name="min_f" maxlength="2" required value="<?php if(!isset($_GET['min_f'])) { echo $data_get['min_f']; } if(isset($_GET['min_f'])) { echo $_GET['min_f']; } ?>"></td>
<td height="40" bgcolor="#769467"><input type="text" name="min_g" maxlength="2" required value="<?php if(!isset($_GET['min_g'])) { echo $data_get['min_g']; } if(isset($_GET['min_g'])) { echo $_GET['min_g']; } ?>"></td>
<?php
}
?>
</tr></tbody></table></p>


<?php
}
if($assign_type != '4' && $assign_type != '5'){
?>
<p><b>Scramble questions</b><br>
<b style="font-size: medium; color: #820000;">Display questions in a different order for each student</b><span class="brmedium"></span>
<input type="radio" style="transform: scale(1.5);" name="scramble" value='1' onclick="showOrder('hide');clearOrder();" <?php if(empty($_GET['scramble']) || $_GET['scramble'] == '1') { echo 'checked'; } ?>> Yes - <input type="radio" style="transform: scale(1.5);" name="scramble" value='2' onclick="showOrder('show');" <?php if($_GET['scramble'] == '2') { echo 'checked'; } ?>> No</p>
<?php
if(empty($_GET['scramble']) || $_GET['scramble'] == '1'){
echo '<div id="select_order" style="display:none">';}
if($_GET['scramble'] == '2'){
echo '<div id="select_order" style="display:block">';}
?>
<select name="order" id="order"><optgroup>
<option value="" disabled <?php if(($_GET['scramble'] == '2' && $_GET['order'] == '') || $_GET['scramble'] == '1' || empty($_GET['scramble']) || empty($_GET['order'])) { echo 'selected'; } ?>>Sort MCQs by</option>
<?php
if($assign_pt == '1'){
?>
<option value='1' <?php if($_GET['scramble'] == '2' && $_GET['order'] == '1') { echo 'selected'; } ?>>Question paper</option>
<?php
}
?>
<option value='2' <?php if($_GET['scramble'] == '2' && $_GET['order'] == '2') { echo 'selected'; } ?>>Syllabus</option>
<option value='3' <?php if($_GET['scramble'] == '2' && $_GET['order'] == '3') { echo 'selected'; } ?>>Most recent first</option>
<option value='4' <?php if($_GET['scramble'] == '2' && $_GET['order'] == '4') { echo 'selected'; } ?>>Oldest first</option>
<option value='5' <?php if($_GET['scramble'] == '2' && $_GET['order'] == '5') { echo 'selected'; } ?>>Easiest first</option>
<option value='6' <?php if($_GET['scramble'] == '2' && $_GET['order'] == '6') { echo 'selected'; } ?>>Hardest first</option>
<option value='7' <?php if($_GET['scramble'] == '2' && $_GET['order'] == '7') { echo 'selected'; } ?>>Random</option>
<optgroup></select></div>
<p><b>Hide questions</b><br>
<b style="font-size: medium; color: #820000;">Prevent students from viewing the questions in the practice section</b><span class="brmedium"></span>
<input type="radio" style="transform: scale(1.5);" name="hide" value='1' <?php if(empty($_GET['hide']) || $_GET['hide'] == '1') { echo 'checked'; } ?>> Yes - <input type="radio" style="transform: scale(1.5);" name="hide" value='2' <?php if($_GET['hide'] == '2') { echo 'checked'; } ?>> No</p>
<p><b>Release reports</b><br>
<b style="font-size: medium; color: #820000;">Allow students to submit and check their answers before the assignment deadline</b><span class="brmedium"></span>
<input type="radio" style="transform: scale(1.5);" name="release" value='1' <?php if(empty($_GET['release']) || $_GET['release'] == '1') { echo 'checked'; } ?>> Yes - <input type="radio" style="transform: scale(1.5);" name="release" value='2' <?php if($_GET['release'] == '2') { echo 'checked'; } ?>> No</p>
<p><b style="font-size: larger;">GRADE THRESHOLDS</b></p>
<p><table align="center" bgcolor="#000000" width="50%"><tbody>
<tr>
<td height="40" bgcolor="#647d57"><b>A</b></td>
<td height="40" bgcolor="#647d57"><b>B</b></td>
<td height="40" bgcolor="#647d57"><b>C</b></td>
<td height="40" bgcolor="#647d57"><b>D</b></td>
<td height="40" bgcolor="#647d57"><b>E</b></td>
<?php
if($s == '1'){
echo '<td height="40" bgcolor="#647d57"><b>F</b></td>
<td height="40" bgcolor="#647d57"><b>G</b></td>';}
?>
</tr>
<tr>
<td height="40" bgcolor="#769467"><input type="text" name="min_a" maxlength="2" required value="<?php if(!isset($_GET['min_a'])) { echo $data_get['min_a']; } if(isset($_GET['min_a'])) { echo $_GET['min_a']; } ?>"></td>
<td height="40" bgcolor="#769467"><input type="text" name="min_b" maxlength="2" required value="<?php if(!isset($_GET['min_b'])) { echo $data_get['min_b']; } if(isset($_GET['min_b'])) { echo $_GET['min_b']; } ?>"></td>
<td height="40" bgcolor="#769467"><input type="text" name="min_c" maxlength="2" required value="<?php if(!isset($_GET['min_c'])) { echo $data_get['min_c']; } if(isset($_GET['min_c'])) { echo $_GET['min_c']; } ?>"></td>
<td height="40" bgcolor="#769467"><input type="text" name="min_d" maxlength="2" required value="<?php if(!isset($_GET['min_d'])) { echo $data_get['min_d']; } if(isset($_GET['min_d'])) { echo $_GET['min_d']; } ?>"></td>
<td height="40" bgcolor="#769467"><input type="text" name="min_e" maxlength="2" required value="<?php if(!isset($_GET['min_e'])) { echo $data_get['min_e']; } if(isset($_GET['min_e'])) { echo $_GET['min_e']; } ?>"></td>
<?php
if($s == '1'){
?>
<td height="40" bgcolor="#769467"><input type="text" name="min_f" maxlength="2" required value="<?php if(!isset($_GET['min_f'])) { echo $data_get['min_f']; } if(isset($_GET['min_f'])) { echo $_GET['min_f']; } ?>"></td>
<td height="40" bgcolor="#769467"><input type="text" name="min_g" maxlength="2" required value="<?php if(!isset($_GET['min_g'])) { echo $data_get['min_g']; } if(isset($_GET['min_g'])) { echo $_GET['min_g']; } ?>"></td>
<?php
}
?>
</tr></tbody></table></p>
<?php
}
?>
<input type="submit" value="CONFIRM" onclick="preload();">
<p>&nbsp;</p>
</form></div></body></html>