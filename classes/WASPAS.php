<?php
// ================================================
// WASPAS (Weighted Aggregated Sum Product Assessment) 
// Calculation Class - FIXED VERSION
// ================================================
// PERHITUNGAN:
// - Nilai di data_kriteria (c1-c14) SUDAH dalam bentuk desimal (0.33, 0.50, 1.00)
// - TIDAK PERLU NORMALISASI LAGI
// - Q1 = 0.5 × Σ(c_ij × w_j) untuk j=1 sampai 14
// - Q2 = 0.5 × Π(c_ij^w_j) untuk j=1 sampai 14
// - Qi_WASPAS = Q1 + Q2 (Final Score)

class WASPAS {
    private $db;
    private $lambda = 0.5;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Calculate WASPAS ranking
     * @param int $tahun Tahun penilaian
     * @param string|null $periode Periode penilaian (optional)
     * @param float $lambda Lambda untuk Q1 dan Q2 (default 0.5)
     * @return array Hasil perhitungan dengan ranking
     */
    public function calculate($tahun, $periode = null, $lambda = 0.5) {
        $this->lambda = $lambda;
        
        // 1. Ambil kriteria aktif (14 kriteria C1-C14)
        $kriteria = $this->getKriteria();
        
        if (empty($kriteria)) {
            return ['error' => 'Belum ada kriteria aktif'];
        }
        
        // 2. Ambil data dari data_kriteria untuk semua penerima
        $dataKriteria = $this->getDataKriteria();
        
        if (empty($dataKriteria)) {
            return ['error' => 'Belum ada data kriteria'];
        }
        
        // 4. Hitung Q1, Q2, dan Qi untuk setiap penerima
        $results = [];
        
        foreach ($dataKriteria as $penerima) {
            $penerimaId = $penerima['penerima_id'];
            
            // Hitung Q1 dan Q2 dari data_kriteria (14 kriteria)
            $q1 = 0;
            $q2 = 1;
            
            // Buat map kriteria berdasarkan kode untuk akses cepat
            $kriteriaMap = [];
            foreach ($kriteria as $k) {
                $kriteriaMap[$k['kode']] = $k;
            }
            
            // Loop untuk setiap kriteria C1-C14
            for ($i = 1; $i <= 14; $i++) {
                $kode = "C$i";
                $col = "c$i";
                
                // Ambil nilai dari data_kriteria (sudah dalam bentuk desimal 0.33, 0.50, 1.00)
                $nilai = floatval($penerima[$col] ?? 0);
                
                // Ambil bobot dari kriteria
                if (isset($kriteriaMap[$kode])) {
                    $bobot = floatval($kriteriaMap[$kode]['bobot']) / 100; // Convert ke desimal (8% -> 0.08)
                    
                    // Q1 = 0.5 × Σ(c_ij × w_j)
                    $q1 += $nilai * $bobot;
                    
                    // Q2 = 0.5 × Π(c_ij^w_j)
                    // Jika nilai = 0, skip untuk menghindari error (0^w = 0)
                    if ($nilai > 0) {
                        $q2 *= pow($nilai, $bobot);
                    }
                }
            }
            
            // Q1 dan Q2 sudah dikalikan 0.5 sesuai formula
            $q1 = $this->lambda * $q1;
            $q2 = (1 - $this->lambda) * $q2;
            
            // Qi_WASPAS = Q1 + Q2
            $qiWaspas = $q1 + $q2;
            
            // Simpan detail nilai untuk setiap kriteria
            $details = [];
            for ($i = 1; $i <= 14; $i++) {
                $kode = "C$i";
                $col = "c$i";
                $nilai = floatval($penerima[$col] ?? 0);
                
                if (isset($kriteriaMap[$kode])) {
                    $details[$kode] = [
                        'kriteria' => $kriteriaMap[$kode]['nama'],
                        'nilai' => $nilai,
                        'bobot' => floatval($kriteriaMap[$kode]['bobot'])
                    ];
                }
            }
            
            $results[] = [
                'penerima_id' => $penerimaId,
                'nik' => $penerima['nik'],
                'nama' => $penerima['nama'],
                'q1' => round($q1, 4),
                'q2' => round($q2, 4),
                'qi_waspas' => round($qiWaspas, 4),
                'details' => $details
            ];
        }
        
        // 5. Ranking berdasarkan Qi WASPAS (descending - yang terbesar ranking 1)
        usort($results, function($a, $b) {
            return $b['qi_waspas'] <=> $a['qi_waspas'];
        });
        
        // 6. Tambahkan ranking
        $rank = 1;
        foreach ($results as &$result) {
            $result['rank'] = $rank++;
        }
        
        return $results;
    }
    
    /**
     * Ambil kriteria aktif dari database
     */
    private function getKriteria() {
        $sql = "SELECT * FROM kriteria WHERE status = 'aktif' AND kode LIKE 'C%' ORDER BY kode";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Ambil data kriteria dari data_kriteria untuk semua penerima
     */
    private function getDataKriteria() {
        $sql = "SELECT dk.*, p.nama, p.nik, p.id as penerima_id 
                FROM data_kriteria dk 
                JOIN penerima_bantuan p ON dk.penerima_id = p.id
                ORDER BY dk.id DESC";
        return $this->db->fetchAll($sql);
    }
    

    
    /**
     * Get statistics
     */
    public function getStats($tahun, $periode = null) {
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM penerima_bantuan) as total_penerima,
                    (SELECT COUNT(*) FROM kriteria WHERE status = 'aktif') as total_kriteria,
                    (SELECT COUNT(*) FROM data_kriteria) as total_data_kriteria";
        
        return $this->db->fetchOne($sql);
    }
    
    /**
     * Set lambda value
     */
    public function setLambda($lambda) {
        $this->lambda = $lambda;
    }
}
