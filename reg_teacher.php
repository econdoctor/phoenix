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
text-align: left;
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
echo '<p><b style="color: #820000;"></b></p>';}
if($_GET['error'] == 3){
echo '<p><b style="color: #820000;">Your answer to the antispam security question does not appear to be correct</b></p>';}
if($_GET['error'] == 4){
echo '<p><b style="color: #820000;">Failed to connect to database</b></p>';}
if($_GET['error'] == 5){
echo '<p><b style="color: #820000;"></b></p>';}
if($_GET['error'] == 6){
echo '<p><b style="color: #820000;">Your password is too short.<br>It should be at least 8 characters long</b></p>';}
if($_GET['error'] == 7){
echo '<p><b style="color: #820000;">You entered two different passwords</b></p>';}
if($_GET['error'] == 8){
echo '<p><b style="color: #820000;">The username or email you entered is already associated with an existing account</b></p>';}
?>
<form action="reg_teacher_db.php" method="post">
<p>
<input id="user_name" style="text-align: center;" name="user_name" placeholder="Username" type="text" required="required" maxlength="32" <?php if(!empty($_GET['user_name'])) { echo 'value="'.$_GET['user_name'].'"'; } ?> onInput="checkUsername()"/>
<br>
<span id="check-username"></span>
</p>
<p>
<select name="user_title" style="text-align: center;" required>
<optgroup>
<option value="" disabled <?php if(empty($_GET['user_title'])) { echo 'selected'; } ?>>Title</option>
<option value="Ms." <?php if($_GET['user_title'] == 'Ms.') { echo 'selected'; } ?>>Ms.</option>
<option value="Mrs." <?php if($_GET['user_title'] == 'Mrs.') { echo 'selected'; } ?>>Mrs.</option>
<option value="Mr." <?php if($_GET['user_title'] == 'Mr.') { echo 'selected'; } ?>>Mr.</option>
<option value="Dr." <?php if($_GET['user_title'] == 'Dr.') { echo 'selected'; } ?>>Dr.</option>
<option value="Pr." <?php if($_GET['user_title'] == 'Pr.') { echo 'selected'; } ?>>Pr.</option>
<optgroup>
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
<select name="timezone">
<optgroup>
<option style="text-align: center;" value="" disabled <?php if(empty($_GET['timezone']) || $_GET['timezone'] == '') { echo 'selected'; } ?>>Timezone</option>
<option value="-720" <?php if($_GET['timezone'] == '-720') { echo 'selected'; } ?>>(GMT -12:00) Eniwetok, Kwajalein</option>
<option value="-660" <?php if($_GET['timezone'] == '-660') { echo 'selected'; } ?>>(GMT -11:00) Midway Island, Samoa</option>
<option value="-600" <?php if($_GET['timezone'] == '-600') { echo 'selected'; } ?>>(GMT -10:00) Hawaii</option>
<option value="-570" <?php if($_GET['timezone'] == '-570') { echo 'selected'; } ?>>(GMT -9:30) Taiohae</option>
<option value="-540" <?php if($_GET['timezone'] == '-540') { echo 'selected'; } ?>>(GMT -9:00) Alaska</option>
<option value="-480" <?php if($_GET['timezone'] == '-480') { echo 'selected'; } ?>>(GMT -8:00) Pacific Time (US &amp; Canada)</option>
<option value="-420" <?php if($_GET['timezone'] == '-420') { echo 'selected'; } ?>>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
<option value="-360" <?php if($_GET['timezone'] == '-360') { echo 'selected'; } ?>>(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
<option value="-300" <?php if($_GET['timezone'] == '-300') { echo 'selected'; } ?>>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
<option value="-270" <?php if($_GET['timezone'] == '-270') { echo 'selected'; } ?>>(GMT -4:30) Caracas</option>
<option value="-240" <?php if($_GET['timezone'] == '-240') { echo 'selected'; } ?>>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
<option value="-210" <?php if($_GET['timezone'] == '-210') { echo 'selected'; } ?>>(GMT -3:30) Newfoundland</option>
<option value="-180" <?php if($_GET['timezone'] == '-180') { echo 'selected'; } ?>>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
<option value="-120" <?php if($_GET['timezone'] == '-120') { echo 'selected'; } ?>>(GMT -2:00) Mid-Atlantic</option>
<option value="-60" <?php if($_GET['timezone'] == '-60') { echo 'selected'; } ?>>(GMT -1:00) Azores, Cape Verde Islands</option>
<option value="0" <?php if(isset($_GET['timezone']) &&  $_GET['timezone'] == '0') { echo 'selected'; } ?>>(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
<option value="60" <?php if($_GET['timezone'] == '60') { echo 'selected'; } ?>>(GMT +1:00) Brussels, Copenhagen, Madrid, Paris</option>
<option value="120" <?php if($_GET['timezone'] == '120') { echo 'selected'; } ?>>(GMT +2:00) Kaliningrad, South Africa</option>
<option value="180" <?php if($_GET['timezone'] == '180') { echo 'selected'; } ?>>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
<option value="210" <?php if($_GET['timezone'] == '210') { echo 'selected'; } ?>>(GMT +3:30) Tehran</option>
<option value="240" <?php if($_GET['timezone'] == '240') { echo 'selected'; } ?>>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
<option value="270" <?php if($_GET['timezone'] == '270') { echo 'selected'; } ?>>(GMT +4:30) Kabul</option>
<option value="300" <?php if($_GET['timezone'] == '300') { echo 'selected'; } ?>>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
<option value="330" <?php if($_GET['timezone'] == '330') { echo 'selected'; } ?>>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
<option value="345" <?php if($_GET['timezone'] == '345') { echo 'selected'; } ?>>(GMT +5:45) Kathmandu, Pokhara</option>
<option value="360" <?php if($_GET['timezone'] == '360') { echo 'selected'; } ?>>(GMT +6:00) Almaty, Dhaka, Colombo</option>
<option value="390" <?php if($_GET['timezone'] == '390') { echo 'selected'; } ?>>(GMT +6:30) Yangon, Mandalay</option>
<option value="420" <?php if($_GET['timezone'] == '420') { echo 'selected'; } ?>>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
<option value="480" <?php if($_GET['timezone'] == '480') { echo 'selected'; } ?>>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
<option value="525" <?php if($_GET['timezone'] == '525') { echo 'selected'; } ?>>(GMT +8:45) Eucla</option>
<option value="540" <?php if($_GET['timezone'] == '540') { echo 'selected'; } ?>>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
<option value="570" <?php if($_GET['timezone'] == '570') { echo 'selected'; } ?>>(GMT +9:30) Adelaide, Darwin</option>
<option value="600" <?php if($_GET['timezone'] == '600') { echo 'selected'; } ?>>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
<option value="630" <?php if($_GET['timezone'] == '630') { echo 'selected'; } ?>>(GMT +10:30) Lord Howe Island</option>
<option value="660" <?php if($_GET['timezone'] == '660') { echo 'selected'; } ?>>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
<option value="690" <?php if($_GET['timezone'] == '690') { echo 'selected'; } ?>>(GMT +11:30) Norfolk Island</option>
<option value="720" <?php if($_GET['timezone'] == '720') { echo 'selected'; } ?>>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
<option value="765" <?php if($_GET['timezone'] == '765') { echo 'selected'; } ?>>(GMT +12:45) Chatham Islands</option>
<option value="780" <?php if($_GET['timezone'] == '780') { echo 'selected'; } ?>>(GMT +13:00) Apia, Nukualofa</option>
<option value="840" <?php if($_GET['timezone'] == '840') { echo 'selected'; } ?>>(GMT +14:00) Line Islands, Tokelau</option>
</optgroup></select>
</p>
<p>
<input name="antispam" type="text" style="text-align: center;" required="required" size="3" maxlength="1" placeholder="2 + 4 = ?" <?php if(!empty($_GET['antispam'])) { echo 'value="'.$_GET['antispam'].'"'; } ?>></p>
</p>
<input type="submit" value="REGISTER">
</form></body></html>