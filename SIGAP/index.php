<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage - Sistem Pelaporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main>
      <section class="hero">
        <div class="container">
          <div class="hero-wrapper">
            <div class="hero-text">
              <h1>Sistem Pelaporan Kekerasan Seksual</h1>
              <h2>Universitas Bakti Tunas Husada</h2>
              <p class="lead">
                Sistem ini menyediakan layanan pelaporan kekerasan seksual secara anonim. Laporan akan diproses oleh Satgas PPKS.
              </p>
              <a href="lapor.php" class="btn btn-lg btn-custom">Buat laporan »</a>
            </div>
            <div class="hero-image">
              <img src="asset/image/hero-logo.png" alt="Ilustrasi" />
            </div>
          </div>
        </div>
      </section>

      <section class="container mt-5">
        <div class="row g-4">
          <div class="col-md-4">
            <a href="lapor.php" class="card-action card-purple">
              <img src="asset/image/buat.png" alt="Icon" class="card-icon" />
              <h5>Lapor Anonim</h5>
              <p>Laporkan tanpa identitas.</p>
            </a>
          </div>
          <div class="col-md-4">
            <a href="tracking.php" class="card-action card-purple">
              <img src="asset/image/tracking.png" alt="Icon" class="card-icon" />
              <h5>Tracking Laporan</h5>
              <p>Pantau perkembangan laporan.</p>
            </a>
          </div>
          <div class="col-md-4">
            <a href="edukasi.php" class="card-action card-purple">
              <img src="asset/image/dukungan.png" alt="Icon" class="card-icon" />
              <h5>Dukungan & Edukasi</h5>
              <p>Informasi dan bantuan korban.</p>
            </a>
          </div>
        </div>
      </section>

      <section class="container text-center mt-5 mb-5">
        <h4 class="mb-4">Informasi & Dukungan</h4>
        <div class="row g-4">
          <div class="col-md-4">
            <a href="#" class="card-action card-light">
              <img src="asset/image/panduan.png" alt="Icon" class="card-icon" />
              <h5>Panduan Pelaporan</h5>
              <p>Langkah melapor.</p>
            </a>
          </div>
          <div class="col-md-4">
            <a href="hak_korban.php" class="card-action card-light">
              <img src="asset/image/hak-korban.png" alt="Icon" class="card-icon" />
              <h5>Hak-Hak Korban</h5>
              <p>Perlindungan korban.</p>
            </a>
          </div>
          <div class="col-md-4">
            <a href="#" class="card-action card-light">
              <img src="asset/image/faq.png" alt="Icon" class="card-icon" />
              <h5>FAQ</h5>
              <p>Pertanyaan umum.</p>
            </a>
          </div>
        </div>
      </section>
    </main>

    <?php include 'footer.php'; ?>

</body>
</html>