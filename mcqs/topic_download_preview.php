<?php
$topic_id = $_GET['topic_id'];
$topic_key = $_GET['k'];
if(empty($topic_id) ||empty($topic_key)){
echo 'Missing information';
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql_x = "SELECT * FROM phoenix_topics WHERE topic_id = '".$topic_id."' AND topic_key = '".$topic_key."'";
$res_x = $mysqli -> query($sql_x);
$nr_x = mysqli_num_rows($res_x);
if($nr_x == 0){
echo 'Topic info is incorrect';
exit();}
$data_x = mysqli_fetch_assoc($res_x);
$s = $data_x['topic_syllabus'];
$unit = $data_x['topic_unit'];
$module = $data_x['topic_module'];
if($s == '3'){
$s_text = "A Level";}
if($s == '2'){
$s_text = "AS Level";}
if($s == '1'){
$s_text = "IGCSE";}
$all = $_GET['all'];
$number = $_GET['number'];
$order = $_GET['order'];
$answers = $_GET['answers'];
$ref = $_GET['ref'];
$student_name = $_GET['name'];
$student_date = $_GET['date'];
$student_score = $_GET['score'];
if(empty($all) || empty($ref) || empty($answers) || empty($order) || $order == ""){
header("Location: topic_download.php?topic_id=$topic_id&error=1&all=$all&number=$number&order=$order&answers=$answers&ref=$ref&sn=$student_name&sd=$student_date&ss=$student_score");
exit();}
if($all == '2' && empty($number)){
header("Location: topic_download.php?topic_id=$topic_id&error=2&all=$all&number=$number&order=$order&answers=$answers&ref=$ref&sn=$student_name&sd=$student_date&ss=$student_score");
exit();}
if($all == '2' && $number == 0 && isset($number)){
header("Location: topic_download.php?topic_id=$topic_id&error=3&all=$all&number=$number&order=$order&answers=$answers&ref=$ref&sn=$student_name&sd=$student_date&ss=$student_score");
exit();}
if($all == '1' && $number != ''){
header("Location: topic_download.php?topic_id=$topic_id&error=4&all=$all&number=$number&order=$order&answers=$answers&ref=$ref&sn=$student_name&sd=$student_date&ss=$student_score");
exit();}
if($all == '2' && !is_numeric($number) && isset($number)){
header("Location: topic_download.php?topic_id=$topic_id&error=5&all=$all&number=$number&order=$order&answers=$answers&ref=$ref&sn=$student_name&sd=$student_date&ss=$student_score");
exit();}
if($order == '7'){
$sql_main = "SELECT * FROM phoenix_questions WHERE question_topic_id = '".$topic_id."' AND question_repeat = '0' AND question_obsolete = '0' ORDER BY question_random";}
if($order == '3'){
$sql_main = "SELECT * FROM phoenix_questions WHERE question_topic_id = '".$topic_id."' AND question_repeat = '0' AND question_obsolete = '0' ORDER BY question_serie DESC";}
if($order == '4'){
$sql_main = "SELECT * FROM phoenix_questions WHERE question_topic_id = '".$topic_id."' AND question_repeat = '0' AND question_obsolete = '0' ORDER BY question_serie ASC";}
if($order == '5'){
$sql_main = "SELECT * FROM phoenix_questions WHERE question_topic_id = '".$topic_id."' AND question_repeat = '0' AND question_obsolete = '0' ORDER BY question_rate DESC";}
if($order == '6'){
$sql_main = "SELECT * FROM phoenix_questions WHERE question_topic_id = '".$topic_id."' AND question_repeat = '0' AND question_obsolete = '0' ORDER BY -question_rate DESC";}
$res_main = $mysqli -> query($sql_main);
$count = mysqli_num_rows($res_main);
if($number > $count){
header("Location: topic_download.php?topic_id=$topic_id&error=6&all=$all&number=$number&order=$order&answers=$answers&ref=$ref&sn=$student_name&sd=$student_date&ss=$student_score");
exit();}
if($all == '1'){
$number = $count;}
$title = $s_text.' Economics - '.$module;
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
document.body.style.background = '#8BA57E';}
</script>
</head>
<body onload="hideBody();">
<div id="confirm" style="display:none">
<table width="80%" bgcolor="#6a855c" align="center" style="border: solid black 4px; border-radius:24px;"><tbody><tr width="95%">
<td width="20%"><a href="main.php"><img src="home_phoenix.png" width="150" height="150"></a></td>
<td width="80%"><p style="font-size: xx-large;"><b>PHOENIX</b></p>
<p>
<input type="button" name="browse" value="BROWSE" style="font-size: x-large; width: 30%; background-color: #ffc000;  color: #3b2c00; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='browse.php';"/>&nbsp;&nbsp;
<input type="button" name="manage" value="MANAGE" style="font-size: x-large; width: 30%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='manage.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 30%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='assign.php';"/>
</td></tr></tbody></table>
<p><input type="button" name="title" style="width: 20%" value="<?php echo strtoupper($s_text); ?> TOPICS" onclick="document.location.href='browse_topic.php?s=<?php echo $s; ?>';"/>
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
<p align="center" style="font-size: small"><b><?php echo strtoupper($s_text); ?> ECONOMICS </b></p>
<p align="center" style="font-size: small"><b><?php echo strtoupper($unit); ?></b></p>
<p align="center" style="font-size: small"><b> <?php echo strtoupper($module); ?></b></p>
</p>
<?php
$i = "1";
while($row = mysqli_fetch_assoc($res_main)){
echo '<table bgcolor="#000000" width="90%" align="center">
<tbody><tr>
<td bgcolor="#CBCBCB" width="5%" style="font-size: small;"><b>'.$i.'</b></td>
<td bgcolor="#FFFFFF"><img src="q/'.$row['question_syllabus'].'/'.$row['question_paper_id'].'/'.$row['question_number'].'.png" width="95%"></td>
</tr>';
if($ref == '1' || $answers == '1'){
echo '<tr><td bgcolor="#FFFFFF" colspan = "2">
<table width="95%" align="center" bgcolor="#FFFFFF"><tbody><tr><td style="text-align: left; font-size: small;">';
if($ref == '1'){
$question_paper = $row['question_paper_id'];
if(substr($question_paper, 3, 1) == '1'){
$serie = 'm - February / March';}
if(substr($question_paper, 3, 1) == '2'){
$serie = 's - May / June';}
if(substr($question_paper, 3, 1) == '3'){
$serie = 'w - October / November';}
if(substr($question_paper, 3, 1) == '4'){
$serie = 'y - Specimen';}
$year = '20'.substr($question_paper, 1, 2);
$version = substr($question_paper, -1, 1);
if($version == 0){
$version = "/";}
if($row['question_syllabus'] != '3'){
$paper = 'P1';}
if($row['question_syllabus'] == '3'){
$paper = 'P3';}
$paper_info = $paper.' | '.$year.' | '.$serie.' | Version '.$version.' | Question '.$row['question_number'];
echo '<b>REFERENCE:</b> '.$paper_info.'<br>';}
if($row['question_answer'] == 1){
$answer = 'A';}
if($row['question_answer'] == 2){
$answer = 'B';}
if($row['question_answer'] == 3){
$answer = 'C';}
if($row['question_answer'] == 4){
$answer = 'D';}
if($answers == '1'){
echo '<b>ANSWER:</b> '.$answer.'';}
echo '</td></tr></tbody></table></td></tr>';}
echo '</tbody></table><br>';
if($i == $number){
break;}
$i++;}
?>
</div></body></html>