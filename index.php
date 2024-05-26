<?php 
session_start();
$buttonPayment = null;
$buttonHapus = null;
$dataAwal = false;

if (isset($_POST['btn'])) {
    # Mengambil data dari form
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];

    if (isset($_SESSION['data_barang'])) {
        foreach ($_SESSION['data_barang'] as $data) {
            # Pengkondisian jika ada nama barang yang sama
            if ($data['nama'] == $nama) {
                $dataAwal = true;
                break;
            }
        }
    }

    if (!$dataAwal) { 
        # Menambahkan data pada form / session
        $_SESSION['data_barang'][] = [
            "nama" => $nama,
            "harga" => $harga,
            "jumlah" => $jumlah,
            "total" => $harga * $jumlah
        ];
    }
}

if (isset($_SESSION['data_barang']) && !empty($_SESSION['data_barang'])) {
    # Menampilkan button payment jika data sudah muncul
    $buttonPayment = '<a href="payment.php" class="btn btn-success mt-3"><i class="fa-solid fa-credit-card"></i> Bayar</a>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Barang</title>
<!-- Link to Bootstrap CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<!-- Link to Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Masukan Data Barang</h2>
    <form action="" method="POST" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" name="nama" class="form-control" placeholder="Nama Barang" required>
            </div>
            <div class="col-md-4">
                <input type="number" name="harga" class="form-control" placeholder="Harga" required>
            </div>
            <div class="col-md-4">
                <input type="number" name="jumlah" class="form-control" placeholder="Jumlah" required>
            </div>
        </div>
        <button type="submit" name="btn" class="btn btn-primary mt-3"><i class="fas fa-cart-plus"></i> Tambah</button>  
        <?= $buttonPayment; ?>
    </form>
        
    <!-- Memunculkan Alert -->
    <?php
    if (isset($_POST['btn'])) {
        if ($dataAwal) {
            # Jika data barang sudah ada
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Data barang sudah ada!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        } else {
            # Jika data berhasil ditambahkan 
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Data barang berhasil ditambahkan!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    }
    ?>
        
    <table class="table table-bordered text-center">
        <thead class="thead-light">
            <p class="text-secondary">List Barang:</p>
            <tr>
                <th class="table-secondary">No</th>
                <th class="table-secondary">Nama Barang</th>
                <th class="table-secondary">Harga</th>
                <th class="table-secondary">Jumlah</th>
                <th class="table-secondary">Total</th>
                <th class="table-secondary">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            $totalHarga = 0;
            $totalBarang = 0;
            if (isset($_SESSION['data_barang']) && is_array($_SESSION['data_barang']) && !empty($_SESSION['data_barang'])) : 
                foreach($_SESSION["data_barang"] as $key => $data) : 
                    $totalBarang += $data['jumlah'];
                    $totalHarga += $data['total'];
                    ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <!-- Menampilkan nama, harga, jumlah, dan total barang -->
                        <td><?= htmlspecialchars($data['nama']); ?></td>
                        <td>Rp <?= number_format($data['harga'], 0, ',', '.'); ?></td>
                        <td><?= htmlspecialchars($data['jumlah']); ?></td>
                        <td>Rp <?= number_format($data['total'], 0, ',', '.'); ?></td>
                        <td>
                            <a href="hapusData.php?id=<?= $key; ?>" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; 
            else : ?>
                <tr>
                    <td colspan="6" style="color: red;">Tidak ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="table-secondary">Total Barang</th>
                <th colspan="3"><?= $totalBarang; ?></th> 
            </tr>
            <tr>
                <th colspan="3" class="table-secondary">Total Harga</th>
                <th colspan="3">Rp <?= number_format($totalHarga, 0, ',', '.'); ?></th>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Link to Bootstrap JS and dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
