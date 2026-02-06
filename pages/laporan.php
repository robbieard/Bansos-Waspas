<?php
require_once '../classes/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/WASPAS.php';

$auth = new Auth();
$auth->requireLogin();
$user = $auth->getUser();

// Get filter parameters
$ids = $_GET['ids'] ?? ''; // IDs dari data_kriteria (opsional)
$tahun = $_GET['tahun'] ?? date('Y');
$periode = $_GET['periode'] ?? null;

$db = Database::getInstance();

// Calculate WASPAS for ranking
$waspas = new WASPAS();
$rankingResults = $waspas->calculate($tahun, $periode);

// Ambil data kriteria + biodata penerima
$params = [];
$where = '';

if ($ids) {
    $selectedIds = array_filter(array_map('trim', explode(',', $ids)), function($v) {
        return $v !== '';
    });

    if (!empty($selectedIds)) {
        $placeholders = implode(',', array_fill(0, count($selectedIds), '?'));
        $where = "WHERE dk.id IN ($placeholders)";
        $params = $selectedIds;
    }
}

$sql = "SELECT dk.*, p.nama, p.nik, p.alamat, p.no_rekening, p.nama_bank
        FROM data_kriteria dk
        JOIN penerima_bantuan p ON dk.penerima_id = p.id
        $where
        ORDER BY p.nama ASC";

$results = $db->fetchAll($sql, $params);

// Create ranking map for quick lookup
$rankingMap = [];
foreach ($rankingResults as $rank) {
    $rankingMap[$rank['penerima_id']] = $rank;
}

// Function to get keterangan based on rank
function getKeterangan($rank) {
    if ($rank == 1) return 'Prioritas Utama';
    if ($rank == 2) return 'Prioritas Tinggi';
    if ($rank == 3) return 'Prioritas Sedang';
    if ($rank <= 5) return 'Prioritas';
    if ($rank <= 10) return 'Pertimbangan';
    return 'Cadangan';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Kriteria - Sistem Bantuan Sosial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        
        @media print {
            .no-print { display: none !important; }
            body { 
                background: white !important; 
                margin: 0; 
                padding: 5px; 
                font-size: 8pt;
            }
            .print-container { 
                width: 100% !important; 
                margin: 0 !important; 
                padding: 0 !important; 
                max-width: none !important;
            }
            .page-break { page-break-after: always; }
            
            /* Force landscape orientation */
            @page {
                size: A4 landscape;
                margin: 8mm;
            }
            
            /* Optimized table for print */
            .table-report { 
                font-size: 0.50rem !important;
                table-layout: fixed !important;
                width: 100% !important;
                border-collapse: collapse !important;
            }
            .table-report th, .table-report td { 
                border: 1px solid #000 !important; 
                padding: 2px !important; 
                text-align: center !important; 
                font-size: 0.45rem !important;
                line-height: 1.1 !important;
                vertical-align: middle !important;
            }
            .table-report th { 
                background-color: #d1d5db !important; 
                color: black !important; 
                font-weight: bold !important;
                writing-mode: vertical-rl;
                text-orientation: mixed;
                height: 60px;
                padding: 1px !important;
            }
            .table-report td { 
                background-color: white !important; 
                color: black !important;
            }
            .table-report .col-nama { 
                text-align: left !important; 
                font-size: 0.40rem !important;
                max-width: 60px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .table-report .col-alamat { 
                text-align: left !important; 
                font-size: 0.35rem !important;
                max-width: 50px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .table-report .col-nik { 
                font-size: 0.35rem !important;
            }
            .table-report .col-ket { 
                font-size: 0.35rem !important;
                font-weight: bold !important;
            }
            
            /* Header colors for print */
            .cost-header { 
                background-color: #fecaca !important; 
                color: #7f1d1d !important;
            }
            .benefit-header { 
                background-color: #bbf7d0 !important; 
                color: #15803d !important;
            }
            
            /* Highlight top 3 */
            .top3-highlight {
                background-color: #fef3c7 !important;
                font-weight: bold !important;
            }
            
            /* Reduce header font size */
            .header-title { font-size: 14pt !important; }
            .header-subtitle { font-size: 11pt !important; }
            .header-info { font-size: 9pt !important; }
            .legend-text { font-size: 7pt !important; }
        }
        
        /* Screen styles */
        .table-report { 
            border-collapse: collapse; 
            font-size: 0.60rem; 
        }
        .table-report th, .table-report td { 
            border: 1px solid #000; 
            padding: 4px; 
            text-align: center; 
        }
        .table-report th { 
            background-color: #d1d5db; 
            color: black; 
            font-weight: bold; 
        }
        .table-report td { 
            background-color: white; 
            color: black; 
        }
        .table-report .col-nama { text-align: left; }
        .table-report .col-alamat { text-align: left; }
        .cost-header { background-color: #fecaca !important; color: #7f1d1d !important; }
        .benefit-header { background-color: #bbf7d0 !important; color: #15803d !important; }
        
        /* For screen view */
        .table-screen {
            overflow-x: auto;
        }
        .table-screen th, .table-screen td {
            min-width: 45px;
        }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Print Button & Back (Hidden when printing) -->
    <div class="no-print fixed top-4 right-4 z-50 flex gap-2">
        <a href="input-kriteria.php" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg shadow-lg transition inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        <button onclick="window.print()" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-lg transition">
            <i class="fas fa-print mr-2"></i>Cetak PDF
        </button>
    </div>
    
    <div class="print-container max-w-7xl mx-auto p-6">
        <!-- Header -->
        <div class="text-center mb-4 border-b-2 border-green-600 pb-3">
            <h1 class="header-title text-2xl font-bold text-gray-800 mb-2">LAPORAN DATA KRITERIA KELUARGA MISKIN</h1>
            <h2 class="header-subtitle text-xl font-semibold text-green-600">SISTEM PENDUKUNG KEPUTUSAN PENYALURAN BANTUAN SOSIAL</h2>
            <p class="header-info text-lg mt-1">Kantor Wali Nagari Ampang Gadang</p>
            <p class="header-info text-sm text-gray-600 mt-1">Metode WASPAS (Weighted Aggregated Sum Product Assessment)</p>
            <p class="header-info text-sm text-gray-600 mt-1">Tanggal Cetak: <?= date('d/m/Y H:i') ?> WIB</p>
        </div>
        
        <?php if (empty($results)): ?>
        <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
            <p>Tidak ada data untuk dicetak.</p>
        </div>
        <?php else: ?>
        
        <!-- Table -->
        <div class="table-screen mb-4">
            <table class="table-report w-full">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 25px;">No</th>
                        <th rowspan="2" style="width: 30px;">Rank</th>
                        <th rowspan="2" style="width: 60px;">Nama</th>
                        <th rowspan="2" style="width: 50px;">NIK</th>
                        <th rowspan="2" style="width: 50px;">Alamat</th>
                        <th rowspan="2" style="width: 40px;">No Rek</th>
                        <th rowspan="2" style="width: 40px;">Bank</th>
                        <th rowspan="2" style="width: 35px;">Ket</th>
                        <th colspan="9" class="cost-header" style="font-size: 0.55rem;">KRITERIA COST</th>
                        <th colspan="5" class="benefit-header" style="font-size: 0.55rem;">KRITERIA BENEFIT</th>
                    </tr>
                    <tr>
                        <!-- COST Criteria - Reordered: C1,C2,C3,C4,C5,C6,C7,C12,C13 -->
                        <th class="cost-header" title="Luas Lantai" style="width: 20px;">LL</th>
                        <th class="cost-header" title="Jenis Lantai" style="width: 20px;">JL</th>
                        <th class="cost-header" title="Jenis Dinding" style="width: 20px;">JD</th>
                        <th class="cost-header" title="Fasilitas Buang air" style="width: 20px;">FB</th>
                        <th class="cost-header" title="Sumber Penerangan" style="width: 20px;">SP</th>
                        <th class="cost-header" title="Sumber Air" style="width: 20px;">SA</th>
                        <th class="cost-header" title="Bahan Bakar" style="width: 20px;">BB</th>
                        <th class="cost-header" title="Penghasilan" style="width: 20px;">PH</th>
                        <th class="cost-header" title="Pendidikan" style="width: 20px;">PD</th>
                        
                        <!-- BENEFIT Criteria - Reordered: C8,C9,C10,C11,C14 -->
                        <th class="benefit-header" title="Konsumsi Protein" style="width: 20px;">KP</th>
                        <th class="benefit-header" title="Pakaian" style="width: 20px;">PA</th>
                        <th class="benefit-header" title="Frekuensi Makan" style="width: 20px;">FM</th>
                        <th class="benefit-header" title="Kemampuan Berobat" style="width: 20px;">KB</th>
                        <th class="benefit-header" title="Aset" style="width: 20px;">AS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($results as $row): 
                        // Get ranking info
                        $rankInfo = isset($rankingMap[$row['penerima_id']]) ? $rankingMap[$row['penerima_id']] : null;
                        $rank = $rankInfo ? $rankInfo['rank'] : 0;
                        $keterangan = $rank ? getKeterangan($rank) : 'Tidak Ada Ranking';
                        $isTop3 = $rank && $rank <= 3;
                        
                        // Reorder criteria data
                        $criteriaData = [
                            'c1' => $row['c1'], 'c2' => $row['c2'], 'c3' => $row['c3'], 'c4' => $row['c4'],
                            'c5' => $row['c5'], 'c6' => $row['c6'], 'c7' => $row['c7'], 'c12' => $row['c12'],
                            'c13' => $row['c13'], 'c8' => $row['c8'], 'c9' => $row['c9'], 'c10' => $row['c10'],
                            'c11' => $row['c11'], 'c14' => $row['c14']
                        ];
                    ?>
                    <tr class="<?= $isTop3 ? 'top3-highlight' : '' ?>">
                        <td><?= $no++ ?></td>
                        <td class="text-center font-bold">
                            <?php if ($isTop3): ?>
                                <i class="fas fa-medal <?= $rank == 1 ? 'text-yellow-500' : ($rank == 2 ? 'text-gray-400' : 'text-orange-600') ?>"></i>
                            <?php endif; ?>
                            #<?= $rank ?>
                        </td>
                        <td class="col-nama font-bold <?= $isTop3 ? 'text-green-700' : '' ?>" title="<?= htmlspecialchars($row['nama']) ?>"><?= htmlspecialchars($row['nama']) ?></td>
                        <td class="col-nik font-mono"><?= htmlspecialchars($row['nik']) ?></td>
                        <td class="col-alamat" title="<?= htmlspecialchars($row['alamat'] ?? '-') ?>"><?= htmlspecialchars($row['alamat'] ?? '-') ?></td>
                        <td class="text-xs font-mono"><?= htmlspecialchars($row['no_rekening'] ?? '-') ?></td>
                        <td class="text-xs"><?= htmlspecialchars($row['nama_bank'] ?? '-') ?></td>
                        <td class="col-ket text-center"><?= $keterangan ?></td>
                        
                        <!-- COST Criteria Values (Reordered) -->
                        <td><?= number_format($criteriaData['c1'], 2) ?></td>
                        <td><?= number_format($criteriaData['c2'], 2) ?></td>
                        <td><?= number_format($criteriaData['c3'], 2) ?></td>
                        <td><?= number_format($criteriaData['c4'], 2) ?></td>
                        <td><?= number_format($criteriaData['c5'], 2) ?></td>
                        <td><?= number_format($criteriaData['c6'], 2) ?></td>
                        <td><?= number_format($criteriaData['c7'], 2) ?></td>
                        <td><?= number_format($criteriaData['c12'], 2) ?></td>
                        <td><?= number_format($criteriaData['c13'], 2) ?></td>
                        
                        <!-- BENEFIT Criteria Values (Reordered) -->
                        <td><?= number_format($criteriaData['c8'], 2) ?></td>
                        <td><?= number_format($criteriaData['c9'], 2) ?></td>
                        <td><?= number_format($criteriaData['c10'], 2) ?></td>
                        <td><?= number_format($criteriaData['c11'], 2) ?></td>
                        <td><?= number_format($criteriaData['c14'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Legend & Footer -->
        <div class="mt-4">
            <!-- Legend/Keterangan Ranking -->
            <div class="mb-4 p-3 bg-gray-50 rounded-lg border legend-text">
                <h4 class="font-bold text-gray-800 mb-2 text-sm">Keterangan:</h4>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="flex items-center">
                        <i class="fas fa-medal text-yellow-500 mr-1"></i>
                        <span><strong>Rank 1-3:</strong> Prioritas Utama (Highlight Kuning)</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 bg-green-100 border border-green-300 mr-1"></span>
                        <span><strong>Rank 4-5:</strong> Prioritas</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 bg-gray-100 border border-gray-300 mr-1"></span>
                        <span><strong>Rank 6-10:</strong> Pertimbangan</span>
                    </div>
                    <div class="flex items-center">
                        <span><strong>Rank >10:</strong> Cadangan</span>
                    </div>
                </div>
                
                <div class="mt-2 text-xs">
                    <p><strong>Kriteria COST (9):</strong> LL=Luas Lantai, JL=Jenis Lantai, JD=Jenis Dinding, FB=Fas Buang Air, SP=Sumber Penerangan, SA=Sumber Air, BB=Bahan Bakar, PH=Penghasilan, PD=Pendidikan</p>
                    <p><strong>Kriteria BENEFIT (5):</strong> KP=Konsumsi Protein, PA=Pakaian, FM=Frekuensi Makan, KB=Kemampuan Berobat, AS=Aset</p>
                </div>
            </div>
            
            <div class="flex justify-between items-end text-xs">
                <div class="text-gray-600">
                    <p>Total Data: <strong><?= count($results) ?> Penerima</strong></p>
                    <p class="mt-1">Dicetak: <?= date('d/m/Y H:i:s') ?> WIB</p>
                </div>
                
                <div class="text-center">
                    <p class="mb-12">Ampang Gadang, <?= date('d F Y') ?></p>
                    <p class="font-bold">Wali Nagari Ampang Gadang</p>
                    <p class="mt-1">( ___________________ )</p>
                </div>
            </div>
        </div>
        
        <?php endif; ?>
    </div>
    
</body>
</html>
