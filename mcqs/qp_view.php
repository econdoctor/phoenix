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
$user_active = $data['user_active'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
$s = $_GET['s'];
if(empty($s)){
echo 'Please go back and choose a course.';
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
echo 'You are not authorized to access this paper.';
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
$sql3 = "SELECT * FROM phoenix_questions INNER JOIN phoenix_topics ON phoenix_questions.question_topic_id = phoenix_topics.topic_id WHERE question_paper_id = '".$paper_id."'";
$res3 = $mysqli -> query($sql3);
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
width: 40%;
border-radius: 4px;
}
.tooltip2 {
position: relative;
display: inline-block;
}
.tooltip2 .tooltiptext2 {
visibility: hidden;
width: 250px;
background-color: black;
color: #fff;
text-align: center;
padding: 10px 10px 10px 10px;
bottom: 150%;
border-radius: 6px;
margin-left: -150px;
margin-top: -150px;
position: absolute;
z-index: 1;
}
.tooltip2 .tooltiptext2::after {
content: " ";
position: absolute;
top: 100%;
left: 50%;
margin-left: -5px;
border-width: 5px;
border-style: solid;
border-color: black transparent transparent transparent;
}
.tooltip2:hover .tooltiptext2 {
visibility: visible;
}
video {
  margin-bottom: -1px;
}
</style>
<script type="text/javascript">
function showVideo(number) {
var div_video = document.getElementById('div_'+number);
var btn_video = document.getElementById('btn_'+number);
var player_video = document.getElementById('player_'+number);
div_video.style.display = 'block';
btn_video.style.display = 'none';
player_video.play();}
</script>
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
<p><input type="button" name="title" style="width: 20%" value="<?php echo strtoupper($s_text); ?> PAPERS" onclick="document.location.href='browse_paper.php?s=<?php echo $s; ?>';"/>
<p style="font-size: x-large"><b>PAPER REFERENCE</b></p>
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
<p style="font-size: x-large;"><b>QUESTIONS</b></p>
<?php
$i = 1;
while($data3 = mysqli_fetch_assoc($res3)){
$video = 0;
$filename = './v/'.$data2['paper_id'].$data3['question_number'].'.mp4';
if(file_exists($filename)){
$video = 1;}
$question_id = $data3['question_id'];
$question_move = $data3['question_move'];
$unit = $data3['topic_unit'];
$module = $data3['topic_module'];
$topic_info = $unit.'<br><br>'.$module;
if($data3['question_answer'] == '0'){
?>
<table width="72%" align="center" bgcolor="#000000">
<tbody>
<tr valign="middle">
<td height="40" colspan ="4" bgcolor="#647d57" valign="middle">
<table align="center" bgcolor="647d57" width="100%">
<tbody><tr valign="middle">
<td width="5%" valign="middle">&nbsp;</td>
<td width="90%"  align="center"><b>QUESTION <?php echo $data3['question_number']; ?></b></td>
<td width="5%" valign="middle">&nbsp;</td>
</tr></tbody></table></td></tr>
<tr><td height="40" bgcolor="#a0b595"><b>This question was removed</b></td>
</tr></tbody></table>
<?php
}
if($data3['question_answer'] != '0'){
?>
<table width="72%" align="center" bgcolor="#000000">
<tbody>
<tr valign="middle">
<td height="40" colspan ="4" bgcolor="#647d57" valign="middle">
<table align="center" bgcolor="647d57" width="100%">
<tbody><tr valign="middle">
<td width="5%" valign="middle">&nbsp;</td>
<td width="90%" align="center"><b>QUESTION <?php echo $data3['question_number']; ?></b></td>
<td width="5%" valign="middle">
<div class="tooltip2"><img src="info.png" valign="middle" height="30">
<span class="tooltiptext2"><b><?php echo $topic_info; ?></b></span></div></td>
</tr></tbody></table></td></tr>
<tr><td colspan="4" bgcolor="#FFFFFF"><img src="q/<?php echo $s; ?>/<?php echo $paper_id; ?>/<?php echo $data3['question_number']; ?>.png" width="95%"></td>
</tr>
<?php
if($data3['question_rate_a'] == NULL && $data3['question_rate_b'] == NULL && $data3['question_rate_c'] == NULL && $data3['question_rate_d'] == NULL){
?>
<tr>
<td height="40" width="25%" <?php if($data3['question_answer'] == '1') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>A</b></td>
<td height="40" width="25%" <?php if($data3['question_answer'] == '2') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>B</b></td>
<td height="40" width="25%" <?php if($data3['question_answer'] == '3') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>C</b></td>
<td height="40" width="25%" <?php if($data3['question_answer'] == '4') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>D</b></td>
</tr>
<?php
}
if($data3['question_rate_a'] != NULL && $data3['question_rate_b'] != NULL && $data3['question_rate_c'] != NULL && $data3['question_rate_d'] != NULL){
$question_rate_a = round($data3['question_rate_a'], 0);
$question_rate_b = round($data3['question_rate_b'], 0);
$question_rate_c = round($data3['question_rate_c'], 0);
$question_rate_d = round($data3['question_rate_d'], 0);
?>
<tr>
<td height="40" width="25%" <?php if($data3['question_answer'] == '1') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>A<?php echo ' ('.$question_rate_a.'%)'; ?></b></td>
<td height="40" width="25%" <?php if($data3['question_answer'] == '2') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>B<?php echo ' ('.$question_rate_b.'%)'; ?></b></td>
<td height="40" width="25%" <?php if($data3['question_answer'] == '3') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>C<?php echo ' ('.$question_rate_c.'%)'; ?></b></td>
<td height="40" width="25%" <?php if($data3['question_answer'] == '4') { echo 'bgcolor = "#246648"'; } else { echo 'bgcolor = "#769467"'; } ?>><b>D<?php echo ' ('.$question_rate_d.'%)'; ?></b></td>
</tr>
<?php
}
if($video == 1){
?>
<tr><td colspan="4" bgcolor="#a0b595">
<p><input id="btn_<?php echo $data3['question_number']; ?>" type="button" style="width: 30%;" value="VIDEO EXPLANATION" onclick="showVideo('<?php echo $data3['question_number']; ?>');"></p>
<div id="div_<?php echo $data3['question_number']; ?>" style="display:none;">
<p><video id="player_<?php echo $data3['question_number']; ?>" width="95%" controls preload="none" style="border: black solid 2px;">
<source src="./v/<?php echo $data2['paper_id'].$data3['question_number']; ?>.mp4" type="video/mp4">
Your browser does not support the video tag.
</video>
</div></p>
</td></tr>
<?php
}
if($question_move == 1){
?>
<tr>
<td colspan="4" bgcolor="a0b595">
<p><b style="color: #033909;">This question relates to content which is now included in the AS Level syllabus</b></p>
</td>
</tr>
<?php
}
if($question_move == 2){
?>
<tr>
<td colspan="4" bgcolor="a0b595">
<p><b style="color: #033909;">This question relates to content which is now included in the A Level syllabus</b></p>
</td>
</tr>
<?php
}
if($question_move == 3){
?>
<tr>
<td colspan="4" bgcolor="a0b595">
<p><b style="color: #033909;">This question relates to content which is no longer included in the AS & A Level syllabus</b></p>
</td>
</tr>
<?php
}
?>
</tbody></table>
<?php
}
?>
<br><br>
<?php
$i++;}
?>
<a href="#top"><b>Back to the top</b></a>
<p>&nbsp;</p>
</body></html>