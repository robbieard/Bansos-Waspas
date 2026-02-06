<?php
// Start output buffering
ob_start();

// Error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'classes/Database.php';
require_once 'classes/Auth.php';

$auth = new Auth();
$auth->requireLogin();

$user = $auth->getUser();
$pageTitle = 'Dashboard';
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
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    </style>
</head>
<body class="bg-gray-50">
    
    <?php include 'includes/navbar.php'; ?>
    
    <div class="flex">
        <?php include 'includes/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-8 ml-64">
            <!-- Welcome Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    Selamat Datang, <?= htmlspecialchars($user['nama_lengkap']) ?>! 👋
                </h1>
                <p class="text-gray-600">Sistem Pendukung Keputusan Penyaluran Bantuan Sosial</p>
                <p class="text-gray-500 text-sm">Kantor Wali Nagari Ampang Gadang - Metode WASPAS</p>
            </div>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <?php
                $db = Database::getInstance();
                
                // Get stats
                $totalPenerima = $db->fetchOne("SELECT COUNT(*) as total FROM penerima_bantuan")['total'];
                $totalKriteria = $db->fetchOne("SELECT COUNT(*) as total FROM kriteria WHERE status = 'aktif'")['total'];
                $totalDataKriteria = $db->fetchOne("SELECT COUNT(*) as total FROM data_kriteria")['total'];
                $totalUsers = $db->fetchOne("SELECT COUNT(*) as total FROM users")['total'];
                
                $stats = [
                    ['icon' => 'fa-users', 'label' => 'Total Penerima', 'value' => $totalPenerima, 'color' => 'green'],
                    ['icon' => 'fa-list-check', 'label' => 'Total Kriteria', 'value' => $totalKriteria, 'color' => 'blue'],
                    ['icon' => 'fa-file-lines', 'label' => 'Data Kriteria', 'value' => $totalDataKriteria, 'color' => 'purple'],
                    ['icon' => 'fa-user-shield', 'label' => 'Total Users', 'value' => $totalUsers, 'color' => 'orange'],
                ];
                
                $colorClasses = [
                    'green' => 'from-green-500 to-green-600',
                    'blue' => 'from-blue-500 to-blue-600',
                    'purple' => 'from-purple-500 to-purple-600',
                    'orange' => 'from-orange-500 to-orange-600',
                ];
                
                foreach ($stats as $stat):
                ?>
                <div class="bg-gradient-to-br <?= $colorClasses[$stat['color']] ?> rounded-xl shadow-lg p-6 text-white card-hover">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90 mb-1"><?= $stat['label'] ?></p>
                            <h3 class="text-4xl font-bold"><?= $stat['value'] ?></h3>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-4">
                            <i class="fas <?= $stat['icon'] ?> text-3xl"></i>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                    Quick Actions
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <?php if ($auth->isAdmin()): ?>
                    <a href="pages/penerima.php" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                        <i class="fas fa-user-plus text-green-600 text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-semibold text-gray-800">Tambah Penerima</h3>
                            <p class="text-sm text-gray-600">Input data calon penerima</p>
                        </div>
                    </a>
                    
                    <a href="pages/input-kriteria.php" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                        <i class="fas fa-pen-to-square text-blue-600 text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-semibold text-gray-800">Input Kriteria</h3>
                            <p class="text-sm text-gray-600">Input 14 kriteria keluarga miskin</p>
                        </div>
                    </a>
                    <?php endif; ?>
                    
                    <a href="pages/hasil.php" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                        <i class="fas fa-trophy text-purple-600 text-2xl mr-3"></i>
                        <div>
                            <h3 class="font-semibold text-gray-800">Lihat Ranking</h3>
                            <p class="text-sm text-gray-600">Hasil perhitungan WASPAS</p>
                        </div>
                    </a>
                </div>
            </div>
            
            <!-- About System -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- About WASPAS -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-green-500 mr-2"></i>
                        Tentang Metode WASPAS
                    </h2>
                    <div class="text-gray-600 space-y-2">
                        <p><strong>WASPAS</strong> (Weighted Aggregated Sum Product Assessment) adalah metode Multi-Criteria Decision Making (MCDM).</p>
                        <p class="text-sm">Metode ini menggabungkan dua pendekatan: WSM (Weighted Sum Model) dan WPM (Weighted Product Model).</p>
                        <div class="mt-4 p-3 bg-green-50 rounded-lg">
                            <p class="text-sm font-semibold mb-2">Formula WASPAS:</p>
                            <p class="text-sm font-mono">Qi1 = Σ(wj × rij)</p>
                            <p class="text-sm font-mono">Qi2 = Π(rij ^ wj)</p>
                            <p class="text-sm font-mono">Qi = λ × Qi1 + (1-λ) × Qi2</p>
                            <p class="text-xs mt-2">λ = 0.5 (default, bobot seimbang)</p>
                        </div>
                    </div>
                </div>
                
                <!-- System Info -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <h2 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-building mr-2"></i>
                        Informasi Sistem
                    </h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="opacity-90">Instansi</span>
                            <span class="font-semibold text-sm">Nagari Ampang Gadang</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="opacity-90">Metode</span>
                            <span class="font-semibold">WASPAS</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="opacity-90">Version</span>
                            <span class="font-semibold">1.0 WASPAS</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="opacity-90">PHP Version</span>
                            <span class="font-semibold"><?= phpversion() ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="opacity-90">Database</span>
                            <span class="font-semibold">MySQL</span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-white border-opacity-20">
                            <p class="text-sm opacity-90">
                                <i class="fas fa-shield-halved mr-1"></i>
                                Sistem berjalan optimal & aman
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
