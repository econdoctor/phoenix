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
$mysqli -> close();
exit();}
$sql_info = "SELECT assign_name, assign_type, assign_game_status, assign_teacher FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_info = $mysqli -> query($sql_info);
$data_info = mysqli_fetch_assoc($res_info);
if($data_info['assign_teacher'] != $user_id){
echo 'Not your assignment';
$mysqli -> close();
exit();}
if($data_info['assign_type'] != '5'){
echo 'Not a game';
$mysqli -> close();
exit();}
if($data_info['assign_game_status'] == 1){
header("Location: assign_game_question.php?assign_id=$assign_id");
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
width: 15%;
border-radius: 4px;}
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
url: "leader_start.php?assign_id=<?php echo $assign_id; ?>",
success: function(data){
if(data == 'die'){
window.location.href = "../login.php";}
if(data != 'die'){
var data_list = data.split(" ");
for(var i = 1; i <= data_list.length; i++){
var student_info = data_list[i].split("_");
var student_id = student_info[0];
var student_status = student_info[1];
if(student_status == 1){
document.getElementById(student_id).setAttribute('style', '-webkit-filter: grayscale(0%); filter: grayscale(0%);');}
if(student_status == 0){
document.getElementById(student_id).setAttribute('style', '-webkit-filter: grayscale(100%); filter: grayscale(100%);');}}}}});}, 2500);
</script>
<body onload='document.getElementById("start_music").play();'>
<table width="100%" width="100%"><tr><td>
<p><a href="main.php"><img src="home_phoenix.png" width="100"></a></p>
<p style="font-size: x-large;"><b><?php echo strtoupper($data_info['assign_name']); ?></b></p>
<p><input type="button" value="START" onclick="document.location.href='assign_game_start_db.php?assign_id=<?php echo $assign_id; ?>';"></p>
<span class="brsmall"></span>
<?php
$sql_count = "SELECT student_id, user_avatar, user_title, user_alias, user_first_name, assign_student_team FROM phoenix_assign_users INNER JOIN phoenix_users ON phoenix_assign_users.student_id = phoenix_users.user_id WHERE assign_id = '".$assign_id."' ORDER BY assign_student_team";
$res_count = $mysqli -> query($sql_count);
$num_students = mysqli_num_rows($res_count);
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
$i = 0;
?>
<p><table width="<?php echo $width; ?>%" align="center">
<?php
while($data_count = mysqli_fetch_assoc($res_count)){
$user_first_name = $data_count['user_first_name'];
$student_id = $data_count['student_id'];
$user_avatar = $data_count['user_avatar'];
$user_title = $data_count['user_title'];
if($user_avatar == 0){
if($user_title == 'Ms.'){
$user_avatar = 27;}
if($user_title == 'Mr.'){
$user_avatar = 48;}}
if($i % 6 == 0){
echo '<tr>';}
?>
<td width="12.5%">
<img id="<?php echo $student_id; ?>" src="./avatars/Character%20<?php echo $user_avatar; ?>.png" width="150" style="-webkit-filter: garyscale(100%); filter: grayscale(100%);">
<br><b style="font-size: larger;"><?php echo $user_first_name; ?></b>
</td>
<?php
$i++;
if($i % 6 == 0){
echo '</tr><tr height="40"></tr>';}}
?>
</table></p>
</td></tr></table>
<audio id="start_music" controls loop src="start_music.mp3" preload="auto" style="display:none;">
<audio id="get_ready" controls loop src="get_ready.mp3" preload="auto" style="display:none;">
<audio id="ongoing" controls loop src="ongoing.mp3" preload="auto" style="display:none;">
<audio id="10sec" controls loop src="10sec.mp3" preload="auto" style="display:none;">
<audio id="finished" controls loop src="finished.mp3" preload="auto" style="display:none;">
<audio id="answer" controls loop src="answer.mp3" preload="auto" style="display:none;">
<audio id="ranking" controls loop src="ranking.mp3" preload="auto" style="display:none;">
<audio id="final" controls loop src="final.mp3" preload="auto" style="display:none;">
<?php
$mysqli -> close();
?>
</body>
</html>