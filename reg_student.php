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
input[type=text] {
padding: 3px 5px;
margin: 2px 0;
box-sizing: border-box;
text-align: center;
font-weight: bold;
font-size: 18px;
border: 2px solid black;
border-radius: 4px;
width: 25%;
}
input[type=password] {
padding: 3px 5px;
margin: 2px 0;
box-sizing: border-box;
text-align: center;
font-weight: bold;
font-size: 18px;
border: 2px solid black;
border-radius: 4px;
width: 25%;
}
input[type=email] {
padding: 3px 5px;
margin: 2px 0;
box-sizing: border-box;
text-align: center;
font-weight: bold;
font-size: 18px;
border: 2px solid black;
border-radius: 4px;
width: 25%;
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
width: 25%;
border-radius: 4px;
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
width: 25%;
border-radius: 4px;
}
optgroup { font-size:18px; }
select {
padding: 3px 5px;
margin: 2px 0;
box-sizing: border-box;
text-align: center;
font-weight: bold;
font-size: 18px;
border: 2px solid black;
border-radius: 4px;
width: 25%;
}
input, select{
box-sizing: border-box;
-moz-box-sizing: border-box;
-webkit-box-sizing: border-box;
}
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script>
function checkUsername(){
jQuery.ajax({
url: "check_availability.php",
data:'username='+$("#user_name").val(),
type: "POST",
success:function(data){
$("#check-username").html(data);},
error:function (){}});}
</script>
</head>
<body onload="checkUsername();">
<br><a href="./mcqs/main.php"><img src="home.png" width="150"></a>
<p style="font-size: x-large;"><b>REGISTER</b></p>
<?php
if($_GET['error'] == 1){
echo '<p><b style="color: #820000;">Please fill in the information required</b></p>';}
if($_GET['error'] == 2){
echo '<p><b style="color: #820000;">There is no teacher account associated with the ID code you entered</b></p>';}
if($_GET['error'] == 3){
echo '<p><b style="color: #820000;">Your answer to the antispam security question does not appear to be correct</b></p>';}
if($_GET['error'] == 4){
echo '<p><b style="color: #820000;">Failed to connect to database</b></p>';}
if($_GET['error'] == 6){
echo '<p><b style="color: #820000;">Your password is too short.<br>It should be at least 8 characters long</b></p>';}
if($_GET['error'] == 7){
echo '<p><b style="color: #820000;">You entered two different passwords</b></p>';}
if($_GET['error'] == 8){
echo '<p><b style="color: #820000;">The username or email you entered is already associated with an existing account</b></p>';}
?>
<form action="reg_student_db.php" method="post">
<p>
<input id="user_name" style="text-align: center;" name="user_name" placeholder="Username" type="text" required="required" maxlength="32" <?php if(!empty($_GET['user_name'])) { echo 'value="'.$_GET['user_name'].'"'; } ?> onInput="checkUsername()"/>
<br>
<span id="check-username"></span>
</p>
<p>
<select name="user_title" required>
<optgroup>
<option value="" disabled <?php if(empty($_GET['user_title'])) { echo 'selected'; } ?>>Title</option>
<option value="Ms." <?php if($_GET['user_title'] == 'Ms.') { echo 'selected'; } ?>>Ms.</option>
<option value="Mr." <?php if($_GET['user_title'] == 'Mr.') { echo 'selected'; } ?>>Mr.</option>
</optgroup>
</select>
</p>
<p>
<input name="user_first_name" style="text-align: center;" placeholder="First name" type="text" required="required" maxlength="32" <?php if(!empty($_GET['user_first_name'])) { echo 'value="'.$_GET['user_first_name'].'"'; } ?>>&nbsp;&nbsp;
</p>
<p>
<input name="user_last_name" style="text-align: center;" type="text" placeholder="Last name" required="required" maxlength="32" <?php if(!empty($_GET['user_last_name'])) { echo 'value="'.$_GET['user_last_name'].'"'; } ?>>
</p>
<p>
<input name="user_email" style="text-align: center;" type="email" placeholder="Email" required="required" maxlength="64" size="36" <?php if(!empty($_GET['user_email'])) { echo 'value="'.$_GET['user_email'].'"'; } ?>>
</p>
<p>
<input name="user_password" style="text-align: center;" placeholder="Password" type="password" required="required" maxlength="32">&nbsp;&nbsp;
</p>
<p>
<input name="user_password_confirm" style="text-align: center;" placeholder="Confirm password" type="password" required="required" maxlength="32">
</p>
<p>
<select name="user_syllabus" required>
<optgroup>
<option value="" disabled <?php if(empty($_GET['user_syllabus'])) { echo 'selected'; } ?>>Course</option>
<option value="1" <?php if($_GET['user_syllabus'] == '1') { echo 'selected'; } ?>>IGCSE</option>
<option value='2' <?php if($_GET['user_syllabus'] == '2') { echo 'selected'; } ?>>AS Level</option>
<option value='3' <?php if($_GET['user_syllabus'] == '3') { echo 'selected'; } ?>>A Level</option>
</optgroup>
</select>
</p>
<p>
<input type="text" required="required" name="user_teacher" style="text-align: center;"  placeholder="Your teacher's ID code" <?php if(!empty($_GET['user_teacher'])) { echo 'value="'.$_GET['user_teacher'].'"'; } ?> maxlength="8"></p>
</p>
<p>
<input name="antispam" type="text" style="text-align: center;" required="required" placeholder="4 + 2 = ?" maxlength="1" <?php if(!empty($_GET['antispam'])) { echo 'value="'.$_GET['antispam'].'"'; } ?>>
</p>
<input type="submit" value="REGISTER">
</form>
</body>
</html>
