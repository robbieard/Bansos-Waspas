<!-- Sidebar -->
<aside class="fixed left-0 top-20 h-full w-64 bg-white shadow-lg overflow-y-auto">
    <div class="p-6">
        <!-- Menu Items -->
        <nav class="space-y-2">
            <!-- Dashboard -->
            <a href="<?= str_contains($_SERVER['PHP_SELF'], '/pages/') ? '../' : '' ?>index.php" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-700 hover:bg-gray-100' ?> transition">
                <i class="fas fa-home w-5"></i>
                <span>Dashboard</span>
            </a>
            
            <?php if ($auth->isAdmin()): ?>
            <!-- Master Data -->
            <div class="pt-4 pb-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Master Data</p>
            </div>
            
            <a href="<?= str_contains($_SERVER['PHP_SELF'], '/pages/') ? '' : 'pages/' ?>penerima.php" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'penerima.php' ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-700 hover:bg-gray-100' ?> transition">
                <i class="fas fa-users w-5"></i>
                <span>Data Penerima Bantuan</span>
            </a>
            
            <a href="<?= str_contains($_SERVER['PHP_SELF'], '/pages/') ? '' : 'pages/' ?>kriteria.php" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'kriteria.php' ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-700 hover:bg-gray-100' ?> transition">
                <i class="fas fa-list-check w-5"></i>
                <span>Data Kriteria</span>
            </a>
            
            <a href="<?= str_contains($_SERVER['PHP_SELF'], '/pages/') ? '' : 'pages/' ?>users.php" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-700 hover:bg-gray-100' ?> transition">
                <i class="fas fa-user-shield w-5"></i>
                <span>Data Users</span>
            </a>
            <?php endif; ?>
            
            <!-- Penilaian -->
            <div class="pt-4 pb-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Penilaian Evaluasi</p>
            </div>
            
            <a href="<?= str_contains($_SERVER['PHP_SELF'], '/pages/') ? '' : 'pages/' ?>input-kriteria.php" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'input-kriteria.php' ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-700 hover:bg-gray-100' ?> transition">
                <i class="fas fa-clipboard-list w-5"></i>
                <span>Input Kriteria Keluarga Miskin</span>
            </a>
            
            <!-- Hasil -->
            <div class="pt-4 pb-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Hasil & Laporan</p>
            </div>
            
            <a href="<?= str_contains($_SERVER['PHP_SELF'], '/pages/') ? '' : 'pages/' ?>hasil.php" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'hasil.php' ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-700 hover:bg-gray-100' ?> transition">
                <i class="fas fa-trophy w-5"></i>
                <span>Ranking WASPAS</span>
            </a>
            
            <a href="<?= str_contains($_SERVER['PHP_SELF'], '/pages/') ? '' : 'pages/' ?>laporan.php" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg <?= basename($_SERVER['PHP_SELF']) == 'laporan.php' ? 'bg-green-50 text-green-700 font-medium' : 'text-gray-700 hover:bg-gray-100' ?> transition">
                <i class="fas fa-file-pdf w-5"></i>
                <span>Cetak Laporan</span>
            </a>
        </nav>
        
        <!-- Help Box -->
        <div class="mt-8 p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg border border-green-200">
            <div class="flex items-center mb-2">
                <i class="fas fa-question-circle text-green-600 mr-2"></i>
                <h3 class="font-semibold text-gray-800">Butuh Bantuan?</h3>
            </div>
            <p class="text-sm text-gray-600 mb-3">Dokumentasi sistem WASPAS</p>
            <a href="#" class="block text-center bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 rounded-lg transition">
                Baca Panduan
            </a>
        </div>
        
        <!-- Logout Menu -->
        <div class="mt-6">
            <button onclick="confirmLogout()" class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition">
                <i class="fas fa-sign-out-alt w-5"></i>
                <span>Logout</span>
            </button>
        </div>
    </div>
</aside>
