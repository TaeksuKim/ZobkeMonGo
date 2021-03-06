<?php
session_start();

header('Content-Type: application/json');

function throwFail() {
    echo json_encode("{ \"status\": \"FAIL\" }");
    exit();
}

$monsterId = $_POST["monsterId"];
if (!$monsterId) {
    throwFail();
}
if (!$_SESSION["userLoginId"]) {
    throwFail();
}

$loginId = $_SESSION["userLoginId"];

include('db_connect.inc');

$sql = "SELECT LOGIN_ID FROM POKE_MONSTER_OWNER WHERE MONSTER_ID = '$monsterId' AND LOGIN_ID = '$loginId'";
$retval = mysql_query( $sql, $conn );
if(! $retval ) {
    #die('Could not get data: ' . mysql_error());
    mysql_close($conn);
    throwFail();
}

$found = false;
while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) {
    $found = true;
}

$update_sql = "";
if ($found) {
    $update_sql .= "DELETE FROM POKE_MONSTER_OWNER WHERE MONSTER_ID = '$monsterId' AND LOGIN_ID = '$loginId'";
} else {
    $update_sql .= "INSERT INTO POKE_MONSTER_OWNER (MONSTER_ID, LOGIN_ID, EDIT_DATE) VALUES ('$monsterId', '$loginId', NOW())";
}
mysql_select_db($schema);
$retval = mysql_query( $update_sql, $conn );

if(! $retval ) {
    throwFail();
}
$output = "{";
$output .= "\"status\":\"OK\"";
$output .= "}";

mysql_close($conn);
echo json_encode($output);
?>
