<!doctype html>
<html>
<head>
<title>Phoenix</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
include "../connectdb.php";
$s = $_GET['s'];
if(empty($s)){
echo 'Missing information';
exit();}
$reqm = "SELECT * FROM phoenix_topics WHERE topic_syllabus = '".$s."' AND topic_unit_id = '".$_POST["topic_unit"]."' ORDER BY topic_module_id ASC";
$resm = $mysqli->query($reqm);
?>
<option value="0">Choose a Chapter</option>
<?php
while($dsm = $resm -> fetch_assoc()){
?>
<option value="<?php echo $dsm["topic_module_id"]; ?>"><?php echo $dsm["topic_module"]; ?></option>
<?php
}
?>
</body></html>