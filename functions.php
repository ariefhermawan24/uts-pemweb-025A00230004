<?php

declare(strict_types=1);

function e(string $string): string
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function formatTanggalIndo(string $tanggal): string
{
    $bulanIndo = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    $pecahkan = explode('-', $tanggal); // [YYYY, MM, DD]

    $hari = $pecahkan[2];
    $bulan = (int)$pecahkan[1];
    $tahun = $pecahkan[0];

    return $hari . ' ' . $bulanIndo[$bulan] . ' ' . $tahun;
}

function getStatusKuota(int $terdaftar, int $kuota): array
{
    $sisa = $kuota - $terdaftar;

    if ($sisa <= 0) {
        return [
            'class_badge' => 'status-full',
            'label' => 'Penuh',
            'btn_class' => 'btn-disabled',
            'btn_text' => 'Sesi Penuh'
        ];
    } elseif ($sisa <= 10) {
        return [
            'class_badge' => 'status-warning',
            'label' => "Tersisa $sisa Kursi",
            'btn_class' => 'btn-primary',
            'btn_text' => 'Daftar'
        ];
    } else {
        return [
            'class_badge' => 'status-available',
            'label' => 'Tersedia',
            'btn_class' => 'btn-primary',
            'btn_text' => 'Daftar'
        ];
    }
}
