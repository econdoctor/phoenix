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
echo 'Missing information about the paper';
exit();}
$sql_info = "SELECT * FROM phoenix_papers WHERE paper_reference = '".$paper_reference."'";
$res_info = $mysqli -> query($sql_info);
$num_rows_info = mysqli_num_rows($res_info);
if($num_rows_info == 0){
header("Location: add_paper.php");
exit();}
$data_info = mysqli_fetch_assoc($res_info);
$paper_id = $data_info['paper_id'];
$paper_syllabus = $data_info['paper_syllabus'];
$paper_a = $data_info['paper_a'];
$paper_b = $data_info['paper_b'];
$paper_c = $data_info['paper_c'];
$paper_d = $data_info['paper_d'];
$paper_e = $data_info['paper_e'];
$paper_f = $data_info['paper_f'];
$paper_g = $data_info['paper_g'];
if($paper_syllabus != 1 && ($paper_a == '0' || $paper_b == '0' || $paper_c == '0' || $paper_d == '0' || $paper_e == '0')){
header("Location: add_paper_gt.php?paper_reference=$paper_reference");
exit();}
if($paper_syllabus == 1 && ($paper_a == '0' || $paper_b == '0' || $paper_c == '0' || $paper_d == '0' || $paper_e == '0' || $paper_f == '0' || $paper_g == '0')){
header("Location: add_paper_gt.php?paper_reference=$paper_reference");
exit();}
$sql_q = "SELECT * FROM phoenix_questions WHERE question_paper_id = '".$paper_id."'";
$res_q = $mysqli -> query($sql_q);
$num_rows_q = mysqli_num_rows($res_q);
if($num_rows_q == 0){
header("Location: add_paper_ms.php?paper_reference=$paper_reference");
exit();}
$sql_ans = "SELECT * FROM phoenix_questions WHERE question_paper_id = '".$paper_id."'";
$res_ans = $mysqli -> query($sql_ans);
while($data_ans = mysqli_fetch_assoc($res_ans)){
$answer = $data_ans['question_answer'];
if($answer != 1 && $answer != 2 && $answer != 3 && $answer != 4 && $answer != 0){
header("Location: add_paper_ms.php?paper_reference=$paper_reference");
exit();}}
$target_check = './q/' . $paper_syllabus . '/' . $paper_id . '/';
$files_in_directory = scandir($target_check);
$items_count = count($files_in_directory);
if($items_count != 22){
echo 'Please upload questions 1 to 20.';
exit();}
for($j=1; $j<=20; $j++){
$filename_check = $_SERVER['DOCUMENT_ROOT'] . '/q/'.$paper_syllabus.'/'.$paper_id.'/'.$j.'.png';
if(!file_exists($filename_check)){
echo 'Error. Please check the file names.';
exit();}}
$target_dir = 'q/' . $paper_syllabus . '/' . $paper_id . '/';
if(isset($_POST['submit'])){
$fileCount = count($_FILES['file']['name']);
if($fileCount != 20){
echo 'Please upload questions 21 to 40';
exit();}
for($i=0; $i < $fileCount; $i++){
$target_file = $target_dir . basename($_FILES['file']['name'][$i]);
$uploadOk = 1;
$imageFileType_raw = pathinfo($target_file,PATHINFO_EXTENSION);
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$filename_pre = pathinfo($_FILES['file']['name'][$i], PATHINFO_FILENAME);
if(strlen($filename_pre) != 2 || !is_numeric($filename_pre) || $filename_pre < 21 || $filename_pre > 40){
echo "There's an issue with the file names. Please check them and try again.";
$uploadOk = 0;}
if($imageFileType_raw != 'png' && $imageFileType_raw != 'PNG'){
echo "The extension of your files must be .png or .PNG. Please rename them and try to upload them again.";
$uploadOk = 0;}
if(file_exists($target_file)){
echo "Sorry, some files already exist.";
$uploadOk = 0;}
if($_FILES["file"]["size"][$i] > 100000){
echo "Sorry, some files are too large.";
$uploadOk = 0;}
if($imageFileType != "png" && $imageFileType != "PNG"){
echo "Sorry, only .png or .PNG files are allowed.";
$uploadOk = 0;}
if($uploadOk == 0){
echo "Sorry, your files were not uploaded.";}
move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file);
if($imageFileType_raw == 'PNG'){
$initial = $target_file;
$final = $target_dir . $filename_pre . '.png';
rename($initial,$final);}}}
header("Location: qp_view.php?paper_id=$paper_id&s=$paper_syllabus");
exit();
?>