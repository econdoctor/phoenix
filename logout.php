<?php
session_start();
if(isset($_SESSION['phoenix_user_id'])){
session_destroy();}
header("Location: login.php");
?>
