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
$target_check = './q/' . $paper_syllabus . '/' . $paper_id . '/';
$files_in_directory = scandir($target_check);
$items_count = count($files_in_directory);
if($items_count != 32){
echo 'Some questions appear to be missing';
exit();}
for($j=1; $j<=30; $j++){
$filename_check = './q/'.$paper_syllabus.'/'.$paper_id . '/'.$j.'.png';
if(!file_exists($filename_check)){
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
width: 25%;
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
width: 50%
}
input, select{
box-sizing: border-box;
-moz-box-sizing: border-box;
-webkit-box-sizing: border-box;
}
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script>
window.onload = function(){
setInterval(function(){$.post('refresh_session.php');}, 60000);};
</script>
<script>
function getModule(val,id){
$.ajax({
type: "POST",
url: "get_module.php?s=<?php echo $paper_syllabus; ?>",
data:'topic_unit='+val,
success: function(data_module){
$("#topic_module"+id).html(data_module);
}});}
</script>
</head>
<body><a name="top"></a>
<iframe style="display:none" name="transFrame" id="transFrame"></iframe>
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
<p style="font-size: x-large"><b>CLASSIFICATION</b></p>
<p><b>Paper reference:</b><br>
<b style="color: #033909"><?php echo $paper_reference; ?></b></p>
<p><b style="color: #820000">Please assign each question to a topic.</b></p>
<p><b style="color: #820000">You should also double check that all answers are correct.</b></p>
<?php
if($paper_syllabus == 1){
?>
<p><b style="color: #820000;"><u>Important</u>: Questions should be classified according to the 2023-2025 syllabus</b></p>
<p><input type="button" value="DOWNLOAD SYLLABUS" onclick="window.open('syllabus_igcse.pdf', '_blank');"></p>
<?php
}
if($paper_syllabus != 1){
?>
<p><b style="color: #820000;"><u>Important</u>: Questions should be classified according to the 2023-2025 syllabus</b></p>
<p><input type="button" value="DOWNLOAD SYLLABUS" onclick="window.open('syllabus_as.pdf', '_blank');"></p>
<?php
}
$sql_get = "SELECT * FROM phoenix_questions WHERE question_paper_id = '".$paper_id."'";
$res_get = $mysqli -> query($sql_get);
$i = 1;
while($data_get = mysqli_fetch_assoc($res_get)){
$question_id = $data_get['question_id'];
$question_number = $data_get['question_number'];
$question_answer = $data_get['question_answer'];
if($question_answer == 0){
$question_answer = 'X';}
if($question_answer == 1){
$question_answer = 'A';}
if($question_answer == 2){
$question_answer = 'B';}
if($question_answer == 3){
$question_answer = 'C';}
if($question_answer == 4){
$question_answer = 'D';}
$question_syllabus = $data_get['question_syllabus'];
$qu = $data_get['question_unit'];
$qm = $data_get['question_module'];
echo '<form method="post" name="form'.$i.'" id="form'.$i.'" action="add_paper_insert4.php?question_id='.$question_id.'&user_id='.$user_id.'&s='.$paper_syllabus.'&question_number='.$i.'" target="transFrame">
<div id="'.$question_number.'">';
echo '<table width="72%" align="center" bgcolor="#000000">
<tbody>';
if($qu == 0 || $qm == 0){
echo '<tr valign="middle">
<td height="40" bgcolor="#6A865D"><b>QUESTION '.$question_number.'</b></td>
</tr>';}
if($qu != 0 && $qm != 0){
echo '<tr valign="middle"><td height="40" colspan ="4" bgcolor="#6A865D" valign="middle">
<table align="center" bgcolor="6A865D" width="100%">
<tbody>
<tr valign="middle">
<td width="5%" valign="middle"><img src="tick.png" height="25"></td>
<td width="90%" align="center"><b>QUESTION '.$question_number.'</b></td>
<td width="5%">&nbsp;</td>
</tr>
</tbody>
</table>
</td></tr>';}
echo '<tr>
<td bgcolor="#FFFFFF"><img src="q/'.$question_syllabus.'/'.$paper_id.'/'.$question_number.'.png" width="95%"></td>
</tr>
<tr>
<td bgcolor="#8EA782">
<table width="95%" bgcolor="#8EA782" align="center">
<tbody>
<tr>
<td>
<p><b>ANSWER:</b>  <b style="color: #033909; font-size: x-large;">'.$question_answer.'</b></p>';
?>
<p>
<select name="topic_unit<?php echo $i; ?>" id="topic_unit<?php echo $i; ?>"  onChange="this.form.submit();getModule(this.value,<?php echo $i; ?>);">
<optgroup>
<option value="0">Choose a Unit</option>
<?php
$req1="SELECT DISTINCT(topic_unit_id), topic_unit  FROM phoenix_topics WHERE topic_syllabus = '".$question_syllabus."' ORDER BY topic_unit_id ASC";
$res1 = $mysqli -> query($req1);
while($ds1=$res1->fetch_assoc()){
?>
<option value="<?php echo $ds1["topic_unit_id"]; ?>" <?php if($qu == $ds1["topic_unit_id"]) { echo 'selected="selected"'; } ?> ><?php echo $ds1["topic_unit"]; ?></option>
<?php
}
?>
</optgroup></select></p>
<p>
<select id="topic_module<?php echo $i; ?>" name="topic_module<?php echo $i; ?>" onload="this.form.submit();" onchange="this.form.submit();">
<optgroup>
<?php
if($qu == 0){
echo '<option value="0">Choose a Chapter</option>';}
if($qu != 0){
$req3="SELECT DISTINCT(topic_module_id), topic_module  FROM phoenix_topics WHERE topic_syllabus = '".$question_syllabus."'AND topic_unit_id = '".$qu."' ORDER BY topic_module_id ASC";
$res3 = $mysqli -> query($req3);
?>
<option value="0" <?php if($qm == 0) { echo 'selected="selected"'; } ?>>Choose a Chapter</option>
<?php
while($ds3=$res3->fetch_assoc()){
?>
<option value="<?php echo $ds3["topic_module_id"]; ?>" <?php if($qm == $ds3["topic_module_id"]) { echo 'selected="selected"'; } ?> ><?php echo $ds3["topic_module"]; ?></option>
<?php
}}
?>
</optgroup>
</select></p>
<?php
echo '</td></tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table></div><br><br></form>';
$i++;}
?>
<p><input type="button" value="FINISH" style="width: 15%;" onclick="document.location.href='add_paper_final.php?paper_reference=<?php echo $paper_reference; ?>';"></p>
<p><a href="#top"><b>Back to the top</b></a></p>
</body>
</html>