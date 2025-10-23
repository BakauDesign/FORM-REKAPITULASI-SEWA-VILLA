<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemesanan Villa - Total Harga</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }

        .container {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: auto;
        }

        h2 {
            text-align: center;
            color: #333;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .harga {
            border: 1px dashed #ccc;
            padding: 15px;
            margin-bottom: 20px;
        }

        .input-group, .total-group {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .input-group label { 
            width: 180px;
            font-weight: bold; 
        }

        .input-group input[type="number"], 
        .input-group select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            flex-grow: 1;
        }

        .total-group label {
            width: 300px;
            font-weight: bold;
            font-size: 1.2em;
            color: #d9534f;
        }

        .total-group input {
            background-color: #fcf8e3;
            border: 2px solid #d9534f;
            font-size: 1.2em;
            color: #d9534f;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>FORM REKAPITULASI SEWA VILLA</h2>

    <div class="harga">
        <h3>DAFTAR HARGA SEWA</h3>
        <p>VILLA 1 (Standard View): **500.000** / malam</p>
        <p>VILLA 2 (Sea View): **850.000** / malam</p>
        <p>VILLA 3 (Premium Suite): **1.200.000** / malam</p>
        <hr>
        <p>Extra Bed: **100.000** / malam</p>
        <p>Perahu Kayak: **150.000** / unit</p>
    </div>

    <form method="post" action="">
        <h3>INPUT PEMESANAN</h3>
        <div class="input-group">
            <label for="jenis_villa">Pilih Villa:</label>
            <select name="jenis_villa" required>
                <option value="" disabled selected>Pilih Villa</option>
                <option value="VILLA1">Villa 1 (500.000)</option>
                <option value="VILLA2">Villa 2 (850.000)</option>
                <option value="VILLA3">Villa 3 (1.200.000)</option>
            </select>
        </div>

        <div class="input-group">
            <label for="durasi">Durasi Sewa (Malam):</label>
            <input type="number" name="durasi" min="1" required>
        </div>
        
        <hr>

        <h3>FASILITAS TAMBAHAN</h3>
        <div class="input-group">
            <label for="qty_bed">Jumlah Extra Bed:</label>
            <input type="number" name="qty_bed" min="0" value="0">
        </div>

        <div class="input-group">
            <label for="qty_kayak">Jumlah Perahu Kayak:</label>
            <input type="number" name="qty_kayak" min="0" value="0">
        </div>
        
        <button type="submit" name="hitung" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 15px;">HITUNG TOTAL HARGA</button>
    </form>

    <?php
    if (isset($_POST['hitung'])) {
        // --- 1. Ambil Input ---
        $jenis_villa = $_POST['jenis_villa'];
        $durasi      = (int)$_POST['durasi'];
        $qty_bed     = (int)$_POST['qty_bed'];
        $qty_kayak   = (int)$_POST['qty_kayak'];

        // --- 2. Tentukan Harga Dasar ---
        $harga_villa = 0;
        switch ($jenis_villa) {
            case 'VILLA1':
                $harga_villa = 500000;
                $nama_villa = "Villa 1 (Standard View)";
                break;
            case 'VILLA2':
                $harga_villa = 850000;
                $nama_villa = "Villa 2 (Sea View)";
                break;
            case 'VILLA3':
                $harga_villa = 1200000;
                $nama_villa = "Villa 3 (Premium Suite)";
                break;
            default:
                $harga_villa = 0;
                $nama_villa = "Tidak Dikenal";
        }

        // --- 3. Hitung Sub Total per item ---
        $subtotal_villa  = $harga_villa * $durasi;
        $subtotal_bed    = 100000 * $qty_bed * $durasi; // Harga per bed per malam
        $subtotal_kayak  = 150000 * $qty_kayak;        // Harga per unit (asumsi untuk seluruh masa sewa)

        // --- 4. Hitung TOTAL PESANAN (SUB TOTAL SELURUH) ---
        $total_pesanan = $subtotal_villa + $subtotal_bed + $subtotal_kayak;

        // --- 5. Hitung PPN (10% dari TOTAL PESANAN) ---
        $ppn = 0.10 * $total_pesanan;

        // --- 6. Hitung TOTAL HARGA AKHIR ---
        // TOTAL HARGA AKHIR = TOTAL PESANAN + PPN
        $total_harga_final = $total_pesanan + $ppn;
        
        // --- Tampilkan Hasil Rekapitulasi ---
    ?>

    <h3>DETAIL PERHITUNGAN</h3>
    <table>
        <thead>
            <tr>
                <th>Item Sewa</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>**Villa**</td>
                <td><?= $nama_villa; ?></td>
                <td><?= $durasi; ?> Malam</td>
                <td><?= number_format($subtotal_villa, 0, ',', '.'); ?></td>
            </tr>
            <?php if ($qty_bed > 0): ?>
            <tr>
                <td>**Extra Bed**</td>
                <td>Sewa <?= $durasi; ?> Malam</td>
                <td><?= $qty_bed; ?> Unit</td>
                <td><?= number_format($subtotal_bed, 0, ',', '.'); ?></td>
            </tr>
            <?php endif; ?>
            <?php if ($qty_kayak > 0): ?>
            <tr>
                <td>**Perahu Kayak**</td>
                <td>Sewa Total</td>
                <td><?= $qty_kayak; ?> Unit</td>
                <td><?= number_format($subtotal_kayak, 0, ',', '.'); ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <div class="total-group">
            <label>TOTAL PESANAN (Sebelum PPN)</label>
            <input type="text" value="Rp <?= number_format($total_pesanan, 0, ',', '.'); ?>" readonly>
        </div>
        <div class="total-group">
            <label>PPN 10%</label>
            <input type="text" value="Rp <?= number_format($ppn, 0, ',', '.'); ?>" readonly>
        </div>
        <hr>
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