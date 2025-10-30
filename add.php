<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama_barang']);
    $harga = floatval($_POST['harga']);
    $stok = intval($_POST['stok']);
    $deskripsi = trim($_POST['deskripsi']);

    if (!empty($nama) && $harga > 0 && $stok >= 0) {
        $stmt = $conn->prepare("INSERT INTO barang (nama_barang, harga, stok, deskripsi) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $nama, $harga, $stok, $deskripsi);

        if ($stmt->execute()) {
            header("Location: index.php?success=Barang berhasil ditambahkan!");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Isi semua field dengan benar!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h2>Tambah Barang Baru</h2>

        <?php if (isset($error)): ?><div class="alert error"><?php echo $error; ?></div><?php endif; ?>

        <form method="POST">
            <label>Nama Barang:</label>
            <input type="text" name="nama_barang" required>

            <label>Harga (Rp):</label>
            <input type="number" name="harga" step="0.01" min="0" required>

            <label>Stok:</label>
            <input type="number" name="stok" min="0" required>

            <label>Deskripsi:</label>
            <textarea name="deskripsi" rows="4"></textarea>

            <button type="submit" class="btn btn-primary">Tambah</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>

</html>