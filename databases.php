<?php
// Membuat database
$sql = "CREATE DATABASE IF NOT EXISTS PBL_Oron";
if (mysqli_query($koneksi, $sql)) {
    echo "Database berhasil dibuat";
} else {
    echo "Error creating database: " . mysqli_error($koneksi);
}

?>