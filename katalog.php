<?php
    declare(strict_types=1);

    require_once 'data.php';
    require_once 'functions.php';

    session_start();

    // Set session variabel
    if (!isset($_SESSION['registrasi'])) {
        $_SESSION['registrasi'] = [];
    }

    $total_peserta = array_sum(array_column($katalog_agenda, 'peserta_terdaftar'));
    $total_agenda = count($katalog_agenda);

    // Logika Proses Pendaftaran
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['daftar_id'], $_POST['nama_peserta'])) {
        $id = (int)$_POST['daftar_id'];
        $nama = trim($_POST['nama_peserta']);

        if ($nama !== '' && !isset($_SESSION['registrasi'][$id])) {
            $tiket = 'CDX-' . rand(10000, 99999);
            $_SESSION['registrasi'][$id] = [
                'tiket' => $tiket
            ];
        }
        header('Location: katalog.php#agenda-' . $id);
        exit;
    }

    // Logika Batal Pendaftaran
    if (isset($_GET['batal_id'])) {
        $id = (int)$_GET['batal_id'];

        if (isset($_SESSION['registrasi'][$id])) {
            unset($_SESSION['registrasi'][$id]);
        }
        header('Location: katalog.php#agenda-' . $id);
        exit;
    }

    // Logika Filter
    $filter_tanggal = $_GET['tanggal'] ?? '';
    $filter_status = $_GET['status'] ?? 'semua';

    $render_katalog = array_filter($katalog_agenda, function ($agenda) use ($filter_tanggal, $filter_status) {
        $id = $agenda['id'];
        $is_registered = isset($_SESSION['registrasi'][$id]);
        
        $total_peserta = $agenda['peserta_terdaftar'] + ($is_registered ? 1 : 0);
        $sisa_kuota = $agenda['kuota'] - $total_peserta;

        if ($filter_tanggal !== '' && $agenda['jadwal'] !== $filter_tanggal) {
            return false;
        }
        if ($filter_status === 'tersedia' && $sisa_kuota <= 0) {
            return false;
        }
        if ($filter_status === 'penuh' && $sisa_kuota > 0) {
            return false;
        }
        if ($filter_status === 'terdaftar' && !$is_registered) {
            return false;
        }
        return true;
    });

    // Logika Agenda Mendatang
    $hari_ini = date('Y-m-d');
    $agenda_mendatang = array_filter($katalog_agenda, function ($item) use ($hari_ini) {
        return $item['jadwal'] >= $hari_ini;
    });

    usort($agenda_mendatang, function ($a, $b) {
        return strtotime($a['jadwal']) <=> strtotime($b['jadwal']);
    });

    $agenda_terdekat = !empty($agenda_mendatang) ? $agenda_mendatang[0] : null;
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
        <div class="catalog-summary-text">
            <span class="summary-item">Menampilkan <strong><?= count($render_katalog) ?></strong> dari <strong><?= $total_agenda ?></strong> agenda</span>
            <span class="summary-separator">•</span>
            <span class="summary-item">Total akumulasi <strong><?= $total_peserta ?></strong> peserta terdaftar</span>
        </div>
        <section class="control-panel-section">
            <div class="control-panel-grid">
                <div class="filter-box">
                    <form action="katalog.php" method="GET" class="filter-form">
                        <div class="filter-inputs">
                            <div class="form-group">
                                <label for="filter-date">Pilih Tanggal</label>
                                <input type="date" id="filter-date" name="tanggal" class="form-control" value="<?= e($filter_tanggal) ?>">
                            </div>
                            <div class="form-group">
                                <label for="filter-status">Status</label>
                                <select id="filter-status" name="status" class="form-control">
                                    <option value="semua" <?= $filter_status === 'semua' ? 'selected' : '' ?>>Semua Status</option>
                                    <option value="tersedia" <?= $filter_status === 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                                    <option value="penuh" <?= $filter_status === 'penuh' ? 'selected' : '' ?>>Penuh</option>
                                    <option value="terdaftar" <?= $filter_status === 'terdaftar' ? 'selected' : '' ?>>Terdaftar</option>
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
                    <?php if ($agenda_terdekat !== null): ?>
                        <h2 class="nearest-title"><?= e($agenda_terdekat['judul']) ?></h2>
                        <p class="nearest-date"><?= e(tanggal_id($agenda_terdekat['jadwal'])) ?></p>
                        <p class="nearest-location"><?= e($agenda_terdekat['lokasi']) ?></p>
                    <?php else: ?>
                        <h2 class="nearest-title">Belum ada jadwal baru</h2>
                        <p class="nearest-date">-</p>
                        <p class="nearest-location">Nantikan update selanjutnya</p>
                    <?php endif; ?>
                </aside>
            </div>
        </section>
        <section class="events-section">
            <h2>Daftar Agenda Terbuka</h2>

            <div class="events-grid">
                <?php if (count($render_katalog) === 0): ?>
                    <div class="empty-state">
                        <p>Tidak ada agenda yang cocok dengan filter Anda.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($render_katalog as $agenda) : ?>
                        <?php
                        $id = $agenda['id'];
                        $is_registered = isset($_SESSION['registrasi'][$id]);
                        $is_registering = (isset($_GET['daftar']) && (int)$_GET['daftar'] === $id);
                        
                        $tp_terbaru = $agenda['peserta_terdaftar'] + ($is_registered ? 1 : 0);
                        $status = getStatusKuota($tp_terbaru, $agenda['kuota']);
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
                                        <span class="stat-number"><?= e((string)($tp_terbaru)) ?></span>
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
                <?php endif; ?>
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