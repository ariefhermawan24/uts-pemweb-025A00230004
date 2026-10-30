<?php

declare(strict_types=1);

require_once 'data.php';
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda Kegiatan - Codexa Community</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="site-header">
        <div class="nav-container">
            <a href="./" class="brand-logo">
                <img src="assets/codexa-logo.png" alt="Logo Codexa Community" class="brand-img">
                <span class="brand-text">Codexa Community</span>
            </a>
            <nav class="main-nav">
                <ul>
                    <li><a href="./" class="nav-link">Profil</a></li>
                    <li><a href="katalog.php" class="nav-link active">Agenda Acara</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="catalog-header">
            <h1>Agenda Kegiatan Komunitas</h1>
            <p>Telusuri jadwal lokakarya, diskusi teknis, dan sesi belajar bersama Codexa Community.</p>
        </section>
        <section class="control-panel-section">
            <div class="control-panel-grid">
                <div class="filter-box">
                    <form action="katalog.php" method="GET" class="filter-form">
                        <div class="filter-inputs">
                            <div class="form-group">
                                <label for="filter-date">Pilih Tanggal</label>
                                <input type="date" id="filter-date" name="tanggal" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="filter-status">Status Ketersediaan</label>
                                <select id="filter-status" name="status" class="form-control">
                                    <option value="semua">Semua Status</option>
                                    <option value="tersedia">Tersedia</option>
                                    <option value="penuh">Penuh</option>
                                </select>
                            </div>
                        </div>
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary btn-filter">Terapkan</button>
                            <a href="katalog.php" class="btn btn-outline-dark btn-filter">Reset</a>
                        </div>
                    </form>
                </div>
                <aside class="nearest-box">
                    <span class="nearest-label">Kegiatan Terdekat</span>
                    <h2 class="nearest-title">Pengenalan HTML untuk Pemula</h2>
                    <p class="nearest-date">Sabtu, 07 November 2026</p>
                    <p class="nearest-location">Ruang Kolaborasi Technopark</p>
                </aside>
            </div>
        </section>
        <section class="events-section">
            <h2>Daftar Agenda Terbuka</h2>

            <div class="events-grid">
                <?php foreach ($katalog_agenda as $agenda) : ?>
                    <?php
                    $status = getStatusKuota($agenda['peserta_terdaftar'], $agenda['kuota']);
                    ?>
                    <article class="event-card">
                        <div class="event-img-wrap">
                            <img src="<?= e($agenda['gambar']) ?>" alt="<?= e($agenda['judul']) ?>" class="event-img">
                            <span class="badge-status <?= e($status['class_badge']) ?>">
                                <?= e($status['label']) ?>
                            </span>
                        </div>
                        <div class="event-body">
                            <h3><?= e($agenda['judul']) ?></h3>

                            <div class="event-stats">
                                <div class="stat-col">
                                    <span class="stat-label">Peserta Terdaftar</span>
                                    <span class="stat-number"><?= e((string)$agenda['peserta_terdaftar']) ?></span>
                                </div>
                                <div class="stat-col">
                                    <span class="stat-label">Batas Kuota</span>
                                    <span class="stat-number"><?= e((string)$agenda['kuota']) ?></span>
                                </div>
                            </div>

                            <div class="event-meta">
                                <p class="meta-item"><strong>Jadwal :</strong> <?= e(formatTanggalIndo($agenda['jadwal'])) ?></p>
                                <p class="meta-item"><strong>Lokasi :</strong> <?= e($agenda['lokasi']) ?></p>
                            </div>

                            <p class="event-desc">
                                <?= e($agenda['deskripsi']) ?>
                            </p>

                            <div class="event-actions">
                                <a href="#masuk" class="btn <?= e($status['btn_class']) ?> btn-sm">
                                    <?= e($status['btn_text']) ?>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
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