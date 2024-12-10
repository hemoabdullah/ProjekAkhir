<?php

$host = "HAMMAM-LAP"; // Nama host SQL Server Anda
$database = "SharedDB"; // Nama database yang akan digunakan
$user = ""; // Kosongkan karena menggunakan Windows Authentication
$password = ""; // Kosongkan karena menggunakan Windows Authentication

// Koneksi info array
$connInfo = array(
    "Database" => $database,
    "UID" => "", // Kosongkan karena Windows Authentication
    "PWD" => "", // Kosongkan karena Windows Authentication
    "CharacterSet" => "UTF-8" // Opsional untuk encoding karakter
);

// Koneksi ke SQL Server
$conn = sqlsrv_connect($host, $connInfo);

if ($conn) {
    echo "Connection successful.<br/>";
} else {
    echo "Connection failed.";
    die(print_r(sqlsrv_errors(), true));
}
?>