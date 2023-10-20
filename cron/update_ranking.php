<?php
include "../connectdb.php";
if($mysqli -> connect_errno){
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();}
$sql_count_igcse = "SELECT * FROM phoenix_questions WHERE question_new_syllabus = '1' AND question_answer <> '0'";
$res_count_igcse = $mysqli -> query($sql_count_igcse);
$count_igcse = mysqli_num_rows($res_count_igcse);
$sql_count_as = "SELECT * FROM phoenix_questions WHERE question_new_syllabus = '2' AND question_answer <> '0'";
$res_count_as = $mysqli -> query($sql_count_as);
$count_as = mysqli_num_rows($res_count_as);
$sql_count_a2 = "SELECT * FROM phoenix_questions WHERE question_new_syllabus = '3' AND question_answer <> '0'";
$res_count_a2 = $mysqli -> query($sql_count_a2);
$count_a2 = mysqli_num_rows($res_count_a2);
$sql1 = "SELECT * FROM phoenix_users WHERE user_type = '1' AND user_refresh = '1' LIMIT 25";
$res1 = $mysqli -> query($sql1);
while($data1 = mysqli_fetch_assoc($res1)){
$check_id = $data1['user_id'];
$user_syllabus = $data1['user_syllabus'];
// BEGIN IGCSE
$sql_answers_igcse = "SELECT DISTINCT question_id FROM phoenix_answers WHERE user_id = '".$check_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '1')";
$res_answers_igcse = $mysqli -> query($sql_answers_igcse);
$answers_igcse = mysqli_num_rows($res_answers_igcse);
$rate_igcse = round($answers_igcse / $count_igcse * 100, 2);
$sql_answers2_igcse = "SELECT * FROM phoenix_answers WHERE user_id = '".$check_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '1')";
$res_answers2_igcse = $mysqli -> query($sql_answers2_igcse);
$answers2_igcse = mysqli_num_rows($res_answers2_igcse);
if($answers2_igcse == 0){
$score_igcse = '0.00';}
if($answers2_igcse > 0){
$sql_valid_igcse = "SELECT * FROM phoenix_answers WHERE user_id = '".$check_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '1') AND answer_valid = '1'";
$res_valid_igcse = $mysqli -> query($sql_valid_igcse);
$valid_igcse = mysqli_num_rows($res_valid_igcse);
$correct_igcse = round($valid_igcse / $answers2_igcse * 100, 2);
$score_igcse = round($correct_igcse * $rate_igcse / 100, 2);}
$sql_up_igcse = "UPDATE phoenix_users SET user_score_igcse = '".$score_igcse."' WHERE user_id = '".$check_id."'";
$res_up_igcse = $mysqli -> query($sql_up_igcse);
// END IGCSE
// BEGIN AS
$sql_answers_as = "SELECT DISTINCT question_id FROM phoenix_answers WHERE user_id = '".$check_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '2')";
$res_answers_as = $mysqli -> query($sql_answers_as);
$answers_as = mysqli_num_rows($res_answers_as);
$rate_as = round($answers_as / $count_as * 100, 2);
$sql_answers2_as = "SELECT * FROM phoenix_answers WHERE user_id = '".$check_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '2')";
$res_answers2_as = $mysqli -> query($sql_answers2_as);
$answers2_as = mysqli_num_rows($res_answers2_as);
if($answers2_as == 0){
$score_as = '0.00';}
if($answers2_as > 0){
$sql_valid_as = "SELECT * FROM phoenix_answers WHERE user_id = '".$check_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '2') AND answer_valid = '1'";
$res_valid_as = $mysqli -> query($sql_valid_as);
$valid_as = mysqli_num_rows($res_valid_as);
$correct_as = round($valid_as / $answers2_as * 100, 2);
$score_as = round($correct_as * $rate_as / 100, 2);}
$sql_up_as = "UPDATE phoenix_users SET user_score_as = '".$score_as."' WHERE user_id = '".$check_id."'";
$res_up_as = $mysqli -> query($sql_up_as);
// END AS
// BEGIN A2
$sql_answers_a2 = "SELECT DISTINCT question_id FROM phoenix_answers WHERE user_id = '".$check_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '3')";
$res_answers_a2 = $mysqli -> query($sql_answers_a2);
$answers_a2 = mysqli_num_rows($res_answers_a2);
$rate_a2 = round($answers_a2 / $count_a2 * 100, 2);
$sql_answers2_a2 = "SELECT * FROM phoenix_answers WHERE user_id = '".$check_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '3')";
$res_answers2_a2 = $mysqli -> query($sql_answers2_a2);
$answers2_a2 = mysqli_num_rows($res_answers2_a2);
if($answers2_a2 == 0){
$score_a2 = '0.00';}
if($answers2_a2 > 0){
$sql_valid_a2 = "SELECT * FROM phoenix_answers WHERE user_id = '".$check_id."' AND answer_type < '3' AND question_id IN (SELECT question_id FROM phoenix_questions WHERE question_new_syllabus = '3') AND answer_valid = '1'";
$res_valid_a2 = $mysqli -> query($sql_valid_a2);
$valid_a2 = mysqli_num_rows($res_valid_a2);
$correct_a2 = round($valid_a2 / $answers2_a2 * 100, 2);
$score_a2 = round($correct_a2 * $rate_a2 / 100, 2);}
$sql_up_a2 = "UPDATE phoenix_users SET user_score_a2 = '".$score_a2."' WHERE user_id = '".$check_id."'";
$res_up_a2 = $mysqli -> query($sql_up_a2);
// END A2
// BEGIN GEN
if($user_syllabus == '1'){
$score_gen = $score_igcse;}
if($user_syllabus == '2'){
$score_gen = $score_as;}
if($user_syllabus == '3'){
$score_gen = $score_a2;}
$sql_up_gen = "UPDATE phoenix_users SET user_score_gen = '".$score_gen."', user_refresh = '0' WHERE user_id = '".$check_id."'";
$res_up_gen = $mysqli -> query($sql_up_gen);
// END GEN
}
echo 'Done';
exit();
?>
