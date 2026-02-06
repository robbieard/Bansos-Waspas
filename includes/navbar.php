<!-- Navbar -->
<nav class="gradient-bg shadow-lg fixed top-0 left-0 right-0 z-50" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
    <div class="px-8 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo & Brand -->
            <div class="flex items-center space-x-3">
                <div class="bg-white p-2 rounded-lg">
                    <i class="fas fa-hand-holding-heart text-2xl text-green-600"></i>
                </div>
                <div class="text-white">
                    <h1 class="text-xl font-bold">SISTEM BANTUAN SOSIAL</h1>
                    <p class="text-xs opacity-90">Nagari Ampang Gadang - WASPAS</p>
                </div>
            </div>
            
            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <button class="relative text-white hover:text-gray-200 transition">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">3</span>
                </button>
                
                <!-- User Profile Dropdown -->
                <div class="relative group">
                    <button class="flex items-center space-x-3 text-white hover:text-gray-200 transition">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="text-left hidden md:block">
                            <p class="text-sm font-semibold"><?= htmlspecialchars($user['nama_lengkap']) ?></p>
                            <p class="text-xs opacity-90"><?= ucfirst($user['role']) ?></p>
                        </div>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                        <div class="py-2">
                            <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-circle mr-2"></i> Profile
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i> Settings
                            </a>
                            <hr class="my-2">
                            <button onclick="confirmLogout()" class="flex items-center w-full px-4 py-2 text-red-600 hover:bg-red-50 text-left">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Spacer for fixed navbar -->
<div class="h-20"></div>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-sign-out-alt text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Logout</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin keluar dari sistem?
                </p>
            </div>
            <div class="flex gap-3 mt-4">
                <button onclick="closeLogoutModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button onclick="doLogout()" class="flex-1 px-4 py-2 bg-red-600 text-white text-base font-medium rounded-lg hover:bg-red-700 transition">
                    Ya, Logout
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function confirmLogout() {
    document.getElementById('logoutModal').classList.remove('hidden');
}

function closeLogoutModal() {
    document.getElementById('logoutModal').classList.add('hidden');
}

function doLogout() {
    window.location.href = '<?= str_contains($_SERVER['PHP_SELF'], '/pages/') ? '../' : '' ?>logout.php';
}
</script>
