<?php
date_default_timezone_set('UTC');
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
include "../connectdb.php";
if ($mysqli -> connect_errno) {
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$assign_id = $_GET['assign_id'];
if(empty($assign_id)){
echo 'Missing info';
$mysqli -> close();
exit();}
$sql_info = "SELECT assign_nq, assign_name, assign_type, assign_syllabus, assign_game_status, assign_teacher, assign_time_limit, assign_game_pause FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
$assign_name = $data_info['assign_name'];
$assign_game_pause = $data_info['assign_game_pause'];
if($data_info['assign_teacher'] != $user_id){
echo 'Not your assignment';
$mysqli -> close();
exit();}
if($data_info['assign_type'] != '5'){
echo 'Not a game';
$mysqli -> close();
exit();}
if($data_info['assign_game_status'] == 0){
header("Location: assign_game_start.php?assign_id=$assign_id");
$mysqli -> close();
exit();}
if($data_info['assign_game_status'] == 2){
header("Location: assign_game_answer.php?assign_id=$assign_id");
$mysqli -> close();
exit();}
if($data_info['assign_game_status'] == 3){
header("Location: assign_game_ranking.php?assign_id=$assign_id");
$mysqli -> close();
exit();}
if($data_info['assign_game_status'] == 4){
header("Location: assign_game_final.php?assign_id=$assign_id");
$mysqli -> close();
exit();}
$sql_c = "SELECT COUNT(*) FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."' AND assign_question_status = '1'";
$res_c = $mysqli -> query($sql_c);
$data_c = mysqli_fetch_assoc($res_c);
if($data_c['COUNT(*)'] == 0){
$sql_e = "SELECT question_id FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."' AND assign_question_status = '0' ORDER BY assign_question_number LIMIT 1";
$res_e = $mysqli -> query($sql_e);
$nr_e = mysqli_num_rows($res_e);
if($nr_e == 0){
echo 'No question found';
$mysqli -> close();
exit();}
$data_e = mysqli_fetch_assoc($res_e);
$now = date("Y-m-d H:i:s");
$now10 =  date("Y-m-d H:i:s", strtotime(''.$now.' + 10 seconds'));
$deadline = date("Y-m-d H:i:s", strtotime(''.$now10.' + '.$data_info['assign_time_limit'].' seconds'));
$sql_init = "UPDATE phoenix_assign_questions SET assign_question_status = '1', assign_question_hide = '1', assign_question_deadline = '".$deadline."' WHERE assign_id = '".$assign_id."' AND question_id = '".$data_e['question_id']."'";
$res_init = $mysqli -> query($sql_init);}
$sql_get = "SELECT phoenix_assign_questions.question_id, assign_question_deadline, question_paper_id, question_number, question_syllabus, assign_question_number FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE assign_id = '".$assign_id."' AND assign_question_status = '1'";
$res_get = $mysqli -> query($sql_get);
$data_get = mysqli_fetch_assoc($res_get);
$question_id = $data_get['question_id'];
$now = date("Y-m-d H:i:s");
if($data_get['assign_question_deadline'] < $now){
header("Location: assign_game_question_db.php?assign_id=$assign_id&question_id=$question_id");
$mysqli -> close();
exit();}
$countdown = date("M d, Y H:i:s", strtotime($data_get['assign_question_deadline']));
$sql_stu = "SELECT COUNT(*) FROM phoenix_assign_users WHERE assign_id = '".$assign_id."'";
$res_stu = $mysqli -> query($sql_stu);
$data_stu = mysqli_fetch_assoc($res_stu);
$nr_stu = $data_stu['COUNT(*)'];
$sql_answers = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."'";
$res_answers = $mysqli -> query($sql_answers);
$data_answers = mysqli_fetch_assoc($res_answers);
$nr_answers = $data_answers['COUNT(*)'];
$percent = round($nr_answers / $nr_stu * 100, 0);
$mysqli -> close();
$time_left = $_GET['time_left'];
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
color: #000000;}
a:visited {
text-decoration: none;
color: #000000;}
a:hover {
text-decoration: none;
color: #000000;}
a:active {
text-decoration: none;
color: #000000;
font-size: large;}
body,td,th {
color: #000000;
font-family: "Segoe", "Segoe UI", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;}
body {
background-color: #8BA57E;
text-align: center;
font-size: large;}
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
border-radius: 4px;}
.brsmall {
display: block;
margin-bottom: 1.5em;}
html {
height:100%;}
body {
position:absolute; top:0; bottom:0; right:0; left:0;}
body {
-ms-overflow-style: none;
scrollbar-width: none;
overflow-y: scroll;}
body::-webkit-scrollbar {
display: none;}
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script>
var start = 0;
var ready = 0;
<?php
if(empty($time_left)){
?>
var time_limit = <?php echo $data_info['assign_time_limit']; ?>;
<?php
}
if(!empty($time_left) && $time_left > 0){
?>
var time_limit = Math.min(<?php echo $time_left; ?>, <?php echo $data_info['assign_time_limit']; ?>);
<?php
}
?>
var countDownDate = new Date("<?php echo $countdown; ?>").getTime();
<?php
if($assign_game_pause == 0){
?>
var x = setInterval(timer, 1000);
<?php
}
?>
function timer(){
var now0 = new Date();
var now = new Date(now0.getTime() + now0.getTimezoneOffset() * 60000);
var distance = countDownDate - now;
var distance_display = Math.floor(distance / 1000);
if(distance_display == 10){
document.getElementById("ongoing").pause();
document.getElementById("10sec").play();}
if(distance_display > time_limit){
document.getElementById("countdown_start").innerHTML = distance_display - time_limit;
if(ready == 0){
document.getElementById("get_ready").play();
ready = 1;}}
if(distance_display <= time_limit){
document.getElementById("ready").style.display = "none";
document.getElementById("cd_start").style.display = "none";
document.getElementById("cd_end").style.display = "block";
document.getElementById("question").style.display = "block";
document.getElementById("countdown_end").innerHTML = distance_display;
if(start == 0){
document.getElementById("get_ready").pause();
document.getElementById("ongoing").play();
start = 1;}}
if(distance_display <= 0){
document.getElementById("countdown_end").innerHTML = '0';
clearInterval(x);
setTimeout(() => {
document.location.href='assign_game_question_db.php?assign_id=<?php echo $assign_id; ?>&question_id=<?php echo $question_id; ?>';}, 2000);
document.getElementById("10sec").pause();
document.getElementById("finished").play();
document.getElementById("question").style.display = "none";
document.getElementById("end").style.display = "block";}}
</script>
<script>
function next(){
document.getElementById("next").removeAttribute("onclick");
document.getElementById("next").setAttribute("style", "cursor: default; background-color: black;");
document.getElementById("btn_pr").removeAttribute("onclick");
document.getElementById("btn_pr").removeAttribute("style");
document.getElementById("get_ready").pause();
document.getElementById("ongoing").pause();
document.getElementById("10sec").pause();
document.getElementById("finished").play();
document.location.href='assign_game_question_db.php?assign_id=<?php echo $assign_id; ?>&question_id=<?php echo $question_id; ?>';}
</script>
<script>
var countDownDate2 = new Date("<?php echo $countdown; ?>").getTime();
function pause(){
clearInterval(x);
var now1 = new Date();
var now2 = new Date(now1.getTime() + now1.getTimezoneOffset() * 60000);
var distance2 = countDownDate2 - now2;
<?php
if(empty($time_left) || (!empty($time_left) && $time_left == $data_info['assign_time_limit'])){
?>
var distance_display2 = Math.min(Math.floor(distance2 / 1000), <?php echo $data_info['assign_time_limit']; ?>);
<?php
}
if(!empty($time_left) && $time_left < $data_info['assign_time_limit']){
?>
var distance_display2 = Math.min(Math.floor(distance2 / 1000), <?php echo $time_left; ?>);
<?php
}
?>
document.getElementById("btn_pr").removeAttribute("onclick");
document.getElementById("btn_pr").setAttribute("style", "cursor: default; background-color: black;");
$.ajax({
url: "assign_game_pause.php?assign_id=<?php echo $assign_id; ?>&question_id=<?php echo $question_id; ?>&count="+distance_display2,
success: function(data){
if(data == 'die'){
window.location.href = "../login.php";}
if(data == '1'){
document.getElementById("pause").style.display = 'block';
document.getElementById("ready").style.display = 'none';
document.getElementById("end").style.display = 'none';
document.getElementById("question").style.display = 'none';
document.getElementById("btn_pr").value = 'RESUME';
document.getElementById("btn_pr").setAttribute("onclick", "resume();");
document.getElementById("btn_pr").removeAttribute("style");
document.getElementById("get_ready").pause();
document.getElementById("ongoing").pause();
document.getElementById("10sec").pause();
document.getElementById("finished").pause();}}})}
</script>
<script>
function resume(){
document.getElementById("btn_pr").removeAttribute("onclick");
document.getElementById("btn_pr").setAttribute("style", "cursor: default; background-color: black;");
$.ajax({
url: "assign_game_resume.php?assign_id=<?php echo $assign_id; ?>&question_id=<?php echo $question_id; ?>",
success: function(data){
if(data == 'die'){
window.location.href = "../login.php";}
if(data > 0){
document.location.href='assign_game_question.php?assign_id=<?php echo $assign_id; ?>&time_left='+data;}}})}
</script>
<script>
setInterval(function refresh(){
$.ajax({
url: "leader_question.php?assign_id=<?php echo $assign_id; ?>&question_id=<?php echo $question_id; ?>",
success: function(data){
if(data == 'die'){
window.location.href = "../login.php";}
if(data == 'R'){
window.location.reload();}
if(data != 'die' && data != 'R'){
if(data > 0 && data < 100){
var data2 = 100 - data;
document.getElementById("none").style.display = 'none';
document.getElementById("some").style.display = 'block';
document.getElementById("all").style.display = 'none';
document.getElementById("cell1").setAttribute('width', data+'%');
document.getElementById("cell1").innerHTML = '<b style="color: #FFFFFF; font-size: larger;">'+data+'%</b>';
document.getElementById("cell2").setAttribute('width', data2+'%');
document.getElementById("cell2").innerHTML = '';}
if(data == 100){
clearInterval(x)
document.getElementById("none").style.display = 'none';
document.getElementById("some").style.display = 'none';
document.getElementById("all").style.display = 'block';
document.getElementById("ongoing").pause();
document.getElementById("10sec").pause();
document.getElementById("finished").play();
document.location.href='assign_game_question_db.php?assign_id=<?php echo $assign_id; ?>&question_id=<?php echo $question_id; ?>';}}}});}, 2500);
</script>
</head>
<body>
<table width="100%" height="100%">
<tr><td width="25%">
<p><a href="main.php"><img src="home_phoenix.png" width="100"></a></p>
<p style="font-size: x-large;"><b><?php echo strtoupper($assign_name); ?></b></p>
<p style="font-size: x-large;"><b>QUESTION <?php echo $data_get['assign_question_number']; ?> / <?php echo $data_info['assign_nq']; ?></b></p>
<p id="cd_start" <?php if($assign_game_pause == 1) { echo 'style="display: none"'; } ?>><b><span id="countdown_start" style="font-size: xx-large; color: #033909;">?</span></b></p>
<p id="cd_end" style="display: none;"><b><span id="countdown_end" style="font-size: xx-large; color: #820000;"></span></b></p>
<span class="brsmall"></span>
<p align="center">
<?php
if($assign_game_pause == 0){
?>
<input type="button" id="btn_pr" value="PAUSE" onclick="pause();">
<?php
}
if($assign_game_pause == 1){
?>
<input type="button" id="btn_pr" value="RESUME" onclick="resume();">
<?php
}
?>
&nbsp;&nbsp;<input type="button" id="next" value="NEXT" onclick="next();"></p>
<span class="brsmall"></span>
<div id="none" <?php if($percent > 0) { echo 'style="display: none;"'; } ?>>
<table width="90%" align="center" style="border: solid black 2px;">
<tr>
<td bgcolor="#8BA57E" height="30"><b style="font-size: larger;">0%</b></td>
</tr>
</table></div>
<div id="some" <?php if($percent == 0 || $percent == 100 ) { echo 'style="display: none;"'; } ?>>
<table width="90%" align="center" bgcolor="#000000"><tr>
<td id="cell1" height="30" bgcolor="#033909" width="<?php echo $percent; ?>%"><b style="color: #FFFFFF; font-size: larger;"><?php echo $percent; ?>%</b></td>
<td id="cell2" height="30" bgcolor="#8BA57E" width="<?php echo 100 - $percent; ?>%"></td>
</tr></table></div>
<div id="all" <?php if($percent < 100) { echo 'style="display: none;"'; } ?>>
<table width="90%" align="center" bgcolor="#000000">
<tr>
<td height="30" bgcolor="#033909"><b style="color: #FFFFFF; font-size: larger;">100%</b></td>
</tr>
</table></div>
</td>
<td width="80%">
<p id="ready" <?php if($assign_game_pause == 1) { echo 'style="display: none"'; } ?>>
<img src="shaun_ready.gif" style="border: black 4px solid">
<span class="brsmall"></span>
<b style="font-size: xx-large; color: #033909;">GET READY...</b></p>
<p id="question" style="display: none;">
<img src="./q/<?php echo $data_get['question_syllabus']; ?>/<?php echo $data_get['question_paper_id']; ?>/<?php echo $data_get['question_number']; ?>.png" style="border: solid black 2px; max-width:95%; max-height:95%; height: auto; width:auto;">
</p>
<p id="end" style="display: none;">
<img src="shaun_end.gif" style="border: black 4px solid">
<span class="brsmall"></span>
<b style="font-size: xx-large; color: #820000;">TIME'S UP!</b></p>
<p id="pause" <?php if($assign_game_pause == 0) { echo 'style="display: none"'; } ?>>
<img src="shaun_paused.gif" style="border: black 4px solid">
<span class="brsmall"></span>
<b style="font-size: xx-large;">GAME PAUSED</b></p>
</td></tr></table>
<audio id="10sec" src="10sec.mp3" preload="auto">
<audio id="get_ready" src="get_ready.mp3" preload="auto">
<audio id="finished" src="finished.mp3" preload="auto">
<audio id="ongoing" src="ongoing.mp3" preload="auto">
</body></html>