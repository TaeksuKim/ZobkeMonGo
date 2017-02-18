<?php
session_start();

if (!$_SESSION["userLoginId"]) {
    echo "{ status: 'FAIL' }";
    exit();
}
$loginId = $_SESSION["userLoginId"];

header('Content-Type: application/json');

include('db_connect.inc');

$sql = "SELECT MONSTER_ID FROM POKE_MONSTER_OWNER WHERE LOGIN_ID = '$loginId'";
$retval = mysql_query( $sql, $conn );
if(! $retval ) {
    echo "{ status : 'FAIL' }";
    die('Could not get data: ' . mysql_error());
    mysql_close($conn);
    exit();
}
$x=0;
while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) {
    $object[$x] = $row['MONSTER_ID'];
    $x++;
}

$output = "{";
$output .= "\"status\":\"OK\",";
$output .= "\"monsters\": [";
$count = count($object);
for ($i = 0; $i < $count; $i++) {
    $output .= $object[$i];
    if ($i < $count - 1) {
        $output .= ",";
    }
}
$output .= "]";
$output .= "}";

mysql_close($conn);
echo json_encode($output);
?>
