<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
$_SESSION['phoenix_user_id'] = $_SESSION['phoenix_user_id'];
$user_id = $_SESSION['phoenix_user_id'];}
if(!isset($_SESSION['phoenix_user_id'])){
header("Location: ../login.php");
exit();}
$perm_id = $_GET['id'];
if(empty($perm_id)){
echo 'Missing information';
exit();}
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_id ='".$user_id."'";
$res = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($res);
$user_type = $data['user_type'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
if($user_type == 1){
echo 'What are you doing here? This page is for teachers only.';
exit();}
$sqlcheck = "SELECT * FROM phoenix_permissions WHERE permission_id = '".$perm_id."'";
$rescheck = $mysqli -> query($sqlcheck);
$datacheck = mysqli_fetch_assoc($rescheck);
$teacher_id = $datacheck['permission_teacher'];
$active = $datacheck['permission_active'];
$s = $datacheck['permission_syllabus'];
if($s == '3'){
$s_text = "A LEVEL";}
if($s == '2'){
$s_text = "AS LEVEL";}
if($s == '1'){
$s_text = 'IGCSE';}
if($active == 1){
echo 'This rule is already active.';
exit();}
if($teacher_id != $user_id){
echo 'You are not authorized to manage this rule.';
exit();}
$search_year = $_POST['paper_year'];
$search_serie = $_POST['paper_serie'];
$search_version = $_POST['paper_version'];
if(empty($search_year) && empty($search_serie) && empty($search_version)){
$sql2 = "SELECT * FROM phoenix_papers WHERE paper_syllabus = '".$s."' ORDER BY paper_year DESC, paper_serie DESC, paper_version ASC";}
if(isset($search_year) && isset($search_serie) && isset($search_version)){
$sql2 = "SELECT * FROM phoenix_papers WHERE paper_syllabus = '".$s."' AND paper_year = (CASE WHEN '".$search_year."' != 'Any' THEN '".$search_year."' ELSE paper_year END) AND paper_serie = (CASE WHEN '".$search_serie."' != 'Any' THEN '".$search_serie."' ELSE paper_serie END) AND paper_version = (CASE WHEN '".$search_version."' != 'Any' THEN '".$search_version."' ELSE paper_version END) ORDER BY paper_year DESC, paper_serie DESC, paper_version ASC";}
$res2 = $mysqli -> query($sql2);
$nr2 = mysqli_num_rows($res2);
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
width: 15%;
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
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
$('#paper_year').change(function(){
$("#paper_serie").val('Any');})});
</script><script>
$(document).ready(function(){
$('#paper_year').change(function(){
$("#paper_version").val('Any');})});
</script><script>
$(document).ready(function(){
$('#paper_serie').change(function(){
$("#paper_version").val('Any');})});
</script><script>
function submitform(){
setTimeout(function(){
document.form1.submit();}, 100);}
</script>
<script language="JavaScript">
function toggle(source) {
checkboxes = document.getElementsByName('permission_paper[]');
for(var i=0, n=checkboxes.length;i<n;i++) {
checkboxes[i].checked = source.checked;}}
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
<p style="font-size: x-large"><b>NEW <?php echo $s_text; ?> RULE</b></p>
<p>Please select the paper(s) you want to hide from your students.</p>
<form name="form1" method="post" action="permission_new_rule_paper.php?id=<?php echo $perm_id; ?>&s=<?php echo $s; ?>"><p>
<select name="paper_year" id="paper_year" onChange="submitform();"><optgroup>
<option value="Any">All years</option>
<?php
$sql3 = "SELECT DISTINCT paper_year FROM phoenix_papers WHERE paper_syllabus = '".$s."' ORDER BY paper_year DESC";
$res3 = $mysqli -> query($sql3);
while($data3 = mysqli_fetch_assoc($res3)){
$paper_year = $data3['paper_year'];
?>
<option value="<?php echo $paper_year; ?>"  <?php echo is_selected($paper_year, $search_year); ?>><?php echo $paper_year; ?></option>
<?php
}
?>
</optgroup></select>
&nbsp;<select name="paper_serie" id="paper_serie" onChange="submitform();"><optgroup>
<?php
if(empty($search_serie)){
echo '<option value="Any">All series</option>';}
if(isset($search_serie) && isset($search_year)){
$sql4 = "SELECT DISTINCT paper_serie FROM phoenix_papers WHERE paper_syllabus = '".$s."' AND paper_year = '".$search_year."' ORDER BY paper_serie ASC";
$res4 = $mysqli -> query($sql4);
echo '<option value="Any">All series</option>';
while($data4 = mysqli_fetch_assoc($res4)){
$paper_serie = $data4['paper_serie'];
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
</optgroup></select>
&nbsp;<select name="paper_version" id="paper_version" onchange="submitform();"><optgroup>
<?php
if(empty($search_version)){
echo '<option value="Any">All versions</option>';}
if(isset($search_serie) && isset($search_year) && isset($search_version)){
$sql5 = "SELECT paper_version FROM phoenix_papers WHERE paper_serie = '".$search_serie."' AND paper_year ='".$search_year."' AND paper_syllabus ='".$s."'";
$res5 = $mysqli -> query($sql5);
echo '<option value="Any">All versions</option>';
while($data5 = mysqli_fetch_assoc($res5)){
$paper_version = $data5['paper_version'];
if($paper_version > 0){
$paper_version_text = $paper_version;}
if($paper_version == 0){
$paper_version_text = "/";}
?>
<option value="<?php echo $paper_version; ?>" <?php echo is_selected($paper_version, $search_version); ?>><?php echo $paper_version_text; ?></option>';
<?php
}}
?>
</optgroup></select></form>
<?php
if($nr2 == 0){
echo '<p><em>No papers found</em></p>';}
if($nr2 > 0){
?>
<p><b><span style="color: #820000"><?php echo number_format($nr2); ?></span> paper(s) found</b></p>
<form method="post" action="permission_new_rule_insert3.php?id=<?php echo $perm_id; ?>">
<table width = "50%" align="center" bgcolor="#000000"><tbody><tr>
<td height="50" width="10%" bgcolor="647d57"><input type="checkbox" style="transform: scale(1.5);" onClick="toggle(this)"></td>
<td height="50" bgcolor="#647d57"><p><b>YEAR</b></p></td>
<td height="50" bgcolor="#647d57"><p><b>SERIE</b></p></td>
<td height="50" bgcolor="#647d57"><p><b>VERSION</b></p></td>
</tr>
<?php
$color = 1;
while($data2 = mysqli_fetch_assoc($res2)){
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
if($color == 1){
echo '<tr>
<td height="40" bgcolor="#769467"><input type="checkbox" style="transform: scale(1.5);" value="'.$data2['paper_id'].'" name="permission_paper[]"></td>
<td height="40" bgcolor="#769467"><p>'.$data2['paper_year'].'</p></td>
<td height="40" bgcolor="#769467"><p>'.$paper_serie_text.'</p></td>
<td height="40" bgcolor="#769467"><p>'.$paper_version.'</p></td>
</tr>';
$color = 2;}
else {
echo '<tr>
<td height="40" bgcolor="#a0b595"><input type="checkbox" style="transform: scale(1.5);" value="'.$data2['paper_id'].'" name="permission_paper[]"></td>
<td height="40" bgcolor="#a0b595"><p>'.$data2['paper_year'].'</p></td>
<td height="40" bgcolor="#a0b595"><p>'.$paper_serie_text.'</p></td>
<td height="40" bgcolor="#a0b595"><p>'.$paper_version.'</p></td>
</tr>';
$color = 1;}}
echo'</tbody></table>
<p><input type="submit" value="NEXT"></p>
</form>
<p><a href="#top"><b>Back to the top</b></a></p>';}
?>

</body></html>