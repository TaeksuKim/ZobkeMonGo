<?php

session_start();

$loginId = $_POST["loginId"];
$password = $_POST["loginPassword"];
$mode = $_POST["mode"];

include('redirect.inc');
include('db_connect.inc');

$sql = "SELECT USER_ID, LOGIN_ID, USER_NAME FROM POKE_USER WHERE LOGIN_ID = '$loginId' AND LOGIN_PASSWORD = PASSWORD('$password')";
$retval = mysql_query( $sql, $conn );
if(! $retval ) {
	die('Could not get data: ' . mysql_error());
	mysql_close($conn);
}

$found = false;
while($row = mysql_fetch_array($retval, MYSQL_ASSOC)) {
	$found = true;
	$_SESSION["userId"] = $row['USER_ID'];
	$_SESSION["userLoginId"] = "$loginId";
	$_SESSION["userName"] = $row['USER_NAME'];
}

mysql_close($conn);

if (!$found) {
	die("Login failed!");
	exit();
}

Redirect("/poke/list.php?mode=$mode", false);
?>
