<?php
session_start();
if(empty($_SESSION['phoenix_user_id']))
{
header("Location: login.php");
exit();
}
$user_id = $_SESSION['phoenix_user_id'];
include "connectdb.php";
if($mysqli -> connect_errno)
{
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();
}
$sql = "SELECT * FROM phoenix_users WHERE user_id ='".$user_id."'";
$result = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($result);
$user_email = $data['user_email'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
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
input[type=email] {
  padding: 3px 5px;
  margin: 2px 0;
  box-sizing: border-box;
  text-align: center;
  font-weight: bold;
  font-size: 18px;
  border: 2px solid black;
  border-radius: 4px;
  width: 30%;
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
  width: 30%;
  border-radius: 4px;
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
  width: 30%;
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
<a href="./mcqs/main.php"><img src="home.png" width="150"></a>
<p style="font-size: x-large"><b>EMAIL</b></p>
<?php
if($_GET['error'] == 1)
{
echo '<p><b  style="color: #820000;">Fill in the information required</b></p>';
}
if($_GET['error'] == 2)
{
echo '<p><b  style="color: #820000;">You entered two different email addresses</b></p>';
}
if($_GET['error'] == 3)
{
echo '<p><b  style="color: #820000;">Failed to connect to database</b></p>';
}
if($_GET['error'] == 4)
{
echo '<p><b  style="color: #820000;">The new email address you entered is already associated with an existing account</b></p>';
}
if($_GET['error'] == 5)
{
echo '<p><b  style="color: #820000;">Wrong password</b></p>';
}
?>
<form action="email_change_db.php" method="post">
<p><b>Your current email address</b><br>
<b style="color: #033909"><?php echo $user_email; ?></b></p>
<p>
<input name="email_new" type="email" required="required" placeholder="Your new email address"  maxlength="64" <?php if(!empty($_GET['email_new'])) { echo 'value="'.$_GET['email_new'].'"'; } ?>>
</p>
<p>
<input name="email_new_confirm" type="email" required="required" maxlength="64" placeholder="Confirm your new email address" <?php if(!empty($_GET['email_new_confirm'])) { echo 'value="'.$_GET['email_new_confirm'].'"'; } ?>>
</p>
<p>
<input name="password" type="password" required="required" placeholder="Password">
</p>
<p>
<input type="submit" value="CONFIRM">
</p>
</form>
</body>
</html>
