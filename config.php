<?php

define('HOST', 'Localhost');
define('DB_name', 'market');
define('DB_user', 'root');
define('DB_pass', '12312');
define('NONCE', '384729873492473947293472938573498574395438');
define('KAY', 'skdlfjli9euteirfusdes9oeupt54t94ruwpwe9w35r90438r0r234905sfuuzHHKw45');
$conn = mysqli_connect(HOST, DB_user, DB_pass, DB_name) or die('db_connection_error!..');
mysqli_set_charset($conn, "utf8");

//$pass_diuration = 2592000; // 30 dge wamebshi 2592000
$rowsAtPage = 10; // ramdeni chanaweri achvenos ert chvenebaze
?>