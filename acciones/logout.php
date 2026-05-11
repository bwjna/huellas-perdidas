<?php 
session_start();
session_destroy();
header("location: /huellas-perdidas/index.php");
die();
?>