<?php
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "UPDATE phoenix_users SET user_archived = '0' WHERE user_last_login >= curdate() - interval 1 year";
$res = $mysqli -> query($sql);
$sql2 = "UPDATE phoenix_users SET user_archived = '1' WHERE user_last_login < curdate() - interval 1 year";
$res2 = $mysqli -> query($sql2);
echo 'Done';
?>