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
$question_id = $_GET['question_id'];
if(empty($assign_id) || empty($question_id)){
echo 'Missing info';
$mysqli -> close();
exit();}
$sql_info = "SELECT assign_type, assign_game_status, assign_time_limit, assign_teacher FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
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
$sql_q = "SELECT assign_question_status FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."'";
$res_q = $mysqli -> query($sql_q);
$data_q = mysqli_fetch_assoc($res_q);
if($data_q['assign_question_status'] != 1){
echo 'Wrong question status';}
$sql_a = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer = '1'";
$res_a = $mysqli -> query($sql_a);
$data_a = mysqli_fetch_assoc($res_a);
$num_a = $data_a['COUNT(*)'];
$sql_b = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer = '2'";
$res_b = $mysqli -> query($sql_b);
$data_b = mysqli_fetch_assoc($res_b);
$num_b = $data_b['COUNT(*)'];
$sql_c = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer = '3'";
$res_c = $mysqli -> query($sql_c);
$data_c = mysqli_fetch_assoc($res_c);
$num_c = $data_c['COUNT(*)'];
$sql_d = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer = '4'";
$res_d = $mysqli -> query($sql_d);
$data_d = mysqli_fetch_assoc($res_d);
$num_d = $data_d['COUNT(*)'];
$sql_v = "SELECT COUNT(*) FROM phoenix_answers WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer_valid = '1'";
$res_v = $mysqli -> query($sql_v);
$data_v = mysqli_fetch_assoc($res_v);
$num_v = $data_v['COUNT(*)'];
$total = $num_a + $num_b + $num_c + $num_d;
if($total == 0){
$sql_up = "UPDATE phoenix_assign_questions SET assign_question_rate = NULL, assign_question_rate_a = NULL, assign_question_rate_b = NULL, assign_question_rate_c = NULL, assign_question_rate_d = NULL WHERE question_id = '".$question_id."' AND assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);}
if($total > 0){
$rate_a = round($num_a / $total * 100, 2);
$rate_b = round($num_b / $total * 100, 2);
$rate_c = round($num_c / $total * 100, 2);
$rate_d = round($num_d / $total * 100, 2);
$rate_v = round($num_v / $total * 100, 2);
$sql_up = "UPDATE phoenix_assign_questions SET assign_question_rate = '".$rate_v."', assign_question_rate_a = '".$rate_a."', assign_question_rate_b = '".$rate_b."', assign_question_rate_c = '".$rate_c."', assign_question_rate_d = '".$rate_d."' WHERE question_id = '".$question_id."' AND assign_id = '".$assign_id."'";
$res_up = $mysqli -> query($sql_up);}
$now = date("Y-m-d H:i:s");
$sql_update_1 = "UPDATE phoenix_assign_questions SET assign_question_status = '2', assign_question_hide = '0', assign_question_deadline = '".$now."' WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."'";
$res_update_1 = $mysqli -> query($sql_update_1);
$sql_update_2 = "UPDATE phoenix_assign SET assign_game_status = '2', assign_game_pause = '0', assign_game_remaining = NULL WHERE assign_id = '".$assign_id."'";
$res_update_2 = $mysqli -> query($sql_update_2);
$mysqli -> close();
?><!doctype html>
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
<script>
function next() {
document.location.href='assign_game_answer.php?assign_id=<?php echo $assign_id; ?>';}
</script>
</head>
<body onload="next();">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><img src="preload.gif" width="100"></p>
</body>
</html>