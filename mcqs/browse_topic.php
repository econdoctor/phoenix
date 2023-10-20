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
$search_unit = $_POST['topic_unit'];
$search_module = $_POST['topic_module'];
$sql = "SELECT * FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_active = $data['user_active'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
$s = $_GET['s'];
if(empty($s)){
echo 'Please go back and choose a course.';
exit();}
if($s == '3'){
$s_text = "A LEVEL";}
if($s == '2'){
$s_text = "AS LEVEL";}
if($s == '1'){
$s_text = "IGCSE";}
if(empty($search_unit) && empty($search_module)){
$sql_get = "SELECT * FROM phoenix_topics WHERE topic_syllabus = '".$s."' ORDER BY topic_unit_id ASC, topic_module_id ASC";}
if(isset($search_unit) && isset($search_module)){
$sql_get = "SELECT * FROM phoenix_topics WHERE topic_syllabus = '".$s."' AND topic_unit_id = (CASE WHEN '".$search_unit."' != 'Any' THEN '".$search_unit."' ELSE topic_unit_id END) AND topic_module_id = (CASE WHEN '".$search_module."' != 'Any' THEN '".$search_module."' ELSE topic_module_id END) ORDER BY topic_unit_id ASC, topic_module_id ASC";}
$res_get = $mysqli -> query($sql_get);
$nr_get = mysqli_num_rows($res_get);
function is_selected($db_value, $html_value){
if($db_value == $html_value){
return "selected";}
else {
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
function submitform(){
setTimeout(function(){
document.form1.submit(); }, 100);}
</script>
<script>
$(document).ready(function(){
$('#topic_unit').change(function() {
$("#topic_module").val('Any');})});
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
<input type="button" name="browse" value="BROWSE" style="font-size: x-large; width: 30%; background-color: #ffc000;  color: #3b2c00; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='browse.php';"/>&nbsp;&nbsp;
<input type="button" name="manage" value="MANAGE" style="font-size: x-large; width: 30%; background-color: #c55b11;  color: #271203; border: 3px solid black; border-radius: 12px; letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='manage.php';"/>&nbsp;&nbsp;
<input type="button" name="assignments" value="ASSIGNMENTS" style="font-size: x-large; width: 30%; background-color: #2f5596;  color: #0a111f; border: 3px solid black; border-radius: 12px;letter-spacing: 4px; padding: 6px 0px;" onclick="document.location.href='assign.php';"/>
</td></tr></tbody></table>
<p style="font-size: x-large"><b><?php echo $s_text; ?> TOPICS</b></p><p>
<form name="form1" method="post" action="browse_topic.php?s=<?php echo $s;?>">
<p><select name="topic_unit" id="topic_unit" onChange="submitform();">
<optgroup>
<option value="Any">All units</option>
<?php
$sql1 = "SELECT DISTINCT(topic_unit_id), topic_unit FROM phoenix_topics WHERE topic_syllabus = '".$s."' ORDER BY topic_unit_id ASC";
$res1 = $mysqli -> query($sql1);
while($data1 = mysqli_fetch_assoc($res1)){
$topic_unit_id = $data1['topic_unit_id'];
$topic_unit = $data1['topic_unit'];
?>
<option value="<?php echo $topic_unit_id; ?>" <?php echo is_selected($topic_unit_id, $search_unit); ?>><?php echo $topic_unit; ?></option>
<?php
}
?>
</select></p>
<p><select id="topic_module" name="topic_module" onchange="submitform();">
<?php
if(empty($search_module)){
echo '<option value="Any">All topics</option>';}
if(isset($search_module) && isset($search_unit)){
$sql2 ="SELECT * FROM phoenix_topics WHERE topic_syllabus = '".$s."' AND topic_unit_id = '".$search_unit."' ORDER BY topic_module_id ASC";
$res2 = $mysqli->query($sql2);
echo '<option value="Any">All topics</option>';
while($data2 = mysqli_fetch_assoc($res2)){
$topic_module_id = $data2['topic_module_id'];
$topic_module = $data2['topic_module'];
?>
<option value="<?php echo $topic_module_id; ?>" <?php echo is_selected($topic_module_id, $search_module); ?>><?php echo $topic_module; ?></option>';
<?php
}}
?>
</select></p></form>
<?php
if($nr_get == 0){
echo '<p><em>No topics found</em></p>';}
if($nr_get > 0){
echo '<p><b><span style="color: #820000">'.number_format($nr_get).'</span> topic(s) found</b></p>
<table width = "80%" align="center" bgcolor="#000000"><tbody>';
$color = 1;
$previous = '';
while($data_get = mysqli_fetch_assoc($res_get)){
$topic_id = $data_get['topic_id'];
$current = $data_get['topic_unit_id'];
$topic_hidden = $data_get['topic_hidden'];
if($current != $previous){
$title = strtoupper($data_get['topic_unit']);
echo '<tr><td height="60" colspan ="4" bgcolor="#647d57"><b>'.$title.'</b></td></tr>';
$color = 1;}
$sql_count = "SELECT * FROM phoenix_questions WHERE question_topic_id = '".$topic_id."' AND question_repeat = '0' AND question_obsolete = '0'";
$res_count = $mysqli -> query($sql_count);
$count_q = mysqli_num_rows($res_count);
if($color == 1){
echo '<tr>
<td height="40" width="80%" bgcolor="#769467">'.$data_get['topic_module'].'</td>
<td height="40" width="10%" bgcolor="#769467"><b>'.number_format($count_q).' MCQs</b></td>';
if($user_active == 0 && $topic_hidden == 1){
echo '<td height="40" bgcolor="#769467" colspan="2"><p><a href="upgrade.php"><img src="unlock.png" title="Unlock" width="30"></a></p></td>';}
else {
echo '<td height="40" width="5%" bgcolor="#769467"><p><a href="topic_view.php?topic_id='.$topic_id.'"><img src="mg.png" width="30" height="30" class="mid"></p></td>
<td height="40" width="5%" bgcolor="#769467"><p><a href="topic_download.php?topic_id='.$topic_id.'"><img src="download.png" width="30" height="30" class="mid"></p></td>';}
echo '</tr>';
$color = 2;}
else {
echo '<tr>
<td height="40" width="80%" bgcolor="#a0b595"><p>'.$data_get['topic_module'].'</p></td>
<td height="40" width="10%" bgcolor="#a0b595"><p><b>'.number_format($count_q).' MCQs</b></p></td>';
if($user_active == 0 && $topic_hidden == 1){
echo '<td height="40" bgcolor="#a0b595" colspan="2"><p><a href="upgrade.php"><img src="unlock.png" title="Unlock" width="30"></a></p></td>';}
else {
echo '<td height="40" width="5%" bgcolor="#a0b595"><p><a href="topic_view.php?topic_id='.$topic_id.'"><img src="mg.png" width="30" height="30" class="mid"></p></td>
<td height="40" width="5%" bgcolor="#a0b595"><p><a href="topic_download.php?topic_id='.$topic_id.'"><img src="download.png" width="30" height="30" class="mid"></p></td>';}
echo '</tr>';
$color = 1;}
$previous = $current;}
echo'</tbody></table>';
}
?>
<p><a href="#top"><b>Back to the top</b></a></p>
</body></html>