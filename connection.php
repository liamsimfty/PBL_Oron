<?php
$connection = mysqli_connect("localhost","root","","oron"); 
if (!$connection) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$connection->close();
?>