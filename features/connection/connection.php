<?php

// Include autoload.php
require_once __DIR__ . '/../../vendor/autoload.php'; // Sesuaikan dengan struktur proyek

// Muat file .env dari direktori root
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'); // Path ke root proyek
$dotenv->load();

// Gunakan variabel dari .env
$conn = oci_connect($_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], 'localhost/xe');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
?>
