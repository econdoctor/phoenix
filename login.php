<?php
session_start();
if(!empty($_SESSION['phoenix_user_id'])){
header("Location: ./mcqs/main.php");}
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
input[type=password] {
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
.brsmall {
display: block;
margin-bottom: 0.5em;
}
</style>
</head>
<body><br>
<a href="./mcqs/main.php"><img src="home.png" width="150"></a>
<form method="post" action="login_db.php">
<?php
if($_GET['error'] == 4){
echo '<p><b style="color: #820000;">Failed to connect to database</b></p>';}
if($_GET['error'] == 1){
echo '<p><b style="color: #820000;">Please fill all textfields</b></p>';}
if($_GET['error'] == 2){
echo '<p><b style="color: #820000;">Invalid username or email</b></p>';}
if($_GET['error'] == 3){
echo '<p><b style="color: #820000;">Wrong password</b></p>';}
if($_GET['cp'] == 1){
echo '<p><b style="color: #033909;">Your password has been updated</b></p>';}
if($_GET['fp'] == 1){
echo '<p><b style="color: #033909;">A temporary password has been sent to you. Please check your mailbox.</b></p>';}
?>
<p><b style="font-size: x-large;">LOG IN</b></p>
<p><input type="text" name="login" placeholder="Username / Email" <?php if(!empty($_GET['login'])) { echo 'value="'.$_GET['login'].'"'; } ?> required></p>
<p><input type="password" name="password" placeholder="Password" required></p>
<p><input type="submit" value="ENTER"></p>
<span class="brsmall"></span>
<p style="font-size: medium"><a href="forgot_password.php"><em>Forgot your password?</em></a></p></form>
<p>&nbsp;</p>
<p><b style="font-size: x-large;">CREATE AN ACCOUNT</p></b>
<p><input type="button" value="REGISTER" onclick="document.location.href='#'"></p>
</body>
</html>