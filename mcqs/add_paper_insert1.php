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
$paper_reference = $_POST['paper_reference'];
if(empty($paper_reference)){
echo 'Missing information about the paper.';
exit();}
if(strlen($paper_reference) != '14' && strlen($paper_reference) != '13'){
echo 'The format of the paper reference is incorrect.';
exit();}
if(substr($paper_reference, 0, 4) != '0455' && substr($paper_reference, 0, 4) != '9708'){
echo 'Wrong syllabus code.';
exit();}
if(substr($paper_reference, 5, 1) != 's' && substr($paper_reference, 5, 1) != 'w' && substr($paper_reference, 5, 1) != 'm' && substr($paper_reference, 5, 1) != 'y'){
echo 'Wrong paper serie.';
exit();}
if(!is_numeric(substr($paper_reference, 6, 2)) || substr($paper_reference, 6, 2) < 18 || substr($paper_reference, 6, 2) > 23){
echo 'Wrong paper year.';
exit();}
if(substr($paper_reference, 9, 2) != 'qp' && substr($paper_reference, 9, 2) != 'sp'){
echo 'The format of the paper reference is incorrect.';
exit();}
if(substr($paper_reference, 5, 1) != 'y' && !is_numeric(substr($paper_reference, 12, 2))){
echo 'The format of the paper reference is incorrect.';
exit();}
if(substr($paper_reference, 5, 1) == 'y' && !is_numeric(substr($paper_reference, 12, 1))){
echo 'The format of the paper reference is incorrect.';
exit();}
if(substr($paper_reference, 0, 4) == '9708' && substr($paper_reference, 12, 1) != '1'){
echo 'The number of the paper reference is incorrect.';
exit();}
if(substr($paper_reference, 0, 4) == '0455' && substr($paper_reference, 12, 1) != '1' && substr($paper_reference, 12, 1) != '2'){
echo 'The number of the paper reference is incorrect.';
exit();}
if(substr($paper_reference, 5, 1) != 'y' && substr($paper_reference, 5, 1) != 'm' && substr($paper_reference, 13, 1) != '1' && substr($paper_reference, 13, 1) != '2' && substr($paper_reference, 13, 1) != '3'){
echo 'The version of the paper reference is incorrect.';
exit();}
if(substr($paper_reference, 5, 1) != 'y' && substr($paper_reference, 5, 1) == 'm' && substr($paper_reference, 13, 1) != '2'){
echo 'The version of the paper reference is incorrect.';
exit();}
if(substr($paper_reference, 4, 1) != '_' || substr($paper_reference, 8, 1) != '_' || substr($paper_reference, 11, 1) != '_'){
echo 'The format of the paper reference is incorrect.';
exit();}
$sql_check = "SELECT * FROM phoenix_papers WHERE paper_reference = '".$paper_reference."'";
$res_check = $mysqli -> query($sql_check);
$num_rows_check = mysqli_num_rows($res_check);
if($num_rows_check > 0){
header("Location: edit_paper.php?paper_reference=$paper_reference");
exit();}
if($num_rows_check == 0){
$paper_year = '20' . substr($paper_reference, 6, 2);
if(substr($paper_reference, 0, 4) == '0455' && substr($paper_reference, 12, 1) == '1'){
$paper_syllabus = '1';}
if(substr($paper_reference, 0, 4) == '0455' && substr($paper_reference, 12, 1) == '2'){
$paper_syllabus = '2';}
if(substr($paper_reference, 0, 4) == '9708'){
$paper_syllabus = '3';}
if(substr($paper_reference, 5, 1) == 's'){
$paper_serie = '2';}
if(substr($paper_reference, 5, 1) == 'w'){
$paper_serie = '3';}
if(substr($paper_reference, 5, 1) == 'm'){
$paper_serie = '1';}
if(substr($paper_reference, 5, 1) == 'y'){
$paper_serie = '4';}
if($paper_serie != 4){
$paper_version = substr($paper_reference, -1, 1);}
if($paper_serie == 4){
$paper_version = 0;}
$paper_hidden = '0';
$paper_id = $paper_syllabus.substr($paper_reference, 6, 2).$paper_serie.$paper_version;
$sql_insert = "INSERT INTO phoenix_papers (paper_id, paper_reference, paper_syllabus, paper_year, paper_serie, paper_version, paper_hidden)
VALUES ('".$paper_id."', '".$paper_reference."', '".$paper_syllabus."', '".$paper_year."', '".$paper_serie."', '".$paper_version."', '".$paper_hidden."')";
$res_insert = $mysqli -> query($sql_insert);
header("Location: add_paper_gt.php?paper_reference=$paper_reference");}
?>