<?php include 'config/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Ikan - SIGI</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h2>SIGI</h2>
        <nav>
            <a href="index.php">Beranda</a>
            <a href="katalog.php">Katalog Ikan</a>
            <a href="keranjang.php">Keranjang</a>
        </nav>
    </header>

    <div class="container">
        <h2>Katalog Ikan Segar</h2>
        <div class="katalog-grid">
            <?php
            $query = "SELECT * FROM ikan WHERE status='aktif'";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                $status_stok = $row['stok'] > $row['stok_minimum'] ? "Tersedia" : ($row['stok'] > 0 ? "Stok Menipis" : "Habis");
            ?>
            <div class="card">
                <img src="assets/img/<?= $row['foto']; ?>" alt="<?= $row['nama_ikan']; ?>">
                <div class="card-body">
                    <span class="badge"><?= $row['kategori']; ?></span>
                    <h3><?= $row['nama_ikan']; ?></h3>
                    <p><i><?= $row['nama_ilmiah']; ?></i></p>
                    <p style="font-size:1.2rem; font-weight:bold; color:#0a9396;">
                        Rp <?= number_format($row['harga'], 0, ',', '.'); ?> / <?= $row['satuan']; ?>
                    </p>
                    <p>Status: <strong><?= $status_stok; ?> (<?= $row['stok']; ?>)</strong></p>
                    <a href="detail.php?id=<?= $row['id_ikan']; ?>" class="btn-primary">Lihat Detail</a>
                    <button class="btn-primary" onclick="tambahKeranjang(<?= $row['id_ikan']; ?>, '<?= $row['nama_ikan']; ?>', <?= $row['harga']; ?>)">+ Keranjang</button>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>