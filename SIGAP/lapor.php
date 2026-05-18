<?php
session_start();
include 'koneksi.php';

$success = "";
$error = "";

/* =========================
   DEVICE + IP IDENTITAS
========================= */
if (!isset($_COOKIE['device_seed'])) {
    $seed = bin2hex(random_bytes(16));
    setcookie("device_seed", $seed, time() + (86400 * 365), "/");
} else {
    $seed = $_COOKIE['device_seed'];
}

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

$device_id = hash('sha256', $seed . $ip . $user_agent);

/* =========================
   FUNCTION LOGGING
========================= */
function logAktivitas($conn, $aktivitas, $device_id, $ip, $ua) {
    $stmt = $conn->prepare("
        INSERT INTO log_aktivitas (aktivitas, device_id, ip_address, user_agent)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("ssss", $aktivitas, $device_id, $ip, $ua);
    $stmt->execute();
    $stmt->close();
}

/* =========================
   PROSES FORM
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function clean($data) {
        return htmlspecialchars(trim($data));
    }

    $jenis_kekerasan = clean($_POST['jenis_kekerasan'] ?? '');
    $lokasi = clean($_POST['lokasi_kejadian'] ?? '');
    $tanggal = $_POST['tanggal'] ?? '';
    $waktu = $_POST['waktu'] ?? '';
    $pelaku = clean($_POST['pelaku'] ?? '');
    $kronologi = clean($_POST['kronologi'] ?? '');

    /* =========================
       VALIDASI INPUT
    ========================= */
    if (
        empty($jenis_kekerasan) ||
        empty($lokasi_kejadian) ||
        empty($tanggal) ||
        empty($waktu) ||
        empty($kronologi)
    ) {
        $error = "Semua field wajib diisi!";
    }

    if (!isset($_POST['pernyataan'])) {
        $error = "Anda harus menyetujui pernyataan!";
    }

    /* =========================
       RATE LIMIT (3/HARI)
    ========================= */
    $today = date("Y-m-d");

    $stmt = $conn->prepare("
        SELECT COUNT(*) as total 
        FROM lapor
        WHERE 
        (device_id = ? OR ip_address = ?)
        AND DATE(created_at) = ?
    ");
    $stmt->bind_param("sss", $device_id, $ip_address, $today);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($result['total'] >= 3) {
        $error = "Maksimal 3 laporan per hari!";
        logAktivitas($conn, "Rate limit tercapai", $device_id, $ip_address, $user_agent);
    }

    /* =========================
       COOLDOWN 5 MENIT
    ========================= */
    if (!$error) {
        $stmt = $conn->prepare("
            SELECT created_at 
            FROM lapor
            WHERE device_id = ?
            ORDER BY created_at DESC 
            LIMIT 1
        ");
        $stmt->bind_param("s", $device_id);
        $stmt->execute();
        $last = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($last) {
            $last_time = strtotime($last['created_at']);
            if (time() - $last_time < 300) {
                $error = "Tunggu 5 menit sebelum kirim lagi!";
            }
        }
    }

    /* =========================
       FILE UPLOAD
    ========================= */
    $bukti = null;

    if (!$error && !empty($_FILES['bukti']['name'])) {

        $allowed = ['jpg','jpeg','png'];
        $maxSize = 2 * 1024 * 1024;

        $fileName = $_FILES['bukti']['name'];
        $tmpName  = $_FILES['bukti']['tmp_name'];
        $size     = $_FILES['bukti']['size'];

        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $error = "Format file hanya JPG/PNG!";
        }

        if ($size > $maxSize) {
            $error = "Ukuran maksimal 2MB!";
        }

        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        if (!$error) {
            $bukti = time() . "_" . bin2hex(random_bytes(5)) . "." . $ext;
            move_uploaded_file($tmpName, "uploads/" . $bukti);
        }
    }

    /* =========================
       INSERT DATA
    ========================= */
    if (!$error) {

        $stmt = $conn->prepare("
            INSERT INTO lapor
            (jenis_kekerasan, lokasi_kejadian, tanggal, waktu, pelaku, kronologi, bukti, device_id, ip_address) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssssssss",
            $jenis_kekerasan,
            $lokasi_kejadian,
            $tanggal,
            $waktu,
            $pelaku,
            $kronologi,
            $bukti,
            $device_id,
            $ip_address
        );

        if ($stmt->execute()) {
            $success = "Laporan berhasil dikirim!";
            logAktivitas($conn, "Kirim laporan", $device_id, $ip_address, $user_agent);
        } else {
            $error = "Gagal menyimpan!";
            logAktivitas($conn, "Gagal simpan", $device_id, $ip_address, $user_agent);
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan Kasus</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/lapor.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="container-lapor">
    <header class="header-lapor text-center">
        <img src="asset/image/logo-satgas.png" class="logo-satgas" alt="Logo Satgas">
        <h1 class="main-title">Buat Laporan Kasus</h1>
    </header>

    <div class="lapor-wrapper">
        
        <div class="form-box card-purple-light">
            <h3 class="form-title">Formulir Pelaporan</h3>
            <form method="POST" enctype="multipart/form-data">
                <label>Jenis Kekerasan</label>
                <select name="jenis_kekerasan" required>
                    <option value="">-- Pilih --</option>
                    <option>Kekerasan Fisik</option>
                    <option>Kekerasan Seksual</option>
                    <option>Pelecehan Verbal</option>
                    <option>Pelecehan Non-Verbal</option>
                    <option>Kekerasan Seksual Berbasis Digital</option>
                    <option>Kekerasan Seksual Lainnya</option>
                </select>

                <label>Lokasi Kejadian</label>
                <input type="text" name="lokasi" required placeholder="Misal: Lab Komputer, Kantin...">

                <div class="row">
                    <div class="col">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" required>
                    </div>
                    <div class="col">
                        <label>Waktu</label>
                        <input type="time" name="waktu" required>
                    </div>
                </div>

                <label>Terduga Pelaku</label>
                <input type="text" name="pelaku">

                <label>Kronologi</label>
                <textarea name="kronologi" rows="5" required></textarea>

                <label>Bukti Pendukung</label>
                <input type="file" name="bukti">

                <div class="checkbox-group">
                    <input type="checkbox" name="pernyataan" required>
                    <span>Saya menyatakan informasi yang saya sampaikan adalah benar.</span>
                </div>

                <button type="submit" name="kirim_laporan" class="btn-submit">Kirim Laporan</button>
            </form>
        </div>

        <aside class="sidebar-lapor">
            <div class="info-card">
                <h4>Petunjuk pengisian</h4>
                <ol>
                    <li>Isi setiap kolom dengan informasi yang jelas.</li>
                    <li>Tuliskan kronologi secara lengkap.</li>
                    <li>Sertakan bukti pendukung jika tersedia.</li>
                </ol>
            </div>

            <div class="info-card info-icon">
                <p>Laporan yang disampaikan melalui sistem ini bersifat <strong>anonim</strong>.</p>
            </div>
        </aside>

    </div> 
</main>
    <?php include 'footer.php'; ?>

</body>
</html>
