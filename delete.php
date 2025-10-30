<?php
session_start();
include 'db_connect.php';

// Proteksi: Hanya admin bisa akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);  // Ambil ID dari URL, validasi integer
$success = '';
$error = '';

if ($id <= 0) {
    header("Location: index.php?error=ID tidak valid!");
    exit();
}

// Proses delete (PERBAIKAN: Gunakan 'id' bukan 'id_barang')
$stmt = $conn->prepare("DELETE FROM barang WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        header("Location: index.php?success=Barang berhasil dihapus!");
    } else {
        header("Location: index.php?error=Data tidak ditemukan atau sudah dihapus!");
    }
} else {
    header("Location: index.php?error=Error hapus: " . $stmt->error);
}

$stmt->close();
$conn->close();
exit();
