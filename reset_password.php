<?php
$m = $_GET['m'];
if($m != 1 && $m != 3){
header("Location: forgot_password.php");
exit();}
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
input[type=text] {
padding: 3px 5px;
margin: 2px 0;
box-sizing: border-box;
text-align: center;
font-weight: bold;
font-size: 18px;
border: 2px solid black;
border-radius: 4px;
width: 20%;
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
.brsmall {
display: block;
margin-bottom: 0.5em;
}
</style>
</head>
<body><br>
<a href="./mcqs/main.php"><img src="home.png" width="150"></a>
<p style="font-size: x-large;"><b>RESET PASSWORD</b></p>
<?php
if($m == 3){
?>
<table align="center" width="67%"><tr><td>
<p align="justify">Your teacher can change your password by following the procedure below.</p>
<p align="justify">- On the homepage, click on <em>"Multiple Choice Questions"</em><br>
- Click on <em>"Manage"</em><br>
- Click on <em>"Students"</em><br>
- Click on <em>"View"</em> / <em>"Details"</em> besides your account information<br>
- Click on <em>"Change password"</em></p></td></tr></table>
<p>
<input type="button" style="width: 15%" value="GO BACK" onclick="document.location.href='login.php';">
</p>
<?php
}
if($m == 1){
if($_GET['error'] == 4){
echo '<p><b  style="color: #820000;">Failed to connect to database</b></p>';}
if($_GET['error'] == 1){
echo '<p><b  style="color: #820000;">Please fill all textfields</b></p>';}
if($_GET['error'] == 2){
echo '<p><b  style="color: #820000;">Invalid username or email</b></p>';}
if($_GET['error'] == 3){
echo '<p><b  style="color: #820000;">The recovery key is incorrect</b></p>';}
if($_GET['error'] == 5){
echo '<p><b  style="color: #820000;">Empty session</b></p>';}
?>
<form method="post" action="reset_password_rc.php">
<p>
<b>Username / Email</b><br>
<span class="brsmall"></span>
<input name="id" type="text" style="text-align: center;" <?php if(!empty($_GET['id'])) { echo 'value="'.$_GET['id'].'"'; } ?> required="required">
</p>

<p><b>Recovery key</b><br>
<span class="brsmall"></span>
<b>
<input name="rc1" type="text" style="text-align: center; width: 7.5%;" required="required"  maxlength="4" <?php if(!empty($_GET['rc1'])) { echo 'value="'.$_GET['rc1'].'"'; } ?>> -
<input name="rc2" type="text" style="text-align: center; width: 7.5%;" required="required"  maxlength="4" <?php if(!empty($_GET['rc2'])) { echo 'value="'.$_GET['rc2'].'"'; } ?>> -
<input name="rc3" type="text" style="text-align: center; width: 7.5%;" required="required"  maxlength="4" <?php if(!empty($_GET['rc3'])) { echo 'value="'.$_GET['rc3'].'"'; } ?>> -
<input name="rc4" type="text" style="text-align: center; width: 7.5%;" required="required"  maxlength="4" <?php if(!empty($_GET['rc4'])) { echo 'value="'.$_GET['rc4'].'"'; } ?>>
</b>
</p>
<p><input type="submit" value="CONFIRM"></p>
</form>
<?php
}
?>
</body>
</html>