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
$mysqli -> close();
exit();}
$sql_info = "SELECT user_alias, user_avatar FROM phoenix_users WHERE user_id = '".$user_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
$user_alias = $data_info['user_alias'];
$user_avatar = $data_info['user_avatar'];
if($user_alias == ''){
header("Location: ../alias_change.php?game=$assign_id");
$mysqli -> close();
exit();}
sleep(1);
$sql_assign = "SELECT assign_type, assign_name, assign_nq, assign_syllabus, assign_teams, assign_game_status FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
$assign_name = $data_assign['assign_name'];
$assign_teams = $data_assign['assign_teams'];
$assign_nq = $data_assign['assign_nq'];
$assign_type = $data_assign['assign_type'];
$assign_syllabus = $data_assign['assign_syllabus'];
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
$sql_get = "SELECT question_answer, assign_question_rate_a, assign_question_rate_b, assign_question_rate_c, assign_question_rate_d, phoenix_assign_questions.question_id, question_paper_id, question_number, question_syllabus, assign_question_number FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE assign_id = '".$assign_id."' AND assign_question_status = '2'";
$res_get = $mysqli -> query($sql_get);
$nr_get = mysqli_num_rows($res_get);
if($nr_get == 0){
echo 'No question found';
exit();}
$data_get = mysqli_fetch_assoc($res_get);
$question_id = $data_get['question_id'];
$answer_question = $data_get['question_answer'];
$sql_answer = "SELECT answer, answer_valid, answer_points FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND user_id = '".$user_id."' AND question_id = '".$question_id."'";
$res_answer = $mysqli -> query($sql_answer);
$nr_answer = mysqli_num_rows($res_answer);
$data_answer = mysqli_fetch_assoc($res_answer);
$answer = $data_answer['answer'];
$answer_valid = $data_answer['answer_valid'];
$answer_points = $data_answer['answer_points'];
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
.brsmall {
display: block;
margin-bottom: 1em;}
.brmedium {
display: block;
margin-bottom: 2em;}
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script>
setInterval(function refresh(){
$.ajax({
url: "ajax_answer.php?assign_id=<?php echo $assign_id; ?>",
success: function(data){
if(data == 'die'){
window.location.href = "../login.php";}
if(data == 'R'){
window.location.reload();}}});}, 2500);
</script>
<script>
function showQ(action){
if(action == 'show'){
document.getElementById("question").style.display = 'block';
document.getElementById("btn_question").value = 'HIDE QUESTION';
document.getElementById("btn_question").setAttribute("onclick", "showQ('hide')");}
if(action == 'hide'){
document.getElementById("question").style.display = 'none';
document.getElementById("btn_question").value = 'SHOW QUESTION';
document.getElementById("btn_question").setAttribute("onclick", "showQ('show')");}}
</script>
</head>
<body style="vertical-align: top;">
<table width="100%" height="100%">
<tr><td>
<p><a href="main.php"><img src="home_phoenix.png" width="100"></a></p>
<p style="font-size: x-large;"><b><?php echo strtoupper($assign_name); ?></b></p>
<p style="font-size: x-large;"><b>QUESTION <?php echo $data_get['assign_question_number']; ?> / <?php echo $assign_nq; ?></b></p>
<p>
<input type="button" id="btn_question" style="width: 25%;" value="SHOW QUESTION" onclick="showQ('show');">
</p>
<span class="brsmall"></span>
<div id="question" style="display: none;">
<p><img src="./q/<?php echo $data_get['question_syllabus']; ?>/<?php echo $data_get['question_paper_id']; ?>/<?php echo $data_get['question_number']; ?>.png" style="border: solid black 2px; max-width: 72%; height: auto; width: auto;"></p>
</div>
<table width="50%" align="center"><tr>
<?php
if($data_get['assign_question_rate_a'] == NULL && $data_get['assign_question_rate_b'] == NULL && $data_get['assign_question_rate_c'] == NULL && $data_get['assign_question_rate_d'] == NULL){
?>
<td height="80" width="25%">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr>
<td <?php if($data_get['question_answer'] == '1') { echo 'bgcolor="#2e835c"'; } if($answer == '1' && $answer_valid == 0) { echo 'bgcolor="#c33c47"'; } else { echo 'bgcolor="#647d57"'; } ?> ><b style="font-size: larger;">A</b></td>
</tr></table>
</td>
<td height="80" width="25%">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr>
<td <?php if($data_get['question_answer'] == '2') { echo 'bgcolor="#2e835c"'; } if($answer == '2' && $answer_valid == 0) { echo 'bgcolor="#c33c47"'; } else { echo 'bgcolor="#647d57"'; } ?> ><b style="font-size: larger;">B</b></td>
</tr></table>
</td>
<td height="80" width="25%">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr>
<td <?php if($data_get['question_answer'] == '3') { echo 'bgcolor="#2e835c"'; } if($answer == '3' && $answer_valid == 0) { echo 'bgcolor="#c33c47"'; } else { echo 'bgcolor="#647d57"'; } ?> ><b style="font-size: larger;">C</b></td>
</tr></table>
</td>
<td height="80" width="25%">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr>
<td <?php if($data_get['question_answer'] == '4') { echo 'bgcolor="#2e835c"'; } if($answer == '4' && $answer_valid == 0) { echo 'bgcolor="#c33c47"'; } else { echo 'bgcolor="#647d57"'; } ?> ><b style="font-size: larger;">D</b></td>
</tr></table>
</td>
<?php
}
if($data_get['assign_question_rate_a'] != NULL && $data_get['assign_question_rate_b'] != NULL && $data_get['assign_question_rate_c'] != NULL && $data_get['assign_question_rate_d'] != NULL){
?>
<td height="80" width="25%">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr>
<td <?php if($data_get['question_answer'] == '1') { echo 'bgcolor="#2e835c"'; } if($answer == '1' && $answer_valid == 0) { echo 'bgcolor="#c33c47"'; } else { echo 'bgcolor="#647d57"'; } ?> ><b style="font-size: larger;">A<br><?php echo round($data_get['assign_question_rate_a'],0); ?>%</b></td>
</tr></table>
</td>
<td height="80" width="25%">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr>
<td <?php if($data_get['question_answer'] == '2') { echo 'bgcolor="#2e835c"'; } if($answer == '2' && $answer_valid == 0) { echo 'bgcolor="#c33c47"'; } else { echo 'bgcolor="#647d57"'; } ?> ><b style="font-size: larger;">B<br><?php echo round($data_get['assign_question_rate_b'],0); ?>%</b></td>
</tr></table>
</td>
<td height="80" width="25%">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr>
<td <?php if($data_get['question_answer'] == '3') { echo 'bgcolor="#2e835c"'; } if($answer == '3' && $answer_valid == 0) { echo 'bgcolor="#c33c47"'; } else { echo 'bgcolor="#647d57"'; } ?> ><b style="font-size: larger;">C<br><?php echo round($data_get['assign_question_rate_c'],0); ?>%</b></td>
</tr></table>
</td>
<td height="80" width="25%">
<table width="95%" height="90%" align="center" bgcolor="#000000"><tr>
<td <?php if($data_get['question_answer'] == '4') { echo 'bgcolor="#2e835c"'; } if($answer == '4' && $answer_valid == 0) { echo 'bgcolor="#c33c47"'; } else { echo 'bgcolor="#647d57"'; } ?> ><b style="font-size: larger;">D<br><?php echo round($data_get['assign_question_rate_d'],0); ?>%</b></td>
</tr></table>
</td>
<?php
}
?>
</td></tr></table>
<span class="brmedium"></span>
<?php
if($assign_teams == 0){
?>
<p><img src="./avatars/Character%20<?php echo $user_avatar; ?>.png" width="200" <?php if($nr_answer == 0 || ($nr_answer > 0 && $answer == '0' && $answer_valid == 0)) { echo 'style="-webkit-filter: grayscale(100%); filter: grayscale(100%);"'; } if($nr_answer > 0 && $answer != '0' && $answer_valid == 0) { echo 'style="-webkit-filter: drop-shadow(0px 0px 40px #c33c47); filter: drop-shadow(0px 0px 40px #c33c47);"'; } if($nr_answer > 0 && $answer_valid == 1) { echo 'style="-webkit-filter: drop-shadow(0px 0px 40px #2e835c); filter: drop-shadow(0px 0px 40px #2e835c);"'; } ?>>
<span class="brsmall"></span>
<b style="font-size: larger;"><?php echo $user_alias; ?></b>
<?php
if($nr_answer > 0 && $answer_valid == 1){
?>
<br><b style="font-size: larger; color: #033909;">+ <?php echo $answer_points; ?> pts</b>
<?php
}
if($nr_answer > 0 && $answer != '0' && $answer_valid == 0){
?>
<br><b style="font-size: larger; color: #820000;">- 50 pts</b>
<?php
}
?>
</p>
<?php
}
if($assign_teams == 1){
$sql_t = "SELECT team_name, team_shield FROM phoenix_assign_teams WHERE assign_id = '".$assign_id."' AND team = '".$assign_student_team."'";
$res_t = $mysqli -> query($sql_t);
$data_t = mysqli_fetch_assoc($res_t);
$team_name = $data_t['team_name'];
$team_shield = $data_t['team_shield'];
$sql_total = "SELECT SUM(answer_points) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND user_id IN (SELECT student_id FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND assign_student_team = '".$assign_student_team."')";
$res_total = $mysqli -> query($sql_total);
$data_total = mysqli_fetch_assoc($res_total);
$total_points = $data_total['SUM(answer_points)'];
if($total_points == NULL){
$total_points = 0;}
?>
<table align="center" width="50%"><tr>
<td width="50%">
<img src="./avatars/Character%20<?php echo $user_avatar; ?>.png" width="200" <?php if($nr_answer == 0 || ($nr_answer > 0 && $answer == '0' && $answer_valid == 0)) { echo 'style="-webkit-filter: grayscale(100%); filter: grayscale(100%);"'; } if($nr_answer > 0 && $answer != '0' && $answer_valid == 0) { echo 'style="-webkit-filter: drop-shadow(0px 0px 40px #c33c47); filter: drop-shadow(0px 0px 40px #c33c47);"'; } if($nr_answer > 0 && $answer_valid == 1) { echo 'style="-webkit-filter: drop-shadow(0px 0px 40px #2e835c); filter: drop-shadow(0px 0px 40px #2e835c);"'; } ?>>
<span class="brsmall"></span>
<b style="font-size: larger;"><?php echo $user_alias; ?></b>
<?php
if($nr_answer > 0 && $answer_valid == 1){
?>
<br><b style="font-size: larger; color: #033909;">+ <?php echo $answer_points; ?> pts</b>
<?php
}
if($nr_answer > 0 && $answer != '0' && $answer_valid == 0){
?>
<br><b style="font-size: larger; color: #820000;">- 50 pts</b>
<?php
}
?>
</td>
<td width="50%">
<img src="./shields/shield_<?php echo $team_shield; ?>.png" width="150" style="-webkit-filter: drop-shadow(0px 0px 20px black); filter: drop-shadow(0px 0px 20px black);">
<span class="brsmall"></span>
<b style="font-size: larger;"><?php echo strtoupper($team_name); ?></b>
<?php
if($total_points >= 0){
?>
<br><b style="font-size: larger; color: #033909;">+ <?php echo $total_points; ?> pts</b>
<?php
}
if($total_points < 0){
?>
<br><b style="font-size: larger; color: #820000;">- <?php echo abs($total_points); ?></b>
<?php
}
?>
</td>
</tr></table>
<span class="brmedium"></span>
<?php
$sql_teammates = "SELECT user_id, user_alias, user_avatar FROM phoenix_assign_users INNER JOIN phoenix_users ON phoenix_assign_users.student_id = phoenix_users.user_id WHERE assign_id = '".$assign_id."' AND assign_student_team = '".$assign_student_team."' AND user_id <> '".$user_id."'";
$res_teammates = $mysqli -> query($sql_teammates);
$nr_teammates = mysqli_num_rows($res_teammates);
if($nr_teammates > 0){
if($nr_teammates == 1){
$width = 15;}
if($nr_teammates == 2){
$width = 30;}
if($nr_teammates == 3){
$width = 45;}
if($nr_teammates == 4){
$width = 60;}
if($nr_teammates == 5){
$width = 75;}
if($nr_teammates > 5){
$width = 90;}
?>
<table width="<?php echo $width; ?>%" align="center">
<?php
$i = 0;
while($data_teammates = mysqli_fetch_assoc($res_teammates)){
$teammate_id = $data_teammates['user_id'];
$teammate_alias = $data_teammates['user_alias'];
$teammate_avatar = $data_teammates['user_avatar'];
$sql_ans = "SELECT answer, answer_valid, answer_points FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND user_id = '".$teammate_id."'";
$res_ans = $mysqli -> query($sql_ans);
$nr_ans = mysqli_num_rows($res_ans);
$data_ans = mysqli_fetch_assoc($res_ans);
$answer_valid = $data_ans['answer_valid'];
$teammate_answer = $data_ans['answer'];
$teammate_answer_valid = $data_ans['answer_valid'];
$teammate_answer_points = $data_ans['answer_points'];
if($i % 6 == 0){
echo '<tr style="vertical-align: top;">';}
$i++;
?>
<td>
<img src="./avatars/Character%20<?php echo $teammate_avatar; ?>.png" width="150" <?php if($nr_ans == 0 || ($nr_ans > 0 && $teammate_answer == '0' && $teammate_answer_valid == 0)) { echo 'style="-webkit-filter: grayscale(100%); filter: grayscale(100%);"'; } if($nr_ans > 0 && $teammate_answer != '0' && $teammate_answer_valid == 0) { echo 'style="-webkit-filter: drop-shadow(0px 0px 40px #c33c47); filter: drop-shadow(0px 0px 40px #c33c47);"'; } if($nr_ans > 0 && $teammate_answer_valid == 1) { echo 'style="-webkit-filter: drop-shadow(0px 0px 40px #2e835c); filter: drop-shadow(0px 0px 40px #2e835c);"'; } ?>>
<span class="brsmall"></span>
<b style="font-size: larger;"><?php echo $teammate_alias; ?></b>
<?php
if($nr_ans > 0 && $teammate_answer != '0' && $teammate_answer_valid == 0){
?>
<br><b style="font-size: larger; color: #820000;"><?php echo $teammate_answer; ?></b>
<?php
}
if($nr_ans > 0 && $teammate_answer_valid == 1){
?>
<br><b style="font-size: larger; color: #033909;">+ <?php echo $teammate_answer_points; ?> pts</b>
<?php
}
if($nr_ans > 0 && $teammate_answer != '0' && $teammate_answer_valid == 0){
?>
<br><b style="font-size: larger; color: #820000;">- 50 pts</b>
<?php
}
?>
</td>
<?php
if($i % 6 == 0){
echo '</tr><tr height="40"></tr>';}
}}
?>
</table>
<?php
}
?>
</td></tr></tbody></table>
<p>&nbsp;</p>
<?php
$mysqli -> close();
?>
</body></html>