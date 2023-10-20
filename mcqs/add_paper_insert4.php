<?php
session_start();
include "../connectdb.php";
$user_id = $_SESSION['phoenix_user_id'];
if(empty($user_id)){
$user_id = $_GET['user_id'];
$_SESSION['phoenix_user_id'] = $user_id;}
$question_id = $_GET['question_id'];
$s = $_GET['s'];
$question_number = $_GET['question_number'];
$post_unit = 'topic_unit' . $question_number;
$post_module = 'topic_module' . $question_number;
$question_unit = $_POST[$post_unit];
$question_module = $_POST[$post_module];
if($question_unit == 0 || $question_module == 0){
$question_topic_id = 999;}
if($question_unit != 0 && $question_module != 0){
$sql_pop = "SELECT * FROM phoenix_topics WHERE topic_syllabus = '".$s."' AND topic_unit_id = '".$question_unit."' AND topic_module_id = '".$question_module."'";
$result_pop = $mysqli -> query($sql_pop);
$data_pop = mysqli_fetch_assoc($result_pop);
$question_topic_id = $data_pop['topic_id'];}
$sql_current = "SELECT * FROM phoenix_questions WHERE question_id = '".$question_id."'";
$result_current = $mysqli -> query($sql_current);
$data_current = mysqli_fetch_assoc($result_current);
$question_unit_current = $data_current['question_unit'];
$question_module_current = $data_current['question_module'];
if($question_unit_current != $question_unit){
$question_module_up = 0;
$question_topic_id_up = 999;}
if($question_unit_current == $question_unit){
$question_module_up = $question_module;
$question_topic_id_up = $question_topic_id;}
$sql_update = "UPDATE phoenix_questions SET question_unit = '".$question_unit."', question_module = '".$question_module_up."', question_topic_id = '".$question_topic_id_up."' WHERE question_id = '".$question_id."'";
$result_update = $mysqli -> query($sql_update);
$sql_ans = "UPDATE phoenix_answers SET topic_id = '".$question_topic_id_up."' WHERE question_id = '".$question_id."' AND answer_type = '2'";
$res_ans = $mysqli -> query($sql_ans);
?>
<!doctype html>
<html>
<head>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<?php
if(($question_module != $question_module_current && $question_unit_current != 0) || ($question_unit != $question_unit_current && $question_unit_current != 0)){
echo '<script>
$(document).ready(function(){
var phpVar = '.$question_number.'
window.parent.$( "#" + phpVar ).load(window.parent.location.href + " #" + phpVar );});
</script>';}
?>
</head>
<body>
</body>
</html>