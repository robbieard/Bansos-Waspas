<?php
require_once '../classes/Database.php';
require_once '../classes/Auth.php';

$auth = new Auth();
$auth->requireAdmin();
$user = $auth->getUser();
$db = Database::getInstance();

$message = '';
$messageType = '';

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'add') {
        try {
            $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $sql = "INSERT INTO users (username, password, nama_lengkap, email, role) VALUES (?, ?, ?, ?, ?)";
            $db->execute($sql, [
                $_POST['username'],
                $hashedPassword,
                $_POST['nama_lengkap'],
                $_POST['email'],
                $_POST['role']
            ]);
            $message = 'User berhasil ditambahkan!';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
    
    if ($action == 'edit') {
        try {
            if (!empty($_POST['password'])) {
                $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $sql = "UPDATE users SET username=?, password=?, nama_lengkap=?, email=?, role=? WHERE id=?";
                $db->execute($sql, [
                    $_POST['username'],
                    $hashedPassword,
                    $_POST['nama_lengkap'],
                    $_POST['email'],
                    $_POST['role'],
                    $_POST['id']
                ]);
            } else {
                $sql = "UPDATE users SET username=?, nama_lengkap=?, email=?, role=? WHERE id=?";
                $db->execute($sql, [
                    $_POST['username'],
                    $_POST['nama_lengkap'],
                    $_POST['email'],
                    $_POST['role'],
                    $_POST['id']
                ]);
            }
            $message = 'User berhasil diupdate!';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
    
    if ($action == 'delete') {
        try {
            $sql = "DELETE FROM users WHERE id=?";
            $db->execute($sql, [$_POST['id']]);
            $message = 'User berhasil dihapus!';
            $messageType = 'success';
        } catch (Exception $e) {
            $message = 'Error: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Get all users
$users = $db->fetchAll("SELECT * FROM users ORDER BY username");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Users - Sistem Bantuan Sosial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    </style>
</head>
<body class="bg-gray-50">
    <?php include '../includes/navbar.php'; ?>
    
    <div class="flex">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="flex-1 p-8 ml-64">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-user-shield text-green-600 mr-2"></i>
                    Data Users
                </h1>
                <p class="text-gray-600">Kelola akun pengguna sistem</p>
            </div>
            
            <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-lg <?= $messageType == 'success' ? 'bg-green-50 border-l-4 border-green-500 text-green-700' : 'bg-red-50 border-l-4 border-red-500 text-red-700' ?>">
                <i class="fas <?= $messageType == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> mr-2"></i>
                <?= $message ?>
            </div>
            <?php endif; ?>
            
            <div class="mb-6">
                <button onclick="showAddModal()" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition shadow-lg">
                    <i class="fas fa-user-plus mr-2"></i>Tambah User
                </button>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Username</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Nama Lengkap</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Role</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Created</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($users as $row): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-semibold"><?= htmlspecialchars($row['username']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                <td class="px-6 py-4 text-sm"><?= htmlspecialchars($row['email']) ?></td>
                                <td class="px-6 py-4 text-center">
                                    <?php if ($row['role'] == 'admin'): ?>
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                        <i class="fas fa-crown"></i> Admin
                                    </span>
                                    <?php else: ?>
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                        <i class="fas fa-user"></i> User
                                    </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick='editData(<?= json_encode($row) ?>)' class="text-blue-600 hover:text-blue-800 mr-2" title="Edit">
                                        <i class="fas fa-edit text-lg"></i>
                                    </button>
                                    <?php if ($row['id'] != $user['id']): ?>
                                    <button onclick="deleteData(<?= $row['id'] ?>, '<?= htmlspecialchars($row['username']) ?>')" class="text-red-600 hover:text-red-800" title="Hapus">
                                        <i class="fas fa-trash text-lg"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 w-full max-w-2xl">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah User</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div class="col-span-2">
                        <label class="block mb-2 font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Email</label>
                        <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Role <span class="text-red-500">*</span></label>
                        <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                    <button type="button" onclick="hideAddModal()" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 w-full max-w-2xl">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit User</h2>
            <form method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" id="edit_username" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="edit_password" placeholder="Kosongkan jika tidak diubah" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div class="col-span-2">
                        <label class="block mb-2 font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="edit_nama_lengkap" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="edit_email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Role <span class="text-red-500">*</span></label>
                        <select name="role" id="edit_role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                    <button type="button" onclick="hideEditModal()" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Form -->
    <form id="deleteForm" method="POST" class="hidden">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" id="delete_id">
    </form>
    
    <script>
        function showAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }
        function hideAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }
        
        function editData(data) {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_username').value = data.username;
            document.getElementById('edit_nama_lengkap').value = data.nama_lengkap;
            document.getElementById('edit_email').value = data.email || '';
            document.getElementById('edit_role').value = data.role;
            document.getElementById('edit_password').value = '';
            document.getElementById('editModal').classList.remove('hidden');
        }
        
        function hideEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        
        function deleteData(id, username) {
            if (confirm('Hapus user "' + username + '"?')) {
                document.getElementById('delete_id').value = id;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</body>
</html>
