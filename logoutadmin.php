<?php
include 'connection.php';

session_unset();
session_destroy();
header("Location: admin.php");  

?>