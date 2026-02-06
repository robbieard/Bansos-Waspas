<?php
require_once 'classes/Database.php';

$db = Database::getInstance();

echo "<h1>🛠️ Criteria Seeding Utility</h1>";
echo "<pre>";

try {
    $db->execute("SET FOREIGN_KEY_CHECKS = 0");
    
    // TRUNCATE kriteria table
    $db->execute("TRUNCATE TABLE kriteria");
    echo "🗑️ Cleared existing criteria.\n";
    
    // TRUNCATE penilaian table (since criteria changed)
    $db->execute("TRUNCATE TABLE penilaian");
    echo "🗑️ Cleared existing assessments.\n";

    $kriteria = [
        ['C1', 'Luas lantai < 8m2/orang'],
        ['C2', 'Lantai tanah/bambu/kayu murah'],
        ['C3', 'Dinding bambu/rumbia/kayu murah/tembok tanpa plester'],
        ['C4', 'BAB tanpa fasilitas/bersama orang lain'],
        ['C5', 'Penerangan tanpa listrik'],
        ['C6', 'Air minum tidak terlindung/sungai/air hujan'],
        ['C7', 'Bahan bakar kayu bakar/arang/minyak tanah'],
        ['C8', 'Konsumsi daging/susu/ayam hanya 1 kali/minggu'],
        ['C9', 'Hanya memiliki satu stel pakaian setahun'],
        ['C10', 'Makan hanya 1-2 kali/hari'],
        ['C11', 'Tidak sanggup berobat ke puskesmas/poliklinik'],
        ['C12', 'Penghasilan < Rp 600.000/bulan'],
        ['C13', 'Pendidikan KK Tidak Tamat SD/SD'],
        ['C14', 'Tidak memiliki tabungan/barang senilai Rp 500.000']
    ];

    $weight = round(100 / 14, 2); // 7.14

    $sql = "INSERT INTO kriteria (kode, nama, bobot, jenis, keterangan) VALUES (?, ?, ?, 'benefit', 'Kriteria Keluarga Miskin')";
    
    foreach ($kriteria as $k) {
        $db->execute($sql, [$k[0], $k[1], $weight]);
        echo "✅ Added: {$k[0]} - {$k[1]} ($weight%)\n";
    }

    $db->execute("SET FOREIGN_KEY_CHECKS = 1");
    echo "\n✨ SUCCESS! 14 Criteria seeded.";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
echo "</pre>";
?>
