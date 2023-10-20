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
$paper_syllabus = $data_info['paper_syllabus'];
$component = substr($paper_reference, 12, 2);
if($paper_syllabus != 1){
$min_a = $data_info['paper_a'];
$min_b = $data_info['paper_b'];}
$min_c = $data_info['paper_c'];
$min_d = $data_info['paper_d'];
$min_e = $data_info['paper_e'];
if($paper_syllabus != 3){
$min_f = $data_info['paper_f'];
$min_g = $data_info['paper_g'];}
$link = substr($paper_reference, 0, 9) . 'gt.pdf';
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
width: 80%;
}
</style>
</head>
<body>
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
<p style="font-size: x-large"><b>GRADE THRESHOLDS</b></p>
<p><b>Paper reference:</b><br>
<b style="color: #033909"><?php echo $paper_reference; ?></b></p>
<p><input type="button" value="DOWNLOAD GRADE THRESHOLDS" onclick="window.open('./gt/<?php echo $link; ?>', '_blank');"></p>
<form autocomplete="off" method="post" action="add_paper_insert2.php?paper_reference=<?php echo $paper_reference; ?>">
<?php
if($paper_syllabus == 3){
?>
<table align="center" width="67%" bgcolor="#000000">
<tbody>
<tr>
<td height="40" bgcolor="#6A865D">&nbsp;</td>
<td height="40" bgcolor="#6A865D"><b>MAX</b></td>
<td height="40" bgcolor="#6A865D"><b>A</b></td>
<td height="40" bgcolor="#6A865D"><b>B</b></td>
<td height="40" bgcolor="#6A865D"><b>C</b></td>
<td height="40" bgcolor="#6A865D"><b>D</b></td>
<td height="40" bgcolor="#6A865D"><b>E</b></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">Component <?php echo $component; ?></td>
<td height="40" bgcolor="#779568">40</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_a" maxlength="2" required="required" size="1" value="<?php echo $min_a; ?>">	</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_b" maxlength="2" required="required" size="1" value="<?php echo $min_b; ?>">	</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_c" maxlength="2" required="required" size="1" value="<?php echo $min_c; ?>">	</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_d" maxlength="2" required="required" size="1" value="<?php echo $min_d; ?>">	</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_e" maxlength="2" required="required" size="1" value="<?php echo $min_e; ?>">	</td>
</tr>
</tbody>
</table>
<?php
}
if($paper_syllabus == 2){
?>
<table align="center" width="80%" bgcolor="#000000">
<tbody>
<tr>
<td height="40" width="20%" bgcolor="#6A865D">&nbsp;</td>
<td height="40" width="10%" bgcolor="#6A865D"><b>MAX</b></td>
<td height="40" width="10%" bgcolor="#6A865D"><b>A</b></td>
<td height="40" width="10%" bgcolor="#6A865D"><b>B</b></td>
<td height="40" width="10%" bgcolor="#6A865D"><b>C</b></td>
<td height="40" width="10%" bgcolor="#6A865D"><b>D</b></td>
<td height="40" width="10%" bgcolor="#6A865D"><b>E</b></td>
<td height="40" width="10%" bgcolor="#6A865D"><b>F</b></td>
<td height="40" width="10%" bgcolor="#6A865D"><b>G</b></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">Component <?php echo $component; ?></td>
<td height="40" bgcolor="#779568">40</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_a" maxlength="2" required="required" size="1" value="<?php echo $min_a; ?>">	</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_b" maxlength="2" required="required" size="1" value="<?php echo $min_b; ?>">	</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_c" maxlength="2" required="required" size="1" value="<?php echo $min_c; ?>">	</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_d" maxlength="2" required="required" size="1" value="<?php echo $min_d; ?>">	</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_e" maxlength="2" required="required" size="1" value="<?php echo $min_e; ?>">	</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_f" maxlength="2" required="required" size="1" value="<?php echo $min_f; ?>">	</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_g" maxlength="2" required="required" size="1" value="<?php echo $min_g; ?>">	</td>
</tr>
</tbody>
</table>
<?php
}
if($paper_syllabus == 1){
?>
<table align="center" width="80%" bgcolor="#000000">
<tbody>
<tr>
<td height="40" width="10%" bgcolor="#6A865D">&nbsp;</td>
<td height="40" width="10%" bgcolor="#6A865D"><b>MAX</b></td>
<td height="40" width="10%" bgcolor="#6A865D"><b>A</b></td>
<td height="40" width="10%" bgcolor="#6A865D"><b>B</b></td>
<td height="40" width="10%" bgcolor="#6A865D"><b>C</b></td>
<td height="40" width="10%" bgcolor="#6A865D"><b>D</b></td>
<td height="40" width="10%" bgcolor="#6A865D"><b>E</b></td>
<td height="40" width="10%" bgcolor="#6A865D"><b>F</b></td>
<td height="40" width="10%" bgcolor="#6A865D"><b>G</b></td>
</tr>
<tr>
<td height="40" bgcolor="#779568">Component <?php echo $component; ?></td>
<td height="40" bgcolor="#779568">40</td>
<td height="40" bgcolor="#779568">-</td>
<td height="40" bgcolor="#779568">-</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_c" maxlength="2" required="required" size="1" value="<?php echo $min_c; ?>">	</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_d" maxlength="2" required="required" size="1" value="<?php echo $min_d; ?>">	</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_e" maxlength="2" required="required" size="1" value="<?php echo $min_e; ?>">	</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_f" maxlength="2" required="required" size="1" value="<?php echo $min_f; ?>">	</td>
<td height="40" bgcolor="#779568"><input style="text-align: center" type="text" name="min_g" maxlength="2" required="required" size="1" value="<?php echo $min_g; ?>">	</td>
</tr>
</tbody>
</table>
<?php
}
?>
<br><input type="submit" value="NEXT">
</form>
<p>&nbsp;</p>
</body>
</html>