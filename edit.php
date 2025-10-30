<?php
session_start();
include 'db_connect.php';

// Proteksi: Hanya admin bisa akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);  // Ambil ID dari URL, validasi integer
$error = '';
$success = '';

if ($id <= 0) {
    header("Location: index.php?error=ID tidak valid!");
    exit();
}

// Ambil data barang berdasarkan ID (PERBAIKAN: Gunakan 'id' bukan 'id_barang')
$stmt = $conn->prepare("SELECT * FROM barang WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: index.php?error=Data tidak ditemukan!");
    exit();
}

$row = $result->fetch_assoc();
$stmt->close();

// Proses update jika POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama_barang']);
    $harga = floatval($_POST['harga']);
    $stok = intval($_POST['stok']);
    $deskripsi = trim($_POST['deskripsi']);

    if (!empty($nama) && $harga > 0 && $stok >= 0) {
        $stmt = $conn->prepare("UPDATE barang SET nama_barang = ?, harga = ?, stok = ?, deskripsi = ? WHERE id = ?");
        $stmt->bind_param("sdsii", $nama, $harga, $stok, $deskripsi, $id);

        if ($stmt->execute()) {
            header("Location: index.php?success=Barang berhasil diupdate!");
            exit();
        } else {
            $error = "Error update: " . $stmt->error;
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
    <title>Edit Barang</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h2>Edit Barang: <?php echo htmlspecialchars($row['nama_barang']); ?></h2>

        <?php if ($error): ?><div class="alert error"><?php echo $error; ?></div><?php endif; ?>

        <form method="POST">
            <label>Nama Barang:</label>
            <input type="text" name="nama_barang" value="<?php echo htmlspecialchars($row['nama_barang']); ?>" required>

            <label>Harga (Rp):</label>
            <input type="number" name="harga" step="0.01" min="0" value="<?php echo $row['harga']; ?>" required>

            <label>Stok:</label>
            <input type="number" name="stok" min="0" value="<?php echo $row['stok']; ?>" required>

            <label>Deskripsi:</label>
            <textarea name="deskripsi" rows="4"><?php echo htmlspecialchars($row['deskripsi']); ?></textarea>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </form>
    </div>
</body>

</html>