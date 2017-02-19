<?php
include('../db_connect.inc');

$userKey = $_GET["key"];

$sql = "SELECT USER_ID, LOGIN_ID, USER_NAME, USER_KEY FROM POKE_USER WHERE USER_KEY = '$userKey'";
$retval = mysql_query( $sql, $conn );
if(! $retval ) {
	mysql_close($conn);
    echo json_encode("{ \"status\": \"FAIL\", \"message\":\"No permission\" }");
    exit();
}

$currentUserId = -1;
$currentLoginId = '';
$currentUserName = '';
$found = false;
while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) {
	$found = true;
	$currentUserId = $row['USER_ID'];
	$currentLoginId = $row['LOGIN_ID'];
	$currentUserName = $row['USER_NAME'];
}

mysql_close($conn);

if (!$found) {
    echo json_encode("{ \"status\": \"FAIL\", \"message\":\"No permission\" }");
    exit();
}
?>
