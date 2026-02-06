<?php
require_once '../classes/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/WASPAS.php';

$auth = new Auth();
$auth->requireLogin();

$user = $auth->getUser();
$pageTitle = 'Ranking WASPAS';

// Get filter parameters
$tahun = $_GET['tahun'] ?? date('Y');
$periode = $_GET['periode'] ?? null;

// Calculate WASPAS
$waspas = new WASPAS();
$results = $waspas->calculate($tahun, $periode);
$stats = $waspas->getStats($tahun, $periode);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - Sistem Bantuan Sosial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .medal-gold { color: #FFD700; }
        .medal-silver { color: #C0C0C0; }
        .medal-bronze { color: #CD7F32; }
    </style>
</head>
<body class="bg-gray-50">
    
    <?php include '../includes/navbar.php'; ?>
    
    <div class="flex">
        <?php include '../includes/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-8 ml-64">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                    Ranking Penerima Bantuan Sosial
                </h1>
                <p class="text-gray-600">Hasil perhitungan metode WASPAS (Weighted Aggregated Sum Product Assessment)</p>
            </div>
            
            <!-- Filter -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <form method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                        <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                            <option value="<?= $y ?>" <?= $tahun == $y ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                        <select name="periode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Periode</option>
                            <option value="Periode 1" <?= $periode == 'Periode 1' ? 'selected' : '' ?>>Periode 1</option>
                            <option value="Periode 2" <?= $periode == 'Periode 2' ? 'selected' : '' ?>>Periode 2</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
            
            <?php if (isset($results['error'])): ?>
            <!-- Error Message -->
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-6 rounded-lg">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?= htmlspecialchars($results['error']) ?>
            </div>
            
            <?php else: ?>
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Penerima</p>
                            <h3 class="text-4xl font-bold mt-1"><?= $stats['total_penerima'] ?? 0 ?></h3>
                        </div>
                        <i class="fas fa-users text-5xl opacity-20"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Total Kriteria</p>
                            <h3 class="text-4xl font-bold mt-1"><?= $stats['total_kriteria'] ?? 0 ?></h3>
                        </div>
                        <i class="fas fa-list-check text-5xl opacity-20"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Data Kriteria</p>
                            <h3 class="text-4xl font-bold mt-1"><?= $stats['total_data_kriteria'] ?? 0 ?></h3>
                        </div>
                        <i class="fas fa-file-lines text-5xl opacity-20"></i>
                    </div>
                </div>
            </div>
            
            <!-- Top 3 Podium -->
            <?php if (count($results) >= 3): ?>
            <div class="mb-8 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
                    <i class="fas fa-medal text-yellow-500 mr-2"></i>
                    TOP 3 PENERIMA PRIORITAS
                </h2>
                <div class="grid grid-cols-3 gap-6 items-end">
                    <!-- Rank 2 (Silver) -->
                    <div class="text-center transform hover:scale-105 transition">
                        <div class="bg-gradient-to-br from-gray-400 to-gray-500 rounded-lg p-6 text-white mb-3 h-40 flex flex-col justify-center">
                            <i class="fas fa-medal medal-silver text-5xl mb-2"></i>
                            <p class="text-xl font-bold">#2</p>
                            <p class="text-sm mt-2"><?= htmlspecialchars($results[1]['nama']) ?></p>
                            <p class="text-2xl font-bold mt-2"><?= number_format($results[1]['qi_waspas'], 4) ?></p>
                        </div>
                    </div>
                    
                    <!-- Rank 1 (Gold) -->
                    <div class="text-center transform hover:scale-110 transition">
                        <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg p-8 text-white mb-3 h-56 flex flex-col justify-center shadow-2xl">
                            <i class="fas fa-crown text-6xl mb-3 animate-bounce"></i>
                            <p class="text-2xl font-bold">#1</p>
                            <p class="text-base mt-2 font-semibold"><?= htmlspecialchars($results[0]['nama']) ?></p>
                            <p class="text-3xl font-bold mt-3"><?= number_format($results[0]['qi_waspas'], 4) ?></p>
                        </div>
                    </div>
                    
                    <!-- Rank 3 (Bronze) -->
                    <div class="text-center transform hover:scale-105 transition">
                        <div class="bg-gradient-to-br from-orange-600 to-orange-700 rounded-lg p-6 text-white mb-3 h-32 flex flex-col justify-center">
                            <i class="fas fa-medal medal-bronze text-4xl mb-2"></i>
                            <p class="text-lg font-bold">#3</p>
                            <p class="text-xs mt-2"><?= htmlspecialchars($results[2]['nama']) ?></p>
                            <p class="text-xl font-bold mt-2"><?= number_format($results[2]['qi_waspas'], 4) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Complete Ranking Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-list-ol text-green-600 mr-2"></i>
                        Ranking Lengkap - Metode WASPAS
                    </h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full" style="table-layout: fixed; width: 100%;">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase" style="width: 80px;">Rank</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase" style="width: 250px;">Nama</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase" style="width: 120px;">NIK</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase" style="width: 100px;">Q1</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase" style="width: 100px;">Q2</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase" style="width: 120px;">Qi</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase" style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($results as $row): ?>
                            <tr class="hover:bg-gray-50 transition <?= $row['rank'] <= 3 ? 'bg-yellow-50' : '' ?>">
                                <td class="px-6 py-4" style="width: 80px;">
                                    <div class="flex items-center">
                                        <?php if ($row['rank'] <= 3): ?>
                                        <i class="fas fa-medal <?= $row['rank'] == 1 ? 'medal-gold' : ($row['rank'] == 2 ? 'medal-silver' : 'medal-bronze') ?> text-2xl mr-2"></i>
                                        <?php endif; ?>
                                        <span class="font-bold text-lg">#<?= $row['rank'] ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4" style="width: 250px;">
                                    <div class="font-semibold text-gray-800 truncate" title="<?= htmlspecialchars($row['nama']) ?>"><?= htmlspecialchars($row['nama']) ?></div>
                                </td>
                                <td class="px-6 py-4 text-gray-600 font-mono text-sm" style="width: 120px;"><?= htmlspecialchars($row['nik']) ?></td>
                                <td class="px-6 py-4 text-center" style="width: 100px;">
                                    <span class="text-sm text-gray-600"><?= number_format($row['q1'], 4) ?></span>
                                </td>
                                <td class="px-6 py-4 text-center" style="width: 100px;">
                                    <span class="text-sm text-gray-600"><?= number_format($row['q2'], 4) ?></span>
                                </td>
                                <td class="px-6 py-4 text-center" style="width: 120px;">
                                    <span class="inline-block px-4 py-2 bg-green-100 text-green-800 font-bold rounded-lg text-lg">
                                        <?= number_format($row['qi_waspas'], 4) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center" style="width: 150px;">
                                    <button onclick='showDetail(<?= json_encode($row) ?>)' 
                                            class="w-full px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transition font-medium">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Export Actions -->
            <div class="mt-6 flex gap-4">
                <a href="laporan.php?tahun=<?= $tahun ?>&periode=<?= urlencode($periode) ?>" 
                   class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition shadow-lg">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Export PDF
                </a>
                <button onclick="window.print()" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition shadow-lg">
                    <i class="fas fa-print mr-2"></i>
                    Print
                </button>
            </div>
            
            <?php endif; ?>
        </div>
    </div>
    
    <!-- MODAL DETAIL -->
    <div id="detailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-xl bg-white mb-20">
            <div class="flex justify-between items-center pb-3 border-b mb-6">
                <h3 class="text-xl font-bold text-gray-900">Detail Perhitungan</h3>
                <button onclick="closeDetail()" class="text-gray-400 hover:text-gray-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="detailContent"></div>
        </div>
    </div>

    <script>
        function showDetail(data) {
            let html = `
                <div class="mb-4">
                    <h4 class="font-bold text-lg mb-2">${data.nama} (${data.nik})</h4>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="p-3 bg-blue-50 rounded">
                            <p class="text-sm text-gray-600">Q1</p>
                            <p class="text-xl font-bold">${data.q1}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded">
                            <p class="text-sm text-gray-600">Q2</p>
                            <p class="text-xl font-bold">${data.q2}</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded">
                            <p class="text-sm text-gray-600">Qi</p>
                            <p class="text-xl font-bold">${data.qi_waspas}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h5 class="font-bold mb-2">Nilai per Kriteria:</h5>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 border">Kode</th>
                                    <th class="px-3 py-2 border">Kriteria</th>
                                    <th class="px-3 py-2 border">Nilai</th>
                                    <th class="px-3 py-2 border">Bobot (%)</th>
                                </tr>
                            </thead>
                            <tbody>`;
            
            if (data.details) {
                for (let kode in data.details) {
                    let d = data.details[kode];
                    html += `
                        <tr>
                            <td class="px-3 py-2 border font-bold">${kode}</td>
                            <td class="px-3 py-2 border">${d.kriteria}</td>
                            <td class="px-3 py-2 border text-center">${d.nilai.toFixed(2)}</td>
                            <td class="px-3 py-2 border text-center">${d.bobot.toFixed(2)}%</td>
                        </tr>`;
                }
            }
            
            html += `</tbody></table></div></div>`;
            
            document.getElementById('detailContent').innerHTML = html;
            document.getElementById('detailModal').classList.remove('hidden');
        }
        
        function closeDetail() {
            document.getElementById('detailModal').classList.add('hidden');
        }
    </script>
</body>
</html>
