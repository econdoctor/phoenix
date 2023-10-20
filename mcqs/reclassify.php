<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
if($user_id != 1){
echo 'Access is restricted';
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id = '".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
$topic_id = $_GET['topic_id'];
$question_id = $_GET['question_id'];
if(empty($question_id) || empty($topic_id)){
echo 'Missing information';
exit();}
$sql_get = "SELECT question_paper_id, question_number, question_syllabus, question_move FROM phoenix_questions WHERE question_id = '".$question_id."'";
$res_get = $mysqli -> query($sql_get);
$data_get = mysqli_fetch_assoc($res_get);
$question_paper_id = $data_get['question_paper_id'];
$question_syllabus = $data_get['question_syllabus'];
$question_number = $data_get['question_number'];
$question_move = $data_get['question_move'];
$m = $_GET['m'];
if($m == '2'){
$topic_syllabus = '2';}
if($m == '3'){
$topic_syllabus = '3';}
if($m != '2' && $m != '3'){
$sql_topic = "SELECT topic_syllabus FROM phoenix_topics WHERE topic_id = '".$topic_id."'";
$res_topic = $mysqli -> query($sql_topic);
$data_topic = mysqli_fetch_assoc($res_topic);
$topic_syllabus = $data_topic['topic_syllabus'];}
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
width: 60%;
border-radius: 4px;
}
optgroup {
font-size:18px;
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
width: 60%;
border-radius: 4px;
}
select {
padding: 3px 5px;
margin: 2px 0;
width: 60%;
box-sizing: border-box;
text-align: center;
font-weight: bold;
font-size: 18px;
border: 2px solid black;
border-radius: 4px;
}
input, select {
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
<input type="button" name="browse" value="BROWSE" style="font-size: x-large; width: 30%; background-color: #ffc000;  color: #3b2c00; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='browse.php';"/>&nbsp;&nbsp;
<input type="button" name="manage" value="MANAGE" style="font-size: x-large; width: 30%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='manage.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 30%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='assign.php';"/>
</td></tr></tbody></table>
<p style="font-size: x-large"><b>RECLASSIFY A QUESTION</b></p>
<table width="67%" align="center">
<tr><td>
<img src="./q/<?php echo $question_syllabus; ?>/<?php echo $question_paper_id; ?>/<?php echo $question_number; ?>.png" width="95%" style="border: black solid 2px;">
</td></tr>
<tr><td>
<form method="post" action="reclassify.php?topic_id=<?php echo $topic_id; ?>&question_id=<?php echo $question_id; ?><?php if($m == '2'){ echo '&m=2'; } if($m == '3'){ echo '&m=3'; } ?>">
<p><select name="topic_unit" onChange="this.form.submit();">
<option value="" <?php if(empty($_POST['topic_unit']) || $_POST['topic_unit'] == ""){ echo 'selected'; } ?>>Choose a Unit</option>
<?php
$sql_units = "SELECT DISTINCT(topic_unit_id), topic_unit FROM phoenix_topics WHERE topic_syllabus = '".$topic_syllabus."' ORDER BY topic_unit_id ASC";
$res_units = $mysqli -> query($sql_units);
while($data_units = mysqli_fetch_assoc($res_units)){
?>
<option value="<?php echo $data_units["topic_unit_id"]; ?>" <?php if(!empty($_POST['topic_unit']) && $_POST['topic_unit'] == $data_units["topic_unit_id"]){ echo 'selected'; } ?>><?php echo $data_units["topic_unit"]; ?></option>
<?php
}
?>
</select></p>
<p><select name="topic_module">
<?php
if(empty($_POST['topic_unit']) || $_POST['topic_unit'] == ""){
?>
<option value="">Choose a Unit</option>
<?php
}
if(!empty($_POST['topic_unit']) || $_POST['topic_unit'] != ""){
?>
<option value="">Choose a Topic</option>
<?php
$sql_modules = "SELECT topic_module, topic_module_id FROM phoenix_topics WHERE topic_syllabus = '".$topic_syllabus."' AND topic_unit_id = '".$_POST['topic_unit']."' ORDER BY topic_module_id ASC";
$res_modules = $mysqli -> query($sql_modules);
while($data_modules = mysqli_fetch_assoc($res_modules)){
?>
<option value="<?php echo $data_modules["topic_module_id"]; ?>"><?php echo $data_modules["topic_module"]; ?></option>
<?php
}}
?>
</select></p>
<p><input type="submit" formaction="reclassify_db.php?topic_id=<?php echo $topic_id; ?>&question_id=<?php echo $question_id; ?>" value="RECLASSIFY"></p>
<?php
if(empty($m)){
if(($question_syllabus == 2 && $question_move == 0) || ($question_syllabus == 3 && $question_move == 1)){
?>
<p><input type="button" onclick="document.location.href='reclassify.php?topic_id=<?php echo $topic_id; ?>&question_id=<?php echo $question_id; ?>&m=3';" value="MOVE TO A2"></p>
<?php
}
if(($question_syllabus == 3 && $question_move == 0) || ($question_syllabus == 2 && $question_move == 2)){
?>
<p><input type="button" onclick="document.location.href='reclassify.php?topic_id=<?php echo $topic_id; ?>&question_id=<?php echo $question_id; ?>&m=2';" value="MOVE TO AS"></p>
<?php
}}
if($m == '2'){
?>
<p><input type="button" onclick="document.location.href='reclassify.php?topic_id=<?php echo $topic_id; ?>&question_id=<?php echo $question_id; ?>&m=3';" value="MOVE TO A2"></p>
<?php
}
if($m == '3'){
?>
<p><input type="button" onclick="document.location.href='reclassify.php?topic_id=<?php echo $topic_id; ?>&question_id=<?php echo $question_id; ?>&m=2';" value="MOVE TO AS"></p>
<?php
}
?>
<p><input type="button" onclick="document.location.href='remove_obsolete.php?topic_id=<?php echo $topic_id; ?>&question_id=<?php echo $question_id; ?>';" value="NO LONGER TESTED"></p>
<p><input type="button" onclick="document.location.href='topic_view.php?topic_id=<?php echo $topic_id; ?>';" value="GO BACK"></p>
<input type="hidden" name="m" value="<?php echo $m; ?>">
<input type="hidden" name="new_syllabus" value="<?php echo $topic_syllabus; ?>">
</form>
</p>
</td>
</tr>
</tbody>
</table>
</body>
</html>
