<?php
session_start();
include 'db_connect.php';

// Proteksi: Hanya admin bisa akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$success = '';
$error = '';
if (isset($_GET['success'])) $success = $_GET['success'];
if (isset($_GET['error'])) $error = $_GET['error'];

// Ambil data barang
$result = $conn->query("SELECT * FROM barang ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Manajemen Barang</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h1>Selamat Datang, <?php echo $_SESSION['username']; ?> (Admin)</h1>

        <?php if ($success): ?><div class="alert success"><?php echo $success; ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert error"><?php echo $error; ?></div><?php endif; ?>

        <div class="actions">
            <a href="add.php" class="btn btn-primary">Tambah Barang Baru</a>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Harga (Rp)</th>
                    <th>Stok</th>
                    <th>Deskripsi</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                        <td><?php echo number_format($row['harga'], 2, ',', '.'); ?></td>
                        <td><?php echo $row['stok']; ?></td>
                        <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-small btn-info">Edit</a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php if ($result->num_rows == 0): ?>
            <p>Tidak ada data barang. <a href="add.php">Tambah sekarang!</a></p>
        <?php endif; ?>
    </div>
</body>

</html>