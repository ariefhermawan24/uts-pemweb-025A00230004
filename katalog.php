<?php

declare(strict_types=1);

session_start();

require_once 'data.php';
require_once 'functions.php';

if (!isset($_SESSION['katalog_agenda'])) {
    $_SESSION['katalog_agenda'] = $katalog_agenda;
}
if (!isset($_SESSION['registrasi'])) {
    $_SESSION['registrasi'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['daftar_id'], $_POST['nama_peserta'])) {
    $id = (int)$_POST['daftar_id'];
    $nama = trim($_POST['nama_peserta']);

    if ($nama !== '' && !isset($_SESSION['registrasi'][$id])) {
        $tiket = 'CDX-' . rand(10000, 99999);
        $_SESSION['registrasi'][$id] = [
            'tiket' => $tiket
        ];

        foreach ($_SESSION['katalog_agenda'] as $key => $agenda) {
            if ($agenda['id'] === $id) {
                $_SESSION['katalog_agenda'][$key]['peserta_terdaftar'] += 1;
                break;
            }
        }
    }
    header('Location: katalog.php#agenda-' . $id);
    exit;
}

if (isset($_GET['batal_id'])) {
    $id = (int)$_GET['batal_id'];

    if (isset($_SESSION['registrasi'][$id])) {
        unset($_SESSION['registrasi'][$id]);
        foreach ($_SESSION['katalog_agenda'] as $key => $agenda) {
            if ($agenda['id'] === $id) {
                $_SESSION['katalog_agenda'][$key]['peserta_terdaftar'] -= 1;
                break;
            }
        }
    }
    header('Location: katalog.php#agenda-' . $id);
    exit;
}
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
                <?php foreach ($_SESSION['katalog_agenda'] as $agenda) : ?>
                    <?php
                    $id = $agenda['id'];
                    $status = getStatusKuota($agenda['peserta_terdaftar'], $agenda['kuota']);

                    // Cek status card
                    $is_registered = isset($_SESSION['registrasi'][$id]);
                    $is_registering = (isset($_GET['daftar']) && (int)$_GET['daftar'] === $id);
                    ?>
                    <article class="event-card" id="agenda-<?= $id ?>">
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
                                    <span class="stat-number"><?= e((string)($agenda['peserta_terdaftar'])) ?></span>
                                </div>
                                <div class="stat-col">
                                    <span class="stat-label">Batas Kuota</span>
                                    <span class="stat-number"><?= e((string)$agenda['kuota']) ?></span>
                                </div>
                            </div>

                            <div class="event-meta">
                                <p class="meta-item"><strong>Jadwal:</strong> <?= e(tanggal_id($agenda['jadwal'])) ?></p>
                                <p class="meta-item"><strong>Lokasi:</strong> <?= e($agenda['lokasi']) ?></p>
                            </div>

                            <p class="event-desc">
                                <?= e($agenda['deskripsi']) ?>
                            </p>

                            <?php if ($is_registered): ?>
                                <!-- Kondisi Terdaftar -->
                                <div class="ticket-box mt-auto">
                                    <strong><?= e($_SESSION['registrasi'][$id]['tiket']) ?></strong>
                                </div>
                                <div class="event-actions">
                                    <a href="katalog.php?batal_id=<?= $id ?>" class="btn btn-outline-danger btn-sm">Batalkan</a>
                                    <button class="btn btn-success btn-disabled btn-sm" disabled>Terdaftar</button>
                                </div>

                            <?php elseif ($is_registering): ?>
                                <!--FORM ISI NAMA untuk Daftar -->
                                <form action="katalog.php" method="POST" class="register-form mt-auto">
                                    <input type="hidden" name="daftar_id" value="<?= $id ?>">
                                    <div class="form-group mb-form">
                                        <label for="nama_<?= $id ?>">Nama Lengkap Anda:</label>
                                        <input type="text" id="nama_<?= $id ?>" name="nama_peserta" class="form-control" required placeholder="Ketik nama...">
                                    </div>
                                    <div class="event-actions">
                                        <a href="katalog.php#agenda-<?= $id ?>" class="btn btn-outline-dark btn-sm">Kembali</a>
                                        <button type="submit" class="btn btn-primary btn-sm">Kirim</button>
                                    </div>
                                </form>

                            <?php else: ?>
                                <!-- Kondisi Default -->
                                <div class="event-actions mt-auto">
                                    <?php if ($status['btn_class'] !== 'btn-disabled'): ?>
                                        <a href="katalog.php?daftar=<?= $id ?>#agenda-<?= $id ?>" class="btn <?= e($status['btn_class']) ?> btn-sm">
                                            Daftar
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-disabled btn-sm" disabled>Sesi Penuh</button>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

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