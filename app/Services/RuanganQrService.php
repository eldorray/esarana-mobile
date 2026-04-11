<?php

namespace App\Services;

use App\Models\Ruangan;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRGdImagePNG;

class RuanganQrService
{
    /**
     * Build a compact plain-text payload for offline QR scanning.
     *
     * Example:
     *   [RUANGAN] RUANG MEETING UTAMA
     *   Lokasi   : Gedung Utama Jakarta (Lt. 2)
     *   Kapasitas: 20 orang | Status: Aktif
     *
     *   ===ASET (3)===
     *   - MacBook Pro M2 [INV-ELK-001] Baik|Tersedia
     *   - Proyektor Epson [INV-ELK-003] Baik|Dipinjam
     *
     *   ===BAHAN (2)===
     *   - Kertas HVS A4 [BHP-ATK-001] 150 rim
     *   - Spidol Whiteboard [BHP-ATK-003] 2 lusin *KRITIS*
     *
     *   Generated: 2025-06-01 09:30
     */
    public function buildPayload(Ruangan $ruangan): string
    {
        $ruangan->loadMissing([
            'lokasi',
            'inventaris',
            'bahanHabisPakais',
        ]);

        $lines = [];

        // Header
        $lines[] = '[RUANGAN] ' . strtoupper($ruangan->nama);
        $lines[] = 'Lokasi   : ' . ($ruangan->lokasi->nama ?? '—')
            . ' (Lt. ' . ($ruangan->lantai ?? '?') . ')';
        $cap = $ruangan->kapasitas ? $ruangan->kapasitas . ' orang' : '—';
        $lines[] = 'Kapasitas: ' . $cap . ' | Status: ' . ucfirst($ruangan->status);

        // Aset Tetap
        $asets = $ruangan->inventaris;
        $lines[] = '';
        $lines[] = '===ASET (' . $asets->count() . ')===';
        if ($asets->isEmpty()) {
            $lines[] = '  (tidak ada aset)';
        } else {
            foreach ($asets as $a) {
                $kondisi = match ($a->kondisi) {
                    'baik'         => 'Baik',
                    'cukup'        => 'Cukup',
                    'rusak_ringan' => 'Rusak Ringan',
                    'rusak_berat'  => 'Rusak Berat',
                    default        => ucfirst((string) $a->kondisi),
                };
                $lines[] = '- ' . $a->nama . ' [' . $a->kode . '] '
                    . $kondisi . '|' . ucfirst($a->status);
            }
        }

        // Bahan Habis Pakai
        $bahans = $ruangan->bahanHabisPakais;
        $lines[] = '';
        $lines[] = '===BAHAN (' . $bahans->count() . ')===';
        if ($bahans->isEmpty()) {
            $lines[] = '  (tidak ada bahan)';
        } else {
            foreach ($bahans as $b) {
                $kritis = $b->isStokRendah() ? ' *KRITIS*' : '';
                $lines[] = '- ' . $b->nama . ' [' . $b->kode . ']'
                    . ' ' . $b->stok . ' ' . $b->satuan . $kritis;
            }
        }

        $lines[] = '';
        $lines[] = 'Generated: ' . now()->format('Y-m-d H:i');

        return implode("\n", $lines);
    }

    /**
     * Generate QR code as a base64 PNG data URI (GD, no external binary).
     */
    public function generateDataUri(string $payload): string
    {
        $options = new QROptions([
            'outputInterface' => QRGdImagePNG::class,
            'outputBase64'    => true,
            'eccLevel'        => EccLevel::M,
            'scale'           => 6,
            'quietzoneSize'   => 3,
            'moduleValues'    => [
                // Dark modules → brand blue
                QRMatrix::M_FINDER_DARK    => [0, 80, 160],
                QRMatrix::M_FINDER_DOT     => [0, 80, 160],
                QRMatrix::M_ALIGNMENT_DARK => [0, 80, 160],
                QRMatrix::M_DATA_DARK      => [0, 80, 160],
                QRMatrix::M_DARKMODULE     => [0, 80, 160],
                QRMatrix::M_TIMING_DARK    => [0, 80, 160],
                QRMatrix::M_FORMAT_DARK    => [0, 80, 160],
                QRMatrix::M_VERSION_DARK   => [0, 80, 160],
                // Light modules → white
                QRMatrix::M_FINDER         => [255, 255, 255],
                QRMatrix::M_ALIGNMENT      => [255, 255, 255],
                QRMatrix::M_DATA           => [255, 255, 255],
                QRMatrix::M_TIMING         => [255, 255, 255],
                QRMatrix::M_FORMAT         => [255, 255, 255],
                QRMatrix::M_VERSION        => [255, 255, 255],
                QRMatrix::M_QUIETZONE      => [255, 255, 255],
                QRMatrix::M_SEPARATOR      => [255, 255, 255],
            ],
        ]);

        return (new QRCode($options))->render($payload);
    }
}
