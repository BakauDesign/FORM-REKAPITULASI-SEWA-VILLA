<?php
// Mendefinisikan biaya dan diskon sebagai konstanta atau array di PHP
const BIAYA_DASAR_PENDAFTARAN = 300000;
const BIAYA_SKS_STANDAR = 250000; // Biaya per SKS
const DISKON_PILIHAN = [
    'SAINS' => 0.05, // Diskon 5% untuk Fakultas Sains
    'SOSIAL' => 0.03, // Diskon 3% untuk Fakultas Sosial
];
// Biaya fasilitas tambahan (asumsi biaya tetap, bukan per SKS)
const BIAYA_FASILITAS = [
    'LAB_KOMPUTER' => 500000,
    'ASURANSI_KESEHATAN' => 150000,
    'BIMBINGAN_KARIR' => 200000,
    'BIAYA_GEDUNG' => 100000
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pendaftaran Mahasiswa Baru</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 20px; }
        .container { background-color: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 800px; margin: auto; }
        h2 { text-align: center; color: #004a8f; border-bottom: 2px solid #004a8f; padding-bottom: 10px; margin-bottom: 20px; }
        h3 { color: #333; margin-top: 20px; }
        .biaya-info { border: 1px dashed #004a8f; padding: 15px; margin-bottom: 20px; background-color: #e6f3ff; }
        .input-group, .total-group { margin-bottom: 15px; display: flex; align-items: center; }
        .input-group label { width: 250px; font-weight: bold; }
        .input-group input[type="number"], .input-group input[type="text"], .input-group select { padding: 8px; border: 1px solid #ddd; border-radius: 4px; flex-grow: 1; }
        .total-group label { width: 350px; font-weight: bold; font-size: 1.3em; color: #d9534f; }
        .total-group input { background-color: #fffacd; border: 2px solid #d9534f; font-size: 1.3em; color: #d9534f; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #004a8f; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h2>FORM PENDAFTARAN MAHASISWA BARU (PMB)</h2>

    <div class="biaya-info">
        <h3>KOMPONEN BIAYA TAHUN AJARAN BARU</h3>
        <p>Biaya Pendaftaran Tetap: **Rp <?= number_format(BIAYA_DASAR_PENDAFTARAN, 0, ',', '.'); ?>**</p>
        <p>Biaya SKS Standar: **Rp <?= number_format(BIAYA_SKS_STANDAR, 0, ',', '.'); ?>** / SKS</p>
        <p>Diskon Fakultas Sains (5%): **Teknik Informatika, Biologi**</p>
        <p>Diskon Fakultas Sosial (3%): **Akuntansi, Hukum**</p>
    </div>

    <form method="post" action="">
        <!-- Data Mahasiswa -->
        <h3>DATA DASAR MAHASISWA</h3>
        <div class="input-group">
            <label for="nama_mhs">Nama Lengkap:</label>
            <input type="text" name="nama_mhs" required>
        </div>
        <div class="input-group">
            <label for="pilih_fakultas">Pilih Fakultas:</label>
            <select name="pilih_fakultas" required>
                <option value="" disabled selected>Pilih Fakultas</option>
                <option value="SAINS">Fakultas Sains (TI/Biologi)</option>
                <option value="SOSIAL">Fakultas Sosial (Akuntansi/Hukum)</option>
            </select>
        </div>
        <div class="input-group">
            <label for="jumlah_sks">Jumlah SKS yang Diambil (Semester 1):</label>
            <input type="number" name="jumlah_sks" min="1" max="24" required>
        </div>
        <hr>

        <!-- Fasilitas Tambahan -->
        <h3>FASILITAS TAMBAHAN (Wajib)</h3>
        <div class="input-group">
            <label for="fasilitas_lab">Biaya Laboratorium Komputer:</label>
            <input type="text" value="Rp <?= number_format(BIAYA_FASILITAS['LAB_KOMPUTER'], 0, ',', '.'); ?>" readonly style="width: auto;">
        </div>
        <div class="input-group">
            <label for="fasilitas_asuransi">Biaya Asuransi Kesehatan:</label>
            <input type="text" value="Rp <?= number_format(BIAYA_FASILITAS['ASURANSI_KESEHATAN'], 0, ',', '.'); ?>" readonly style="width: auto;">
        </div>
        
        <button type="submit" name="hitung" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 15px;">HITUNG TOTAL BIAYA</button>
    </form>

    <?php
    if (isset($_POST['hitung'])) {
        // --- 1. Ambil Input ---
        $nama_mhs       = htmlspecialchars($_POST['nama_mhs']);
        $pilih_fakultas = $_POST['pilih_fakultas'];
        $jumlah_sks     = (int)$_POST['jumlah_sks'];
        $total_biaya_fasilitas = 0;

        // --- 2. Hitung Biaya Dasar ---
        $biaya_sks_mentah = BIAYA_SKS_STANDAR * $jumlah_sks;
        // $total_biaya_fasilitas = BIAYA_FASILITAS['LAB_KOMPUTER'] + BIAYA_FASILITAS['ASURANSI_KESEHATAN'];

        foreach (BIAYA_FASILITAS as $nama_fasilitas => $harga_fasilitas) {
            $total_biaya_fasilitas += $harga_fasilitas;
        }

        // --- 3. Hitung Diskon ---
        $diskon_persen = DISKON_PILIHAN[$pilih_fakultas];
        $nilai_diskon = $biaya_sks_mentah * $diskon_persen;

        // --- 4. Hitung Sub Total ---
        $subtotal_biaya = BIAYA_DASAR_PENDAFTARAN + 
                          ($biaya_sks_mentah - $nilai_diskon) + 
                          $total_biaya_fasilitas;

        // --- 5. Tentukan Keterangan ---
        $keterangan_fakultas = ($pilih_fakultas == 'SAINS') ? "Fakultas Sains (TI/Biologi)" : "Fakultas Sosial (Akuntansi/Hukum)";
        $keterangan_diskon   = number_format($diskon_persen * 100, 0) . "%";
        
        // --- 6. Finalisasi Total Harga Akhir ---
        $total_harga_final = $subtotal_biaya;
    ?>

    <h3>REKAPITULASI BIAYA UNTUK <?= strtoupper($nama_mhs); ?></h3>
    <table>
        <thead>
            <tr>
                <th>Komponen Biaya</th>
                <th>Keterangan</th>
                <th>Sub Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Biaya Pendaftaran</td>
                <td>Biaya tetap satu kali</td>
                <td><?= number_format(BIAYA_DASAR_PENDAFTARAN, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td>Biaya SKS</td>
                <td><?= $jumlah_sks; ?> SKS x <?= number_format(BIAYA_SKS_STANDAR, 0, ',', '.'); ?></td>
                <td><?= number_format($biaya_sks_mentah, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td>Diskon Fakultas (<?= $keterangan_diskon; ?>)</td>
                <td>Fakultas <?= $pilih_fakultas; ?></td>
                <td style="color: red;">- <?= number_format($nilai_diskon, 0, ',', '.'); ?></td>
            </tr>
             <tr>
                <td>
                    Biaya Fasilitas (Komputer, Asuransi & Biaya Gedung)
                </td>
                <td>Wajib</td>
                <td><?= number_format($total_biaya_fasilitas, 0, ',', '.'); ?></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <div class="total-group">
            <label>**TOTAL HARGA YANG HARUS DIBAYAR**</label>
            <input type="text" value="Rp <?= number_format($total_harga_final, 0, ',', '.'); ?>" readonly>
        </div>
    </div>

    <?php
    } // Akhir dari if (isset($_POST['hitung']))
    ?>
</div>

</body>
</html>