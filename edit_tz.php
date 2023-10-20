<?php
session_start();
if(empty($_SESSION['phoenix_user_id'])){
header("Location: login.php");
exit();}
$user_id = $_SESSION['phoenix_user_id'];
include "connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_id ='".$user_id."'";
$result = $mysqli -> query($sql);
$data = mysqli_fetch_assoc($result);
$user_timezone = $data['user_timezone'];
$user_title = $data['user_title'];
$user_first_name = $data['user_first_name'];
$user_last_name = $data['user_last_name'];
$user_name = $data['user_name'];
$sql_tz = "SELECT * FROM phoenix_timezones WHERE value = '".$user_timezone."'";
$result_tz = $mysqli -> query($sql_tz);
$data_tz = mysqli_fetch_assoc($result_tz);
$user_timezone_label = $data_tz['label'];
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
optgroup { font-size:18px; }
select {
  padding: 3px 5px;
  margin: 2px 0;
  box-sizing: border-box;
  font-weight: bold;
  font-size: 18px;
  border: 2px solid black;
  border-radius: 4px;
  width: 30%;
}
input, select{
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
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
<a href="./mcqs/main.php"><img src="home.png" width="150"></a>
<p style="font-size: x-large;"><b>TIMEZONE</b></p>
<?php
if($_GET['error'] == 1)
{
echo '<p><b  style="color: #820000;">Please choose a timezone</b></p>';
}
if($_GET['error'] == 2)
{
echo '<p><b  style="color: #820000;">Failed to connect to database</b></p>';
}
if($_GET['error'] == 3)
{
echo '<p><b  style="color: #820000;">Your new timezone is identical to your current timezone</b></p>';
}
if($_GET['m'] == 1)
{
echo '<p><b  style="color: #820000;">Due to a technical glitch, your timezone was not saved properly.</b></p>';
}
?>
<form action="edit_tz_db.php" method="post">
<?php
if($_GET['m'] != 1){
?>
<p><b>Your current timezone</b><br>
<b style="color: #033909"><?php echo $user_timezone_label; ?></b></p>
<?php
}
?>
<p>
<select name="timezone">
<optgroup>
<option value="" disabled selected style="text-align: center">Your new timezone</option>
<option value="-720">(GMT -12:00) Eniwetok, Kwajalein</option>
<option value="-660">(GMT -11:00) Midway Island, Samoa</option>
<option value="-600">(GMT -10:00) Hawaii</option>
<option value="-570">(GMT -9:30) Taiohae</option>
<option value="-540">(GMT -9:00) Alaska</option>
<option value="-480">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
<option value="-420">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
<option value="-360">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
<option value="-300">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
<option value="-270">(GMT -4:30) Caracas</option>
<option value="-240">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
<option value="-210">(GMT -3:30) Newfoundland</option>
<option value="-180">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
<option value="-120">(GMT -2:00) Mid-Atlantic</option>
<option value="-60">(GMT -1:00) Azores, Cape Verde Islands</option>
<option value="0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
<option value="60">(GMT +1:00) Brussels, Copenhagen, Madrid, Paris</option>
<option value="120">(GMT +2:00) Kaliningrad, South Africa</option>
<option value="180">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
<option value="210">(GMT +3:30) Tehran</option>
<option value="240">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
<option value="270">(GMT +4:30) Kabul</option>
<option value="300">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
<option value="330">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
<option value="345">(GMT +5:45) Kathmandu, Pokhara</option>
<option value="360">(GMT +6:00) Almaty, Dhaka, Colombo</option>
<option value="390">(GMT +6:30) Yangon, Mandalay</option>
<option value="420">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
<option value="480">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
<option value="525">(GMT +8:45) Eucla</option>
<option value="540">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
<option value="570">(GMT +9:30) Adelaide, Darwin</option>
<option value="600">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
<option value="630">(GMT +10:30) Lord Howe Island</option>
<option value="660">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
<option value="690">(GMT +11:30) Norfolk Island</option>
<option value="720">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
<option value="765">(GMT +12:45) Chatham Islands</option>
<option value="780">(GMT +13:00) Apia, Nukualofa</option>
<option value="840">(GMT +14:00) Line Islands, Tokelau</option>
</optgroup>
</select></p>
<p><input type="submit" value="CONFIRM"></p>
</form>
</body>
</html>
