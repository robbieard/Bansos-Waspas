<?php
require_once '../classes/Database.php';
require_once '../classes/Auth.php';

$auth = new Auth();
$auth->requireAdmin();
$user = $auth->getUser();
$db = Database::getInstance();

// Sub-kriteria mapping untuk setiap kriteria
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

// HANDLE ACTIONS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'edit') {
        $id = $_POST['id'] ?? '';
        $bobot = floatval($_POST['bobot'] ?? 0);
        $status = $_POST['status'] ?? 'aktif';
        
        $db->execute("UPDATE kriteria SET bobot = ?, status = ? WHERE id = ?", [$bobot, $status, $id]);
        header('Location: kriteria.php?msg=updated');
        exit;
    }
}

// GET DATA KRITERIA (14 kriteria C1-C14)
$kriteria_list = $db->fetchAll("SELECT * FROM kriteria WHERE kode LIKE 'C%' ORDER BY id");

// Jika belum ada data, seed default
if (empty($kriteria_list)) {
    $defaultKriteria = [
        ['C1', 'Luas lantai per kapita', 8.00, 'cost'],
        ['C2', 'Jenis lantai', 6.00, 'cost'],
        ['C3', 'Jenis dinding', 6.00, 'cost'],
        ['C4', 'Akses fasilitas buang air', 7.00, 'cost'],
        ['C5', 'Sumber penerangan', 6.00, 'cost'],
        ['C6', 'Sumber air minum', 6.00, 'cost'],
        ['C7', 'Bahan bakar memasak', 5.00, 'cost'],
        ['C8', 'Konsumsi protein mingguan', 6.00, 'benefit'],
        ['C9', 'Kepemilikan pakaian tahunan', 4.00, 'benefit'],
        ['C10', 'Frekuensi makan harian', 7.00, 'benefit'],
        ['C11', 'Kemampuan berobat', 7.00, 'benefit'],
        ['C12', 'Sumber penghasilan & besaran', 12.00, 'cost'],
        ['C13', 'Pendidikan kepala keluarga', 10.00, 'cost'],
        ['C14', 'Aset/tabungan likuid', 10.00, 'benefit'],
    ];
    
    $sql = "INSERT INTO kriteria (kode, nama, bobot, jenis, keterangan, status) VALUES (?, ?, ?, ?, 'Kriteria Keluarga Miskin', 'aktif')";
    foreach ($defaultKriteria as $k) {
        $db->execute($sql, $k);
    }
    
    header('Location: kriteria.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kriteria - Sistem Bantuan Sosial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <?php include '../includes/navbar.php'; ?>
    
    <div class="flex">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="flex-1 p-8 ml-64">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Data Kriteria (14 Kriteria Keluarga Miskin)</h1>
                    <p class="text-sm text-gray-500">Kelola kriteria C1-C14, edit bobot dan status aktif/nonaktif</p>
                </div>
            </div>
            
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                <p class="font-bold">Sukses!</p>
                <p>Data kriteria berhasil diperbarui.</p>
            </div>
            <?php endif; ?>
            
            <!-- TABLE KRITERIA -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800">Daftar Kriteria</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Kode</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Nama Kriteria</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Bobot (%)</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Jenis</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php $no = 1; foreach ($kriteria_list as $kriteria): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium"><?= $no++ ?></td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">
                                        <?= htmlspecialchars($kriteria['kode']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    <?= htmlspecialchars($kriteria['nama']) ?>
                                    <button onclick="toggleDetail('<?= $kriteria['kode'] ?>')" 
                                            class="ml-2 text-green-600 hover:text-green-800 text-xs">
                                        <i class="fas fa-chevron-down" id="icon-<?= $kriteria['kode'] ?>"></i>
                                        Detail
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold"><?= number_format($kriteria['bobot'], 2) ?>%</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $kriteria['jenis'] == 'benefit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                        <?= ucfirst($kriteria['jenis']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $kriteria['status'] == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' ?>">
                                        <?= ucfirst($kriteria['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick='openEditModal(<?= json_encode($kriteria) ?>)' 
                                            class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- SUB-KRITERIA ROW (Collapsed by default) -->
                            <tr id="detail-<?= $kriteria['kode'] ?>" class="hidden bg-blue-50">
                                <td colspan="7" class="px-6 py-4">
                                    <div class="bg-white rounded-lg p-4 border border-blue-200">
                                        <h4 class="font-bold text-gray-800 mb-3">Sub-Kriteria untuk <?= htmlspecialchars($kriteria['kode']) ?> - <?= htmlspecialchars($kriteria['nama']) ?></h4>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <?php if (isset($subKriteria[$kriteria['kode']])): ?>
                                                <?php foreach ($subKriteria[$kriteria['kode']]['options'] as $sub): ?>
                                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                                    <p class="text-sm text-gray-700 mb-1"><?= htmlspecialchars($sub['label']) ?></p>
                                                    <p class="text-xs text-gray-500">Nilai: <span class="font-bold text-green-600"><?= number_format($sub['nilai'], 2) ?></span></p>
                                                </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
            <div class="flex justify-between items-center pb-3 border-b mb-6">
                <h3 class="text-xl font-bold text-gray-900">Edit Kriteria</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form method="POST" id="editForm">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="editId">
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kode</label>
                    <input type="text" id="editKode" readonly 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kriteria</label>
                    <input type="text" id="editNama" readonly 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bobot (%) *</label>
                    <input type="number" name="bobot" id="editBobot" step="0.01" min="0" max="100" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <p class="text-xs text-gray-500 mt-1">Masukkan bobot dalam persentase (0-100)</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                    <select name="status" id="editStatus" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                
                <div class="mt-6 flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-5 py-2.5 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition shadow-lg">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleDetail(kode) {
            const row = document.getElementById('detail-' + kode);
            const icon = document.getElementById('icon-' + kode);
            
            if (row.classList.contains('hidden')) {
                row.classList.remove('hidden');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                row.classList.add('hidden');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }
        
        function openEditModal(data) {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editId').value = data.id;
            document.getElementById('editKode').value = data.kode;
            document.getElementById('editNama').value = data.nama;
            document.getElementById('editBobot').value = data.bobot;
            document.getElementById('editStatus').value = data.status;
        }
        
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editForm').reset();
        }
    </script>
</body>
</html>
