<?php
$user = "system";
$pass = "mufti123";
$host = "localhost/XE";
$dbconn = oci_connect($user,$pass,$host);
if(!$dbconn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message']), ENT_QUOTES, E_USER_ERROR);
} else {
    echo "ORACLE DATABASES CONNECTED SUCCESSFULLY";
}
?>
