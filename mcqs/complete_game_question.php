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
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$assign_id = $_GET['assign_id'];
if(empty($assign_id)){
echo 'Missing information about the assignment.';
exit();}
$sql_alias = "SELECT user_alias FROM phoenix_users WHERE user_id = '".$user_id."'";
$res_alias = $mysqli -> query($sql_alias);
$data_alias = mysqli_fetch_assoc($res_alias);
if($data_alias['user_alias'] == ''){
header("Location: ../alias_change.php?game=$assign_id");
exit();}
$sql_check = "SELECT assign_student_team FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND student_id = '".$user_id."'";
$res_check = $mysqli -> query($sql_check);
$nr_check = mysqli_num_rows($res_check);
if($nr_check == 0){
echo 'You are not authorized to complete this assignment.';
exit();}
$data_check = mysqli_fetch_assoc($res_check);
$assign_student_team = $data_check['assign_student_team'];
$sql_assign_header = "SELECT assign_name, assign_game_pause, assign_teams, assign_nq, assign_syllabus, assign_game_status, assign_time_limit FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign_header = $mysqli -> query($sql_assign_header);
$data_assign_header = mysqli_fetch_assoc($res_assign_header);
$assign_name = $data_assign_header['assign_name'];
$assign_game_status = $data_assign_header['assign_game_status'];
$assign_game_pause = $data_assign_header['assign_game_pause'];
if($assign_game_status == 0){
header("Location: complete_game_start.php?assign_id=$assign_id");
exit();}
if($assign_game_status == 2){
header("Location: complete_game_answer.php?assign_id=$assign_id");
exit();}
if($assign_game_status == 3){
header("Location: complete_game_ranking.php?assign_id=$assign_id");
exit();}
if($assign_game_status == 4){
header("Location: complete_game_final.php?assign_id=$assign_id");
exit();}
$assign_syllabus = $data_assign_header['assign_syllabus'];
$assign_time_limit = $data_assign_header['assign_time_limit'];
$assign_nq = $data_assign_header['assign_nq'];
$assign_teams = $data_assign_header['assign_teams'];
$sql_get = "SELECT phoenix_assign_questions.question_id, assign_question_deadline, assign_question_number, question_number, question_paper_id, question_syllabus FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE assign_id = '".$assign_id."' AND assign_question_status = '1'";
$res_get = $mysqli -> query($sql_get);
$nr_get = mysqli_num_rows($res_get);
if($nr_get == 0){
sleep(1);
header("Location: complete_game_question.php?assign_id=$assign_id");
exit();}
$data_get = mysqli_fetch_assoc($res_get);
$assign_question_deadline = $data_get['assign_question_deadline'];
$countdown = date("M d, Y H:i:s", strtotime($assign_question_deadline));
$now = date("Y-m-d H:i:s");
if($assign_question_deadline <= $now){
header("Location: complete_game_answer.php?assign_id=$assign_id");
exit();}
$question_id = $data_get['question_id'];
$question_paper_id = $data_get['question_paper_id'];
$question_number = $data_get['question_number'];
$assign_question_number = $data_get['assign_question_number'];
$sql_a = "SELECT answer FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND user_id = '".$user_id."'";
$res_a = $mysqli -> query($sql_a);
$nr_a = mysqli_num_rows($res_a);
if($nr_a > 0){
$data_a = mysqli_fetch_assoc($res_a);
$answer = $data_a['answer'];}
$tl = $_GET['tl'];
$mysqli -> close();
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
.custom-clickable-row {
cursor: pointer;}
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
.brsmall {
display: block;
margin-bottom: 1em;
}
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script>
<?php
if($assign_game_pause == 0){
?>
var assign_game_pause = 'ongoing';
<?php
}
if($assign_game_pause == 1){
?>
var assign_game_pause = 'pause';
<?php
}
?>
setInterval(function refresh(){
$.ajax({
url: "ajax_question.php?assign_id=<?php echo $assign_id; ?>&assign_game_pause="+assign_game_pause,
success: function(data){
if(data == 'die'){
window.location.href = "../login.php";}
if(data.substring(0,1) == 'R'){
window.location.reload();}
if(data.substring(0,1) == 'P'){
var tl = data.substring(2);
clearInterval(x);
document.getElementById("question").style.display = 'none';
document.getElementById("pause").style.display = 'block';
document.getElementById("answers").style.display = 'none';
document.getElementById("ready").style.display = 'none';
document.getElementById("countdown_end").innerHTML = tl;
assign_game_pause = 'pause';}
if(data.substring(0,1) == 'G'){
var tl = data.substring(2);
document.location.href = "complete_game_question.php?assign_id=<?php echo $assign_id; ?>&tl="+tl;}}});}, 2500);
</script>
<?php
if($assign_game_pause == 0){
?>
<script>
function answerDisplay(ans){
document.getElementById("ans1").removeAttribute("class");
document.getElementById("ans1").removeAttribute("onclick");
document.getElementById("ans2").removeAttribute("class");
document.getElementById("ans2").removeAttribute("onclick");
document.getElementById("ans3").removeAttribute("class");
document.getElementById("ans3").removeAttribute("onclick");
document.getElementById("ans4").removeAttribute("class");
document.getElementById("ans4").removeAttribute("onclick");
document.getElementById("ans0").removeAttribute("class");
document.getElementById("ans0").removeAttribute("onclick");
document.getElementById("ans"+ans).style.backgroundColor = "#5C7090";
document.getElementById("ans"+ans).innerHTML = '<img src="hourglass.png" width="30" style="vertical-align: bottom;">';}
</script>
<script>
function answerDB(ans){
var d = new Date();
var n = d.toUTCString();
$.ajax({
url: "answer_game.php?question_id=<?php echo $question_id; ?>&assign_id=<?php echo $assign_id; ?>&answer="+ans+"&answer_date="+n,
success: function(data){
if(ans == 1){
ans_display = 'A';}
if(ans == 2){
ans_display = 'B';}
if(ans == 3){
ans_display = 'C';}
if(ans == 4){
ans_display = 'D';}
if(ans == 0){
ans_display = 'IDK';}
$( "#ans" + ans ).html('<b style="font-size: x-large;">'+ans_display+'</b>');
if(data != 'die'){
if(data.substring(0,1) == 0){
$( "#ans1" ).addClass("custom-clickable-row");
$( "#ans1" ).attr("onclick", "answerDisplay('1');answerDB('1');");
$( "#ans2" ).addClass("custom-clickable-row");
$( "#ans2" ).attr("onclick", "answerDisplay('2);answerDB('2');");
$( "#ans3" ).addClass("custom-clickable-row");
$( "#ans3" ).attr("onclick", "answerDisplay('3');answerDB('3');");
$( "#ans4" ).addClass("custom-clickable-row");
$( "#ans4" ).attr("onclick", "answerDisplay('4');answerDB('4');");
$( "#ans0" ).addClass("custom-clickable-row");
$( "#ans0" ).attr("onclick", "answerDisplay('0');answerDB('0');");
$( "#ans" + ans ).css("background-color", "#647d57");}
if(data.substring(0,1) == 1){
$( "#ans" + data.substring(1,2) ).css("background-color", "#ffc000");}}}});}
</script>
<script>
<?php
if(empty($tl)){
?>
var time_limit = <?php echo $assign_time_limit; ?>;
<?php
}
if(!empty($tl) && $tl > 0){
?>
var time_limit = Math.min(<?php echo $tl; ?>, <?php echo $assign_time_limit; ?>);
<?php
}
?>
var countDownDate = new Date("<?php echo $countdown; ?>").getTime();
var x = setInterval(function(){
var now0 = new Date();
var now = new Date(now0.getTime() + now0.getTimezoneOffset() * 60000);
var distance = countDownDate - now;
var distance_display = Math.floor(distance / 1000);
if(distance_display > time_limit){
document.getElementById("countdown_start").innerHTML = distance_display - time_limit;}
if(distance_display > 0 && distance_display <= time_limit){
document.getElementById("ready").style.display = "none";
document.getElementById("cd_start").style.display = "none";
document.getElementById("cd_end").style.display = "block";
document.getElementById("question").style.display = "block";
document.getElementById("answers").style.display = "block";
document.getElementById("countdown_end").innerHTML = distance_display;}
if(distance_display <= 0){
clearInterval(x);
document.getElementById("countdown_end").innerHTML = '0';
document.getElementById("ready").style.display = "none";
document.getElementById("cd_start").style.display = "none";
document.getElementById("cd_end").style.display = "block";
document.getElementById("question").style.display = "none";
document.getElementById("end").style.display = "block";
document.getElementById("answers").style.display = "none";}}, 1000);
</script>
<?php
}
?>
</head><body>
<table width="100%" height="100%"><tbody>
<td width="25%">
<p><a href="main.php"><img src="home_phoenix.png" width="100"></a></p>
<p style="font-size: x-large;"><b><?php echo strtoupper($assign_name); ?></b></p>
<p style="font-size: x-large;"><b>QUESTION <?php echo $data_get['assign_question_number']; ?> / <?php echo $assign_nq; ?></b></p>
<div id="cd_start" <?php if($assign_game_pause == 1) { echo 'style="display: none;"'; } ?>><b><span id="countdown_start" style="font-size: xx-large; color: #033909;">?</span></b></div>
<div id="cd_end" style="display: none;"><b><span id="countdown_end" style="font-size: xx-large; color: #820000;"></span></b></div>
<div id="answers" style="display: none;">
<p><table width="90%" align="center"><tbody>
<?php
if($nr_a > 0){
?>
<tr>
<td height="100" width="50%"><table width="90%" height="90%" align="center" bgcolor="#000000"><tr><td <?php if($answer == '1') { echo 'bgcolor="FFCC00"'; } else { echo 'bgcolor="769467"'; } ?>><b style="font-size: x-large;">A</b></td></tr></table></td>
<td height="100" width="50%"><table width="90%" height="90%" align="center" bgcolor="#000000"><tr><td <?php if($answer == '2') { echo 'bgcolor="FFCC00"'; } else { echo 'bgcolor="769467"'; } ?>><b style="font-size: x-large;">B</b></td></tr></table></td>
</tr>
<tr>
<td height="100" width="50%"><table width="90%" height="90%" align="center" bgcolor="#000000"><tr><td <?php if($answer == '3') { echo 'bgcolor="FFCC00"'; } else { echo 'bgcolor="769467"'; } ?>><b style="font-size: x-large;">C</b></td></tr></table></td>
<td height="100" width="50%"><table width="90%" height="90%" align="center" bgcolor="#000000"><tr><td <?php if($answer == '4') { echo 'bgcolor="FFCC00"'; } else { echo 'bgcolor="769467"'; } ?>><b style="font-size: x-large;">D</b></td></tr></table></td>
</tr>
<tr>
<td height="100" colspan="2">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr><td <?php if($answer == '0') { echo 'bgcolor="FFCC00"'; } else { echo 'bgcolor="769467"'; } ?>><b style="font-size: x-large;">IDK</b></td></tr></table>
</td>
</tr>
<?php
}
if($nr_a == 0){
?>
<tr>
<td width="50%" height="100"><table width="90%" height="90%" bgcolor="#000000"><tr><td id="ans1" bgcolor="647d57" class="custom-clickable-row" onclick="answerDisplay('1');answerDB('1');"><b style="font-size: x-large;">A</b></td></tr></table></td>
<td width="50%" height="100"><table width="90%" height="90%" bgcolor="#000000"><tr><td id="ans2" bgcolor="647d57" class="custom-clickable-row" onclick="answerDisplay('2');answerDB('2');"><b style="font-size: x-large;">B</b></td></tr></table></td>
</tr>
<tr>
<td width="50%" height="100"><table width="90%" height="90%" bgcolor="#000000"><tr><td id="ans3" bgcolor="647d57" class="custom-clickable-row" onclick="answerDisplay('3');answerDB('3');"><b style="font-size: x-large;">C</b></td></tr></table></td>
<td width="50%" height="100"><table width="90%" height="90%" bgcolor="#000000"><tr><td id="ans4" bgcolor="647d57" class="custom-clickable-row" onclick="answerDisplay('4');answerDB('4');"><b style="font-size: x-large;">D</b></td></tr></table></td>
</tr>
<tr>
<td width="100%" height="100" colspan="2"><table width="95%" height="90%" bgcolor="#000000"><tr><td id="ans0" bgcolor="647d57" class="custom-clickable-row" onclick="answerDisplay('0');answerDB('0');"><b style="font-size: x-large;">IDK</b></td></tr></table>
</td>
</tr>
<?php
}
?>
</tbody></table></p></div></td>
<td width="75%">
<p id="ready" <?php if($assign_game_pause == 1) { echo 'style="display: none;"'; } ?>>
<img src="shaun_ready.gif" style="border: black 4px solid">
<span class="brsmall"></span>
<b style="font-size: xx-large; color: #033909;">GET READY...</b></p>
<div id="question" style="display: none;">
<img src="./q/<?php echo $data_get['question_syllabus']; ?>/<?php echo $data_get['question_paper_id']; ?>/<?php echo $data_get['question_number']; ?>.png" style="border: solid black 2px; max-width:95%; max-height:95%; height: auto; width:auto;">
</div>
<p id="end" style="display: none;">
<img src="shaun_end.gif" style="border: black 4px solid">
<span class="brsmall"></span>
<b style="font-size: xx-large; color: #820000;">TIME'S UP!</b></p>
<p id="pause" <?php if($assign_game_pause == 0) { echo 'style="display: none;"'; } ?>>
<img src="shaun_paused.gif" style="border: black 4px solid">
<span class="brsmall"></span>
<b style="font-size: xx-large;">GAME PAUSED</b></p>
</td>
</tr></tbody></table>
</body></html>