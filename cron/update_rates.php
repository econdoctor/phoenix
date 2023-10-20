<?php

include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}

$sql_get = "SELECT * FROM phoenix_questions WHERE question_refresh = '1' AND question_answer <> '0' LIMIT 50";
$res_get = $mysqli -> query($sql_get);

while($data_get = mysqli_fetch_assoc($res_get)){

$question_id = $data_get['question_id'];
$question_answer = $data_get['question_answer'];

$sql_ans = "SELECT * FROM phoenix_answers WHERE question_id = '".$question_id."' AND answer <> '0'";
$res_ans = $mysqli -> query($sql_ans);
$nr_ans = number_format(mysqli_num_rows($res_ans));

if($nr_ans == 0){

$sql_up = "UPDATE phoenix_questions SET question_rate = NULL, question_rate_a = NULL, question_rate_b = NULL, question_rate_c = NULL, question_rate_d = NULL, question_refresh = '0' WHERE question_id = '".$question_id."'";
$res_up = $mysqli -> query($sql_up);}

if($nr_ans > 0){

$sql_a = "SELECT * FROM phoenix_answers WHERE question_id = '".$question_id."' AND answer = '1'";
$res_a = $mysqli -> query($sql_a);
$nr_a = number_format(mysqli_num_rows($res_a));

if($question_answer == '1'){
$nr_correct = $nr_a;}

$question_rate_a = round($nr_a / $nr_ans * 100, 2);

$sql_b = "SELECT * FROM phoenix_answers WHERE question_id = '".$question_id."' AND answer = '2'";
$res_b = $mysqli -> query($sql_b);
$nr_b = number_format(mysqli_num_rows($res_b));

if($question_answer == '2'){
$nr_correct = $nr_b;}

$question_rate_b = round($nr_b / $nr_ans * 100, 2);

$sql_c = "SELECT * FROM phoenix_answers WHERE question_id = '".$question_id."' AND answer = '3'";
$res_c = $mysqli -> query($sql_c);
$nr_c = number_format(mysqli_num_rows($res_c));

if($question_answer == '3'){
$nr_correct = $nr_c;}

$question_rate_c = round($nr_c / $nr_ans * 100, 2);

$sql_d = "SELECT * FROM phoenix_answers WHERE question_id = '".$question_id."' AND answer = '4'";
$res_d = $mysqli -> query($sql_d);
$nr_d = number_format(mysqli_num_rows($res_d));

if($question_answer == '4'){
$nr_correct = $nr_d;}

$question_rate_d = round($nr_d / $nr_ans * 100, 2);

$question_rate = round($nr_correct / $nr_ans * 100, 2);

$sql_up = "UPDATE phoenix_questions SET question_rate = '".$question_rate."', question_rate_a = '".$question_rate_a."', question_rate_b = '".$question_rate_b."', question_rate_c = '".$question_rate_c."', question_rate_d = '".$question_rate_d."', question_refresh = '0' WHERE question_id = '".$question_id."'";
$res_up = $mysqli -> query($sql_up);
}}
echo 'Done ';

?>