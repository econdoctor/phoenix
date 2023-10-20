<?php
$assign_id = $_GET['assign_id'];
$assign_key = $_GET['k'];
if(empty($assign_id) || empty($assign_key)){
echo 'Missing information';
exit();}
$answers = $_GET['answers'];
$ref = $_GET['ref'];
$topic = $_GET['topic'];
$ta = $_GET['ta'];
$ddl = $_GET['ddl'];
$student_name = $_GET['name'];
$student_date = $_GET['date'];
$student_score = $_GET['score'];
if(empty($ref) || empty($answers) || empty($topic)){
header("Location: assign_download.php?assign_id=$assign_id&error=1&answers=$answers&ref=$ref&topic=$topic&ta=$ta&ddl=$ddl&sn=$student_name&sd=$student_date&ss=$student_score");
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql_info = "SELECT assign_name, assign_syllabus, assign_teacher, assign_deadline, assign_time_allowed FROM phoenix_assign WHERE assign_id = '".$assign_id."' AND assign_key = '".$assign_key."'";
$res_info = $mysqli -> query($sql_info);
$nr_info = mysqli_num_rows($res_info);
if($nr_info == 0){
echo 'Wrong assignment info';
exit();}
$data_info = mysqli_fetch_assoc($res_info);
$assign_name = $data_info['assign_name'];
$assign_syllabus = $data_info['assign_syllabus'];
if($assign_syllabus == '3'){
$s_text = "A Level";}
if($assign_syllabus == '2'){
$s_text = "AS Level";}
if($assign_syllabus == '1'){
$s_text = "IGCSE";}
$assign_teacher = $data_info['assign_teacher'];
$sql_tz = "SELECT user_timezone FROM phoenix_users WHERE user_id = '".$assign_teacher."'";
$res_tz = $mysqli -> query($sql_tz);
$data_tz = mysqli_fetch_assoc($res_tz);
$tz = $data_tz['user_timezone'];
$assign_deadline = $data_info['assign_deadline'];
if($tz >= 0){
$assign_deadline_display = date('Y-m-d H:i:s', strtotime(''.$assign_deadline.' + '.$tz.' minutes'));}
if($tz < 0){
$assign_deadline_display = date('Y-m-d H:i:s', strtotime(''.$assign_deadline.' - '.abs($tz).' minutes'));}
$assign_time_allowed = $data_info['assign_time_allowed'];
$title = $s_text.' Economics - '.$assign_name;
$sql_main = "SELECT question_paper_id, question_syllabus, topic_unit, topic_module, question_number, question_answer FROM phoenix_assign_questions INNER JOIN phoenix_questions ON phoenix_assign_questions.question_id = phoenix_questions.question_id INNER JOIN phoenix_topics ON phoenix_questions.question_topic_id = phoenix_topics.topic_id WHERE assign_id = '".$assign_id."' ORDER BY assign_question_number ASC";
$res_main = $mysqli -> query($sql_main);
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
background-color: #FFFFFF;
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
width: 40%;
border-radius: 4px;
}
table, tr, td, th, tbody, thead, tfoot {
page-break-inside: avoid !important;
}
</style>
<script>
function hideBody(){
document.getElementById('main').style.display = 'none';
document.getElementById('confirm').style.display = 'block';
document.body.style.background = '#8BA57E';
}
</script>
</head>
<body onload="hideBody();"><br>
<div id="confirm" style="display:none">
<table width="80%" bgcolor="#6a855c" align="center" style="border: solid black 4px; border-radius:24px;"><tbody><tr width="95%">
<td width="20%"><a href="main.php"><img src="home_phoenix.png" width="150" height="150"></a></td>
<td width="80%"><p style="font-size: xx-large;"><b>PHOENIX</b></p>
<p>
<input type="button" name="browse" value="BROWSE" style="font-size: x-large; width: 30%; background-color: #ffc000;  color: #3b2c00; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='browse.php';"/>&nbsp;&nbsp;
<input type="button" name="manage" value="MANAGE" style="font-size: x-large; width: 30%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='manage.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 30%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='assign.php';"/>
</td></tr></tbody></table>
<p style="font-size: x-large;"><b><?php echo strtoupper($assign_name); ?></b></p>
<p>&nbsp;</p>
<p style="font-size: large; color: #033909"><b>Your PDF is ready</b></p>
<p>
<a style="color: #820000; font-size: xx-large;"  href="//pdfcrowd.com/url_to_pdf/?width=210mm&height=297mm&footer_text=page%20$p%20/%20$n&pdf_name=<?php echo $title; ?>&no_javascript=1" onclick="if(!this.p)href+='&url='+encodeURIComponent(location.href);this.p=1">
<input type="button" name="download" style="width: 20%" value="DOWNLOAD">
</a></p></div>
<div id="main">
<p class="pdfcrowd-remove">&nbsp;</p><p class="pdfcrowd-remove">&nbsp;</p><p class="pdfcrowd-remove">&nbsp;</p>
<p class="pdfcrowd-remove" style="font-size: xx-large;"><b>YOUR PDF IS LOADING...</b></p>
<div class="pdfcrowd-remove" style="height:2000px;">&nbsp;</div>
<?php
if($student_name == 'name_yes' || $student_date == 'date_yes' || $student_score == 'score_yes'){
echo '<p align="left" style="font-size: small">';
if($student_name == "name_yes"){
echo '<b>NAME: __________________________</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';}
if($student_date == "date_yes"){
echo '<b>DATE: ____________________</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';}
if($student_score == "score_yes"){
echo '<b>SCORE: _________________</b>';}
echo '</p><br>';
}
?>
<p align="center" style="font-size: small"><b><?php echo strtoupper($s_text); ?> ECONOMICS</b></p>
<p align="center" style="font-size: small"><b><?php echo strtoupper($assign_name); ?></b></p>
<?php
if($ddl == 'ddl_yes'){
echo '<p align="center" style="font-size: small"><b>DEADLINE:</b> '.$assign_deadline_display.'</p>';}
if($ta == 'ta_yes'){
echo '<p align="center" style="font-size: small"><b>TIME ALLOWED:</b> '.$assign_time_allowed.' minutes</p>';}
$i = 1;
while($data_main = mysqli_fetch_assoc($res_main)){
echo '<table bgcolor="#000000" width="90%" align="center">
<tbody><tr>
<td bgcolor="#CBCBCB" width="5%" style="font-size: small;"><b>'.$i.'</b></td>
<td bgcolor="#FFFFFF"><img src="q/'.$data_main['question_syllabus'].'/'.$data_main['question_paper_id'].'/'.$data_main['question_number'].'.png" width="95%"></td>
</tr>';
if($ref == '1' || $answers == '1' || $topic == '1'){
echo '<tr><td bgcolor="#FFFFFF" colspan = "2">
<table width="95%" align="center" bgcolor="#FFFFFF"><tbody><tr><td style="text-align: left; font-size: small;">';

if($ref == '1'){
$question_paper_id = $data_main['question_paper_id'];
if(substr($question_paper_id, 3, 1) == '1'){
$serie = 'm - February / March';}
if(substr($question_paper_id, 3, 1) == '2'){
$serie = 's - May / June';}
if(substr($question_paper_id, 3, 1) == '3'){
$serie = 'w - October / November';}
if(substr($question_paper_id, 3, 1) == '4'){
$serie = 'y - Specimen';}
$year = '20'.substr($question_paper_id, 1, 2);
$version = substr($question_paper_id, -1, 1);
if($version == 0){
$version = "/";}
if($data_main['question_syllabus'] != '3'){
$paper = 'P1';}
if($data_main['question_syllabus'] == '3'){
$paper = 'P3';}
$paper_info = $paper.' | '.$year.' | '.$serie.' | Version '.$version.' | Question '.$data_main['question_number'];
echo '<b>REFERENCE:</b> '.$paper_info.'<br>';}
if($topic == '1'){
echo '<b>CLASSIFICATION:</b> '.$data_main['topic_unit'].' | '.$data_main['topic_module'].'<br>';}
if($answers == '1'){
if($data_main['question_answer'] == 1){
$answer = 'A';}
if($data_main['question_answer'] == 2){
$answer = 'B';}
if($data_main['question_answer'] == 3){
$answer = 'C';}
if($data_main['question_answer'] == 4){
$answer = 'D';}
echo '<b>ANSWER:</b> '.$answer.'';}
echo '</td></tr></tbody></table></td></tr>';}
echo '</tbody></table><br>';
$i++;}
?>
</div></body></html>