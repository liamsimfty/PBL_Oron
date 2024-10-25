<?php
$connection = mysqli_connect("localhost","root","","oron"); 
if (!$connection) {
    die("Connection Failed: " . mysqli_connect_error());
}

$connection->close();
?>