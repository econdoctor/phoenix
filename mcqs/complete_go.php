<?php
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
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
$sql = "SELECT user_type, user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 2){
echo 'What are you doing here? This page is for students only.';
exit();}
$assign_id = $_GET['assign_id'];
if(empty($assign_id)){
echo 'Missing information about the assignment.';
exit();}
$sql_assign = "SELECT assign_name, assign_syllabus, assign_scramble, assign_start, assign_release FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res_assign = $mysqli -> query($sql_assign);
$data_assign = mysqli_fetch_assoc($res_assign);
$assign_name = $data_assign['assign_name'];
$assign_syllabus = $data_assign['assign_syllabus'];
$assign_scramble = $data_assign['assign_scramble'];
$assign_start = date("Y-m-d H:i:s", strtotime($data_assign['assign_start']));
$assign_release = $data_assign['assign_release'];
if($now < $assign_start){
echo 'The assignment has not started yet.';
exit();}
$sql_check = "SELECT assign_student_start, assign_student_end FROM phoenix_assign_users WHERE assign_id = '".$assign_id."' AND student_id = '".$user_id."'";
$res_check = $mysqli -> query($sql_check);
$nr_check = mysqli_num_rows($res_check);
if($nr_check == 0){
echo 'You are not authorized to complete this assignment.';
exit();}
$data_check = mysqli_fetch_assoc($res_check);
if($data_check['assign_student_start'] == NULL){
header("Location: complete_start.php?assign_id=$assign_id");
exit();}
if($now > $data_check['assign_student_end']){
echo 'The assignment is over.';
exit();}
if($assign_scramble == '1'){
$sql_get = "SELECT phoenix_questions.question_id, phoenix_questions.question_paper_id, phoenix_questions.question_syllabus, phoenix_questions.question_number FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY SIN(phoenix_questions.question_id * $user_id) ASC";}
if($assign_scramble != '1'){
$sql_get = "SELECT phoenix_questions.question_id, phoenix_questions.question_paper_id, phoenix_questions.question_syllabus, phoenix_questions.question_number FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id WHERE phoenix_assign_questions.assign_id = '".$assign_id."' ORDER BY phoenix_assign_questions.assign_question_number ASC";}
$res_get = $mysqli -> query($sql_get);
$count = mysqli_num_rows($res_get);
$end_f = date("M d, Y H:i:s", strtotime($data_check['assign_student_end']));
function is_checked($db_value, $html_value){
if($db_value == $html_value){
return "checked";}
else{
return "";}}
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
  width: 20%;
  border-radius: 4px;
}
.custom-clickable-row {
cursor: pointer;
}
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script>
setInterval(function refresh(){
$.ajax({
url: "refresh_session.php",
success: function(data){
if(data == 'die'){
window.location.href = "../login.php";}}});}, 60000);
</script>
<script>
var countDownDate = new Date("<?php echo $end_f; ?>").getTime();
var x = setInterval(function(){
var now0 = new Date();
var now = new Date(now0.getTime() + now0.getTimezoneOffset() * 60000);
var distance = countDownDate - now;
var days = Math.floor(distance / (1000 * 60 * 60 * 24));
var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
var seconds = Math.floor((distance % (1000 * 60)) / 1000);
document.getElementById("countdown").innerHTML = days + "d " + hours + "h "+ minutes + "m " + seconds + "s ";
document.title = days + "d " + hours + "h "+ minutes + "m " + seconds + "s - Phoenix";
if (distance < 0){
clearInterval(x);
document.getElementById("countdown").innerHTML = "<span style='color: #820000'>TIME'S UP!</span>";
window.location.href = "complete.php?r=1";}}, 1000);
</script>
<script>
function AlertIt(){
var answer = confirm ("You will not be able to modify your answers once they are submitted.")
if(answer){
window.location="assign_submit.php?assign_id=<?php echo $assign_id; ?>&user_id=<?php echo $user_id; ?>";}};
</script>
<script>
function answerDisplay(answer, question_number){
var audio = document.getElementById("audio_pop");
audio.play();
document.getElementById('1'+question_number).style.backgroundColor = '#769467';
document.getElementById('1'+question_number).removeAttribute('class');
document.getElementById('1'+question_number).removeAttribute('onclick');
document.getElementById('2'+question_number).style.backgroundColor = '#769467';
document.getElementById('2'+question_number).removeAttribute('class');
document.getElementById('2'+question_number).removeAttribute('onclick');
document.getElementById('3'+question_number).style.backgroundColor = '#769467';
document.getElementById('3'+question_number).removeAttribute('class');
document.getElementById('3'+question_number).removeAttribute('onclick');
document.getElementById('4'+question_number).style.backgroundColor = '#769467';
document.getElementById('4'+question_number).removeAttribute('class');
document.getElementById('4'+question_number).removeAttribute('onclick');
document.getElementById(answer+question_number).style.backgroundColor = '#5c7090';
document.getElementById(answer+question_number).innerHTML = '<b>SAVING...</b>';
document.getElementById('tick'+question_number).setAttribute('style','display:none;');}
</script>
<script>
function answer(question_id, assign_id, answer, question_number){
$.ajax({
url: "answer_assignment.php?question_id="+question_id+"&assign_id="+assign_id+"&answer="+answer,
success: function(data){
var oc1 = "answer('"+question_id+"', '"+assign_id+"', '1', '"+question_number+"');answerDisplay('1', '"+question_number+"');";
var oc2 = "answer('"+question_id+"', '"+assign_id+"', '2', '"+question_number+"');answerDisplay('2', '"+question_number+"');";
var oc3 = "answer('"+question_id+"', '"+assign_id+"', '3', '"+question_number+"');answerDisplay('3', '"+question_number+"');";
var oc4 = "answer('"+question_id+"', '"+assign_id+"', '4', '"+question_number+"');answerDisplay('4', '"+question_number+"');";
if(answer == 1){
answer_display = 'A';}
if(answer == 2){
answer_display = 'B';}
if(answer == 3){
answer_display = 'C';}
if(answer == 4){
answer_display = 'D';}
$( "#" + answer + question_number ).html('<b>'+answer_display+'</b>');
$(" #1" + question_number ).addClass("custom-clickable-row");
$(" #1" + question_number ).attr("onclick", oc1);
$( "#2" + question_number ).addClass("custom-clickable-row");
$(" #2" + question_number ).attr("onclick", oc2);
$( "#3" + question_number ).addClass("custom-clickable-row");
$(" #3" + question_number ).attr("onclick", oc3);
$( "#4" + question_number ).addClass("custom-clickable-row");
$(" #4" + question_number ).attr("onclick", oc4);
$("#count").html(data.substring(2));
if(data == 'die'){
window.location.href = "../login.php";}
if(data != 'die'){
if(data.substring(0,1) == 0){
$( "#tick" + question_number ).hide();
$( "#" + answer + question_number ).css("background-color", "#769467");}
if(data.substring(0,1) == 1){
$( "#tick" + question_number ).show();
$( "#" + data.substring(1,2) + question_number ).removeAttr("onclick").removeAttr("class").css("background-color", "#ffc000");}}}});}
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
<input type="button" name="practice" value="PRACTICE" style="font-size: x-large; width: 45%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='practice.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 45%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='complete.php';"/>
</p></td></tr></tbody></table>
<p style="font-size: x-large"><b><?php echo strtoupper($assign_name); ?></b></p>
<p><b>Time left:<br><span id="countdown" style="color: #033909"></span></b></p>
<p><b>Progress:</b>
<?php
$sql_ans ="SELECT COUNT(*) FROM phoenix_answers WHERE answer_type = '3' AND assign_id = '".$assign_id."' AND user_id = '".$user_id."'";
$res_ans = $mysqli -> query($sql_ans);
$data_ans = mysqli_fetch_assoc($res_ans);
$count_ans = $data_ans['COUNT(*)'];
echo '<br><b style="color: #033909"><span id="count">'.$count_ans.'</span> / '.$count.'</b></p>';
if($assign_release == '1'){
?>
<p><input type="button" style="width: 25%" name="submit_answers" value="SUBMIT MY ANSWERS" onclick="AlertIt();"/></p>
<?php
}
 ?>
<p><b style="color: #820000"><u>Instructions</u>:<br>- Your answers are saved automatically.
<br> - You can edit your answers until the end of the assignment (see countdown above).
<br>- You can leave the page and come back later to finish the assignment.
<br>- The countdown will NOT stop when leaving the page.</b></p><br>
<?php
$i = 1;
while($data_get = mysqli_fetch_assoc($res_get)){
echo '<div id="'.$i.'">';
$question_id = $data_get['question_id'];
$sql8 = "SELECT answer FROM phoenix_answers WHERE user_id = '".$user_id."' AND question_id = '".$question_id."' AND assign_id = '".$assign_id."' AND answer_type = '3'";
$res8 = $mysqli -> query($sql8);
$data8 = mysqli_fetch_assoc($res8);
$answer_student = $data8['answer'];
?>
<table width="72%" align="center" bgcolor="#000000"><tbody><tr>
<td height="40" colspan="4" bgcolor="#6a855c">
<table align="center" width="100%"><tbody><tr>
<td width="5%" valign="middle" height="40" bgcolor="#6a855c"><img id="tick<?php echo $i; ?>" src="tick.png" height="25" <?php if(empty($answer_student)) { echo 'style="display:none"'; } ?> ></td>
<td width="90%" height="40" colspan ="2" bgcolor="#6a855c"><b>QUESTION <?php echo $i; ?></b></td>
<td width="5%" height="40" bgcolor="#6a855c">&nbsp;</td>
</tr></tbody></table>
</td></tr><tr>
<td bgcolor="#FFFFFF" colspan ="4"><img src="q/<?php echo $data_get['question_syllabus']; ?>/<?php echo $data_get['question_paper_id']; ?>/<?php echo $data_get['question_number']; ?>.png" width="95%"></td>
</tr><tr>
<td id="1<?php echo $i; ?>" height="40" <?php if($answer_student == '1') { echo 'bgcolor = "#ffc000"'; } else { echo 'class="custom-clickable-row" bgcolor = "#769467"'; ?> onclick="answer('<?php echo $question_id; ?>', '<?php echo $assign_id; ?>', '<?php echo '1'; ?>', '<?php echo $i; ?>');answerDisplay('<?php echo '1'; ?>', '<?php echo $i; ?>');" <?php } ?> width = "25%"><b>A</b></td>
<td id="2<?php echo $i; ?>" height="40" <?php if($answer_student == '2') { echo 'bgcolor = "#ffc000"'; } else { echo 'class="custom-clickable-row" bgcolor = "#769467"'; ?> onclick="answer('<?php echo $question_id; ?>', '<?php echo $assign_id; ?>', '<?php echo '2'; ?>', '<?php echo $i; ?>');answerDisplay('<?php echo '2'; ?>', '<?php echo $i; ?>');" <?php } ?> width = "25%"><b>B</b></td>
<td id="3<?php echo $i; ?>" height="40" <?php if($answer_student == '3') { echo 'bgcolor = "#ffc000"'; } else { echo 'class="custom-clickable-row" bgcolor = "#769467"'; ?> onclick="answer('<?php echo $question_id; ?>', '<?php echo $assign_id; ?>', '<?php echo '3'; ?>', '<?php echo $i; ?>');answerDisplay('<?php echo '3'; ?>', '<?php echo $i; ?>');" <?php } ?> width = "25%"><b>C</b></td>
<td id="4<?php echo $i; ?>" height="40" <?php if($answer_student == '4') { echo 'bgcolor = "#ffc000"'; } else { echo 'class="custom-clickable-row" bgcolor = "#769467"'; ?> onclick="answer('<?php echo $question_id; ?>', '<?php echo $assign_id; ?>', '<?php echo '4'; ?>', '<?php echo $i; ?>');answerDisplay('<?php echo '4'; ?>', '<?php echo $i; ?>');" <?php } ?> width = "25%"><b>D</b></td>
</tr></tbody></table></div><br><br>
<?php
$i++;}
?>
<p><a href="#top"><b>Back to the top</b></a></p>
<p>&nbsp;</p>
<audio id="audio_pop" src="pop.mp3" preload="auto">
</body>
</html>