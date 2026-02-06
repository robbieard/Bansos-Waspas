<?php
require_once '../classes/Database.php';
require_once '../classes/Auth.php';

$auth = new Auth();
$auth->requireLogin();
$user = $auth->getUser();
$db = Database::getInstance();

// Mapping sub-kriteria dengan nilai desimal
$subKriteria = [
    'C1' => ['nama' => 'Luas lantai per kapita', 'jenis' => 'cost', 'bobot' => 8.00, 'options' => [
        ['label' => '< 8 m²/orang', 'nilai' => 1.00],
        ['label' => '8–12 m²/orang', 'nilai' => 0.50],
        ['label' => '> 12 m²/orang', 'nilai' => 0.33]
    ]],
    'C2' => ['nama' => 'Jenis lantai', 'jenis' => 'cost', 'bobot' => 6.00, 'options' => [
        ['label' => 'Tanah/bambu/kayu murah', 'nilai' => 1.00],
        ['label' => 'Semen/ubin sederhana', 'nilai' => 0.50],
        ['label' => 'Keramik/parket', 'nilai' => 0.33]
    ]],
    'C3' => ['nama' => 'Jenis dinding', 'jenis' => 'cost', 'bobot' => 6.00, 'options' => [
        ['label' => 'Bambu/rumbia/kayu murah', 'nilai' => 1.00],
        ['label' => 'Tembok tanpa plester', 'nilai' => 0.50],
        ['label' => 'Tembok diplester/finishing', 'nilai' => 0.33]
    ]],
    'C4' => ['nama' => 'Akses fasilitas buang air', 'jenis' => 'cost', 'bobot' => 7.00, 'options' => [
        ['label' => 'Tanpa fasilitas/bersama', 'nilai' => 1.00],
        ['label' => 'Jamban sederhana', 'nilai' => 0.50],
        ['label' => 'Jamban sehat pribadi', 'nilai' => 0.33]
    ]],
    'C5' => ['nama' => 'Sumber penerangan', 'jenis' => 'cost', 'bobot' => 6.00, 'options' => [
        ['label' => 'Tanpa listrik', 'nilai' => 1.00],
        ['label' => 'Listrik subsidi', 'nilai' => 0.50],
        ['label' => 'Listrik non-subsidi', 'nilai' => 0.33]
    ]],
    'C6' => ['nama' => 'Sumber air minum', 'jenis' => 'cost', 'bobot' => 6.00, 'options' => [
        ['label' => 'Sumur tak terlindung/sungai/air hujan', 'nilai' => 1.00],
        ['label' => 'Sumur terlindung', 'nilai' => 0.50],
        ['label' => 'PDAM/air layak', 'nilai' => 0.33]
    ]],
    'C7' => ['nama' => 'Bahan bakar memasak', 'jenis' => 'cost', 'bobot' => 5.00, 'options' => [
        ['label' => 'Kayu/arang/minyak tanah', 'nilai' => 1.00],
        ['label' => 'LPG subsidi', 'nilai' => 0.50],
        ['label' => 'LPG non-subsidi/kompor listrik', 'nilai' => 0.33]
    ]],
    'C8' => ['nama' => 'Konsumsi protein mingguan', 'jenis' => 'benefit', 'bobot' => 6.00, 'options' => [
        ['label' => '≤ 1 kali/minggu', 'nilai' => 0.33],
        ['label' => '2–3 kali/minggu', 'nilai' => 0.67],
        ['label' => '≥ 4 kali/minggu', 'nilai' => 1.00]
    ]],
    'C9' => ['nama' => 'Kepemilikan pakaian tahunan', 'jenis' => 'benefit', 'bobot' => 4.00, 'options' => [
        ['label' => '1 stel/tahun', 'nilai' => 0.33],
        ['label' => '2 stel/tahun', 'nilai' => 0.67],
        ['label' => '≥ 3 stel/tahun', 'nilai' => 1.00]
    ]],
    'C10' => ['nama' => 'Frekuensi makan harian', 'jenis' => 'benefit', 'bobot' => 7.00, 'options' => [
        ['label' => '1–2 kali/hari', 'nilai' => 0.33],
        ['label' => '2–3 kali/hari', 'nilai' => 0.67],
        ['label' => '≥ 3 kali/hari', 'nilai' => 1.00]
    ]],
    'C11' => ['nama' => 'Kemampuan berobat', 'jenis' => 'benefit', 'bobot' => 7.00, 'options' => [
        ['label' => 'Tidak sanggup ke puskesmas', 'nilai' => 0.33],
        ['label' => 'Sanggup layanan dasar', 'nilai' => 0.67],
        ['label' => 'Sanggup layanan komprehensif', 'nilai' => 1.00]
    ]],
    'C12' => ['nama' => 'Sumber penghasilan & besaran', 'jenis' => 'cost', 'bobot' => 12.00, 'options' => [
        ['label' => 'Upah ≤ Rp600.000/bulan', 'nilai' => 1.00],
        ['label' => 'Rp600.001–Rp1.500.000', 'nilai' => 0.50],
        ['label' => '≥ Rp1.500.001', 'nilai' => 0.33]
    ]],
    'C13' => ['nama' => 'Pendidikan kepala keluarga', 'jenis' => 'cost', 'bobot' => 10.00, 'options' => [
        ['label' => 'Tidak sekolah/tidak tamat SD', 'nilai' => 1.00],
        ['label' => 'Tamat SD/SMP', 'nilai' => 0.50],
        ['label' => 'SMA/lebih tinggi', 'nilai' => 0.33]
    ]],
    'C14' => ['nama' => 'Aset/tabungan likuid', 'jenis' => 'benefit', 'bobot' => 10.00, 'options' => [
        ['label' => 'Tidak memiliki ≥ Rp500.000', 'nilai' => 0.33],
        ['label' => 'Rp500.000–Rp2.000.000', 'nilai' => 0.67],
        ['label' => '≥ Rp2.000.001', 'nilai' => 1.00]
    ]]
];

// Mapping singkatan untuk header tabel
$singkatanKriteria = [
    'C1' => 'LL',   // Luas Lantai per kapita
    'C2' => 'JL',   // Jenis Lantai
    'C3' => 'JD',   // Jenis Dinding
    'C4' => 'FB',   // Fasilitas Buang air
    'C5' => 'SP',   // Sumber Penerangan
    'C6' => 'SA',   // Sumber Air minum
    'C7' => 'BB',   // Bahan Bakar memasak
    'C8' => 'KP',   // Konsumsi Protein
    'C9' => 'PA',   // Pakaian tahunan
    'C10' => 'FM',  // Frekuensi Makan
    'C11' => 'KB',  // Kemampuan Berobat
    'C12' => 'PH',  // Penghasilan
    'C13' => 'PD',  // Pendidikan KK
    'C14' => 'AS'   // Aset/tabungan
];

// HANDLE ACTIONS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'save') {
        $penerima_id = $_POST['penerima_id'] ?? '';
        
        if (!$penerima_id) {
            header('Location: input-kriteria.php?err=nonama');
            exit;
        }
        
        // Collect nilai untuk setiap kriteria (C1-C14)
        $cols = ['penerima_id'];
        $params = [$penerima_id];
        $placeholders = ['?'];
        
        for ($i = 1; $i <= 14; $i++) {
            $key = "c$i";
            $val = floatval($_POST[$key] ?? 0);
            $cols[] = $key;
            $placeholders[] = '?';
            $params[] = $val;
        }
        
        // Delete existing data untuk penerima ini
        $db->execute("DELETE FROM data_kriteria WHERE penerima_id = ?", [$penerima_id]);
        
        // Insert new data
        $sql = "INSERT INTO data_kriteria (" . implode(',', $cols) . ") VALUES (" . implode(',', $placeholders) . ")";
        $db->execute($sql, $params);
        
        header('Location: input-kriteria.php?msg=saved');
        exit;
    }
    
    if ($action === 'delete') {
        $id = $_POST['id'] ?? '';
        if ($id) {
            $db->execute("DELETE FROM data_kriteria WHERE id = ?", [$id]);
            header('Location: input-kriteria.php?msg=deleted');
            exit;
        }
    }
}

// GET PENERIMA LIST
$penerima_list = $db->fetchAll("SELECT id, nama, nik, alamat FROM penerima_bantuan ORDER BY nama");

// GET EXISTING DATA
$data_input = $db->fetchAll("
    SELECT dk.*, p.nama, p.nik, p.alamat, p.no_rekening, p.nama_bank
    FROM data_kriteria dk 
    JOIN penerima_bantuan p ON dk.penerima_id = p.id 
    ORDER BY dk.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Kriteria Keluarga Miskin - Sistem Bantuan Sosial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .checkbox-custom { width: 16px; height: 16px; cursor: pointer; }
    </style>
</head>
<body class="bg-gray-50">
    <?php include '../includes/navbar.php'; ?>
    
    <div class="flex">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="flex-1 p-8 ml-64">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Input Kriteria Keluarga Miskin</h1>
                    <p class="text-sm text-gray-500">Form penilaian 14 kriteria (C1-C14) untuk setiap penerima bantuan</p>
                </div>
                <button form="formInput" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 font-medium">
                    <i class="fas fa-save mr-2"></i> Simpan Data
                </button>
            </div>
            
            <?php if (isset($_GET['err']) && $_GET['err'] == 'nonama'): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm">
                <i class="fas fa-exclamation-circle mr-2"></i> Pilih penerima terlebih dahulu.
            </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'saved'): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center">
                <i class="fas fa-check-circle text-2xl mr-3"></i>
                <div>
                    <p class="font-bold">Sukses!</p>
                    <p>Data kriteria berhasil disimpan.</p>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center">
                <i class="fas fa-trash-alt text-2xl mr-3"></i>
                <div>
                    <p class="font-bold">Sukses!</p>
                    <p>Data kriteria berhasil dihapus.</p>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- FORM INPUT -->
            <form method="POST" id="formInput" class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-8">
                <input type="hidden" name="action" value="save">
                
                <!-- PILIH PENERIMA -->
                <div class="mb-8 bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Penerima Bantuan <span class="text-red-500">*</span></label>
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <select name="penerima_id" id="penerimaSelect" onchange="fillBio()" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                                <option value="">-- Cari Nama / NIK --</option>
                                <?php foreach ($penerima_list as $p): ?>
                                <option value="<?= $p['id'] ?>" 
                                        data-nik="<?= htmlspecialchars($p['nik']) ?>"
                                        data-alamat="<?= htmlspecialchars($p['alamat']) ?>">
                                    <?= htmlspecialchars($p['nama']) ?> (<?= htmlspecialchars($p['nik']) ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="w-1/3 grid grid-cols-2 gap-4">
                            <div class="bg-white p-3 rounded border">
                                <span class="block text-gray-400 text-xs uppercase font-bold">NIK</span>
                                <span class="font-semibold text-gray-800" id="view_nik">-</span>
                            </div>
                            <div class="bg-white p-3 rounded border">
                                <span class="block text-gray-400 text-xs uppercase font-bold">Alamat</span>
                                <span class="font-semibold text-gray-800 truncate" id="view_alamat">-</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- FORM 14 KRITERIA (Split 2 Kolom) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- KOLOM KIRI: COST -->
                    <div>
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-red-200">
                            <div class="bg-red-100 text-red-600 p-2 rounded-lg"><i class="fas fa-arrow-down"></i></div>
                            <h3 class="font-bold text-gray-800 text-lg">Kriteria COST</h3>
                            <span class="text-xs text-red-500 bg-red-50 px-2 py-1 rounded-full font-medium ml-auto">Semakin Rendah = Semakin Baik</span>
                        </div>
                        
                        <div class="space-y-5">
                            <?php foreach ($subKriteria as $kode => $data): ?>
                                <?php if($data['jenis'] == 'cost'): ?>
                                <div class="p-4 border border-gray-100 rounded-lg hover:border-red-200 hover:shadow-sm transition bg-white group">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-bold"><?= $kode ?></span>
                                            <h4 class="font-semibold text-gray-800 group-hover:text-red-600 transition"><?= htmlspecialchars($data['nama']) ?></h4>
                                        </div>
                                        <span class="text-xs font-mono text-gray-400 bg-gray-50 px-2 py-0.5 rounded">Bobot: <?= $data['bobot'] ?>%</span>
                                    </div>
                                    <div class="space-y-2 pl-2">
                                        <?php foreach ($data['options'] as $opt): ?>
                                        <label class="flex items-center space-x-3 p-2 hover:bg-red-50 rounded-lg cursor-pointer transition border border-transparent hover:border-red-100">
                                            <input type="radio" name="<?= strtolower($kode) ?>" value="<?= $opt['nilai'] ?>" required
                                                   class="w-4 h-4 text-red-600 focus:ring-red-500 border-gray-300">
                                            <span class="text-sm text-gray-600 flex-1">
                                                <?= htmlspecialchars($opt['label']) ?>
                                            </span>
                                            <span class="text-xs font-bold text-gray-400"><?= number_format($opt['nilai'], 2) ?></span>
                                        </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: BENEFIT -->
                    <div>
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-green-200">
                            <div class="bg-green-100 text-green-600 p-2 rounded-lg"><i class="fas fa-arrow-up"></i></div>
                            <h3 class="font-bold text-gray-800 text-lg">Kriteria BENEFIT</h3>
                            <span class="text-xs text-green-500 bg-green-50 px-2 py-1 rounded-full font-medium ml-auto">Semakin Tinggi = Semakin Baik</span>
                        </div>

                        <div class="space-y-5">
                            <?php foreach ($subKriteria as $kode => $data): ?>
                                <?php if($data['jenis'] == 'benefit'): ?>
                                <div class="p-4 border border-gray-100 rounded-lg hover:border-green-200 hover:shadow-sm transition bg-white group">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-bold"><?= $kode ?></span>
                                            <h4 class="font-semibold text-gray-800 group-hover:text-green-600 transition"><?= htmlspecialchars($data['nama']) ?></h4>
                                        </div>
                                        <span class="text-xs font-mono text-gray-400 bg-gray-50 px-2 py-0.5 rounded">Bobot: <?= $data['bobot'] ?>%</span>
                                    </div>
                                    <div class="space-y-2 pl-2">
                                        <?php foreach ($data['options'] as $opt): ?>
                                        <label class="flex items-center space-x-3 p-2 hover:bg-green-50 rounded-lg cursor-pointer transition border border-transparent hover:border-green-100">
                                            <input type="radio" name="<?= strtolower($kode) ?>" value="<?= $opt['nilai'] ?>" required
                                                   class="w-4 h-4 text-green-600 focus:ring-green-500 border-gray-300">
                                            <span class="text-sm text-gray-600 flex-1">
                                                <?= htmlspecialchars($opt['label']) ?>
                                            </span>
                                            <span class="text-xs font-bold text-gray-400"><?= number_format($opt['nilai'], 2) ?></span>
                                        </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </form>
            
            <!-- TABEL DATA -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Data Kriteria Tersimpan</h2>
                        <p class="text-xs text-gray-500 mt-1">Total: <?= count($data_input) ?> data</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="toggleExpandAll()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition shadow text-sm font-medium">
                            <i class="fas fa-expand-alt mr-2"></i> Expand All
                        </button>
                        <button id="btnCetak" onclick="cetakSelected()" class="hidden bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition shadow text-sm font-medium">
                            <i class="fas fa-file-pdf mr-2"></i> Cetak PDF Terpilih
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left" style="table-layout: fixed; width: 100%;">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-center w-10" style="width: 40px;">
                                    <input type="checkbox" id="selectAll" onclick="toggleAll()" class="checkbox-custom rounded text-green-600 focus:ring-green-500">
                                </th>
                                <th class="px-4 py-3 font-semibold text-gray-700" style="width: 50px;">No</th>
                                <th class="px-4 py-3 font-semibold text-gray-700" style="width: 200px;">Nama Lengkap</th>
                                <th class="px-4 py-3 font-semibold text-gray-700" style="width: 120px;">NIK</th>
                                <th class="px-4 py-3 font-semibold text-gray-700 text-center" style="width: 80px;">Cost</th>
                                <th class="px-4 py-3 font-semibold text-gray-700 text-center" style="width: 80px;">Benefit</th>
                                <th class="px-4 py-3 font-semibold text-gray-700 text-center" style="width: 100px;">Detail</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700" style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (empty($data_input)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-gray-400 py-8">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                                        <p>Belum ada data kriteria yang diinput.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($data_input as $row): ?>
                                <tr class="hover:bg-gray-50 transition group">
                                    <td class="px-4 py-3 text-center" style="width: 40px;">
                                        <input type="checkbox" class="checkbox-row checkbox-custom rounded text-green-600 focus:ring-green-500" value="<?= $row['id'] ?>">
                                    </td>
                                    <td class="px-4 py-3 text-gray-500" style="width: 50px;"><?= $no++ ?></td>
                                    <td class="px-4 py-3 font-medium text-gray-900 truncate" style="width: 200px;" title="<?= htmlspecialchars($row['nama']) ?>"><?= htmlspecialchars($row['nama']) ?></td>
                                    <td class="px-4 py-3 text-gray-500 font-mono text-xs" style="width: 120px;"><?= htmlspecialchars($row['nik']) ?></td>
                                    
                                    <!-- Cost Summary -->
                                    <td class="px-3 py-3 text-center" style="width: 80px;">
                                        <div class="text-xs font-medium text-red-600 bg-red-50 rounded px-2 py-1">
                                            <?php 
                                            $costSum = 0;
                                            for ($i = 1; $i <= 7; $i++) $costSum += $row["c$i"];
                                            echo number_format($costSum, 1);
                                            ?>
                                        </div>
                                    </td>
                                    
                                    <!-- Benefit Summary -->
                                    <td class="px-3 py-3 text-center" style="width: 80px;">
                                        <div class="text-xs font-medium text-green-600 bg-green-50 rounded px-2 py-1">
                                            <?php 
                                            $benefitSum = 0;
                                            for ($i = 8; $i <= 14; $i++) $benefitSum += $row["c$i"];
                                            echo number_format($benefitSum, 1);
                                            ?>
                                        </div>
                                    </td>
                                    
                                    <!-- Detail Button -->
                                    <td class="px-4 py-3 text-center" style="width: 100px;">
                                        <button onclick="toggleDetail(<?= $row['id'] ?>)" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </button>
                                    </td>
                                    
                                    <td class="px-4 py-3 text-center" style="width: 80px;">
                                        <div class="flex justify-center space-x-1">
                                            <button onclick='editData(<?= $row['penerima_id'] ?>)' 
                                                    class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded transition" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <button onclick='deleteData(<?= $row['id'] ?>)' 
                                                    class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-100 rounded transition" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Expandable Detail Row -->
                                <tr id="detail-<?= $row['id'] ?>" class="hidden bg-gray-50">
                                    <td colspan="8" class="px-4 py-4">
                                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                                            <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                                <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                                                Detail 14 Kriteria - <?= htmlspecialchars($row['nama']) ?>
                                            </h4>
                                            <div class="grid grid-cols-2 gap-6">
                                                <!-- Cost Criteria -->
                                                <div>
                                                    <h5 class="text-sm font-bold text-red-600 mb-2">Kriteria COST (Semakin Rendah = Semakin Baik)</h5>
                                                    <div class="space-y-1">
                                                        <?php 
                                                        $costNames = ['LL' => 'Luas Lantai', 'JL' => 'Jenis Lantai', 'JD' => 'Jenis Dinding', 'FB' => 'Fasilitas Buang Air', 'SP' => 'Sumber Penerangan', 'SA' => 'Sumber Air', 'BB' => 'Bahan Bakar'];
                                                        for ($i = 1; $i <= 7; $i++): 
                                                            $singkat = array_keys($costNames)[$i-1];
                                                            $nama = $costNames[$singkat];
                                                        ?>
                                                        <div class="flex justify-between items-center p-2 bg-red-50 rounded">
                                                            <span class="text-xs font-medium text-gray-700">C<?= $i ?> - <?= $nama ?></span>
                                                            <span class="text-xs font-bold text-red-600 bg-white px-2 py-1 rounded"><?= number_format($row["c$i"], 2) ?></span>
                                                        </div>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                                
                                                <!-- Benefit Criteria -->
                                                <div>
                                                    <h5 class="text-sm font-bold text-green-600 mb-2">Kriteria BENEFIT (Semakin Tinggi = Semakin Baik)</h5>
                                                    <div class="space-y-1">
                                                        <?php 
                                                        $benefitNames = ['KP' => 'Konsumsi Protein', 'PA' => 'Pakaian', 'FM' => 'Frekuensi Makan', 'KB' => 'Kemampuan Berobat', 'PH' => 'Penghasilan', 'PD' => 'Pendidikan', 'AS' => 'Aset'];
                                                        for ($i = 8; $i <= 14; $i++): 
                                                            $singkat = array_keys($benefitNames)[$i-8];
                                                            $nama = $benefitNames[$singkat];
                                                        ?>
                                                        <div class="flex justify-between items-center p-2 bg-green-50 rounded">
                                                            <span class="text-xs font-medium text-gray-700">C<?= $i ?> - <?= $nama ?></span>
                                                            <span class="text-xs font-bold text-green-600 bg-white px-2 py-1 rounded"><?= number_format($row["c$i"], 2) ?></span>
                                                        </div>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillBio() {
            const select = document.getElementById('penerimaSelect');
            const option = select.options[select.selectedIndex];
            
            if (option.value) {
                document.getElementById('view_nik').innerText = option.getAttribute('data-nik') || '-';
                document.getElementById('view_alamat').innerText = option.getAttribute('data-alamat') || '-';
            } else {
                document.getElementById('view_nik').innerText = '-';
                document.getElementById('view_alamat').innerText = '-';
            }
        }
        
        function editData(penerimaId) {
            document.getElementById('penerimaSelect').value = penerimaId;
            fillBio();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        function toggleDetail(id) {
            const detailRow = document.getElementById('detail-' + id);
            detailRow.classList.toggle('hidden');
        }
        
        function toggleExpandAll() {
            const allDetails = document.querySelectorAll('[id^="detail-"]');
            const allHidden = Array.from(allDetails).every(row => row.classList.contains('hidden'));
            
            allDetails.forEach(row => {
                if (allHidden) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
            
            // Update button text
            const btn = event.target.closest('button');
            if (btn) {
                btn.innerHTML = allHidden ? '<i class="fas fa-compress-alt mr-2"></i> Collapse All' : '<i class="fas fa-expand-alt mr-2"></i> Expand All';
            }
        }
        
        function toggleAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.checkbox-row');
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateCetakButton();
        }
        
        function updateCetakButton() {
            const checkboxes = document.querySelectorAll('.checkbox-row:checked');
            const btnCetak = document.getElementById('btnCetak');
            if (checkboxes.length > 0) {
                btnCetak.classList.remove('hidden');
            } else {
                btnCetak.classList.add('hidden');
            }
        }
        
        function cetakSelected() {
            const checkboxes = document.querySelectorAll('.checkbox-row:checked');
            const ids = Array.from(checkboxes).map(cb => cb.value);
            if (ids.length > 0) {
                window.location.href = 'laporan.php?ids=' + ids.join(',');
            } else {
                alert('Pilih data yang ingin dicetak terlebih dahulu!');
            }
        }
        
        function deleteData(id) {
            if (confirm('Yakin ingin menghapus data ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // Event listener untuk setiap checkbox
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.checkbox-row');
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateCetakButton);
            });
        });
    </script>
</body>
</html>