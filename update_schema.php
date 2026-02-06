<?php
require_once 'classes/Database.php';

$db = Database::getInstance();

echo "<h1>🛠️ JPS Schema Update</h1>";
echo "<pre>";

try {
    $db->execute("SET FOREIGN_KEY_CHECKS = 0");
    
    // 1. Update Penerima Bantuan (Add nama_bank)
    // We recreate it to be clean and ordered
    $db->execute("DROP TABLE IF EXISTS penerima_bantuan");
    
    $sqlPenerima = "CREATE TABLE `penerima_bantuan` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `nama` varchar(100) NOT NULL,
      `nik` varchar(20) NOT NULL,
      `alamat` text,
      `no_rekening` varchar(50),
      `nama_bank` varchar(50) COMMENT 'NEW: Nama Bank',
      `jml_penghuni` int(11),
      `pkh` varchar(10),
      `bpnt` varchar(10),
      `kp` varchar(10),
      `kehilangan_pekerjaan` varchar(10),
      `tidak_terdata` varchar(10),
      `sakit_kronis` varchar(10),
      `rekomendasi` varchar(20),
      `no_hp` varchar(20),
      `keterangan` text,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $db->execute($sqlPenerima);
    echo "✅ Re-created 'penerima_bantuan' with 'nama_bank'.\n";

    // 2. Seed JPS Criteria (10 Items)
    $db->execute("TRUNCATE TABLE kriteria");
    $criteria = [
        ['C1', 'PKH'],
        ['C2', 'BPNT'],
        ['C3', 'KP'],
        ['C4', 'Kehilangan Mata Pencaharian'],
        ['C5', 'Tidak Terdata'],
        ['C6', 'Sakit Kronis'],
        ['C7', 'Rekomendasi MS'],
        ['C8', 'Rekomendasi TMS'],
        ['C9', 'KTP Setempat'],
        ['C10', 'KTP Luar']
    ];
    
    $sqlK = "INSERT INTO kriteria (kode, nama, bobot, jenis, keterangan) VALUES (?, ?, 10.00, 'benefit', 'JPS Checklist')";
    foreach ($criteria as $c) {
        $db->execute($sqlK, [$c[0], $c[1]]);
    }
    echo "✅ Seeded 10 JPS Criteria (PKH, BPNT, etc).\n";

    // 3. Ensure Data Kriteria exists
    $db->execute("DROP TABLE IF EXISTS data_kriteria");
    $sqlData = "CREATE TABLE `data_kriteria` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `penerima_id` int(11) NOT NULL,
      `c1` tinyint(1) DEFAULT 0,
      `c2` tinyint(1) DEFAULT 0,
      `c3` tinyint(1) DEFAULT 0,
      `c4` tinyint(1) DEFAULT 0,
      `c5` tinyint(1) DEFAULT 0,
      `c6` tinyint(1) DEFAULT 0,
      `c7` tinyint(1) DEFAULT 0,
      `c8` tinyint(1) DEFAULT 0,
      `c9` tinyint(1) DEFAULT 0,
      `c10` tinyint(1) DEFAULT 0,
      `c11` tinyint(1) DEFAULT 0, -- Extra space
      `c12` tinyint(1) DEFAULT 0,
      `c13` tinyint(1) DEFAULT 0,
      `c14` tinyint(1) DEFAULT 0,
      `total_bobot` decimal(10,2) DEFAULT 0,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `penerima_id` (`penerima_id`),
      CONSTRAINT `fk_data_penerima` FOREIGN KEY (`penerima_id`) REFERENCES `penerima_bantuan` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $db->execute($sqlData);
    echo "✅ Re-created 'data_kriteria'.\n";

    // 4. Dummy Data
    $db->execute("INSERT INTO penerima_bantuan (nama, nik, alamat, no_rekening, nama_bank) VALUES 
    ('Budi Santoso', '1371010101920001', 'Jorong Ampang Gadang', '12345-67890', 'BPD Sumbar'), 
    ('Siti Aminah', '1371010202920002', 'Jorong Pasar', '09876-54321', 'BRI')");
    echo "✅ Inserted Dummy Data.\n";

    $db->execute("SET FOREIGN_KEY_CHECKS = 1");
    echo "\n✨ SUCCESS! JPS System Ready.";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
echo "</pre>";
?>
