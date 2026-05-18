<?php
session_start();
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hak-Hak Korban - Satgas PPKS</title>
    <link rel="stylesheet" href="css/style.css"> 
    <link rel="stylesheet" href="css/hak.css"> </head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="container-hak">
        <header class="header-hak text-center">
            <img src="asset/image/logo-satgas.png" class="logo-satgas" alt="Logo Satgas">
            <h1 class="main-title">Hak-Hak Korban</h1>
        </header>

        <div class="hak-grid">
            
            <div class="hak-card purple-bg">
                <img src="asset/image/hak1.png" alt="Perlindungan" class="hak-icon">
                <div class="hak-content">
                    <h3>Hak Perlindungan</h3>
                    <p>Korban berhak mendapatkan perlindungan dari ancaman, intimidasi, atau tekanan dari pihak pelaku maupun pihak lain selama proses pelaporan dan penanganan kasus.</p>
                </div>
            </div>

            <div class="hak-card white-bg">
                <img src="asset/image/hak2.png" alt="Kerahasiaan" class="hak-icon">
                <div class="hak-content">
                    <h3>Hak Kerahasiaan Data</h3>
                    <p>Identitas korban dijamin kerahasiaannya oleh Satgas PPKS dan pihak kampus guna menjaga keamanan serta mencegah stigma sosial.</p>
                </div>
            </div>

            <div class="hak-card white-bg">
                <img src="asset/image/hak3.png" alt="Pendampingan" class="hak-icon">
                <div class="hak-content">
                    <h3>Hak Pendampingan</h3>
                    <p>Korban berhak mendapatkan pendampingan berupa:</p>
                    <ul>
                        <li>Pendampingan psikologis</li>
                        <li>Pendampingan hukum</li>
                        <li>Pendampingan selama proses investigasi</li>
                        <li>Konseling pemulihan trauma</li>
                    </ul>
                </div>
            </div>

            <div class="hak-card purple-bg">
                <img src="asset/image/hak4.png" alt="Pemulihan" class="hak-icon">
                <div class="hak-content">
                    <h3>Hak Pemulihan</h3>
                    <p>Korban berhak mendapatkan pemulihan berupa:</p>
                    <ul>
                        <li>Konseling psikologis</li>
                        <li>Dukungan akademik</li>
                        <li>Perlindungan lingkungan belajar</li>
                        <li>Rujukan layanan profesional</li>
                    </ul>
                </div>
            </div>

            <div class="hak-card purple-bg full-width">
                <img src="asset/image/hak5.png" alt="Keadilan" class="hak-icon">
                <div class="hak-content text-center">
                    <h3>Hak Mendapatkan Keadilan</h3>
                    <p>Korban berhak melanjutkan kasus melalui mekanisme penanganan kampus maupun jalur hukum sesuai dengan peraturan yang berlaku.</p>
                </div>
            </div>

        </div>
    </main>

    <?php include 'footer.php'; ?>

</body>
</html>