<?php
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
exit();}
$sql_info = "SELECT assign_type, assign_game_status, assign_name, assign_teams, assign_nq, assign_teacher FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
$assign_teams = $data_info['assign_teams'];
if($data_info['assign_teacher'] != $user_id){
echo 'Not your assignment';
exit();}
if($data_info['assign_type'] != '5'){
echo 'Not a game';
exit();}
if($data_info['assign_game_status'] == 0){
header("Location: assign_game_start.php?assign_id=$assign_id");
exit();}
if($data_info['assign_game_status'] == 1){
header("Location: assign_game_question.php?assign_id=$assign_id");
exit();}
if($data_info['assign_game_status'] == 2){
header("Location: assign_game_answer.php?assign_id=$assign_id");
exit();}
if($data_info['assign_game_status'] == 4){
header("Location: assign_game_final.php?assign_id=$assign_id");
exit();}
$sql_c = "SELECT COUNT(*) FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."' AND assign_question_status = '3'";
$res_c = $mysqli -> query($sql_c);
$data_c = mysqli_fetch_assoc($res_c);
$num_c = $data_c['COUNT(*)'];
$completed = round($num_c / $data_info['assign_nq'] * 100, 0);
if($assign_teams == 1){
$sql_team = "SELECT score, team_name, team_shield FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."' ORDER BY score DESC";
$res_team = $mysqli -> query($sql_team);
$nr_teams = mysqli_num_rows($res_team);}
$sql_ind = "SELECT assign_student_points, user_first_name, user_avatar FROM phoenix_assign_users INNER JOIN phoenix_users ON phoenix_assign_users.student_id = phoenix_users.user_id WHERE assign_id = '".$assign_id."' ORDER BY assign_student_points DESC";
$res_ind = $mysqli -> query($sql_ind);
$nr_ind = mysqli_num_rows($res_ind);
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
position:absolute; top:0; bottom:0; right:0; left:0;}
body {
-ms-overflow-style: none;
scrollbar-width: none;
overflow-y: scroll;}
body::-webkit-scrollbar {
display: none;}
.brxsmall {
display: block;
margin-bottom: 0.5em;}
.brsmall {
display: block;
margin-bottom: 1em;}
.brmedium {
display: block;
margin-bottom: 2em;}
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<?php
if(($assign_teams == 1 && $nr_teams <= 3) || ($assign_teams == 0 && $nr_ind <= 3)){
?>
<script>
var count = 7;
var counter = setInterval(timer, 1000);
function timer(){
count = count - 1;
if(count <= 0){
clearInterval(counter);
document.getElementById("next").style.display = 'none';
window.location.href = 'assign_game_ranking_db.php?assign_id=<?php echo $assign_id; ?>';
return;}
document.getElementById("next").value = "NEXT ("+count+")";}
</script>
<?php
}
if(($assign_teams == 1 && $nr_teams > 3) || ($assign_teams == 0 && $nr_ind > 3)){
?>
<script>
var count = 12;
var counter = setInterval(timer, 1000);
function timer(){
count = count - 1;
if(count == 6){
<?php
if($assign_teams == 1){
?>
document.getElementById("top_teams").style.display = 'none';
document.getElementById("bottom_teams").style.display = 'block';
document.getElementById("btn_top_teams").removeAttribute("style");
document.getElementById("btn_top_teams").setAttribute("onclick", "top_teams();");
document.getElementById("btn_bottom_teams").removeAttribute("onclick");
document.getElementById("btn_bottom_teams").setAttribute("style", "width: 15%; cursor: default; background-color: black;");
<?php
}
if($assign_teams == 0){
?>
document.getElementById("top_players").style.display = 'none';
document.getElementById("bottom_players").style.display = 'block';
document.getElementById("btn_top_players").removeAttribute("style");
document.getElementById("btn_top_players").setAttribute("onclick", "top_players();");
document.getElementById("btn_bottom_players").removeAttribute("onclick");
document.getElementById("btn_bottom_players").setAttribute("style", "width: 15%; cursor: default; background-color: black;");
<?php
}
?>
}
if(count <= 0){
clearInterval(counter);
document.getElementById("next").style.display = 'none';
window.location.href = 'assign_game_ranking_db.php?assign_id=<?php echo $assign_id; ?>';
return;}
document.getElementById("next").value = "NEXT ("+count+")";}
</script>
<?php
}
?>
<script>
setInterval(function refresh(){
$.ajax({
url: "leader_ranking.php?assign_id=<?php echo $assign_id; ?>",
success: function(data){
if(data == 'die'){
window.location.href = "../login.php";}
if(data == 'R'){
window.location.reload();}}});}, 2500);
</script>
<script>
function next(){
clearInterval(counter);
document.getElementById("next").style.display = "none";
<?php
if(($assign_teams == 1 && $nr_teams <= 3) || ($assign_teams == 0 && $nr_ind <= 3)){
?>
document.getElementById("btn_pause").style.display = "none";
<?php
}
if($assign_teams == 1 && $nr_teams > 3){
?>
document.getElementById("btn_top_teams").style.display = "none";
document.getElementById("btn_bottom_teams").style.display = "none";
<?php
}
if($assign_teams == 0 && $nr_ind > 3){
?>
document.getElementById("btn_top_players").style.display = "none";
document.getElementById("btn_bottom_players").style.display = "none";
<?php
}
?>
document.location.href='assign_game_ranking_db.php?assign_id=<?php echo $assign_id; ?>';}
</script>
<?php
if($assign_teams == 0 && $nr_ind > 3){
?>
<script>
function top_players(){
document.getElementById("bottom_players").style.display = 'none';
document.getElementById("btn_bottom_players").setAttribute("onclick", "bottom_players();");
document.getElementById("btn_bottom_players").removeAttribute("style");
document.getElementById("top_players").style.display = 'block';
document.getElementById("btn_top_players").removeAttribute("onclick");
document.getElementById("btn_top_players").setAttribute("style", "width: 15%; cursor: default; background-color: black;");
clearInterval(counter);
document.getElementById("next").value = "NEXT";}
</script>
<script>
function bottom_players() {
document.getElementById("top_players").style.display = 'none';
document.getElementById("btn_top_players").setAttribute("onclick", "top_players();");
document.getElementById("btn_top_players").removeAttribute("style");
document.getElementById("bottom_players").style.display = 'block';
document.getElementById("btn_bottom_players").removeAttribute("onclick");
document.getElementById("btn_bottom_players").setAttribute("style", "width: 15%; cursor: default; background-color: black;");
clearInterval(counter);
document.getElementById("next").value = "NEXT";}
</script>
<?php
}
if($assign_teams == 1 && $nr_teams > 3){
?>
<script>
function top_teams(){
document.getElementById("bottom_teams").style.display = 'none';
document.getElementById("btn_bottom_teams").setAttribute("onclick", "bottom_teams();");
document.getElementById("btn_bottom_teams").removeAttribute("style");
document.getElementById("top_teams").style.display = 'block';
document.getElementById("btn_top_teams").removeAttribute("onclick");
document.getElementById("btn_top_teams").setAttribute("style", "width: 15%; cursor: default; background-color: black;");
clearInterval(counter);
document.getElementById("next").value = "NEXT";}
</script>
<script>
function bottom_teams() {
document.getElementById("top_teams").style.display = 'none';
document.getElementById("btn_top_teams").setAttribute("onclick", "top_teams();");
document.getElementById("btn_top_teams").removeAttribute("style");
document.getElementById("bottom_teams").style.display = 'block';
document.getElementById("btn_bottom_teams").removeAttribute("onclick");
document.getElementById("btn_bottom_teams").setAttribute("style", "width: 15%; cursor: default; background-color: black;");
clearInterval(counter);
document.getElementById("next").value = "NEXT";}
</script>
<?php
}
if(($assign_teams == 1 && $nr_teams <= 3) || ($assign_teams == 0 && $nr_ind <= 3)){
?>
<script>
function pause() {
clearInterval(counter);
document.getElementById("next").value = 'NEXT';
document.getElementById("btn_pause").style.display = 'none';
document.getElementById("ranking").pause();}
</script>
<?php
}
?>
</head>
<body onload="timer();document.getElementById('ranking').play();">
<table width="100%" width="100%">
<tr><td>
<p><a href="main.php"><img src="home_phoenix.png" width="100"></a></p>
<p style="font-size: x-large;"><b><?php echo strtoupper($data_info['assign_name']); ?></b></p>
<p><table width="30%" align="center" bgcolor="#000000"><tr>
<td height="30" width="<?php echo $completed; ?>%" bgcolor="#033909"><b style="color: white;"><?php echo $completed; ?>%</b></td>
<td height="30" width="<?php echo 100 - $completed; ?>%" bgcolor="8BA57E"></td>
</tr></table></p>
<?php
if($assign_teams == 1){
while($data_team = mysqli_fetch_assoc($res_team)){
$team_shield[] = $data_team['team_shield'];
$team_score[] = $data_team['score'];
$team_name[] = $data_team['team_name'];}
?>
<p>
<?php
if($nr_teams <= 3){
?>
<input type="button" id="btn_pause" value="PAUSE" onclick="pause();">&nbsp;&nbsp;
<?php
}
if($nr_teams > 3){
?>
<input type="button" id="btn_top_teams" value="TOP 3 TEAMS" style="width: 15%; cursor: default; background-color: black;">&nbsp;&nbsp;
<input type="button" id="btn_bottom_teams" value="OTHER TEAMS" onclick="bottom_teams();">&nbsp;&nbsp;
<?php
}
?>
<input type="button" id="next" value="NEXT (10)" onclick="next();">
</p>
<div id="top_teams">
<span class="brmedium"></span>
<?php
if($nr_teams >= 3){
?>
<table width="50%" align="center">
<tr>
<td width="33%" style="vertical-align: bottom;">
<img src="./shields/shield_<?php echo $team_shield[1]; ?>.png" width="120" <?php if($team_score[0] == $team_score[1]) { echo 'style="-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);"'; } ?>>
<span class="brsmall"></span>
<table width="95%" bgcolor="647d57" <?php if($team_score[0] == $team_score[1]) { echo 'height="250"'; } else { echo 'height="200"'; } ?> align="center" style="border: solid black 4px">
<tr><td>
<img <?php if($team_score[0] == $team_score[1]) { echo 'src="gold.png"'; } else { echo 'src="silver.png"'; } ?> width="50"><br><b style="font-size: larger;"><?php echo strtoupper($team_name[1]); ?></b><br><b style="font-size: larger"><?php echo $team_score[1]; ?> pts</b>
</td></tr>
</table>
</td>
<td width="34%" style="vertical-align: bottom;">
<img src="./shields/shield_<?php echo $team_shield[0]; ?>.png" width="120" style="-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);">
<span class="brsmall"></span>
<table width="95%" bgcolor="647d57" height="250" align="center" style="border: solid black 4px">
<tr><td>
<img src="gold.png" width="50"><br><b style="font-size: larger;"><?php echo strtoupper($team_name[0]); ?></b><br><b style="font-size: larger"><?php echo $team_score[0]; ?> pts</b>
</td></tr>
</table>
</td>
<td width="33%" style="vertical-align: bottom;">
<img src="./shields/shield_<?php echo $team_shield[2]; ?>.png" width="120" <?php if($team_score[0] == $team_score[1] && $team_score[1] == $team_score[2]) { echo 'style="-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);"'; } ?>>
<span class="brsmall"></span>
<table width="95%" bgcolor="647d57" <?php if($team_score[0] == $team_score[1] && $team_score[1] == $team_score[2]) { echo 'height="250"'; } if(($team_score[0] == $team_score[1] && $team_score[1] > $team_score[2]) || ($team_score[0] > $team_score[1] && $team_score[1] == $team_score[2])) { echo 'height="200"'; } else { echo 'height="150"'; } ?> align="center" style="border: solid black 4px">
<tr><td>
<img <?php if($team_score[0] == $team_score[1] && $team_score[1] == $team_score[2]) { echo 'src="gold.png"'; } if(($team_score[0] == $team_score[1] && $team_score[1] > $team_score[2]) || ($team_score[0] > $team_score[1] && $team_score[1] == $team_score[2])) { echo 'src="silver.png"'; } else { echo 'src="bronze.png"'; } ?> width="50"><br><b style="font-size: larger;"><?php echo strtoupper($team_name[2]); ?></b><br><b style="font-size: larger"><?php echo $team_score[2]; ?> pts</b>
</td></tr>
</table>
</td>
</tr>
</table>
<?php
}
if($nr_teams < 3){
?>
<table width="50%" align="center">
<tr>
<td width="50%">
<img src="./shields/shield_<?php echo $team_shield[0]; ?>.png" width="120" style="-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);">
<span class="brsmall"></span>
<img src="gold.png" width="50">
<br><b style="font-size: larger;"><?php echo strtoupper($team_name[0]); ?></b>
<br><b style="font-size: larger; color: <?php if($team_score[0] >= 0) { echo '#033909;'; } else { echo '#820000;'; } ?>"><?php echo $team_score[0]; ?> pts</b>
</td>
<td width="50%">
<img src="./shields/shield_<?php echo $team_shield[1]; ?>.png" width="120" <?php if($team_score[0] == $team_score[1]) { echo 'style="-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);"'; } ?>>
<span class="brsmall"></span>
<img <?php if($team_score[0] == $team_score[1]) { echo 'src="gold.png"'; }  else { echo 'src="silver.png"'; } ?> width="50">
<br><b style="font-size: larger;"><?php echo strtoupper($team_name[1]); ?></b>
<br><b style="font-size: larger; <?php if($team_score[1] >= 0) { echo '#033909;'; } else { echo '#820000;'; } ?>"><?php echo $team_score[1]; ?> pts</b>
</td>
</tr>
</table>
<?php
}
?>
</div>
<?php
if($nr_teams > 3){
if($nr_teams == 4){
$width = 18;}
if($nr_teams == 5){
$width = 36;}
if($nr_teams == 6){
$width = 54;}
if($nr_teams > 6){
$width = 72;}
?>
<div id="bottom_teams" style="display: none;">
<span class="brsmall"></span>
<table width="90%" align="center">
<?php
for($i = 3; $i <= $nr_teams - 1; $i++){
if($i == 3 || $i == 7){
echo '<tr>';}
?>
<td><img src="./shields/shield_<?php echo $team_shield[$i]; ?>.png" width="120" style="-webkit-filter: drop-shadow(0px 0px 20px black); filter: drop-shadow(0px 0px 20px black);">
<br><span class="brxsmall"></span><b style="font-size: larger;"><?php echo strtoupper($team_name[$i]); ?></b>
<br><b style="font-size: larger; color: <?php if($team_score[$i] >= 0) { echo '#033909;'; } else { echo '#820000;'; } ?>"><?php echo $team_score[$i]; ?> pts</b></td>
<?php
if($i == 7){
echo '</tr><tr height="40"></tr>';}}
?>
</table>
</div>
<?php
}}

if($assign_teams == 0){
while($data_ind = mysqli_fetch_assoc($res_ind)){
$user_avatar[] = $data_ind['user_avatar'];
$user_score[] = $data_ind['assign_student_points'];
$user_first_name[] = $data_ind['user_first_name'];}
?>
<p>
<?php
if($nr_ind <= 3){
?>
<input type="button" id="btn_pause" value="PAUSE" onclick="pause();">&nbsp;&nbsp;
<?php
}
if($nr_ind > 3){
?>
<input type="button" id="btn_top_players" value="TOP 3 PLAYERS" style="width: 15%; cursor: default; background-color: black;">&nbsp;&nbsp;
<input type="button" id="btn_bottom_players" value="OTHER PLAYERS" onclick="bottom_players();">&nbsp;&nbsp;
<?php
}
?>
<input type="button" id="next" value="NEXT (10)" onclick="next();">
</p>
<div id="top_players">
<span class="brmedium"></span>
<?php
if($nr_ind > 2){
?>
<table width="50%" align="center">
<tr>
<td width="33%" style="vertical-align: bottom;">
<img src="./avatars/Character%20<?php echo $user_avatar[1]; ?>.png" width="200" <?php if($user_score[0] == $user_score[1]) { echo 'style="-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);"'; } ?>>
<table width="95%" bgcolor="647d57" <?php if($user_score[0] == $user_score[1]) { echo 'height="250"'; } else { echo 'height="200"'; } ?> align="center" style="border: solid black 4px">
<tr><td>
<img <?php if($user_score[0] == $user_score[1]) { echo 'src="gold.png"'; } else { echo 'src="silver.png"'; } ?> width="50">
<br><b style="font-size: larger;"><?php echo $user_first_name[1]; ?></b>
<br><b style="font-size: larger;"><?php echo $user_score[1]; ?> pts</b>
</td></tr>
</table>
</td>
<td width="34%" style="vertical-align: bottom;">
<img src="./avatars/Character%20<?php echo $user_avatar[0]; ?>.png" width="200" style="-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);">
<table width="95%" bgcolor="647d57" height="250" align="center" style="border: solid black 4px">
<tr><td>
<img src="gold.png" width="50">
<br><b style="font-size: larger;"><?php echo $user_first_name[0]; ?></b>
<br><b style="font-size: larger"><?php echo $user_score[0]; ?> pts</b>
</td></tr>
</table>
</td>
<td width="33%" style="vertical-align: bottom;">
<img src="./avatars/Character%20<?php echo $user_avatar[2]; ?>.png" width="200" <?php if($user_score[0] == $user_score[1] && $user_score[1] == $user_score[2]) { echo 'style="-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);"'; } ?>>
<table width="95%" bgcolor="647d57" <?php if($user_score[0] == $user_score[1] && $user_score[1] == $user_score[2]) { echo 'height="250"'; } if(($user_score[0] == $user_score[1] && $user_score[1] > $user_score[2]) || ($user_score[0] > $user_score[1] && $user_score[1] == $user_score[2])) { echo 'height="200"'; } else { echo 'height="150"'; } ?> align="center" style="border: solid black 4px">
<tr><td>
<img <?php if($user_score[0] == $user_score[1] && $user_score[1] == $user_score[2]) { echo 'src="gold.png"'; } if(($user_score[0] == $user_score[1] && $user_score[1] > $user_score[2]) || ($user_score[0] > $user_score[1] && $user_score[1] == $user_score[2])) { echo 'src="silver.png"'; } else { echo 'src="bronze.png"'; } ?> width="50">
<br><b style="font-size: larger;"><?php echo $user_first_name[2]; ?></b>
<br><b style="font-size: larger;"><?php echo $user_score[2]; ?> pts</b>
</td></tr>
</table>
</td>
</tr>
</table>
<?php
}
if($nr_ind == 2){
?>
<table width="50%" align="center">
<tr>
<td width="50%">
<img src="./avatars/Character%20<?php echo $user_avatar[0]; ?>.png" width="200" style="-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);">
<span class="brsmall"></span>
<img src="gold.png" width="50">
<br><b style="font-size: larger;"><?php echo $user_first_name[0]; ?></b>
<br><b style="font-size: larger; color: <?php if($user_score[0] >= 0) { echo '#033909;'; } else { echo '#820000;'; } ?>"><?php echo $user_score[0]; ?> pts</b>
</td>
<td width="50%">
<img src="./avatars/Character%20<?php echo $user_avatar[1]; ?>.png" width="200" <?php if($user_score[0] == $user_score[1]) { echo 'style="-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);"'; } ?>>
<span class="brsmall"></span>
<img <?php if($user_score[0] == $user_score[1]) { echo 'src="gold.png"'; }  else { echo 'src="silver.png"'; } ?> width="50">
<br><b style="font-size: larger;"><?php echo $user_first_name[1]; ?></b>
<br><b style="font-size: larger; color: <?php if($user_score[1] >= 0) { echo '#033909;'; } else { echo '#820000;'; } ?>"><?php echo $user_score[1]; ?> pts</b>
</td>
</tr>
</table>
<?php
}
if($nr_ind == 1){
?>
<table width="30%" align="center">
<tr><td>
<img src="./avatars/Character%20<?php echo $user_avatar[0]; ?>.png" width="200">
<br><b style="font-size: larger;"><?php echo $user_first_name[0]; ?></b>
<br><b style="font-size: larger; color: <?php if($user_score[0] >= 0) { echo '#033909;'; } else { echo '#820000;'; } ?>"><?php echo $user_score[0]; ?> pts</b>
</td></tr>
</table>
<?php
}
?>
</div>
<?php
if($nr_ind > 3){
if($nr_ind == 4){
$width = 15;}
if($nr_ind == 5){
$width = 30;}
if($nr_ind == 6){
$width = 45;}
if($nr_ind == 7){
$width = 60;}
if($nr_ind == 8){
$width = 75;}
if($nr_ind > 8){
$width = 90;}
?>
<div id="bottom_players" style="display: none;">
<span class="brmedium"></span>
<table width="<?php echo $width; ?>%" align="center">
<?php
$j = 0;
for($i = 3; $i <= $nr_ind - 1; $i++){
if($j % 6 == 0){
echo '<tr>';}
$j++;
?>
<td><img src="./avatars/Character%20<?php echo $user_avatar[$i]; ?>.png" width="150">
<br><b style="font-size: larger;"><?php echo $user_first_name[$i]; ?></b>
<br><b style="font-size: larger; color:  <?php if($user_score[$i] >= 0) { echo '#033909;'; } else { echo '#820000;'; } ?>"><?php echo $user_score[$i]; ?> pts</b></td>
<?php
if($j % 6 == 0){
echo '</tr><tr height="40"></tr>';}}
?>
</table>
</div>
<?php
}}
?>
</td></tr></table>
<p>&nbsp;</p>
<?php
$mysqli -> close();
?>
<audio id="ranking" src="ranking.mp3" style="display: none;" preload="auto" controls loop>
</body>
</html>