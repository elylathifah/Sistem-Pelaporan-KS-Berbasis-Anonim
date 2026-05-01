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
    $stmt->bind_param("sss", $device_id, $ip, $today);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($result['total'] >= 3) {
        $error = "Maksimal 3 laporan per hari!";
        logAktivitas($conn, "Rate limit tercapai", $device_id, $ip, $user_agent);
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
            $ip
        );

        if ($stmt->execute()) {
            $success = "Laporan berhasil dikirim!";
            logAktivitas($conn, "Kirim laporan", $device_id, $ip, $user_agent);
        } else {
            $error = "Gagal menyimpan!";
            logAktivitas($conn, "Gagal simpan", $device_id, $ip, $user_agent);
        }

        $stmt->close();
    }
}
?>

<div class="lapor-container">
    <head>
    <meta charset="utf-8">
    <title>Buat Laporan Kasus</title>

    <!-- CSS GLOBAL -->
    <link rel="stylesheet" href="css/style.css">

    <!-- CSS KHUSUS HALAMAN LAPOR -->
    <link rel="stylesheet" href="css/lapor.css">
</head>

    <!-- LEFT: FORM -->
    <div class="form-box">
        <h3>Formulir Pelaporan</h3>

        <form method="POST" enctype="multipart/form-data">

            <label>Jenis Kekerasan</label>
            <select name="jenis_kekerasan" required>
                <option value="">-- Pilih --</option>
                <option>Kekerasan Fisik</option>
                <option>Kekerasan Verbal</option>
                <option>Kekerasan Seksual</option>
                <option>Penyebaran Konten Seksual tanpa Izin</option>
                <option>Bentuk kekerasan lainnya</option>
            </select>

            <label>Lokasi Kejadian</label>
            <input type="text" name="lokasi" required>

            <div class="row">
                <div>
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" required>
                </div>
                <div>
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

            <div class="checkbox">
                <input type="checkbox" name="pernyataan">
                <span>Saya menyatakan informasi benar</span>
            </div>

            <button type="submit" class="btn-submit">Kirim Laporan</button>
        </form>
    </div>

    <!-- RIGHT: INFO -->
    <div class="side-box">

        <div class="info-card">
            <h4>Petunjuk pengisian</h4>
            <ol>
                <li>Isi data dengan jelas</li>
                <li>Sertakan bukti jika ada</li>
                <li>Pastikan kronologi lengkap</li>
                <li>Laporan bisa anonim</li>
            </ol>
        </div>

        <div class="info-card">
            <p>Laporan akan diproses sesuai prosedur.</p>
        </div>

        <div class="info-card">
            <p>Laporan Anda aman dan dirahasiakan.</p>
        </div>

    </div>

</div>