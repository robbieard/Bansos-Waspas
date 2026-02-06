<?php
require_once '../classes/Database.php';
require_once '../classes/Auth.php';

$auth = new Auth();
$auth->requireAdmin();
$user = $auth->getUser();
$db = Database::getInstance();

// DEFINISI FIELD 5 DATA UTAMA (JPS)
$fields = [
    'nama' => ['label' => 'Nama', 'type' => 'text'],
    'nik' => ['label' => 'NIK', 'type' => 'text'],
    'alamat' => ['label' => 'Alamat', 'type' => 'textarea'],
    'no_rekening' => ['label' => 'No Rekening', 'type' => 'text'],
    'nama_bank' => ['label' => 'Nama Bank', 'type' => 'text'], // New Field
    'no_hp' => ['label' => 'No HP/WA', 'type' => 'text'],
    'keterangan' => ['label' => 'Keterangan Tambahan', 'type' => 'text'],
];

// HANDLE ACTIONS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    $params = [];
    foreach ($fields as $key => $val) {
        $params[] = $_POST[$key] ?? '';
    }

    if ($action == 'add') {
        $sql = "INSERT INTO penerima_bantuan (" . implode(', ', array_keys($fields)) . ") VALUES (" . str_repeat('?,', count($fields)-1) . "?)";
        $db->execute($sql, $params);
        header('Location: penerima.php?msg=added');
        exit;
    }
    
    if ($action == 'edit') {
        $id = $_POST['id'];
        $setQuery = implode(' = ?, ', array_keys($fields)) . ' = ?';
        $params[] = $id; 
        
        $sql = "UPDATE penerima_bantuan SET $setQuery WHERE id = ?";
        $db->execute($sql, $params);
        header('Location: penerima.php?msg=updated');
        exit;
    }
    
    if ($action == 'delete') {
        $id = $_POST['id'];
        $db->execute("DELETE FROM penerima_bantuan WHERE id = ?", [$id]);
        header('Location: penerima.php?msg=deleted');
        exit;
    }
}

// GET DATA
$data = $db->fetchAll("SELECT * FROM penerima_bantuan ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penerima Bantuan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap'); * { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50">
    <?php include '../includes/navbar.php'; ?>
    
    <div class="flex">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="flex-1 p-8 ml-64">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Master Data Penerima</h1>
                    <p class="text-sm text-gray-500">Database Warga Calon Penerima JPS</p>
                </div>
                <button onclick="openModal('add')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition">
                    <i class="fas fa-plus mr-2"></i> Input Data Warga
                </button>
            </div>
            
            <?php if (isset($_GET['msg'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                <p class="font-bold">Sukses!</p>
                <p>Data berhasil diperbarui.</p>
            </div>
            <?php endif; ?>
            
            <!-- TABLE -->
            <div class="bg-white rounded-xl shadow-lg run-overflow-x-auto overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Nama</th>
                            <th class="px-6 py-4">NIK</th>
                            <th class="px-6 py-4">Alamat</th>
                            <th class="px-6 py-4">Bank</th>
                            <th class="px-6 py-4">No Rekening</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php $no=1; foreach ($data as $row): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium"><?= $no++ ?></td>
                            <td class="px-6 py-4 font-bold text-gray-900"><?= htmlspecialchars($row['nama']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($row['nik']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($row['alamat']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($row['nama_bank']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($row['no_rekening']) ?></td>
                            <td class="px-6 py-4 text-center">
                                <button onclick='openModal("edit", <?= json_encode($row) ?>)' class="text-blue-600 hover:text-blue-900 mr-2"><i class="fas fa-edit"></i></button>
                                <form method="POST" class="inline" onsubmit="return confirm('Hapus data ini?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($data)): ?>
                        <tr><td colspan="7" class="px-6 py-4 text-center text-gray-400">Belum ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div id="modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-xl bg-white mb-20">
            <div class="flex justify-between items-center pb-3 border-b mb-6">
                <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Formulir Warga</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-900"><i class="fas fa-times"></i></button>
            </div>
            
            <form method="POST" id="formInput">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="id" id="dataId">
                
                <div class="grid grid-cols-1 gap-4">
                    <?php foreach($fields as $key => $config): ?>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1"><?= $config['label'] ?></label>
                        <?php if ($config['type'] == 'textarea'): ?>
                        <textarea name="<?= $key ?>" id="<?= $key ?>" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required></textarea>
                        <?php else: ?>
                        <input type="<?= $config['type'] ?>" name="<?= $key ?>" id="<?= $key ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-8 flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition shadow-lg">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(mode, data = null) {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('formAction').value = mode;
            document.getElementById('modalTitle').innerText = mode === 'add' ? 'Tambah Warga Baru' : 'Edit Data Warga';
            
            if (mode === 'edit' && data) {
                document.getElementById('dataId').value = data.id;
                <?php foreach(array_keys($fields) as $k): ?>
                if(document.getElementById('<?= $k ?>')) document.getElementById('<?= $k ?>').value = data.<?= $k ?>;
                <?php endforeach; ?>
            } else {
                document.getElementById('formInput').reset();
                document.getElementById('formAction').value = 'add';
            }
        }
        function closeModal() { document.getElementById('modal').classList.add('hidden'); }
    </script>
</body>
</html>
