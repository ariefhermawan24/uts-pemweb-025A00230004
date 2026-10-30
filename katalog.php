<?php

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Kegiatan — Codexa Community</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="site-header">
        <div class="nav-container">
            <a href="index.php" class="brand-logo">
                <img src="assets/codexa-logo.png" alt="Logo Codexa Community" class="brand-img">
                <span class="brand-text">Codexa Community</span>
            </a>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php" class="nav-link">Profil</a></li>
                    <li><a href="katalog.php" class="nav-link active">Agenda Acara</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">

        <section class="catalog-header">
            <h1>Agenda Kegiatan Komunitas</h1>
            <p>Jelajahi lokakarya, sesi bedah kode, dan pertemuan teknis yang dapat Anda ikuti bersama Codexa Community.</p>
        </section>

        <section class="summary-section">
            <h2>Ringkasan Agenda</h2>
            <div class="summary-grid">
                <article class="summary-card">
                    <span class="summary-title">Total Peserta Terdaftar</span>
                    <p class="summary-value">68 <span>Peserta</span></p>
                </article>
                <article class="summary-card">
                    <span class="summary-title">Status Ketersediaan</span>
                    <p class="summary-value">1 <span>Tersedia</span> / 1 <span>Penuh</span></p>
                </article>
                <article class="summary-card">
                    <span class="summary-title">Kegiatan Terdekat</span>
                    <p class="summary-highlight">Bedah Logika PHP Bersih</p>
                    <small class="summary-meta">Sabtu, 07 November 2026</small>
                </article>
            </div>
        </section>

        <!-- Daftar Katalog Kartu Agenda dummy tampilan -->
        <section class="events-section">
            <h2>Daftar Agenda Terbuka</h2>
            
            <div class="events-grid">

                <!-- Kartu Agenda 1: Status Tersedia -->
                <article class="event-card">
                    <div class="event-img-wrap">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRh2CPJLa2gAksqObrNmxHwNsYBunoNHPqYUPqRw1yw4w&s=10" alt="Suasana Sesi Bedah Logika PHP" class="event-img">
                        <span class="badge-status status-available">Tersedia</span>
                    </div>
                    <div class="event-body">
                        <h3>Bedah Logika PHP Bersih</h3>
                        <div class="event-meta">
                            <p class="meta-item"><strong>Jadwal:</strong> Sabtu, 07 November 2026</p>
                            <p class="meta-item"><strong>Lokasi:</strong> Ruang Kolaborasi Technopark</p>
                            <p class="meta-item"><strong>Kuota:</strong> 28 / 30 Peserta</p>
                        </div>
                        <p class="event-desc">
                            Mempelajari penulisan arsitektur fungsi bertipe dan standardisasi PSR-12 secara langsung lewat studi kasus web sederhana.
                        </p>
                        <div class="event-actions">
                            <a href="#masuk" class="btn btn-primary btn-sm">Masuk</a>
                            <a href="#peserta" class="btn btn-outline-dark btn-sm">Daftar Peserta</a>
                        </div>
                    </div>
                </article>

                <!-- Kartu Agenda 2: Status Penuh -->
                <article class="event-card">
                    <div class="event-img-wrap">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRh2CPJLa2gAksqObrNmxHwNsYBunoNHPqYUPqRw1yw4w&s=10" alt="Sesi Diskusi Arsitektur Antarmuka CSS" class="event-img">
                        <span class="badge-status status-full">Penuh</span>
                    </div>
                    <div class="event-body">
                        <h3>Arsitektur CSS Modern & Flexbox</h3>
                        <div class="event-meta">
                            <p class="meta-item"><strong>Jadwal:</strong> Minggu, 15 November 2026</p>
                            <p class="meta-item"><strong>Lokasi:</strong> Online (Google Meet)</p>
                            <p class="meta-item"><strong>Kuota:</strong> 40 / 40 Peserta</p>
                        </div>
                        <p class="event-desc">
                            Praktik membangun tata letak antarmuka web responsif tanpa framework menggunakan box model, Grid, dan Flexbox.
                        </p>
                        <div class="event-actions">
                            <a href="#masuk" class="btn btn-disabled btn-sm">Sesi Penuh</a>
                            <a href="#peserta" class="btn btn-outline-dark btn-sm">Daftar Peserta</a>
                        </div>
                    </div>
                </article>

            </div>
        </section>

    </main>

    <footer class="site-footer">
        <div class="footer-container">
            <p>&copy; 2026 Codexa Community. Code. Learn. Evolve.</p>
        </div>
    </footer>

</body>
</html>