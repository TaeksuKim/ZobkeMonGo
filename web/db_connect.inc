<?php
include('db_info.inc');

$conn = mysql_connect($DB_HOST, $DB_USER, $DB_PASS);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

mysql_select_db($DB_NAME);
mysql_set_charset('utf8');
mysql_query("SET NAMES UTF8");
?>
