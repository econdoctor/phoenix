<?php
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
$search_unit = $_POST['unit'];
$search_module = $_POST['module'];
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];}
unset($_SESSION['phoenix_order']);
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT user_type, user_teacher, user_active, user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_teacher = $data['user_teacher'];
$user_active = $data['user_active'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
$s = $_GET['s'];
if(empty($s)){
echo 'Missing information about your course.';
exit();}
if($s == '1'){
$s_text = 'IGCSE';}
if($s == '2'){
$s_text = 'AS LEVEL';}
if($s == '3'){
$s_text = 'A LEVEL';}
if($user_type == 2){
echo 'What are you doing here? This page is for students only.';
exit();}
if($user_teacher != 0){
$sql_gt = "SELECT * FROM phoenix_thresholds WHERE syllabus = '".$s."' AND teacher_id = '".$user_teacher."'";}
if($user_teacher == 0){
$sql_gt = "SELECT * FROM phoenix_thresholds WHERE syllabus = '".$s."' AND teacher_id = '".$user_id."'";}
$res_gt = $mysqli -> query($sql_gt);
$data_gt = mysqli_fetch_assoc($res_gt);
$min_a = $data_gt['min_a'];
$min_b = $data_gt['min_b'];
$min_c = $data_gt['min_c'];
$min_d = $data_gt['min_d'];
$min_e = $data_gt['min_e'];
if($s == '1'){
$min_f = $data_gt['min_f'];
$min_g = $data_gt['min_g'];}
if(empty($search_unit) && empty($search_module)){
$sql2 = "SELECT * FROM phoenix_topics WHERE topic_syllabus = '".$s."' ORDER BY topic_unit_id ASC, topic_module_id ASC";}
if(isset($search_unit) && isset($search_module)){
$sql2 = "SELECT * FROM phoenix_topics WHERE topic_syllabus = '".$s."' AND topic_unit_id = (CASE WHEN '".$search_unit."' != 'Any' THEN '".$search_unit."' ELSE topic_unit_id END) AND topic_module_id = (CASE WHEN '".$search_module."' != 'Any' THEN '".$search_module."' ELSE topic_module_id END) ORDER BY topic_unit_id ASC, topic_module_id ASC";}
$res2 = $mysqli -> query($sql2);
$nr_2 = mysqli_num_rows($res2);
function is_selected($db_value, $html_value){
if($db_value == $html_value){
return "selected";}
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
optgroup {
font-size:18px;
}
select {
padding: 3px 5px;
margin: 2px 0;
box-sizing: border-box;
text-align: center;
font-weight: bold;
font-size: 18px;
border: 2px solid black;
border-radius: 4px;
}
input, select{
box-sizing: border-box;
-moz-box-sizing: border-box;
-webkit-box-sizing: border-box;
}
.mid {
vertical-align:middle
}
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script>
function submitForm(){
document.getElementById('module').value = 'Any';
document.getElementById('form1').submit();}
</script>
<script>
function showGT(){
var e = document.getElementById('gt');
if(e.style.display == 'block')
e.style.display = 'none';
else
e.style.display = 'block';}
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
<?php
if($user_type == 1 && $user_teacher == 0){
?>
<input type="button" name="practice" value="PRACTICE" style="font-size: x-large; width: 45%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='practice.php';"/>
<?php
}
if($user_type == 1 && $user_teacher != 0){
?>
<input type="button" name="practice" value="PRACTICE" style="font-size: x-large; width: 45%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='practice.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 45%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='complete.php';"/>
<?php
}
?>
</td></tr></tbody></table>
<p style="font-size: x-large"><b><?php echo $s_text; ?> TOPICS</b></p>
<p><input type="button" name="thresholds" value="GRADE THRESHOLDS" width="25%" onclick="showGT();"/>
<div id="gt" style="display:none">
<table align="center" width="67%" bgcolor="#000000">
<tbody><tr>
<td height="40" bgcolor="#647d57"><b>A</b></td>
<td height="40" bgcolor="#647d57"><b>B</b></td>
<td height="40" bgcolor="#647d57"><b>C</b></td>
<td height="40" bgcolor="#647d57"><b>D</b></td>
<td height="40" bgcolor="#647d57"><b>E</b></td>
<?php
if($s == '1'){
echo '<td height="40" bgcolor="#647d57"><b>F</b></td>
<td height="40" bgcolor="#647d57"><b>G</b></td>';}
?>
<td height="40" bgcolor="#647d57"><b>U</b></td>
</tr><tr>
<td height="40" bgcolor="#769467">&#8805; <?php echo $min_a; ?>%</td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $min_b; ?>%</td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $min_c; ?>%</td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $min_d; ?>%</td>
<td height="40" bgcolor="#769467">&#8805; <?php echo $min_e; ?>%</td>
<?php
if($s != '1'){
echo '<td height="40" bgcolor="#769467">< '.$min_e.'%</td>';}
if($s == "1"){
echo '<td height="40" bgcolor="#769467">&#8805; '.$min_f.'%</td>
<td height="40" bgcolor="#769467">&#8805; '.$min_g.'%</td>
<td height="40" bgcolor="#769467">< '.$min_g.'%</td>';}
?>
</tr></tbody></table></div>
<form method="post" name="form1" id="form1" action="practice_topic.php?s=<?php echo $s; ?>">
<p><select name="unit" id="unit" onChange="submitForm();">
<optgroup>
<option value="Any">All Units</option>
<?php
$sql_unit = "SELECT DISTINCT(topic_unit_id), topic_unit FROM phoenix_topics WHERE topic_syllabus = '".$s."' ORDER BY topic_unit_id ASC";
$res_unit = $mysqli -> query($sql_unit);
while($data_unit = mysqli_fetch_assoc($res_unit)){
?>
<option value="<?php echo $data_unit['topic_unit_id']; ?>" <?php echo is_selected($data_unit['topic_unit_id'], $search_unit); ?>><?php echo $data_unit['topic_unit']; ?></option>
<?php
}
?>
</optgroup></select></p>
<p><select id="module" name="module" onchange="this.form.submit();">
<optgroup>
<?php
if(empty($search_module)){
echo '<option value="Any">All Topics</option>';}
if(isset($search_module) && isset($search_unit)){
$sql_module ="SELECT * FROM phoenix_topics WHERE topic_syllabus = '".$s."' AND topic_unit_id = '".$search_unit."' ORDER BY topic_module_id ASC";
$res_module = $mysqli -> query($sql_module);
echo '<option value="Any">All Topics</option>';
while($data_module = mysqli_fetch_assoc($res_module)){
$module = $data_module['topic_module_id'];
$module_text = $data_module['topic_module'];
?>
<option value="<?php echo $module; ?>" <?php echo is_selected($module, $search_module); ?>><?php echo $module_text; ?></option>';
<?php
}}
?>
</optgroup></select></form>
<?php
if($nr_2 == 0){
echo '<p><em>No topics found</em></p>';}
if($nr_2 > 0){
echo '<p><b><span style="color: #820000">'.$nr_2.'</span> topic(s) found</b></p>
<table width="80%" align="center" bgcolor="#000000"><tbody>';
$color = 1;
$previous = '';
while($data2 = mysqli_fetch_assoc($res2)){
$topic_id = $data2['topic_id'];
$topic_hidden = $data2['topic_hidden'];
$mod = $data2['topic_module_id'];
$un = $data2['topic_unit_id'];
$current = $data2['topic_unit_id'];
if($current != $previous){
$title_raw = $data2['topic_unit'];
$title = strtoupper($title_raw);
echo'<tr><td height="40" colspan ="4" bgcolor = "#647d57"><p><b>'.$title.'</b></p></td></tr>';
$color = "1";}
$sql_perm = "SELECT COUNT(*) FROM phoenix_permissions_topics INNER JOIN phoenix_permissions_users ON phoenix_permissions_users.permission_id = phoenix_permissions_topics.permission_id WHERE phoenix_permissions_topics.topic_id = '".$topic_id."' AND phoenix_permissions_users.student_id = '".$user_id."'";
$res_perm = $mysqli -> query($sql_perm);
$data_perm = mysqli_fetch_assoc($res_perm);
$nr_perm = $data_perm['COUNT(*)'];
$sql_count = "SELECT COUNT(*) FROM phoenix_questions WHERE question_topic_id = '".$topic_id."' AND question_repeat = '0' AND question_obsolete = '0'";
$res_count = $mysqli -> query($sql_count);
$data_count = mysqli_fetch_assoc($res_count);
$count_q = $data_count['COUNT(*)'];
$sql_run = "SELECT answer_date, answer_valid FROM phoenix_answers WHERE topic_id = '".$topic_id."' AND user_id = '".$user_id."' AND answer_type = '2'";
$res_run = $mysqli -> query($sql_run);
$nr_run = mysqli_num_rows($res_run);
if($nr_run == $count_q){
$correct = 0;
$ans_date = '1970-01-01 00:00:00';
while($data_run = mysqli_fetch_assoc($res_run)){
if($data_run['answer_valid'] == 1){
$correct++;}
if($data_run['answer_date'] > $ans_date){
$ans_date = $data_run['answer_date'];}}
$score = $correct;
$answer_date = date('Y-m-d', strtotime($ans_date));
$percent = round($score / $count_q * 100,0);
if($percent >= $min_a){
$letter = "A";}
if($percent < $min_a && $percent >= $min_b){
$letter = "B";}
if($percent < $min_b && $percent >= $min_c){
$letter = "C";}
if($percent < $min_c && $percent >= $min_d){
$letter = "D";}
if($percent < $min_d && $percent >= $min_e){
$letter = "E";}
if($s != "1" && $percent < $min_e){
$letter = "U";}
if($s == '1'){
if($percent < $min_e && $percent >= $min_f){
$letter = "F";}
if($percent < $min_f && $percent >= $min_g){
$letter = "G";}
if($percent < $min_g){
$letter = "U";}}}
if($color == 1){
echo '<tr><td height="40" bgcolor="#769467"><p>'.$data2['topic_module'].'</p></td>
<td height="40" bgcolor="#769467"><p><b>'.$count_q.' MCQs</b></p></td>
<td height="40" bgcolor="#769467"><p>';
if(($user_active == 1 && $nr_perm > 0) || ($user_active == 0 && ($topic_hidden == 1 || $nr_perm > 0))){
echo '<b style="color: #820000">LOCKED</b>';}
if(($user_active == 1 && $nr_perm == 0) || ($user_active == 0 && $topic_hidden == 0 && $nr_perm == 0)){
if($count_q == 0){
echo '<b style="color: #820000">NO MCQ AVAILABLE</b>';}
if($count_q > 0){
if($nr_run == 0){
echo '<b>NOT STARTED YET</b>';}
if($nr_run > 0 && $nr_run < $count_q){
echo '<b style = "color: #0E2366">IN PROGRESS</b><br><b>('.round($nr_run / $count_q * 100, 0).'%)</b>';}
if($nr_run == $count_q){
echo '<b style="color: #033909">FINISHED<br>'.$answer_date.'</b><br><b>'.$score.' / '.$count_q.' ('.$percent.'% - '.$letter.')</b>';}}}
echo '</p></td>
<td height="40" bgcolor="#769467" class="mid"><p>';
if($nr_perm > 0){
echo '&nbsp;&nbsp;<img src="cross.png" width="30" height="30" class="mid">&nbsp;&nbsp;';}
if($nr_perm == 0){
if($user_active == 0 && $topic_hidden == 1){
echo '&nbsp;&nbsp;<a href="upgrade.php"><img src="unlock.png" width="30" width="30" class="mid" title="Unlock"></a>&nbsp;&nbsp;';}
if($user_active == 1 || ($user_active == 0 && $topic_hidden == 0)){
if($count_q == 0){
echo '&nbsp;&nbsp;<img src="cross.png" width="30" height="30" class="mid">&nbsp;&nbsp;';}
if($count_q > 0){
if($nr_run == 0){
echo '&nbsp;&nbsp;<a href="t_practice.php?topic_id='.$topic_id.'"><img src="start.png" width="30" height="30" class="mid" title="Start"></b>&nbsp;&nbsp;';}
if($nr_run > 0 && $nr_run < $count_q){
echo '&nbsp;&nbsp;<a href="t_practice.php?topic_id='.$topic_id.'"><img src="start.png" width="30" height="30" class="mid" title="Continue"></a>&nbsp;&nbsp;';
if($user_teacher == 0 || 1 == 1){
?>
&nbsp;&nbsp;<a href="reset_topic.php?topic_id=<?php echo $topic_id; ?>&s=<?php echo $s; ?>" onclick="return confirm('Are you sure you want to delete your answers?')"><img src="reset.png" width="30" height="30" class="mid" title="Reset"></a>&nbsp;&nbsp;
<?php
}}
if($nr_run == $count_q){
echo '&nbsp;&nbsp;<a href="t_practice.php?topic_id='.$topic_id.'"><img src="mg.png" width="30" height="30" class="mid" title="Report"></a>&nbsp;&nbsp;';
if($user_teacher == 0 || 1 == 1){
?>
&nbsp;&nbsp;<a href="reset_topic.php?topic_id=<?php echo $topic_id; ?>&s=<?php echo $s; ?>" onclick="return confirm('Are you sure you want to delete your answers?')"><img src="reset.png" width="30" height="30" class="mid" title="Reset"></a>&nbsp;&nbsp;
<?php
}}}}}
echo '</p></td></tr>';
$color = 2;}
else {
echo '<tr><td height="40" bgcolor="#a0b595"><p>'.$data2['topic_module'].'</p></td>
<td height="40" bgcolor="#a0b595"><p><b>'.$count_q.' MCQs</b></p></td>
<td height="40" bgcolor="#a0b595"><p>';
if(($user_active == 1 && $nr_perm > 0) || ($user_active == 0 && ($topic_hidden == 1 || $nr_perm > 0))){
echo '<b style="color: #820000">LOCKED</b>';}
if(($user_active == 1 && $nr_perm == 0) || ($user_active == 0 && $topic_hidden == 0 && $nr_perm == 0)){
if($count_q == 0){
echo '<b style="color: #820000">NO MCQ AVAILABLE</b>';}
if($count_q > 0){
if($nr_run == 0){
echo '<b>NOT STARTED YET</b>';}
if($nr_run > 0 && $nr_run < $count_q){
echo '<b style = "color: #0E2366">IN PROGRESS</b><br><b>('.round($nr_run / $count_q * 100, 0).'%)</b>';}
if($nr_run == $count_q){
echo '<b style="color: #033909">FINISHED<br>'.$answer_date.'</b><br><b>'.$score.' / '.$count_q.' ('.$percent.'% - '.$letter.')</b>';}}}
echo '</p></td>
<td height="40" bgcolor="#a0b595" class="mid"><p>';
if($nr_perm > 0){
echo '&nbsp;&nbsp;<img src="cross.png" width="30" height="30" class="mid">&nbsp;&nbsp;';}
if($nr_perm == 0){
if($user_active == 0 && $topic_hidden == 1){
echo '&nbsp;&nbsp;<a href="upgrade.php"><img src="unlock.png" width="30" width="30" class="mid" title="Unlock"></a>&nbsp;&nbsp;';}
if($user_active == 1 || ($user_active == 0 && $topic_hidden == 0)){
if($count_q == 0){
echo '&nbsp;&nbsp;<img src="cross.png" width="30" height="30" class="mid">&nbsp;&nbsp;';}
if($count_q > 0){
if($nr_run == 0){
echo '&nbsp;&nbsp;<a href="t_practice.php?topic_id='.$topic_id.'"><img src="start.png" width="30" height="30" class="mid" title="Start"></b>&nbsp;&nbsp;';}
if($nr_run > 0 && $nr_run < $count_q){
echo '&nbsp;&nbsp;<a href="t_practice.php?topic_id='.$topic_id.'"><img src="start.png" width="30" height="30" class="mid" title="Continue"></a>&nbsp;&nbsp;';
if($user_teacher == 0 || 1 == 1){
?>
&nbsp;&nbsp;<a href="reset_topic.php?topic_id=<?php echo $topic_id; ?>&s=<?php echo $s; ?>" onclick="return confirm('Are you sure you want to delete your answers?')"><img src="reset.png" width="30" height="30" class="mid" title="Reset"></a>&nbsp;&nbsp;
<?php
}}
if($nr_run == $count_q){
echo '&nbsp;&nbsp;<a href="t_practice.php?topic_id='.$topic_id.'"><img src="mg.png" width="30" height="30" class="mid" title="Report"></a>&nbsp;&nbsp;';
if($user_teacher == 0 || 1 == 1){
?>
&nbsp;&nbsp;<a href="reset_topic.php?topic_id=<?php echo $topic_id; ?>&s=<?php echo $s; ?>" onclick="return confirm('Are you sure you want to delete your answers?')"><img src="reset.png" width="30" height="30" class="mid" title="Reset"></a>&nbsp;&nbsp;
<?php
}}}}}
echo '</p></td></tr>';
$color = 1;}
$previous = $current;}
echo'</tbody></table>';}
?>
<p><a href="#top"><b>Back to the top</b></p>
</body></html>