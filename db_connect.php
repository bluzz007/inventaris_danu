<?php
$servername = "localhost";
$username = "root";  // Ganti jika beda (misal di hosting)
$password = "";      // Default XAMPP kosong
$dbname = "db_aplikasi";  // Dari skrip SQL sebelumnya

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");  // Dukung karakter Indonesia
