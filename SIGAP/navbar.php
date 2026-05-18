<?php
  $current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
  <div class="navbar-left">
    <a href="index.php" class="navbar-logo">
      <img src="asset/image/logo.png" alt="Logo" />
    </a>
  </div>

<button class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </button>

  <div class="menu">
    <a href="index.php" class="menu-item <?= ($current_page == 'index.php') ? 'active' : ''; ?>">Beranda</a>
    <a href="lapor.php" class="menu-item <?= ($current_page == 'lapor.php') ? 'active' : ''; ?>">Buat Laporan</a>
    <a href="tracking.php" class="menu-item <?= ($current_page == 'tracking.php') ? 'active' : ''; ?>">Tracking Laporan</a>
    <a href="edukasi.php" class="menu-item <?= ($current_page == 'edukasi.php') ? 'active' : ''; ?>">Dukungan & Edukasi</a>
  </div>
</nav>

<script>
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('nav-menu');

    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
    });
</script>