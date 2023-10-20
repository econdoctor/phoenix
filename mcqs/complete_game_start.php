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
$sql_alias = "SELECT user_alias, user_avatar FROM phoenix_users WHERE user_id = '".$user_id."'";
$res_alias = $mysqli -> query($sql_alias);
$data_alias = mysqli_fetch_assoc($res_alias);
$user_alias = $data_alias['user_alias'];
$user_avatar = $data_alias['user_avatar'];
if($user_alias == ''){
header("Location: ../alias_change.php?game=$assign_id");
exit();}
if($user_avatar == 0){
header("Location: ../avatar_change.php?game=$assign_id");
exit();}
$sql_assign = "SELECT assign_name, assign_teams, assign_game_status FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
$assign_name = $data_assign['assign_name'];
$assign_teams = $data_assign['assign_teams'];
$assign_game_status = $data_assign['assign_game_status'];
if($assign_game_status == 1){
header("Location: complete_game_question.php?assign_id=$assign_id");
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
$sql_check = "SELECT assign_student_team FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND student_id = '".$user_id."'";
$res_check = $mysqli -> query($sql_check);
$nr_check = mysqli_num_rows($res_check);
if($nr_check == 0){
echo 'You are not authorized to complete this assignment.';
exit();}
$data_check = mysqli_fetch_assoc($res_check);
$assign_student_team = $data_check['assign_student_team'];
$sql_tn = "SELECT team_name, team_shield FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."' AND team = '".$assign_student_team."'";
$res_tn = $mysqli -> query($sql_tn);
$data_tn = mysqli_fetch_assoc($res_tn);
$team_name = $data_tn['team_name'];
$team_shield = $data_tn['team_shield'];
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
position:absolute; top:0; bottom:0; right:0; left:0;}
body {
-ms-overflow-style: none;
scrollbar-width: none;
overflow-y: scroll;}
body::-webkit-scrollbar {
display: none;}
.brsmall {
display: block;
margin-bottom: 1.5em;}
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script>
setInterval(function refresh(){
$.ajax({
url: "ajax_start.php?assign_id=<?php echo $assign_id; ?>&team=<?php echo $assign_student_team;?>",
success: function(data){
if(data == 'die'){
window.location.href = "../login.php";}
if(data == 'R'){
window.location.reload();}
if(data != 'die' && data != 'R'){
var data_list = data.split(" ");
for(var i = 1; i < data_list.length; i++){
var student_info = data_list[i].split("_");
var student_id = student_info[0];
var student_status = student_info[1];
if(student_status == 0){
document.getElementById(student_id).setAttribute('style', '-webkit-filter: grayscale(100%); filter: grayscale(100%);');}
if(student_status == 1){
document.getElementById(student_id).setAttribute('style', '-webkit-filter: grayscale(0%); filter: grayscale(0%);');}}}}});}, 2500);
</script>
</head><body>
<table width="100%" height="100%">
<tr><td>
<p><a href="main.php"><img src="home_phoenix.png" width="100"></a></p>
<p style="font-size: x-large;"><b><?php echo strtoupper($assign_name); ?></b><br>
<img src="preload.gif" width="100"><br>
<b style="color: #033909;">Please wait until your teacher starts the game...</b>
<p>&nbsp;</p>
<?php
if($assign_teams == 0){
?>
<img src="./avatars/Character%20<?php echo $user_avatar; ?>.png" width="200" style="-webkit-filter: drop-shadow(0px 0px 40px white); filter: drop-shadow(0px 0px 40px white);">
<br><b style="font-size: larger;"><?php echo $user_alias; ?></b>
<p><a href="../alias_change.php?game=<?php echo $assign_id; ?>" style="font-size: medium;"><b>Change your alias</b></a> - <a href="../avatar_change.php?game=<?php echo $assign_id; ?>" style="font-size: medium;"><b>Change your avatar</b></a></p>
<?php
}
if($assign_teams == 1){
if($team_name == ''){
?>
<p><img src="./shields/shield_<?php echo $team_shield; ?>.png" width="150" style="-webkit-filter: drop-shadow(0px 0px 40px white); filter: drop-shadow(0px 0px 40px white);"><br>
<b id="team_name" style="font-size: x-large;">TEAM <?php echo $assign_student_team; ?></b><br>
<b><a href="team_name.php?assign_id=<?php echo $assign_id; ?>"  style="color: #820000; font-size: medium">Change team name</a>
<br><a href="team_shield.php?assign_id=<?php echo $assign_id; ?>"  style="color: #820000; font-size: medium">Change team shield</a></b></p>
<?php
}
if($team_name != ''){
?>
<p><img src="./shields/shield_<?php echo $team_shield; ?>.png" width="150" style="-webkit-filter: drop-shadow(0px 0px 40px white); filter: drop-shadow(0px 0px 40px white);"><br>
<b id="team_name" style="font-size: x-large;"><?php echo strtoupper($team_name); ?></b><br>
<b><a href="team_name.php?assign_id=<?php echo $assign_id; ?>"  style="color: #820000; font-size: medium">Change team name</a>
<br><a href="team_shield.php?assign_id=<?php echo $assign_id; ?>"  style="color: #820000; font-size: medium">Change team shield</a></b></p>
<?php
}
?>
<span class="brsmall"></span>
<?php
$sql_count = "SELECT student_id, user_alias, user_avatar, user_title FROM phoenix_assign_users INNER JOIN phoenix_users ON phoenix_assign_users.student_id = phoenix_users.user_id WHERE assign_id = '".$assign_id."' AND assign_student_team = '".$assign_student_team."'";
$res_count = $mysqli -> query($sql_count);
$num_students = mysqli_num_rows($res_count);
$i = 0;
if($num_students == 1){
$width = 15;}
if($num_students == 2){
$width = 30;}
if($num_students == 3){
$width = 45;}
if($num_students == 4){
$width = 60;}
if($num_students == 5){
$width = 75;}
if($num_students > 5){
$width = 90;}
echo '<table width="'.$width.'%" align="center">';
while($data_count = mysqli_fetch_assoc($res_count)){
$teammate_id = $data_count['student_id'];
$teammate_alias = $data_count['user_alias'];
if($teammate_alias == ''){
$teammate_alias = "Student #".$teammate_id;}
$teammate_avatar = $data_count['user_avatar'];
$teammate_title = $data_count['user_title'];
if($teammate_title == 'Mr.'){
$default_avatar = 48;}
if($teammate_title == 'Ms.'){
$default_avatar = 27;}
if($i % 6 == 0){
echo '<tr style="vertical-align: top;">';}
if($user_id != $teammate_id){
?>
<td>
<?php
if($teammate_avatar != 0){
?>
<img id="<?php echo $teammate_id; ?>" src="./avatars/Character%20<?php echo $teammate_avatar; ?>.png" width="200" style="-webkit-filter: grayscale(100%); filter: grayscale(100%);">
<?php
}
if($teammate_avatar == 0){
?>
<img id="<?php echo $teammate_id; ?>" src="./avatars/Character%20<?php echo $default_avatar; ?>.png" width="200" style="-webkit-filter: grayscale(100%); filter: grayscale(100%);">
<?php
}
?>
<br><b style="font-size: larger;"><?php echo $teammate_alias; ?></b>
</td>
<?php
}
if($user_id == $teammate_id){
?>
<td>
<img src="./avatars/Character%20<?php echo $user_avatar; ?>.png" width="200" style="-webkit-filter: drop-shadow(0px 0px 40px white); filter: drop-shadow(0px 0px 40px white);">
<br><b style="font-size: larger;"><?php echo $user_alias; ?></b>
<br><a href="../alias_change.php?game=<?php echo $assign_id; ?>" style="color: #820000; font-size: medium;"><b>Change alias</b></a>
<br> <a href="../avatar_change.php?game=<?php echo $assign_id; ?>" style="color: #820000; font-size: medium;"><b>Change avatar</b></a>
</td>
<?php
}
$i++;
if($i % 6 == 0){
echo '</tr><tr height="40"></tr>';}}
?>
</table>
<?php
}
?>
</td></tr></table>
<p>&nbsp;</p>
</body></html>