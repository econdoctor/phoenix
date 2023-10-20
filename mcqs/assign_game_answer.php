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
if($mysqli -> connect_errno) {
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$assign_id = $_GET['assign_id'];
if(empty($assign_id)){
echo 'Missing info';
$mysqli -> close();
exit();}
$sql_info = "SELECT assign_nq, assign_name, assign_type, assign_syllabus, assign_game_status, assign_teacher, assign_teams FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
$assign_teams = $data_info['assign_teams'];
$assign_name = $data_info['assign_name'];
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
if($data_info['assign_game_status'] == 1){
header("Location: assign_game_question.php?assign_id=$assign_id");
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
$sql_get = "SELECT question_answer, assign_question_rate_a, assign_question_rate_b, assign_question_rate_c, assign_question_rate_d, phoenix_assign_questions.question_id, question_paper_id, question_number, question_syllabus, assign_question_number FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE assign_id = '".$assign_id."' AND assign_question_status = '2'";
$res_get = $mysqli -> query($sql_get);
$nr_get = mysqli_num_rows($res_get);
if($nr_get == 0){
echo 'No question found';
$mysqli -> close();
exit();}
$data_get = mysqli_fetch_assoc($res_get);
$question_id = $data_get['question_id'];
$video = 0;
$filename = './v/'.$data_get['question_paper_id'].$data_get['question_number'].'.mp4';
if(file_exists($filename)){
$video = 1;}
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
html {
height:100%;}
body {
-ms-overflow-style: none;
scrollbar-width: none;
overflow-y: scroll;}
body::-webkit-scrollbar {
display: none;}
.brsmall {
display: block;
margin-bottom: 2em;}
video {
margin-bottom: -1px;
}
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script>
function next(){
clearInterval(counter);
document.getElementById("next").removeAttribute("onclick");
document.getElementById("next").value = 'NEXT';
document.getElementById("next").setAttribute("style", "cursor: default; background-color: black;");
document.getElementById("btn_question").removeAttribute("style");
document.getElementById("btn_question").removeAttribute("onclick");
document.getElementById("btn_answers").removeAttribute("style");
document.getElementById("btn_answers").removeAttribute("onclick");
document.getElementById("btn_players").removeAttribute("style");
document.getElementById("btn_players").removeAttribute("onclick");
<?php
if($assign_teams == 1){
?>
document.getElementById("btn_teams").removeAttribute("style");
document.getElementById("btn_teams").removeAttribute("onclick");
<?php
}
if($video == 1){
?>
document.getElementById("player_video").pause();
<?php
}
?>
document.location.href='assign_game_answer_db.php?assign_id=<?php echo $assign_id; ?>&question_id=<?php echo $question_id; ?>';}
</script>
<script>
function showAnswers(){
clearInterval(counter);
document.getElementById("next").value = "NEXT";
document.getElementById("answers").style.display = 'block';
document.getElementById("btn_answers").setAttribute('style', 'width: 15%; cursor: default; background-color: black;"');
document.getElementById("btn_answers").removeAttribute("onclick");
document.getElementById("question").style.display = 'none';
document.getElementById("btn_question").removeAttribute("style");
document.getElementById("btn_question").setAttribute("onclick", "showQuestion();");
document.getElementById("players").style.display = 'none';
document.getElementById("btn_players").removeAttribute("style");
document.getElementById("btn_players").setAttribute("onclick", "showPlayers();");
<?php
if($assign_teams == 1){
?>
document.getElementById("btn_teams").removeAttribute("style");
document.getElementById("btn_teams").setAttribute("onclick", "showTeams();");
<?php
}
if($video == 1){
?>
document.getElementById("player_video").pause();
<?php
}
?>
}
</script>
<script>
function showQuestion(){
clearInterval(counter);
document.getElementById("answer").pause();
document.getElementById("next").value = "NEXT";
document.getElementById("img_question").style.display = 'block';
<?php
if($video == 1){
?>
document.getElementById("img_question").style.display = 'block';
document.getElementById("btn_video").style.display = 'block';
document.getElementById("div_video").style.display = 'none';
<?php
}
?>
document.getElementById("question").style.display = 'block';
document.getElementById("btn_question").setAttribute('style', 'width: 15%; cursor: default; background-color: black;"');
document.getElementById("btn_question").removeAttribute("onclick");
document.getElementById("answers").style.display = 'none';
document.getElementById("btn_answers").removeAttribute("style");
document.getElementById("btn_answers").setAttribute("onclick", "showAnswers();");
document.getElementById("players").style.display = 'none';
document.getElementById("btn_players").removeAttribute("style");
document.getElementById("btn_players").setAttribute("onclick", "showPlayers();");
<?php
if($assign_teams == 1){
?>
document.getElementById("btn_teams").removeAttribute("style");
document.getElementById("btn_teams").setAttribute("onclick", "showTeams();");
<?php
}
?>
}
</script>
<script>
function showPlayers(){
clearInterval(counter);
document.getElementById("next").value = "NEXT";
document.getElementById("players").style.display = 'block';
document.getElementById("btn_players").setAttribute('style', 'width: 15%; cursor: default; background-color: black;"');
document.getElementById("btn_players").removeAttribute("onclick");
document.getElementById("answers").style.display = 'none';
document.getElementById("btn_answers").removeAttribute("style");
document.getElementById("btn_answers").setAttribute("onclick", "showAnswers();");
document.getElementById("question").style.display = 'none';
document.getElementById("btn_question").removeAttribute("style");
document.getElementById("btn_question").setAttribute("onclick", "showQuestion();");
<?php
if($assign_teams == 1){
?>
document.getElementById("btn_teams").removeAttribute("style");
document.getElementById("btn_teams").setAttribute("onclick", "showTeams();");
<?php
}
if($video == 1){
?>
document.getElementById("player_video").pause();
<?php
}
?>
}
</script>
<?php
if($assign_teams == 1){
?>
<script>
function showTeams(){
clearInterval(counter);
document.getElementById("next").value = "NEXT";
document.getElementById("teams").style.display = 'block';
document.getElementById("btn_teams").setAttribute('style', 'width: 15%; cursor: default; background-color: black;"');
document.getElementById("btn_teams").removeAttribute("onclick");
document.getElementById("answers").style.display = 'none';
document.getElementById("btn_answers").removeAttribute("style");
document.getElementById("btn_answers").setAttribute("onclick", "showAnswers();");
document.getElementById("question").style.display = 'none';
document.getElementById("btn_question").removeAttribute("style");
document.getElementById("btn_question").setAttribute("onclick", "showQuestion();");
document.getElementById("players").style.display = 'none';
document.getElementById("btn_players").removeAttribute("style");
document.getElementById("btn_players").setAttribute("onclick", "showPlayers();");
}
<?php
if($video == 1){
?>
document.getElementById("player_video").pause();
<?php
}
?>
</script>
<?php
}
?>
<?php
if($video == 1){
?>
<script type="text/javascript">
function showVideo(){
var div_video = document.getElementById('div_video');
var btn_video = document.getElementById('btn_video');
var player_video = document.getElementById('player_video');
btn_video.style.display = 'none';
img_question.style.display = 'none';
div_video.style.display = 'block';
player_video.play();}
</script>
<?php
}
?>
<script>
setInterval(function refresh(){
$.ajax({
url: "leader_answer.php?assign_id=<?php echo $assign_id; ?>",
success: function(data){
if(data == 'die'){
window.location.href = "../login.php";}
if(data == 'R'){
window.location.reload();}}});}, 2500);
</script>

<script>
<?php
if($assign_teams == 0){
?>
max = 10;
count = 10;
<?php
}
if($assign_teams == 1){
?>
max = 15;
count = 15;
<?php
}
?>
var counter = setInterval(timer, 1000);
function timer(){
count = count - 1;
<?php
if($assign_teams == 0){
?>
if(count == 5){
document.getElementById("answers").style.display = "none";
document.getElementById("btn_answers").removeAttribute("style");
document.getElementById("btn_answers").setAttribute("onclick", "showAnswers();");
document.getElementById("players").style.display = "block";
document.getElementById("btn_players").removeAttribute("onclick");
document.getElementById("btn_players").setAttribute("style", "width: 15%; cursor: default; background-color: black;");}
<?php
}
if($assign_teams == 1){
?>
if(count == 10){
document.getElementById("answers").style.display = "none";
document.getElementById("btn_answers").removeAttribute("style");
document.getElementById("btn_answers").setAttribute("onclick", "showAnswers();");
document.getElementById("players").style.display = "block";
document.getElementById("btn_players").removeAttribute("onclick");
document.getElementById("btn_players").setAttribute("style", "width: 15%; cursor: default; background-color: black;");}
if(count == 5){
document.getElementById("players").style.display = "none";
document.getElementById("btn_players").removeAttribute("style");
document.getElementById("btn_players").setAttribute("onclick", "showPlayers();");
document.getElementById("teams").style.display = "block";
document.getElementById("btn_teams").removeAttribute("onclick");
document.getElementById("btn_teams").setAttribute("style", "width: 15%; cursor: default; background-color: black;");}
<?php
}
?>
if(count <= 0){
clearInterval(counter);
document.getElementById("next").style.display = "none";
window.location.href = 'assign_game_answer_db.php?assign_id=<?php echo $assign_id; ?>&question_id=<?php echo $question_id; ?>';
return;}
document.getElementById("next").value = "NEXT ("+count+")";}
</script>
</head>
<body onload="timer();document.getElementById('answer').play();" style="vertical-align: top;">
<table width="100%" height="100%"><tr><td>
<p><a href="main.php"><img src="home_phoenix.png" width="100"></a></p>
<p style="font-size: x-large;"><b><?php echo strtoupper($assign_name); ?></b></p>
<p style="font-size: x-large;"><b>QUESTION <?php echo $data_get['assign_question_number']; ?> / <?php echo $data_info['assign_nq']; ?></b></p>
<p>
<input type="button" id="btn_question" value="QUESTION" onclick="showQuestion();">&nbsp;&nbsp;
<input type="button" id="btn_answers" value="ANSWERS" style="width: 15%; cursor: default; background-color: black;">&nbsp;&nbsp;
<input type="button" id="btn_players" value="PLAYERS" onclick="showPlayers();">&nbsp;&nbsp;
<?php
if($assign_teams == 1){
?>
<input type="button" id="btn_teams" value="TEAMS" onclick="showTeams();">&nbsp;&nbsp;
<?php
}
?>
<input type="button" id="next" value="NEXT (<?php if($assign_teams == 0) { echo '16'; } else { echo '24'; } ?>)" onclick="next();">
</p>
<span class="brsmall"></span>
<div id="question" style="display: none;">
<p id="img_question"><img src="./q/<?php echo $data_get['question_syllabus']; ?>/<?php echo $data_get['question_paper_id']; ?>/<?php echo $data_get['question_number']; ?>.png" style="border: solid black 2px; max-width: 72%; width: auto; height: auto;"></p>
<?php
if($video == 1){
?>
<p align="center"><input id="btn_video" type="button" style="width: 20%;" value="VIDEO EXPLANATION" onclick="showVideo();"></p>
<div id="div_video" style="display:none;">
<p><video id="player_video" width="67%" controls preload="none" style="border: black solid 2px;">
<source src="./v/<?php echo $data_get['question_paper_id'].$data_get['question_number']; ?>.mp4" type="video/mp4">
Your browser does not support the video tag.
</video>
</div></p>
<?php
}
?>
</div>

<div id="answers">
<table width="50%" align="center"><tr>
<?php
if($data_get['assign_question_rate_a'] == NULL && $data_get['assign_question_rate_b'] == NULL && $data_get['assign_question_rate_c'] == NULL && $data_get['assign_question_rate_d'] == NULL){
?>
<td height="80" width="25%">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr>
<td <?php if($data_get['question_answer'] == '1') { echo 'bgcolor="#2e835c"'; } else { echo 'bgcolor="#647d57"'; } ?> ><b style="font-size: larger;">A</b></td>
</tr></table>
</td>
<td height="80" width="25%">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr>
<td <?php if($data_get['question_answer'] == '2') { echo 'bgcolor="#2e835c"'; } else { echo 'bgcolor="#647d57"'; } ?> ><b style="font-size: larger;">B</b></td>
</tr></table>
</td>
<td height="80" width="25%">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr>
<td <?php if($data_get['question_answer'] == '3') { echo 'bgcolor="#2e835c"'; } else { echo 'bgcolor="#647d57"'; } ?> ><b style="font-size: larger;">C</b></td>
</tr></table>
</td>
<td height="80" width="25%">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr>
<td <?php if($data_get['question_answer'] == '4') { echo 'bgcolor="#2e835c"'; } else { echo 'bgcolor="#647d57"'; } ?> ><b style="font-size: larger;">D</b></td>
</tr></table>
</td>
<?php
}
if($data_get['assign_question_rate_a'] != NULL && $data_get['assign_question_rate_b'] != NULL && $data_get['assign_question_rate_c'] != NULL && $data_get['assign_question_rate_d'] != NULL){
?>
<td height="80" width="25%">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr>
<td <?php if($data_get['question_answer'] == '1') { echo 'bgcolor="#2e835c"'; } else { echo 'bgcolor="#647d57"'; } ?> ><b style="font-size: larger;">A<br><?php echo round($data_get['assign_question_rate_a'],0); ?>%</b></td>
</tr></table>
</td>
<td height="80" width="25%">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr>
<td <?php if($data_get['question_answer'] == '2') { echo 'bgcolor="#2e835c"'; } else { echo 'bgcolor="#647d57"'; } ?> ><b style="font-size: larger;">B<br><?php echo round($data_get['assign_question_rate_b'],0); ?>%</b></td>
</tr></table>
</td>
<td height="80" width="25%">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr>
<td <?php if($data_get['question_answer'] == '3') { echo 'bgcolor="#2e835c"'; } else { echo 'bgcolor="#647d57"'; } ?> ><b style="font-size: larger;">C<br><?php echo round($data_get['assign_question_rate_c'],0); ?>%</b></td>
</tr></table>
</td>
<td height="80" width="25%">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr>
<td <?php if($data_get['question_answer'] == '4') { echo 'bgcolor="#2e835c"'; } else { echo 'bgcolor="#647d57"'; } ?> ><b style="font-size: larger;">D<br><?php echo round($data_get['assign_question_rate_d'],0); ?>%</b></td>
</tr></table>
</td>
<?php
}
?>
</td></tr></table>
</div>
<div id="players" style="display: none;">
<?php
$sql_gs = "SELECT student_id, user_first_name, user_avatar FROM phoenix_assign_users INNER JOIN phoenix_users ON phoenix_assign_users.student_id = phoenix_users.user_id WHERE assign_id = '".$assign_id."' ORDER BY assign_student_team";
$res_gs = $mysqli -> query($sql_gs);
$nr_stu = mysqli_num_rows($res_gs);
if($nr_stu > 5){
$width = 90;}
if($nr_stu == 5){
$width = 75;}
if($nr_stu == 4){
$width = 60;}
if($nr_stu == 3){
$width = 45;}
if($nr_stu == 2){
$width = 30;}
if($nr_stu == 1){
$width = 15;}
echo '<table width="'.$width.'%" align="center">';
$i = 0;
$array_student = array();
$max_student = 0;
while($data_gs = mysqli_fetch_assoc($res_gs)){
$user_first_name = $data_gs['user_first_name'];
$user_avatar = $data_gs['user_avatar'];
$student_id = $data_gs['student_id'];
$sql_ans = "SELECT answer, answer_valid, answer_points FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND user_id = '".$student_id."'";
$res_ans = $mysqli -> query($sql_ans);
$nr_ans = mysqli_num_rows($res_ans);
$data_ans = mysqli_fetch_assoc($res_ans);
$answer_valid = $data_ans['answer_valid'];
$answer = $data_ans['answer'];
$answer_points = $data_ans['answer_points'];
if($answer_points > 0 && $answer_points == $max_student){
$max_student = $answer_points;
array_push($array_student, $student_id);}
if($answer_points > $max_student){
$max_student = $answer_points;
$array_student = array();
array_push($array_student, $student_id);}
if($i % 6 == 0){
echo '<tr style="vertical-align: top;">';}
?>
<td>
<img id="student_<?php echo $student_id; ?>" src="./avatars/Character%20<?php echo $user_avatar; ?>.png" width="150" <?php if($nr_ans == 0 || ($nr_ans > 0 && $answer == '0' && $answer_valid == 0)) { echo 'style="-webkit-filter: grayscale(100%); filter: grayscale(100%);"'; } if($nr_ans > 0 && $answer != '0' && $answer_valid == 0) { echo 'style-"-webkit-filter: drop-shadow(0px 0px 20px #c33c47); filter: drop-shadow(0px 0px 20px #c33c47);"'; } if($nr_ans > 0 && $answer_valid == 1) { echo 'style="-webkit-filter: drop-shadow(0px 0px 20px #2e835c); filter: drop-shadow(0px 0px 20px #2e835c);"'; } ?>>
<br><b style="font-size: larger;"><?php echo $user_first_name; ?></b>
<?php
if($nr_ans > 0 && $answer != '0' && $answer_valid == 0){
if($answer == '1'){
$answer_display = 'A';}
if($answer == '2'){
$answer_display = 'B';}
if($answer == '3'){
$answer_display = 'C';}
if($answer == '4'){
$answer_display = 'D';}
?>
<br><b style="font-size: larger; color: #820000;"><?php echo $answer_display; ?> | - 50 pts</b>
<?php
}
if($nr_ans > 0 && $answer_valid == 1){
?>
<br><b style="font-size: larger; color: #033909;">+ <?php echo $answer_points; ?> pts</b>
<?php
}
?>
</td>
<?php
$i++;
if($i % 6 == 0){
echo '</tr><tr height="40"></tr>';}}
?>
<script type="text/javascript">
var array_student = <?php echo json_encode($array_student); ?>;
for(var i = 0; i < array_student.length; i++){
document.getElementById("student_"+array_student[i]).setAttribute("style", "-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);");}
</script>
</table></div>
<?php
if($assign_teams == 1){
?>
<div id="teams" style="display: none;">
<?php
$i = 1;
$array_team = array();
$max_team = 0;
$sql_gt = "SELECT team, team_name, team_shield FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."'";
$res_gt = $mysqli -> query($sql_gt);
$nr_teams = mysqli_num_rows($res_gt);
if($nr_teams > 4){
$width = 90;}
if($nr_teams == 4){
$width = 72;}
if($nr_teams == 3){
$width = 54;}
if($nr_teams == 2){
$width = 36;}
echo '<table width="'.$width.'%" align="center">';
while($data_gt = mysqli_fetch_assoc($res_gt)){
$team = $data_gt['team'];
$sql_coeff = "SELECT assign_student_coeff FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_team = '".$team."' LIMIT 1";
$res_coeff = $mysqli -> query($sql_coeff);
$data_coeff = mysqli_fetch_assoc($res_coeff);
$coeff = $data_coeff['assign_student_coeff'];
$team_name = $data_gt['team_name'];
$team_shield = $data_gt['team_shield'];
$sql_ans_t = "SELECT SUM(answer_points) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND user_id IN (SELECT student_id FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_team = '".$team."')";
$res_ans_t = $mysqli -> query($sql_ans_t);
$data_ans_t = mysqli_fetch_assoc($res_ans_t);
if($data_ans_t['SUM(answer_points)'] == NULL){
$team_points = 0;}
else {
$team_points = round($data_ans_t['SUM(answer_points)'] * $coeff /  100, 0);}
if($team_points > 0 && $team_points == $max_team){
$max_team = $team_points;
array_push($array_team, $team);}
if($team_points > $max_team){
$max_team = $team_points;
$array_team = array();
array_push($array_team, $team);}
if($i == 1 || $i == 6){
echo '<tr>';}
?>
<td>
<img id="team_<?php echo $team; ?>" src="./shields/shield_<?php echo $team_shield; ?>.png" width="120">
<br><b style="font-size: larger;"><?php echo strtoupper($team_name); ?></b>
<br>
<?php
if($team_points >= 0){
?>
<b style="font-size: larger; color: #033909;">+ <?php echo $team_points; ?> pts</b>
<?php
}
if($team_points < 0){
?>
<b style="font-size: larger; color: #820000;">- <?php echo abs($team_points); ?> pts</b>
<?php
}
?>
</td>
<?php
$i++;
if($i == 6){
echo '</tr><tr height="40"></tr>';}}
?>
<script type="text/javascript">
var array_team = <?php echo json_encode($array_team); ?>;
for(var i = 0; i < array_team.length; i++){
document.getElementById("team_"+array_team[i]).setAttribute("style", "-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);");}
</script>
</table>
</div>
<?php
}
?>
</td></tr></table>
<p>&nbsp;</p>
<audio id="answer" src="answer.mp3" style="display: none;" preload="auto">
<?php
$mysqli -> close();
?>
</body></html>