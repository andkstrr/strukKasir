<?php
session_start();

$totalBelanja = 0;
$uang_dibayar = 0;
$kembalian = 0;

# Cek jika ada data barang dalam session
if (!isset($_SESSION['data_barang']) || empty($_SESSION['data_barang'])) {
    echo "<script>alert('Keranjang belanja kosong!'); window.location.href='index.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # Ambil jumlah uang yang dibayar dari form
    $uang_dibayar = $_POST['uang_dibayar'];

    # Hitung total belanja dari data barang dalam session
    foreach ($_SESSION['data_barang'] as $barang) {
        $totalBelanja += $barang['total'];
    }

    # Validasi jumlah uang yang dibayarkan
    if ($uang_dibayar < $totalBelanja) {
        # Jika uang tidak cukup
        echo "<script>alert('Uang yang dibayarkan kurang! Pembayaran dibatalkan.'); window.location.href='index.php';</script>";
        exit;
    } else {
        # Menghitung kembalian
        $kembalian = $uang_dibayar - $totalBelanja;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pembayaran</title>
<!-- Link to Bootstrap CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Pembayaran</h2>
    <h4>Total Belanja: Rp <?= number_format($totalBelanja, 0, ',', '.') ?></h4>
    <form action="" method="POST" class="mt-4">
        <div class="input-group mb-3">
            <!-- Form untuk menginput nominal pembayaran -->
            <span class="input-group-text">Rp</span>
            <input type="number" name="uang_dibayar" class="form-control" placeholder="Masukan Nominal Pembayaran" required>
        </div>
        <button type="submit" class="btn btn-success"><i class="fas fa-money-bill-wave"></i> Bayar</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && $uang_dibayar >= $totalBelanja): ?>
    <h2 class="text-center mt-5 mb-3">Bukti Pembayaran</h2>
    <!-- Menampilkan informasi pembayaran -->
    <p>No. Transaksi: <?= rand(100000000, 999999999); ?></p>
    <p>Bulan, tanggal: <?= date('F d, Y'); ?></p>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['data_barang'] as $barang): ?>
            <tr>
                <!-- Memunculkan nama, harga, jumlah dan total harga barang -->
                <td><?= $barang['nama'] ?></td>
                <td>Rp <?= number_format($barang['harga'], 0, ',', '.') ?></td>
                <td><?= $barang['jumlah'] ?></td>
                <td>Rp <?= number_format($barang['total'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- Memunculkan nominal pembayaran -->
    <p>Uang yang Dibayarkan: Rp <?= number_format($uang_dibayar, 0, ',', '.') ?></p>
    <p>Total: Rp <?= number_format($totalBelanja, 0, ',', '.') ?></p>
    <p>Kembalian: Rp <?= number_format($kembalian, 0, ',', '.') ?></p>
    <p>Terimakasih telah berbelanja di toko kami!</p>
    <a href="index.php" class="btn btn-primary mb-5">Kembali</a>
    <?php session_destroy(); ?>
    <?php endif; ?>
</div>
<!-- Link to Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
