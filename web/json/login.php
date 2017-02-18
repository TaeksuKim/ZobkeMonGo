<?php

header('Content-Type: application/json');

function throwFail() {
    echo json_encode("{ \"status\": \"FAIL\" }");
    exit();
}

$loginId = $_GET["loginId"];
$password = $_GET["loginPassword"];

include('../db_connect.inc');

$sql = "SELECT USER_ID, LOGIN_ID, USER_NAME, USER_KEY FROM POKE_USER WHERE LOGIN_ID = '$loginId' AND LOGIN_PASSWORD = PASSWORD('$password')";
$retval = mysql_query( $sql, $conn );
if(! $retval ) {
	mysql_close($conn);
    throwFail();
}

$userId = -1;
$userName = '';
$userKey = '';
$found = false;
while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) {
	$found = true;
	$userId = $row['USER_ID'];
	$userName = $row['USER_NAME'];
	$userKey = $row['USER_KEY'];
}

mysql_close($conn);

if (!$found) {
    throwFail();
}

$output = "{";
$output .= "\"status\":\"OK\"";
$output .= ",\"userId\":\"$userId\"";
$output .= ",\"loginId\":\"$loginId\"";
$output .= ",\"userName\":\"$userName\"";
$output .= ",\"userKey\":\"$userKey\"";
$output .= "}";

echo json_encode($output);
?>
