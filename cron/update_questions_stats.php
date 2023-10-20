<?php
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql_refresh = "SELECT question_id, assign_id FROM phoenix_assign_questions WHERE assign_question_refresh = '1' LIMIT 50";
$res_refresh = $mysqli -> query($sql_refresh);
while($data_refresh = mysqli_fetch_assoc($res_refresh)){
$question_id = $data_refresh['question_id'];
$assign_id = $data_refresh['assign_id'];
$sql_a = "SELECT COUNT(*) FROM phoenix_answers WHERE answer_type = '3' AND assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer = '1'";
$res_a = $mysqli -> query($sql_a);
$data_a = mysqli_fetch_assoc($res_a);
$answers_a = $data_a['COUNT(*)'];
$sql_b = "SELECT COUNT(*) FROM phoenix_answers WHERE answer_type = '3' AND assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer = '2'";
$res_b = $mysqli -> query($sql_b);
$data_b = mysqli_fetch_assoc($res_b);
$answers_b = $data_b['COUNT(*)'];
$sql_c = "SELECT COUNT(*) FROM phoenix_answers WHERE answer_type = '3' AND assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer = '3'";
$res_c = $mysqli -> query($sql_c);
$data_c = mysqli_fetch_assoc($res_c);
$answers_c = $data_c['COUNT(*)'];
$sql_d = "SELECT COUNT(*) FROM phoenix_answers WHERE answer_type = '3' AND assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer = '4'";
$res_d = $mysqli -> query($sql_d);
$data_d = mysqli_fetch_assoc($res_d);
$answers_d = $data_d['COUNT(*)'];
$total_answers = $answers_a + $answers_b + $answers_c + $answers_d;
if($total_answers > 0){
$rate_a = round($answers_a / $total_answers * 100, 2);
$rate_b = round($answers_b / $total_answers * 100, 2);
$rate_c = round($answers_c / $total_answers * 100, 2);
$rate_d = round($answers_d / $total_answers * 100, 2);
$sql_correct = "SELECT COUNT(*) FROM phoenix_answers WHERE answer_type = '3' AND assign_id = '".$assign_id."' AND question_id = '".$question_id."' AND answer_valid = '1'";
$res_correct = $mysqli -> query($sql_correct);
$data_correct = mysqli_fetch_assoc($res_correct);
$answers_correct = $data_correct['COUNT(*)'];
$rate_correct = round($answers_correct / $total_answers * 100, 2);
$sql_up = "UPDATE phoenix_assign_questions SET assign_question_rate_a = '".$rate_a."',  assign_question_rate_b = '".$rate_b."', assign_question_rate_c = '".$rate_c."', assign_question_rate_d = '".$rate_d."', assign_question_rate = '".$rate_correct."' WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."'";
$res_up = $mysqli -> query($sql_up);}
$sql_up2 = "UPDATE phoenix_assign_questions SET assign_question_refresh = '0' WHERE assign_id = '".$assign_id."' AND question_id = '".$question_id."'";
$res_up2 = $mysqli -> query($sql_up2);}
echo 'Done';
?>