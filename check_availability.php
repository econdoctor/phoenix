<?php
include "connectdb.php";
if(!empty($_POST["username"])){
$sql = "SELECT * FROM phoenix_users WHERE user_name='" . $_POST["username"] . "'";
$res = $mysqli -> query($sql);
$nr = mysqli_num_rows($res);
if($nr>0) {
echo "<span style='color:#820000'><b>Not available</b></span>";
echo "<script>$('#submit').prop('disabled',true);</script>";
}
else {
echo "<span style='color:#033909'><b>Available</b></span>";
echo "<script>$('#submit').prop('disabled',false);</script>";}}
?>