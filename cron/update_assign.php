<?php
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql_max = "SELECT MAX(assign_id) FROM phoenix_assign";
$res_max = $mysqli -> query($sql_max);
$data_max = mysqli_fetch_assoc($res_max);
$assign_max = $data_max['MAX(assign_id)'];
$limit = $assign_max - 10;
$sql_get = "SELECT * FROM phoenix_assign WHERE assign_active = '0' AND assign_id < '".$limit."'";
$res_get = $mysqli -> query($sql_get);
while($data_get = mysqli_fetch_assoc($res_get)){
$assign_id = $data_get['assign_id'];
$sql13 = "DELETE FROM phoenix_assign WHERE assign_id = '".$assign_id."'";
$res13 = $mysqli -> query($sql13);
$sql14 = "DELETE FROM phoenix_assign_qp WHERE assign_id = '".$assign_id."'";
$res14 = $mysqli -> query($sql14);
$sql15 = "DELETE FROM phoenix_assign_t WHERE assign_id = '".$assign_id."'";
$res15 = $mysqli -> query($sql15);
$sql16 = "DELETE FROM phoenix_assign_questions WHERE assign_id = '".$assign_id."'";
$res16 = $mysqli -> query($sql16);
$sql17 = "DELETE FROM phoenix_assign_users WHERE assign_id = '".$assign_id."'";
$res17 = $mysqli -> query($sql17);}
echo 'Done';
?>