<?php
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql = "SELECT * FROM phoenix_users WHERE user_id IN (SELECT DISTINCT assign_teacher FROM phoenix_assign) ORDER BY RAND() LIMIT 3";
$res = $mysqli -> query($sql);
while($data = mysqli_fetch_assoc($res)){
$user_id = $data['user_id'];
$sql_del = "DELETE FROM phoenix_assign_questions_originality WHERE teacher_id = '".$user_id."'";
$res_del = $mysqli -> query($sql_del);
$sql_get = "SELECT DISTINCT question_id FROM phoenix_assign_questions INNER JOIN phoenix_assign ON phoenix_assign_questions.assign_id = phoenix_assign.assign_id WHERE phoenix_assign.assign_teacher = '".$user_id."' AND phoenix_assign.assign_active = '1'";
$res_get = $mysqli -> query($sql_get);
while($data_get = mysqli_fetch_assoc($res_get)){
$question_id = $data_get['question_id'];
$sql_count = "SELECT * FROM phoenix_assign_questions INNER JOIN phoenix_assign ON phoenix_assign_questions.assign_id = phoenix_assign.assign_id WHERE phoenix_assign_questions.question_id = '".$question_id."' AND phoenix_assign.assign_teacher = '".$user_id."' AND phoenix_assign.assign_active = '1'";
$res_count = $mysqli -> query($sql_count);
$nr_count = mysqli_num_rows($res_count);
$sql_ins = "INSERT INTO phoenix_assign_questions_originality (teacher_id, question_id, originality) VALUES ('".$user_id."', '".$question_id."', '".$nr_count."')";
$res_ins = $mysqli -> query($sql_ins);}}
echo 'Done';
?>