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
echo 'Missing information about the paper.';
exit();}
$sql_check = "SELECT * FROM phoenix_papers WHERE paper_reference = '".$paper_reference."'";
$res_check = $mysqli -> query($sql_check);
$num_rows_check = mysqli_num_rows($res_check);
if($num_rows_check == 0){
header("Location: add_paper.php");
exit();}
if(substr($paper_reference, 0, 4) == '9708' || (substr($paper_reference, 0, 4) == '0455' && substr($paper_reference, 12, 1) == '2')){
$min_a = $_POST['min_a'];
$min_b = $_POST['min_b'];}
$min_c = $_POST['min_c'];
$min_d = $_POST['min_d'];
$min_e = $_POST['min_e'];
if(substr($paper_reference, 0, 4) == '0455'){
$min_f = $_POST['min_f'];
$min_g = $_POST['min_g'];}
if(!isset($min_c) || !isset($min_d) || !isset($min_e)){
echo 'All fields are required.<br>Please go back and try again.';
exit();}
if(substr($paper_reference, 0, 4) == '0455' && (!isset($min_f) || !isset($min_g))){
echo 'All fields are required.<br>Please go back and try again.';
exit();}
if((substr($paper_reference, 0, 4) == '9708' || (substr($paper_reference, 0, 4) == '0455' && substr($paper_reference, 12, 1) == '2')) && (!isset($min_a) || !isset($min_b))){
echo 'All fields are required.<br>Please go back and try again.';
exit();}
if(!is_numeric($min_c) || !is_numeric($min_d) || !is_numeric($min_e)){
echo 'The grade thresholds must be integers.<br>Please go back and try again.';
exit();}
if(substr($paper_reference, 0, 4) == '0455' && (!is_numeric($min_f) || !is_numeric($min_g))){
echo 'The grade thresholds must be integers.<br>Please go back and try again.';
exit();}
if((substr($paper_reference, 0, 4) == '9708' || (substr($paper_reference, 0, 4) == '0455' && substr($paper_reference, 12, 1) == '2')) && (!is_numeric($min_a) || !is_numeric($min_b))){
echo 'The grade thresholds must be integers.<br>Please go back and try again.';
exit();}
if(substr($paper_reference, 0, 4) == '9708' && $min_a < 40 && $min_a > $min_b && $min_b > $min_c && $min_c > $min_d && $min_d > $min_e && $min_e > 0){
$sql_update = "UPDATE phoenix_papers SET paper_a = '".$min_a."', paper_b = '".$min_b."', paper_c = '".$min_c."', paper_d = '".$min_d."', paper_e = '".$min_e."'WHERE paper_reference = '".$paper_reference."'";
$res_update = $mysqli -> query($sql_update);
header("Location: add_paper_ms.php?paper_reference=$paper_reference");
exit();}
if(substr($paper_reference, 0, 4) == '0455' && substr($paper_reference, 12, 1) == '2' && $min_a < 40 && $min_a > $min_b && $min_b > $min_c && $min_c > $min_d && $min_d > $min_e && $min_e > $min_f && $min_f > $min_g && $min_g > 0){
$sql_update = "UPDATE phoenix_papers SET paper_a = '".$min_a."', paper_b = '".$min_b."', paper_c = '".$min_c."', paper_d = '".$min_d."', paper_e = '".$min_e."', paper_f = '".$min_f."', paper_g = '".$min_g."' WHERE paper_reference = '".$paper_reference."'";
$res_update = $mysqli -> query($sql_update);
header("Location: add_paper_ms.php?paper_reference=$paper_reference");
exit();}
if(substr($paper_reference, 0, 4) == '0455' && substr($paper_reference, 12, 1) == '1' && $min_c < 40 && $min_c > $min_d && $min_d > $min_e && $min_e > $min_f && $min_f > $min_g && $min_g > 0){
$sql_update = "UPDATE phoenix_papers SET paper_a = '99', paper_b = '99', paper_c = '".$min_c."', paper_d = '".$min_d."', paper_e = '".$min_e."', paper_f = '".$min_f."', paper_g = '".$min_g."' WHERE paper_reference = '".$paper_reference."'";
$res_update = $mysqli -> query($sql_update);
header("Location: add_paper_ms.php?paper_reference=$paper_reference");
exit();}
else {
echo 'The grade thresholds you entered does not appear to be correct.<br>Please go back and try again.';
exit();}
?>