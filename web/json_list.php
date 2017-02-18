<?php
session_start();

if (!$_SESSION["userId"]) {
    echo "{ status: 'FAIL' }";
    exit();
}

header('Content-Type: application/json; charset=utf-8');

$loginId = $_POST["userLoginId"];
$password = $_POST["loginPassword"];

include('db_connect.inc');

$sql  = "";
$sql .= "SELECT ";
$sql .= "Z.MONSTER_ID AS MONSTER_ID, ";
$sql .= "Z.MONSTER_NUMBER AS MONSTER_NUMBER, ";
$sql .= "Z.MONSTER_NAME AS MONSTER_NAME, ";
$sql .= "Z.MONSTER_IMAGE AS MONSTER_IMAGE, ";
$sql .= "Z.OWNER_COUNT AS OWNER_COUNT, ";
$sql .= "IF(Z.EDIT_DATE < 100, 'N', IF(Z.NOW_DATE - Z.EDIT_DATE < 60 * 60 * 24, 'Y', 'N')) AS NEW_ITEM ";
$sql .= "FROM ";
$sql .= "( ";
$sql .= "SELECT ";
$sql .= "PM.MONSTER_ID, PM.MONSTER_NUMBER, ";
$sql .= "PM.MONSTER_NAME, PM.MONSTER_IMAGE, ";
$sql .= "(SELECT COUNT(LOGIN_ID) FROM POKE_MONSTER_OWNER WHERE MONSTER_ID = PM.MONSTER_ID) AS OWNER_COUNT, ";
$sql .= "UNIX_TIMESTAMP(IFNULL((SELECT MAX(EDIT_DATE) FROM POKE_MONSTER_OWNER ";
$sql .= "WHERE MONSTER_ID = PM.MONSTER_ID), 0)) AS EDIT_DATE, ";
$sql .= "UNIX_TIMESTAMP(NOW()) AS NOW_DATE ";
$sql .= "FROM POKE_MONSTER PM ";
$sql .= ") Z ";

$retval = mysql_query( $sql, $conn );
if(! $retval ) {
    echo "{ status : 'FAIL' }";
    die('Could not get data: ' . mysql_error());
    mysql_close($conn);
    exit();
}

$x=0;
while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) {
    $object[$x]['id'] = $row['MONSTER_ID'];
    $object[$x]['number'] = $row['MONSTER_NUMBER'];
    $object[$x]['name'] = $row['MONSTER_NAME'];
    $object[$x]['image'] = $row['MONSTER_IMAGE'];
    $object[$x]['ownerCount'] = $row['OWNER_COUNT'];
    $object[$x]['newItem'] = $row['NEW_ITEM'];
    $x++;
}

$count = count($object);
$output = "{";
$output .= "\"status\":\"OK\",";
$output .= "\"monsters\": [";
for ($i = 0; $i < $count; $i++) {
    $obj = $object[$i];
    $monsterId = $obj['id'];
    $monsterNumber = $obj['number'];
    $monsterName = $obj['name'];
    $monsterImage = $obj['image'];
    $ownerCount = $obj['ownerCount'];
    $newItem = $obj['newItem'];
    $output .= "{";
    $output .= "\"monster_id\": \"$monsterId\", ";
    $output .= "\"monster_number\": \"$monsterNumber\", ";
    $output .= "\"monster_name\": \"$monsterName\", ";
    $output .= "\"monster_image\": \"$monsterImage\", ";
    $output .= "\"owner_count\": \"$ownerCount\", ";
    $output .= "\"new_item\": \"$newItem\" ";
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
