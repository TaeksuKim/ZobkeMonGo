<?php
session_start();

if (!$_SESSION["userLoginId"]) {
    echo "{ status: 'FAIL' }";
    exit();
}
$loginId = $_SESSION["userLoginId"];

if (!$_GET["monsterId"]) {
    echo "{ status: 'FAIL' }";
    exit();
}
$monsterId = $_GET["monsterId"];

header('Content-Type: application/json');

include('db_connect.inc');

$sql = "SELECT B.USER_ID, B.LOGIN_ID, B.USER_NAME FROM POKE_MONSTER_OWNER A, POKE_USER B WHERE A.LOGIN_ID = B.LOGIN_ID AND A.MONSTER_ID = $monsterId ORDER BY B.USER_NAME ASC";
$retval = mysql_query( $sql, $conn );
if(! $retval ) {
    echo "{ status : 'FAIL' }";
    die('Could not get data: ' . mysql_error());
    mysql_close($conn);
    exit();
}
$x=0;
while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) {
    $object[$x]['userId'] = $row['USER_ID'];
    $object[$x]['loginId'] = $row['LOGIN_ID'];
    $object[$x]['userName'] = $row['USER_NAME'];
    $x++;
}

$output = "{";
$output .= "\"status\":\"OK\",";
$output .= "\"monser_id\":\"$monsterId\",";
$output .= "\"owners\": [";
$count = count($object);
for ($i = 0; $i < $count; $i++) {
    $obj = $object[$i];
    $userId = $obj['userId'];
    $loginId = $obj['loginId'];
    $userName = $obj['userName'];
    $output .= "{";
    $output .= "\"user_id\": \"$userId\", ";
    $output .= "\"login_id\": \"$loginId\", ";
    $output .= "\"user_name\": \"$userName\" ";
    $output .= "}";
    if ($i < $count - 1) {
        $output .= ",";
    }
}
$output .= "]";
$output .= "}";

mysql_close($conn);
echo json_encode($output);
?>
