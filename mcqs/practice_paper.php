<?php
$s = $_GET['s'];
if(empty($s)){
echo 'Missing information about the course.';
exit();}
if($s == '1'){
$s_text = 'IGCSE';}
if($s == '2'){
$s_text = 'AS LEVEL';}
if($s == '3'){
$s_text = 'A LEVEL';}
date_default_timezone_set('UTC');
$now = date("Y-m-d H:i:s");
$search_year = $_POST['paper_year'];
$search_serie = $_POST['paper_serie'];
$search_version = $_POST['paper_version'];
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
if($user_type == 2){
echo 'What are you doing here? This page is for students only.';
exit();}
if(empty($search_year) && empty($search_serie) && empty($search_version)){
$sql2 = "SELECT * FROM phoenix_papers WHERE paper_syllabus = '".$s."' ORDER BY paper_year DESC, paper_serie DESC, paper_version ASC";}
if(isset($search_year) && isset($search_serie) && isset($search_version)){
$sql2 = "SELECT * FROM phoenix_papers WHERE paper_syllabus = '".$s."' AND paper_year = (CASE WHEN '".$search_year."' != 'Any' THEN '".$search_year."' ELSE paper_year END) AND paper_serie = (CASE WHEN '".$search_serie."' != 'Any' THEN '".$search_serie."' ELSE paper_serie END) AND paper_version = (CASE WHEN '".$search_version."' != 'Any' THEN '".$search_version."' ELSE paper_version END) ORDER BY paper_year DESC, paper_serie DESC, paper_version ASC";}
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
function submitForm(id){
if(id == 'year'){
document.getElementById('paper_serie').value = 'Any';
document.getElementById('paper_version').value = 'Any';
document.getElementById('form1').submit();}
if(id == 'serie'){
document.getElementById('paper_version').value = 'Any';
document.getElementById('form1').submit();}};
</script></head>
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
<p style="font-size: x-large"><b><?php echo $s_text; ?> PAPERS</b></p>
<form method="post" id="form1" action="practice_paper.php?s=<?php echo $s;?>">
<p><select name="paper_year" id="paper_year" onChange="submitForm('year');">
<option value="Any">All years</option>
<?php
$sql_y = "SELECT DISTINCT(paper_year) FROM phoenix_papers WHERE paper_syllabus = '".$s."' ORDER BY paper_year DESC";
$res_y = $mysqli -> query($sql_y);
while($data_y = mysqli_fetch_assoc($res_y)){
?>
<option value="<?php echo $data_y["paper_year"]; ?>"  <?php echo is_selected($data_y["paper_year"], $search_year); ?>><?php echo $data_y["paper_year"]; ?></option>
<?php
}
?>
</select>
&nbsp;&nbsp;<select name="paper_serie" id="paper_serie" onChange="submitForm('serie');">
<?php
if(empty($search_serie)){
echo '<option value="Any">All series</option>';}
if(isset($search_serie) && isset($search_year)){
$sql_s = "SELECT DISTINCT(paper_serie) FROM phoenix_papers WHERE paper_syllabus = '".$s."' AND paper_year = '".$search_year."' ORDER BY paper_serie ASC";
$res_s = $mysqli->query($sql_s);
echo '<option value="Any">All series</option>';
while($data_s = mysqli_fetch_assoc($res_s)){
$paper_serie = $data_s['paper_serie'];
if($paper_serie == 1){
$paper_serie_text = "m - February / March";}
if($paper_serie == 2){
$paper_serie_text = "s - May / June";}
if($paper_serie == 3){
$paper_serie_text = "w - October / November";}
if($paper_serie == 4){
$paper_serie_text = "y - Specimen";}
?>
<option value="<?php echo $paper_serie; ?>" <?php echo is_selected($paper_serie, $search_serie); ?>><?php echo $paper_serie_text; ?></option>';
<?php
}}
?>
</select>
&nbsp;&nbsp;<select name="paper_version" id="paper_version" onchange="this.form.submit();">
<?php
if(empty($search_version)){
echo '<option value="Any">All versions</option>';}
if(isset($search_serie) && isset($search_year) && isset($search_version)){
$sql_v = "SELECT * FROM phoenix_papers WHERE paper_serie = '".$search_serie."' AND paper_year ='".$search_year."' AND paper_syllabus ='".$s."'";
$res_v = $mysqli -> query($sql_v);
echo '<option value="Any">All versions</option>';
while($data_v = mysqli_fetch_assoc($res_v)){
$paper_version = $data_v['paper_version'];
if($paper_version > 0){
$paper_version_text = $paper_version;}
if($paper_version == 0){
$paper_version_text = "/";}
?>
<option value="<?php echo $paper_version; ?>" <?php echo is_selected($paper_version, $search_version); ?>><?php echo $paper_version_text; ?></option>';
<?php
}}
?>
</select></form>
<?php
if($nr_2 == 0){
echo '<p><em>No papers found</em></p>';}
if($nr_2 > 0){
echo '<p><b><span style="color: #820000">'.$nr_2.'</span> paper(s) found</b></p>
<table width = "80%" align="center" bgcolor="#000000"><tbody><tr>
<td height="40" bgcolor="#647d57"><p><b>YEAR</b></p></td>
<td height="40" bgcolor="#647d57"><p><b>SERIE</b></p></td>
<td height="40" bgcolor="#647d57"><p><b>VERSION</b></p></td>
<td height="40" bgcolor="#647d57"><p><b>STATUS</b></p></td>
<td height="40" bgcolor="#647d57"><p><b>ACTIONS</b></p></td>
</tr>';
$color = 1;
while($data2 = mysqli_fetch_assoc($res2)){
$paper_id = $data2['paper_id'];
$paper_ref = $data2['paper_id'];
$paper_hidden = $data2['paper_hidden'];
$paper_serie = $data2['paper_serie'];
if($paper_serie == 1){
$paper_serie_text = "m - February / March";}
if($paper_serie == 2){
$paper_serie_text = "s - May / June";}
if($paper_serie == 3){
$paper_serie_text = "w - October / November";}
if($paper_serie == 4){
$paper_serie_text = "y - Specimen";}
$paper_version = $data2['paper_version'];
if($paper_version == 0){
$paper_version = "/";}
$sql_count = "SELECT COUNT(*) FROM phoenix_questions WHERE question_paper_id = '".$paper_ref."' AND question_answer <> '0'";
$res_count = $mysqli -> query($sql_count);
$data_count = mysqli_fetch_assoc($res_count);
$num_q = $data_count['COUNT(*)'];
$sql_perm = "SELECT COUNT(*) FROM phoenix_permissions_papers INNER JOIN phoenix_permissions_users ON phoenix_permissions_users.permission_id = phoenix_permissions_papers.permission_id WHERE phoenix_permissions_papers.paper_id = '".$paper_ref."' AND phoenix_permissions_users.student_id = '".$user_id."'";
$res_perm = $mysqli -> query($sql_perm);
$data_perm = mysqli_fetch_assoc($res_perm);
$nr_perm = $data_perm['COUNT(*)'];
$sql_run = "SELECT answer_valid, answer_date FROM phoenix_answers WHERE paper_id = '".$paper_ref."' AND user_id = '".$user_id."'";
$res_run = $mysqli -> query($sql_run);
$nr_run = mysqli_num_rows($res_run);
if($nr_run == $num_q){
$correct = 0;
$ans_date = '1970-01-01 00:00:00';
while($data_run = mysqli_fetch_assoc($res_run)){
if($data_run['answer_valid'] == 1){
$correct++;}
if($data_run['answer_date'] > $ans_date){
$ans_date = $data_run['answer_date'];}}
$score = $correct;
$answer_date = date('Y-m-d', strtotime($ans_date));
$percent = round($score / $num_q * 100,0);
$min_a = $data2['paper_a'];
$min_b = $data2['paper_b'];
$min_c = $data2['paper_c'];
$min_d = $data2['paper_d'];
$min_e = $data2['paper_e'];
if($s == "1"){
$min_f = $data2['paper_f'];
$min_g = $data2['paper_g'];}
if($score >= $min_a){
$letter = "A";}
if($score < $min_a && $score >= $min_b){
$letter = "B";}
if($score < $min_b && $score >= $min_c){
$letter = "C";}
if($score < $min_c && $score >= $min_d){
$letter = "D";}
if($score < $min_d && $score >= $min_e){
$letter = "E";}
if($s != '1' && $score < $min_e){
$letter = "U";}
if($s == '1' && $score < $min_e && $score >= $min_f){
$letter = "F";}
if($s == '1' && $score < $min_f && $score >= $min_g){
$letter = "G";}
if($s == '1' && $score < $min_g){
$letter = "U";}}
if($color == 1){
echo '<tr>
<td height="40" bgcolor="#769467"><p>'.$data2['paper_year'].'</p></td>
<td height="40" bgcolor="#769467"><p>'.$paper_serie_text.'</p></td>
<td height="40" bgcolor="#769467"><p>'.$paper_version.'</p></td>
<td height="40" bgcolor="#769467"><p>';
if(($user_active == 1 && $nr_perm > 0) || ($user_active == 0 && ($paper_hidden == 1 || $nr_perm > 0))){
echo '<b style="color: #820000">LOCKED</b>';}
if(($user_active == 1 && $nr_perm == 0) || ($user_active == 0 && $paper_hidden == 0 && $nr_perm == 0)){
if($nr_run == 0){
echo '<b>NOT STARTED YET</b>';}
if($nr_run > 0 && $nr_run < $num_q){
echo '<b style = "color: #0E2366">IN PROGRESS</b><br><b>('.round($nr_run / $num_q * 100, 0).'%)</b>';}
if($nr_run == $num_q){
echo '<b style="color: #033909">FINISHED<br>'.$answer_date.'</b><br><b>'.$score.' / '.$num_q.' ('.$percent.'% - '.$letter.')</b>';}}
echo '</p></td>
<td height="40" bgcolor="#769467" class="mid"><p>';
if($nr_perm > 0){
echo '<img src="cross.png" width="30" width="30" class="mid">';}
if($nr_perm == 0){
if($user_active == 0 && $paper_hidden == 1){
echo '<a href="upgrade.php"><img src="unlock.png" width="30" width="30" class="mid" title="Unlock"></a>';}
if($user_active == 1 || ($user_active == 0 && $paper_hidden == 0)){
if($nr_run == 0){
echo '<a href="qp_practice.php?paper_id='.$paper_id.'"><img src="start.png" width="30" height="30" class="mid" title="Start"></a>
&nbsp;&nbsp;<a href="gt_view.php?paper_id='.$paper_id.'&s='.$s.'"><img src="gt.png" width="30" height="30" class="mid" title="Grade Thresholds"></a>';}
if($nr_run > 0 && $nr_run < $num_q){
echo '<a href="qp_practice.php?paper_id='.$paper_id.'"><img src="start.png" width="30" height="30" class="mid" title="Continue"></a>
&nbsp;&nbsp;<a href="gt_view.php?paper_id='.$paper_id.'&s='.$s.'"><img src="gt.png" width="30" height="30" class="mid" title="Grade Thresholds"></a>';
if($user_teacher == 0 || 1 == 1){
?>
&nbsp;&nbsp;<a href="reset_paper.php?paper_id=<?php echo $paper_ref; ?>&s=<?php echo $s; ?>" onclick="return confirm('Are you sure you want to delete your answers?')"><img src="reset.png" width="30" height="30" class="mid" title="Reset"></a>
<?php
}}
if($nr_run == $num_q){
echo '<a href="qp_practice.php?paper_id='.$paper_id.'"><img src="mg.png" width="30" height="30" class="mid" title="Report"></a>
&nbsp;&nbsp;<a href="gt_view.php?paper_id='.$paper_id.'&s='.$s.'"><img src="gt.png" width="30" height="30" class="mid" title="Grade Thresholds"></a></b>';
if($user_teacher == 0 || 1 == 1){
?>
&nbsp;&nbsp;<a href="reset_paper.php?paper_id=<?php echo $paper_ref; ?>&s=<?php echo $s; ?>" onclick="return confirm('Are you sure you want to delete your answers?')"><img src="reset.png" width="30" height="30" class="mid" title="Reset"></a>
<?php
}}}}
echo '</p></td></tr>';
$color = 2;}
else {
echo '<tr>
<td height="40" bgcolor="#a0b595"><p>'.$data2['paper_year'].'</p></td>
<td height="40" bgcolor="#a0b595"><p>'.$paper_serie_text.'</p></td>
<td height="40" bgcolor="#a0b595"><p>'.$paper_version.'</p></td>
<td height="40" bgcolor="#a0b595"><p>';
if(($user_active == 1 && $nr_perm > 0) || ($user_active == 0 && ($paper_hidden == 1 || $nr_perm > 0))){
echo '<b style="color: #820000">LOCKED</b>';}
if(($user_active == 1 && $nr_perm == 0) || ($user_active == 0 && $paper_hidden == 0 && $nr_perm == 0)){
if($nr_run == 0){
echo '<b>NOT STARTED YET</b>';}
if($nr_run > 0 && $nr_run < $num_q){
echo '<b style = "color: #0E2366">IN PROGRESS</b><br><b>('.round($nr_run / $num_q * 100, 0).'%)</b>';}
if($nr_run == $num_q){
echo '<b style="color: #033909">FINISHED<br>'.$answer_date.'</b><br><b>'.$score.' / '.$num_q.' ('.$percent.'% - '.$letter.')</b>';}}
echo '</p></td>
<td height="40" bgcolor="#a0b595" class="mid"><p>';
if($nr_perm > 0){
echo '<img src="cross.png" width="30" width="30" class="mid">';}
if($nr_perm == 0){
if($user_active == 0 && $paper_hidden == 1){
echo '<a href="upgrade.php"><img src="unlock.png" width="30" width="30" class="mid" title="Unlock"></a>';}
if($user_active == 1 || ($user_active == 0 && $paper_hidden == 0)){
if($nr_run == 0){
echo '<a href="qp_practice.php?paper_id='.$paper_id.'"><img src="start.png" width="30" height="30" class="mid" title="Start"></a>
&nbsp;&nbsp;<a href="gt_view.php?paper_id='.$paper_id.'&s='.$s.'"><img src="gt.png" width="30" height="30" class="mid" title="Grade Thresholds"></a>';}
if($nr_run > 0 && $nr_run < $num_q){
echo '<a href="qp_practice.php?paper_id='.$paper_id.'"><img src="start.png" width="30" height="30" class="mid" title="Continue"></a>
&nbsp;&nbsp;<a href="gt_view.php?paper_id='.$paper_id.'&s='.$s.'"><img src="gt.png" width="30" height="30" class="mid" title="Grade Thresholds"></a>';
if($user_teacher == 0 || 1 == 1){
?>
&nbsp;&nbsp;<a href="reset_paper.php?paper_id=<?php echo $paper_ref; ?>&s=<?php echo $s; ?>" onclick="return confirm('Are you sure you want to delete your answers?')"><img src="reset.png" width="30" height="30" class="mid" title="Reset"></a>
<?php
}}
if($nr_run == $num_q){
echo '<a href="qp_practice.php?paper_id='.$paper_id.'"><img src="mg.png" width="30" height="30" class="mid" title="Report"></a>
&nbsp;&nbsp;<a href="gt_view.php?paper_id='.$paper_id.'&s='.$s.'"><img src="gt.png" width="30" height="30" class="mid" title="Grade Thresholds"></a></b>';
if($user_teacher == 0 || 1 == 1){
?>
&nbsp;&nbsp;<a href="reset_paper.php?paper_id=<?php echo $paper_ref; ?>&s=<?php echo $s; ?>" onclick="return confirm('Are you sure you want to delete your answers?')"><img src="reset.png" width="30" height="30" class="mid" title="Reset"></a>
<?php
}}}}
echo '</p></td></tr>';
$color = 1;}}
echo'</tbody></table>';}
?>
<p><a href="#top"><b>Back to the top</b></a></p>
</body></html>