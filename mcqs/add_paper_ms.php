<?php
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
$sql = "SELECT user_title, user_first_name, user_last_name, user_name FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_id != 1){
echo 'Access restricted';
exit();}
$paper_reference = $_GET['paper_reference'];
if(empty($paper_reference)){
echo 'Missing information about the paper';
exit();}
$sql_info = "SELECT * FROM phoenix_papers WHERE paper_reference = '".$paper_reference."'";
$res_info = $mysqli -> query($sql_info);
$num_rows_info = mysqli_num_rows($res_info);
if($num_rows_info == 0){
header("Location: add_paper.php");
exit();}
$data_info = mysqli_fetch_assoc($res_info);
$paper_id = $data_info['paper_id'];
$paper_syllabus = $data_info['paper_syllabus'];
$paper_year = $data_info['paper_year'];
$paper_a = $data_info['paper_a'];
$paper_b = $data_info['paper_b'];
$paper_c = $data_info['paper_c'];
$paper_d = $data_info['paper_d'];
$paper_e = $data_info['paper_e'];
$paper_f = $data_info['paper_f'];
$paper_g = $data_info['paper_g'];
if($paper_syllabus != 1 && ($paper_a == '0' || $paper_b == '0' || $paper_c == '0' || $paper_d == '0' || $paper_e == '0')){
header("Location: add_paper_gt.php?paper_reference=$paper_reference");
exit();}
if($paper_syllabus == 1 && ($paper_a == '0' || $paper_b == '0' || $paper_c == '0' || $paper_d == '0' || $paper_e == '0' || $paper_f == '0' || $paper_g == '0')){
header("Location: add_paper_gt.php?paper_reference=$paper_reference");
exit();}
$sql_q = "SELECT * FROM phoenix_questions WHERE question_paper_id = '".$paper_id."'";
$res_q = $mysqli -> query($sql_q);
$num_rows_q = mysqli_num_rows($res_q);
if($num_rows_q == 0){
$serie = substr($paper_reference, 5, 3);
if($serie == 'm18'){
$paper_serie = '1';}
if($serie == 's18'){
$paper_serie = '2';}
if($serie == 'w18'){
$paper_serie = '3';}
if($serie == 'm19'){
$paper_serie = '4';}
if($serie == 's19'){
$paper_serie = '5';}
if($serie == 'w19'){
$paper_serie = '6';}
if($serie == 'm20'){
$paper_serie = '7';}
if($serie == 's20'){
$paper_serie = '8';}
if($serie == 'w20'){
$paper_serie = '9';}
if($serie == 'm21'){
$paper_serie = '10';}
if($serie == 's21'){
$paper_serie = '11';}
if($serie == 'w21'){
$paper_serie = '12';}
if($serie == 'm22'){
$paper_serie = '13';}
if($serie == 's22'){
$paper_serie = '14';}
if($serie == 'w22'){
$paper_serie = '15';}
if($serie == 'm23'){
$paper_serie = '16';}
if($serie == 's23'){
$paper_serie = '17';}
if($serie == 'w23'){
$paper_serie = '18';}
if($serie == 'y23'){
$paper_serie = '19';}
for($x = 1; $x <= 30; $x++){
$sql_insert = "INSERT INTO phoenix_questions (question_paper_id, question_number, question_syllabus, question_serie, question_random, question_answer) VALUES ('".$paper_id."', '".$x."', '".$paper_syllabus."', '".$paper_serie."', RAND()*(99999999-10000000)+10000000, '5')";
$res_insert - $mysqli -> query($sql_insert);}}
$link = $paper_syllabus . '/' . substr($paper_reference, 0, 9) . 'ms' . substr($paper_reference, 11, 3) . '.pdf';
$sql_value = "SELECT * FROM phoenix_questions WHERE question_paper_id = '".$paper_id."'";
$res_value = $mysqli -> query($sql_value);
$key = array();
$i = 1;
while($data_value = mysqli_fetch_assoc($res_value)){
$answer = $data_value['question_answer'];
if($answer == 0){
$answer = 'X';}
if($answer == 1){
$answer = 'A';}
if($answer == 2){
$answer = 'B';}
if($answer == 3){
$answer = 'C';}
if($answer == 4){
$answer = 'D';}
if($answer == 5){
$answer = '';}
$key[$i] = $answer;
$i++;}
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
width: 30%;
border-radius: 4px;
}
input[type=submit]{
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
input[type=text] {
padding: 3px 5px;
margin: 2px 0;
box-sizing: border-box;
text-align: center;
font-weight: bold;
font-size: 18px;
border: 2px solid black;
border-radius: 4px;
width: 50%;
}
</style>
</head>
<body><a name="top"></a>
<table align="center" width="90%"><tbody>
<tr>
<td>
<p style="text-align: right;"><img src="online.png" width="15">&nbsp;&nbsp;<b><a href="profile.php"><?php echo $user_title.' '.$user_first_name.' '.$user_last_name.' ('.$user_name.')'; ?></a> - <a href="logout.php">Log out</a></b></p>
</td>
</tr>
</tbody></table>
<table width="80%" bgcolor="#6a855c" align="center" style="border: solid black 4px; border-radius:24px;"><tbody><tr width="95%">
<td width="20%"><a href="main.php"><img src="home.png" width="150" height="150"></a></td>
<td width="80%"><p style="font-size: xx-large;"><b>PHOENIX</b></p>
<p>
<input type="button" name="browse" value="BROWSE" style="font-size: x-large; width: 30%; background-color: #ffc000;  color: #3b2c00; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='browse.php';"/>&nbsp;&nbsp;
<input type="button" name="manage" value="MANAGE" style="font-size: x-large; width: 30%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='manage.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 30%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='assign.php';"/>
</td></tr></tbody></table>
<p style="font-size: x-large"><b>MARK SCHEME</b></p>
<p><b>Paper reference:</b><br>
<b style="color: #033909"><?php echo $paper_reference; ?></b></p>
<p><input type="button" value="DOWNLOAD MARK SCHEME" onclick="window.open('./ms/<?php echo $link; ?>', '_blank');"></p>
<p><b style="color: #820000;">If a question was removed, just type "X".</b></p>
<form autocomplete="off" method="post" action="add_paper_insert3.php?paper_reference=<?php echo $paper_reference; ?>">
<table width="30%" align="center" bgcolor="#000000">
<tbody>
<tr>
<td height="40" width="50%" bgcolor="#6A865D"><p><b>QUESTION<br>NUMBER</b></p></td>
<td height="40" width="50%" bgcolor="#6A865D"><p><b>ANSWER</b></p></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">1</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="q1" maxlength="1" required="required" size="1" value="<?php echo $key[1]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#8EA782">2</td>
<td height="40" bgcolor="#8EA782"><input style="text-align: center" type="text" name="q2" maxlength="1" required="required" size="1" value="<?php echo $key[2]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">3</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="q3" maxlength="1" required="required" size="1" value="<?php echo $key[3]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#8EA782">4</td>
<td height="40" bgcolor="#8EA782"><input style="text-align: center" type="text" name="q4" maxlength="1" required="required" size="1" value="<?php echo $key[4]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">5</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="q5" maxlength="1" required="required" size="1" value="<?php echo $key[5]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#8EA782">6</td>
<td height="40" bgcolor="#8EA782"><input style="text-align: center" type="text" name="q6" maxlength="1" required="required" size="1" value="<?php echo $key[6]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">7</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="q7" maxlength="1" required="required" size="1" value="<?php echo $key[7]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#8EA782">8</td>
<td height="40" bgcolor="#8EA782"><input style="text-align: center" type="text" name="q8" maxlength="1" required="required" size="1" value="<?php echo $key[8]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">9</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="q9" maxlength="1" required="required" size="1" value="<?php echo $key[9]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#8EA782">10</td>
<td height="40" bgcolor="#8EA782"><input style="text-align: center" type="text" name="q10" maxlength="1" required="required" size="1" value="<?php echo $key[10]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">11</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="q11" maxlength="1" required="required" size="1" value="<?php echo $key[11]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#8EA782">12</td>
<td height="40" bgcolor="#8EA782"><input style="text-align: center" type="text" name="q12" maxlength="1" required="required" size="1" value="<?php echo $key[12]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">13</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="q13" maxlength="1" required="required" size="1" value="<?php echo $key[13]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#8EA782">14</td>
<td height="40" bgcolor="#8EA782"><input style="text-align: center" type="text" name="q14" maxlength="1" required="required" size="1" value="<?php echo $key[14]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">15</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="q15" maxlength="1" required="required" size="1" value="<?php echo $key[15]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#8EA782">16</td>
<td height="40" bgcolor="#8EA782"><input style="text-align: center" type="text" name="q16" maxlength="1" required="required" size="1" value="<?php echo $key[16]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">17</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="q17" maxlength="1" required="required" size="1" value="<?php echo $key[17]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#8EA782">18</td>
<td height="40" bgcolor="#8EA782"><input style="text-align: center" type="text" name="q18" maxlength="1" required="required" size="1" value="<?php echo $key[18]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">19</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="q19" maxlength="1" required="required" size="1" value="<?php echo $key[19]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#8EA782">20</td>
<td height="40" bgcolor="#8EA782"><input style="text-align: center" type="text" name="q20" maxlength="1" required="required" size="1" value="<?php echo $key[20]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">21</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="q21" maxlength="1" required="required" size="1" value="<?php echo $key[21]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#8EA782">22</td>
<td height="40" bgcolor="#8EA782"><input style="text-align: center" type="text" name="q22" maxlength="1" required="required" size="1" value="<?php echo $key[22]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">23</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="q23" maxlength="1" required="required" size="1" value="<?php echo $key[23]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#8EA782">24</td>
<td height="40" bgcolor="#8EA782"><input style="text-align: center" type="text" name="q24" maxlength="1" required="required" size="1" value="<?php echo $key[24]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">25</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="q25" maxlength="1" required="required" size="1" value="<?php echo $key[25]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#8EA782">26</td>
<td height="40" bgcolor="#8EA782"><input style="text-align: center" type="text" name="q26" maxlength="1" required="required" size="1" value="<?php echo $key[26]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">27</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="q27" maxlength="1" required="required" size="1" value="<?php echo $key[27]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#8EA782">28</td>
<td height="40" bgcolor="#8EA782"><input style="text-align: center" type="text" name="q28" maxlength="1" required="required" size="1" value="<?php echo $key[28]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">29</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="q29" maxlength="1" required="required" size="1" value="<?php echo $key[29]; ?>"></td>
</tr>
<tr>
<td height="40" bgcolor="#8EA782">30</td>
<td height="40" bgcolor="#8EA782"><input style="text-align: center" type="text" name="q30" maxlength="1" required="required" size="1" value="<?php echo $key[30]; ?>"></td>
</tr>
</tbody>
</table>
<br>
<input type="submit" value="NEXT">
</form>
<p><a href="#top"><b>Back to the top</b></a></p>
</body>
</html>

