<?php
session_start();
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dukungan & Edukasi - Satgas PPKS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/edukasi.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="container-edukasi">
        <header class="header-edukasi text-center">
            <img src="asset/image/logo-satgas.png" class="logo-satgas" alt="Logo Satgas">
            <h1 class="main-title">Dukungan & Edukasi</h1>
            <p class="subtitle">
                Pelajari lebih lanjut mengenai <strong>kekerasan seksual</strong> di lingkungan kampus, hak-hak korban, serta langkah-langkah yang perlu dilakukan. <br>
                Kami menyediakan <strong>layanan bantuan 24/7</strong> untuk mendukung Anda.
            </p>
        </header>

        <div class="cards-grid">
            
            <div class="card-item">
                <div class="card-content">
                    <img src="asset\image\pertanyaan.png" alt="Apa itu kekerasan seksual" class="card-img">
                    <div class="card-text">
                        <h3>Apa itu kekerasan seksual?</h3>
                        <p>Pelajari apa yang dimaksud dengan kekerasan seksual, jenis-jenisnya, serta dampaknya di lingkungan kampus.</p>
                        <a href="edu.php" class="btn-more" style="text-decoration: none; display: inline-block;">Selengkapnya >></a>
                    </div>
                </div>
            </div>

            <div class="card-item">
                <div class="card-content">
                    <img src="asset/image/lindungi.png" alt="Hak-hak korban" class="card-img">
                    <div class="card-text">
                        <h3>Hak-hak korban</h3>
                        <p>Ketahui hak-hak yang dimiliki korban kekerasan seksual serta perlindungan yang bisa didapatkan untuk mendukung pemulihan.</p>
                        <a href="hak_korban.php" class="btn-more" style="text-decoration: none; display: inline-block;">Selengkapnya >></a>
                    </div>
                </div>
            </div>

            <div class="card-item">
                <div class="card-content">
                    <img src="asset/image/langkah.png" alt="Langkah-langkah" class="card-img">
                    <div class="card-text">
                        <h3>Langkah-langkah jika kamu mengalami kekerasan</h3>
                        <p>Panduan langkah-langkah yang perlu diambil setelah mengalami kekerasan seksual untuk mendapatkan bantuan dan perlindungan.</p>
                        <a href="langkah.php" class="btn-more" style="text-decoration: none; display: inline-block;">Selengkapnya >></a>
                    </div>
                </div>
            </div>

            <div class="card-item">
                <div class="card-content">
                    <img src="asset/image/help.png" alt="Layanan pendampingan" class="card-img">
                    <div class="card-text">
                        <h3>Layanan pendampingan</h3>
                        <p>Hubungi layanan bantuan dan konseling 24/7 yang siap membantu Anda melalui telepon atau pesan singkat.</p>
                        <a href="layanan.php" class="btn-more" style="text-decoration: none; display: inline-block;">Selengkapnya >></a>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <?php include 'footer.php'; ?>

</body>
</html>