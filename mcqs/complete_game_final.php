<?php
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
$sql_assign = "SELECT assign_type, assign_name, assign_nq, assign_teams, assign_game_status FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
$assign_name = $data_assign['assign_name'];
$assign_teams = $data_assign['assign_teams'];
$assign_nq = $data_assign['assign_nq'];
$assign_type = $data_assign['assign_type'];
$assign_game_status = $data_assign['assign_game_status'];
if($assign_type != '5'){
echo 'Not a game';
exit();}
if($assign_game_status == 0){
header("Location: complete_game_start.php?assign_id=$assign_id");
exit();}
if($assign_game_status == 1){
header("Location: complete_game_question.php?assign_id=$assign_id");
exit();}
if($assign_game_status == 2){
header("Location: complete_game_answer.php?assign_id=$assign_id");
exit();}
if($assign_game_status == 3){
header("Location: complete_game_ranking.php?assign_id=$assign_id");
exit();}
$sql_check = "SELECT assign_student_team FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND student_id = '".$user_id."'";
$res_check = $mysqli -> query($sql_check);
$nr_check = mysqli_num_rows($res_check);
if($nr_check == 0){
echo 'You are not authorized to complete this assignment.';
exit();}
$data_check = mysqli_fetch_assoc($res_check);
$assign_student_team = $data_check['assign_student_team'];
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
margin-bottom: 1em;}
.brmedium {
display: block;
margin-bottom: 2em;}
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
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script>
setInterval(function refresh(){
$.ajax({
url: "ajax_final.php?assign_id=<?php echo $assign_id; ?>",
success: function(data){
if(data == 'die'){
window.location.href = "../login.php";}
if(data == 'R'){
window.location.reload();}}});}, 2500);
</script>
<?php
if($assign_teams == 1){
?>
<script>
function full_ranking() {
document.getElementById("own_ranking").style.display = 'none';
document.getElementById("ind_ranking").style.display = 'block';
document.getElementById("team_ranking").style.display = 'block';
document.getElementById("btn_full_ranking").style.display = 'none';}
</script>
<?php
}
if($assign_teams == 0){
?>
<script>
function full_ranking() {
document.getElementById("own_ranking").style.display = 'none';
document.getElementById("ind_ranking").style.display = 'block';
document.getElementById("btn_full_ranking").style.display = 'none';}
</script>
<?php
}
?>
</head>
<body style="vertical-align: top;">
<table width="100%" height="100%">
<tr><td>
<p><a href="main.php"><img src="home_phoenix.png" width="100"></a></p>
<p style="font-size: x-large;"><b><?php echo strtoupper($assign_name); ?></b></p>
<p><table width="30%" align="center" bgcolor="#000000"><tbody><tr>
<td height="30" bgcolor="#033909"><b style="color: #FFFFFF;">100%</b></td>
</tr></tbody></table></p>
<p>
<input type="button" id="exit" value="EXIT GAME" onclick="document.location.href='complete.php';">
</p>
<span class="brmedium"></span>
<div id="own_ranking">
<span class="brmedium"></span>
<?php
$sql_own = "SELECT user_alias, user_avatar, assign_student_points FROM phoenix_assign_users INNER JOIN phoenix_users ON phoenix_assign_users.student_id = phoenix_users.user_id WHERE assign_id = '".$assign_id."' AND student_id = '".$user_id."'";
$res_own = $mysqli -> query($sql_own);
$data_own = mysqli_fetch_assoc($res_own);
$own_alias = $data_own['user_alias'];
$own_avatar = $data_own['user_avatar'];
$own_score = $data_own['assign_student_points'];
$own_rank = 1;
$previous = '';
$sql_rank = "SELECT student_id, assign_student_points FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' ORDER BY assign_student_points DESC";
$res_rank = $mysqli -> query($sql_rank);
while($data_rank = mysqli_fetch_assoc($res_rank)){
if($data_rank['student_id'] != $user_id){
if($data_rank['assign_student_points'] != $previous){
$own_rank++;
$previous = $data_rank['assign_student_points'];}}
if($data_rank['student_id'] == $user_id){
break;}}
$ending = 'th';
if(substr($own_rank, -1) == 1 && $own_rank != 11){
$ending = 'st';}
if(substr($own_rank, -1) == 2 && $own_rank != 12){
$ending = 'nd';}
if(substr($own_rank, -1) == 3 && $own_rank != 13){
$ending = 'rd';}
if($assign_teams == 0){
?>
<table width="25%" align="center">
<tr>
<td>
<img src="./avatars/Character%20<?php echo $own_avatar?>.png" width="200" style="-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);">
<br><b style="font-size: larger;"><?php echo $own_alias; ?></b>
<br><b style="font-size: larger; color: #033909;"><?php echo $own_rank; ?><sup><?php echo $ending; ?></sup> | <?php echo $own_score; ?> pts</b>
</td>
</tr>
</table>
<?php
}
if($assign_teams == 1){
$sql_own_team = "SELECT team_name, team_shield, score FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."' AND team = '".$assign_student_team."'";
$res_own_team = $mysqli -> query($sql_own_team);
$data_own_team = mysqli_fetch_assoc($res_own_team);
$own_team_name = $data_own_team['team_name'];
$own_team_shield = $data_own_team['team_shield'];
$own_team_score = $data_own_team['score'];
$own_team_rank = 1;
$previous = '';
$sql_team_rank = "SELECT team, score FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' ORDER BY assign_student_points DESC";
$res_team_rank = $mysqli -> query($sql_team_rank);
while($data_team_rank = mysqli_fetch_assoc($res_team_rank)){
if($data_team_rank['team'] != $assign_student_team){
if($data_team_rank['score'] != $previous){
$own_team_rank++;
$previous = $data_team_rank['score'];}}
if($data_team_rank['team'] == $assign_student_team){
break;}}
$ending = 'th';
if($own_team_rank == 1){
$ending = 'st';}
if($own_team_rank == 2){
$ending = 'nd';}
if($own_team_rank == 3){
$ending = 'rd';}
?>
<table width="50%" align="center">
<tr>
<td width="50%">
<img src="./avatars/Character%20<?php echo $own_avatar?>.png" width="200" style="-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);">
<br><b style="font-size: larger;"><?php echo $own_alias; ?></b>
<br><b style="font-size: larger; color: #033909;"><?php echo $own_rank; ?><sup><?php echo $ending; ?></sup> | <?php echo $own_score; ?> pts</b>
</td>
<td width="50%">
<img src="./shields/shield_<?php echo $own_team_shield?>.png" width="150" style="-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);">
<br><b style="font-size: larger;"><?php echo strtoupper($own_team_name); ?></b>
<br><b style="font-size: larger; color: #033909;"><?php echo $own_team_rank; ?><sup><?php echo $ending; ?></sup> | <?php echo $own_team_score; ?> pts</b>
</td>
</tr>
</table>
<?php
}
?>
<span class="brmedium"></span>
</div>
<p>
<input type="button" id="btn_full_ranking" value="FULL RANKING" onclick="full_ranking();">
</p>
<?php
if($assign_teams == 1){
?>
<div id="team_ranking" style="display: none;">
<p><b style="font-size: larger;">TEAMS</b></p>
<?php
$sql_teams = "SELECT team, score, team_name, team_shield FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."' ORDER BY score DESC";
$res_teams = $mysqli -> query($sql_teams);
$nr_teams = mysqli_num_rows($res_teams);
if($nr_teams == 2){
$width = 36;}
if($nr_teams == 3){
$width = 54;}
if($nr_teams == 4){
$width = 72;}
if($nr_teams > 4){
$width = 90;}
echo '<table width="'.$width.'%" align="center">';
$i = 0;
$rank = 0;
while($data_teams = mysqli_fetch_assoc($res_teams)){
$team = $data_teams['team'];
$team_name = $data_teams['team_name'];
$team_shield = $data_teams['team_shield'];
$score = $data_teams['score'];
if($score != $previous){
$rank++;}
$previous = $score;
$ending = 'th';
if(substr($rank, -1) == 1 && $rank != 11){
$ending = 'st';}
if(substr($rank, -1) == 2 && $rank != 12){
$ending = 'nd';}
if(substr($rank, -1) == 3 && $rank != 13){
$ending = 'rd';}
if($i % 5 == 0){
echo '<tr>';}
$i++;
?>
<td>
<img src="./shields/shield_<?php echo $team_shield; ?>.png" width="120" <?php if($team == $assign_student_team) { echo 'style="-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);"'; } else { echo 'style="-webkit-filter: drop-shadow(0px 0px 20px black); filter: drop-shadow(0px 0px 20px black);"'; } ?>>
<br><b style="font-size: larger;"><?php echo strtoupper($team_name); ?></b>
<br><b style="font-size: larger; color: #033909"><?php echo $rank; ?><sup><?php echo $ending; ?></sup> | <?php echo $score; ?> pts</b>
</td>
<?php
if($i % 5 == 0){
echo '</tr><tr height="40"></tr>';}
?>
<?php
}
?>
</table>
</div>
<p>&nbsp;</p>
<?php
}
?>
<div id="ind_ranking" style="display: none;">
<p><b style="font-size: larger;">PLAYERS</b></p>
<?php
$sql_stu = "SELECT student_id, user_alias, user_avatar, assign_student_points FROM phoenix_assign_users INNER JOIN phoenix_users ON phoenix_assign_users.student_id = phoenix_users.user_id WHERE assign_id = '".$assign_id."' ORDER BY assign_student_points DESC";
$res_stu = $mysqli -> query($sql_stu);
$nr_stu = mysqli_num_rows($res_stu);
if($nr_stu == 1){
$width = 15;}
if($nr_stu == 2){
$width = 30;}
if($nr_stu == 3){
$width = 45;}
if($nr_stu == 4){
$width = 60;}
if($nr_stu == 5){
$width = 75;}
if($nr_stu > 5){
$width = 90;}
echo '<table width="'.$width.'%" align="center">';
$i = 0;
$rank = 0;
$previous_score = '';
while($data_stu = mysqli_fetch_assoc($res_stu)){
$student_id = $data_stu['student_id'];
$student_alias = $data_stu['user_alias'];
$student_avatar = $data_stu['user_avatar'];
$student_score = $data_stu['assign_student_points'];
if($student_score != $previous_score){
$rank++;}
$previous_score = $student_score;
$ending = 'th';
if(substr($rank, -1) == 1 && $rank != 11){
$ending = 'st';}
if(substr($rank, -1) == 2 && $rank != 12){
$ending = 'nd';}
if(substr($rank, -1) == 3 && $rank != 13){
$ending = 'rd';}
if($i % 6 == 0){
echo '<tr>';}
$i++;
?>
<td>
<img src="./avatars/Character%20<?php echo $student_avatar; ?>.png" width="150" <?php if($student_id == $user_id) { echo 'style="-webkit-filter: drop-shadow(0px 0px 20px white); filter: drop-shadow(0px 0px 20px white);"'; } ?>>
<br><b style="font-size: larger;"><?php echo $student_alias; ?></b>
<br><b style="font-size: larger; color: #033909"><?php echo $rank; ?><sup><?php echo $ending; ?></sup> | <?php echo $student_score; ?> pts</b>
</td>
<?php
if($i % 6 == 0){
echo '</tr><tr height="40"></tr>';}
?>
<?php
}
?>
</table>
</div>
</td></tr></table>
<p>&nbsp;</p>
<?php
$mysqli -> close();
?>
</body></html>