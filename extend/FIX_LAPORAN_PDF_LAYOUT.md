# 🎯 PROMPT FIX LAPORAN PDF - TABEL DATA KRITERIA

## PROBLEM STATEMENT
PDF yang di-generate dari `pages/laporan.php` memiliki layout yang berantakan:
- ❌ Kolom terlalu sempit & tidak proporsional
- ❌ Text terpotong
- ❌ Tidak ada background color untuk header COST/BENEFIT
- ❌ Font terlalu kecil
- ❌ Pagination buruk (tabel terpotong)
- ❌ Overall layout tidak match dengan tampilan web

**Target:** Membuat PDF seperti tampilan web (Image 1 - rapi, colorful, readable)

---

## 🎨 SOLUTION: COMPLETE REDESIGN PDF LAYOUT

**File:** `pages/laporan.php`

### ASSUMPTION
Menggunakan **TCPDF** library (standard di PHP)

---

## 📄 STEP 1: SETUP PDF CONFIGURATION

**LOCATION:** Beginning of laporan.php (after PHP opening tag)

### 1.1 Include Libraries & Dependencies

```php
<?php
require_once '../classes/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/WASPAS.php';

// Include TCPDF library
require_once('../vendor/tcpdf/tcpdf.php'); // Adjust path sesuai instalasi

$auth = new Auth();
$auth->requireLogin();

$db = Database::getInstance();
```

### 1.2 Get Data & Calculate WASPAS

```php
// Get filter parameters
$tahun = $_GET['tahun'] ?? date('Y');
$periode = $_GET['periode'] ?? null;
$ids = isset($_GET['ids']) ? explode(',', $_GET['ids']) : null;

// Calculate WASPAS
$waspas = new WASPAS();
$results = $waspas->calculate($tahun, $periode);

// Filter by IDs if specified (dari cetak terpilih)
if ($ids && !empty($ids)) {
    $results = array_filter($results, function($row) use ($ids) {
        return in_array($row['penerima_id'], $ids);
    });
    // Re-index array
    $results = array_values($results);
}

// If no data, show message
if (empty($results)) {
    die('Tidak ada data untuk dicetak.');
}
```

---

## 📊 STEP 2: INITIALIZE PDF WITH PROPER SETTINGS

```php
// ========================================
// PDF CONFIGURATION
// ========================================

// Create PDF instance - LANDSCAPE ORIENTATION
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Sistem Bantuan Sosial');
$pdf->SetAuthor('Kantor Wali Nagari Ampang Gadang');
$pdf->SetTitle('Laporan Data Kriteria Keluarga Miskin');
$pdf->SetSubject('Metode WASPAS');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margins - IMPORTANT untuk landscape
$pdf->SetMargins(10, 10, 10); // Left, Top, Right
$pdf->SetAutoPageBreak(TRUE, 10); // Auto page break dengan margin bottom 10mm

// Set font
$pdf->SetFont('helvetica', '', 9);

// Add a page
$pdf->AddPage();
```

---

## 🎨 STEP 3: CREATE PDF HEADER (Title, Subtitle, Metadata)

```php
// ========================================
// PDF HEADER SECTION
// ========================================

// Title
$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 8, 'LAPORAN DATA KRITERIA KELUARGA MISKIN', 0, 1, 'C');

// Subtitle
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetTextColor(0, 128, 0); // Green color
$pdf->Cell(0, 6, 'SISTEM PENDUKUNG KEPUTUSAN PENYALURAN BANTUAN SOSIAL', 0, 1, 'C');

// Organization
$pdf->SetFont('helvetica', '', 11);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 5, 'Kantor Wali Nagari Ampang Gadang', 0, 1, 'C');

// Method info
$pdf->SetFont('helvetica', '', 9);
$pdf->SetTextColor(80, 80, 80);
$pdf->Cell(0, 5, 'Metode WASPAS (Weighted Aggregated Sum Product Assessment)', 0, 1, 'C');

// Print date
$pdf->Cell(0, 5, 'Tanggal Cetak: ' . date('d/m/Y H:i') . ' WIB', 0, 1, 'C');

// Add line separator
$pdf->Ln(2);
$pdf->SetLineWidth(0.5);
$pdf->SetDrawColor(0, 128, 0);
$pdf->Line(10, $pdf->GetY(), 287, $pdf->GetY()); // 287 = A4 Landscape width - margin
$pdf->Ln(3);
```

---

## 📋 STEP 4: CREATE TABLE WITH PROPER COLUMN WIDTHS

### 4.1 Define Column Widths (CRITICAL!)

```php
// ========================================
// TABLE CONFIGURATION
// ========================================

// Total usable width for Landscape A4 = 297mm - 20mm (margins) = 277mm
// We'll use 270mm to be safe

// Column widths (in mm) - CAREFULLY CALCULATED
$colWidths = [
    'no'      => 8,   // No
    'rank'    => 10,  // Rank
    'nama'    => 35,  // Nama
    'nik'     => 25,  // NIK
    'alamat'  => 30,  // Alamat
    'norek'   => 20,  // No Rek
    'bank'    => 15,  // Bank
    'ket'     => 15,  // Keterangan
    // 14 Kriteria columns (C1-C14)
    'll'      => 8,   // C1 - LL
    'jl'      => 8,   // C2 - JL
    'jd'      => 8,   // C3 - JD
    'fb'      => 8,   // C4 - FB
    'sp'      => 8,   // C5 - SP
    'sa'      => 8,   // C6 - SA
    'bb'      => 8,   // C7 - BB
    'kp'      => 8,   // C8 - KP
    'pa'      => 8,   // C9 - PA
    'fm'      => 8,   // C10 - FM
    'kb'      => 8,   // C11 - KB
    'ph'      => 8,   // C12 - PH
    'pd'      => 8,   // C13 - PD
    'as'      => 8,   // C14 - AS
];

// Total: 8+10+35+25+30+20+15+15 + (14×8) = 158 + 112 = 270mm ✓

// Singkatan kriteria
$kriteriaSingkat = [
    'C1' => 'LL', 'C2' => 'JL', 'C3' => 'JD', 'C4' => 'FB', 
    'C5' => 'SP', 'C6' => 'SA', 'C7' => 'BB', 'C8' => 'KP',
    'C9' => 'PA', 'C10' => 'FM', 'C11' => 'KB', 'C12' => 'PH',
    'C13' => 'PD', 'C14' => 'AS'
];

// COST kriteria (9 kriteria)
$costKriteria = ['C1', 'C2', 'C3', 'C4', 'C5', 'C6', 'C7', 'C12', 'C13'];
// BENEFIT kriteria (5 kriteria)
$benefitKriteria = ['C8', 'C9', 'C10', 'C11', 'C14'];
```

---

## 📊 STEP 5: CREATE TABLE HEADER (2 ROWS WITH COLSPAN)

```php
// ========================================
// TABLE HEADER
// ========================================

$pdf->SetFont('helvetica', 'B', 7);

// ============ HEADER ROW 1 (Main Grouping) ============
$currentY = $pdf->GetY();

// Basic Info Columns (rowspan = 2)
$pdf->SetFillColor(220, 220, 220); // Light gray
$pdf->SetTextColor(0, 0, 0);

// No
$pdf->MultiCell($colWidths['no'], 10, 'No', 1, 'C', true, 0, '', '', true, 0, false, true, 10, 'M');
// Rank
$pdf->MultiCell($colWidths['rank'], 10, 'Rank', 1, 'C', true, 0, '', '', true, 0, false, true, 10, 'M');
// Nama
$pdf->MultiCell($colWidths['nama'], 10, 'Nama', 1, 'C', true, 0, '', '', true, 0, false, true, 10, 'M');
// NIK
$pdf->MultiCell($colWidths['nik'], 10, 'NIK', 1, 'C', true, 0, '', '', true, 0, false, true, 10, 'M');
// Alamat
$pdf->MultiCell($colWidths['alamat'], 10, 'Alamat', 1, 'C', true, 0, '', '', true, 0, false, true, 10, 'M');
// No Rek
$pdf->MultiCell($colWidths['norek'], 10, 'No Rek', 1, 'C', true, 0, '', '', true, 0, false, true, 10, 'M');
// Bank
$pdf->MultiCell($colWidths['bank'], 10, 'Bank', 1, 'C', true, 0, '', '', true, 0, false, true, 10, 'M');
// Keterangan
$pdf->MultiCell($colWidths['ket'], 10, 'Ket', 1, 'C', true, 0, '', '', true, 0, false, true, 10, 'M');

// COST Group Header (colspan = 9)
$costWidth = array_sum(array_slice($colWidths, 8, 9)); // Sum of 9 COST columns
$pdf->SetFillColor(254, 202, 202); // Light red (red-200)
$pdf->SetTextColor(127, 29, 29); // Dark red text
$pdf->MultiCell($costWidth, 5, 'COST', 1, 'C', true, 0, '', '', true, 0, false, true, 5, 'M');

// BENEFIT Group Header (colspan = 5)
$benefitWidth = array_sum(array_slice($colWidths, 17, 5)); // Sum of 5 BENEFIT columns
$pdf->SetFillColor(187, 247, 208); // Light green (green-200)
$pdf->SetTextColor(21, 128, 61); // Dark green text
$pdf->MultiCell($benefitWidth, 5, 'BENEFIT', 1, 'C', true, 1, '', '', true, 0, false, true, 5, 'M');

// ============ HEADER ROW 2 (Individual Criteria) ============
$pdf->SetY($currentY + 5); // Position 5mm below first row
$pdf->SetX(10 + $colWidths['no'] + $colWidths['rank'] + $colWidths['nama'] + 
           $colWidths['nik'] + $colWidths['alamat'] + $colWidths['norek'] + 
           $colWidths['bank'] + $colWidths['ket']); // Skip basic columns

$pdf->SetFont('helvetica', 'B', 6);

// COST Criteria Headers
$pdf->SetFillColor(254, 202, 202);
$pdf->SetTextColor(127, 29, 29);
foreach ($costKriteria as $kode) {
    $short = $kriteriaSingkat[$kode];
    $colKey = strtolower($short);
    $pdf->MultiCell($colWidths[$colKey], 5, $short, 1, 'C', true, 0, '', '', true, 0, false, true, 5, 'M');
}

// BENEFIT Criteria Headers
$pdf->SetFillColor(187, 247, 208);
$pdf->SetTextColor(21, 128, 61);
foreach ($benefitKriteria as $kode) {
    $short = $kriteriaSingkat[$kode];
    $colKey = strtolower($short);
    $pdf->MultiCell($colWidths[$colKey], 5, $short, 1, 'C', true, 0, '', '', true, 0, false, true, 5, 'M');
}

$pdf->Ln(); // New line after header
```

**ALTERNATIVE (Simpler Header - Single Row):**

If rowspan is too complex, use single row header:

```php
// ========================================
// TABLE HEADER - SIMPLE VERSION
// ========================================

$pdf->SetFont('helvetica', 'B', 7);
$pdf->SetFillColor(220, 220, 220);
$pdf->SetTextColor(0, 0, 0);

// Basic columns
$pdf->Cell($colWidths['no'], 8, 'No', 1, 0, 'C', true);
$pdf->Cell($colWidths['rank'], 8, 'Rank', 1, 0, 'C', true);
$pdf->Cell($colWidths['nama'], 8, 'Nama', 1, 0, 'C', true);
$pdf->Cell($colWidths['nik'], 8, 'NIK', 1, 0, 'C', true);
$pdf->Cell($colWidths['alamat'], 8, 'Alamat', 1, 0, 'C', true);
$pdf->Cell($colWidths['norek'], 8, 'No Rek', 1, 0, 'C', true);
$pdf->Cell($colWidths['bank'], 8, 'Bank', 1, 0, 'C', true);
$pdf->Cell($colWidths['ket'], 8, 'Ket', 1, 0, 'C', true);

// COST columns (red background)
$pdf->SetFillColor(254, 202, 202);
$pdf->SetTextColor(127, 29, 29);
foreach ($costKriteria as $kode) {
    $short = $kriteriaSingkat[$kode];
    $colKey = strtolower($short);
    $pdf->Cell($colWidths[$colKey], 8, $short, 1, 0, 'C', true);
}

// BENEFIT columns (green background)
$pdf->SetFillColor(187, 247, 208);
$pdf->SetTextColor(21, 128, 61);
foreach ($benefitKriteria as $kode) {
    $short = $kriteriaSingkat[$kode];
    $colKey = strtolower($short);
    $pdf->Cell($colWidths[$colKey], 8, $short, 1, 0, 'C', true);
}

$pdf->Ln(); // New line
```

---

## 📝 STEP 6: FILL TABLE WITH DATA

```php
// ========================================
// TABLE BODY - DATA ROWS
// ========================================

$pdf->SetFont('helvetica', '', 6);
$pdf->SetTextColor(0, 0, 0);

$no = 1;
foreach ($results as $row) {
    // Determine Keterangan based on rank
    if ($row['rank'] <= 3) {
        $keterangan = 'Prioritas Utama';
        $fillColor = [255, 243, 205]; // Yellow for top 3
    } elseif ($row['rank'] <= 10) {
        $keterangan = 'Prioritas';
        $fillColor = [255, 255, 255]; // White
    } elseif ($row['qi_waspas'] >= 0.5) {
        $keterangan = 'Layak';
        $fillColor = [255, 255, 255];
    } else {
        $keterangan = 'Pertimbangan';
        $fillColor = [255, 255, 255];
    }
    
    $pdf->SetFillColor($fillColor[0], $fillColor[1], $fillColor[2]);
    
    // Basic columns
    $pdf->Cell($colWidths['no'], 6, $no++, 1, 0, 'C', true);
    $pdf->Cell($colWidths['rank'], 6, $row['rank'], 1, 0, 'C', true);
    $pdf->Cell($colWidths['nama'], 6, substr($row['nama'], 0, 25), 1, 0, 'L', true);
    $pdf->Cell($colWidths['nik'], 6, $row['nik'], 1, 0, 'C', true);
    
    // Get full data from database for alamat, no_rek, bank
    $penerimaData = $db->fetchOne(
        "SELECT alamat, no_rekening, nama_bank FROM penerima_bantuan WHERE id = ?",
        [$row['penerima_id']]
    );
    
    $pdf->Cell($colWidths['alamat'], 6, substr($penerimaData['alamat'] ?? '-', 0, 20), 1, 0, 'L', true);
    $pdf->Cell($colWidths['norek'], 6, $penerimaData['no_rekening'] ?? '-', 1, 0, 'C', true);
    $pdf->Cell($colWidths['bank'], 6, substr($penerimaData['nama_bank'] ?? '-', 0, 10), 1, 0, 'C', true);
    $pdf->Cell($colWidths['ket'], 6, $keterangan, 1, 0, 'C', true);
    
    // Get nilai kriteria from database
    $kriteriaData = $db->fetchOne(
        "SELECT c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, c11, c12, c13, c14 
         FROM data_kriteria WHERE penerima_id = ?",
        [$row['penerima_id']]
    );
    
    // COST criteria values
    foreach ($costKriteria as $kode) {
        $colNum = str_replace('C', 'c', $kode);
        $nilai = number_format(floatval($kriteriaData[$colNum] ?? 0), 2);
        $short = $kriteriaSingkat[$kode];
        $colKey = strtolower($short);
        $pdf->Cell($colWidths[$colKey], 6, $nilai, 1, 0, 'C', true);
    }
    
    // BENEFIT criteria values
    foreach ($benefitKriteria as $kode) {
        $colNum = str_replace('C', 'c', $kode);
        $nilai = number_format(floatval($kriteriaData[$colNum] ?? 0), 2);
        $short = $kriteriaSingkat[$kode];
        $colKey = strtolower($short);
        $pdf->Cell($colWidths[$colKey], 6, $nilai, 1, 0, 'C', true);
    }
    
    $pdf->Ln(); // New line for next row
}
```

---

## 📌 STEP 7: ADD LEGEND & FOOTER

```php
// ========================================
// LEGEND SECTION
// ========================================

$pdf->Ln(3);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 5, 'KETERANGAN:', 0, 1, 'L');

$pdf->SetFont('helvetica', '', 7);
$pdf->SetTextColor(60, 60, 60);

$legends = [
    '• LL = Luas Lantai, JL = Jenis Lantai, JD = Jenis Dinding, FB = Fasilitas Buang Air',
    '• SP = Sumber Penerangan, SA = Sumber Air, BB = Bahan Bakar',
    '• KP = Konsumsi Protein, PA = Pakaian, FM = Frekuensi Makan, KB = Kemampuan Berobat',
    '• PH = Penghasilan, PD = Pendidikan KK, AS = Aset/Tabungan',
    '',
    '• COST = Kriteria biaya (nilai tinggi = kondisi buruk = prioritas)',
    '• BENEFIT = Kriteria keuntungan (nilai tinggi = kondisi baik)',
    '',
    '• Prioritas Utama: Ranking 1-3 (paling berhak menerima)',
    '• Prioritas: Ranking 4-10 (sangat layak)',
    '• Layak: Qi Score ≥ 0.5',
    '• Pertimbangan: Perlu evaluasi lebih lanjut',
];

foreach ($legends as $legend) {
    $pdf->Cell(0, 4, $legend, 0, 1, 'L');
}

// ========================================
// FOOTER
// ========================================

$pdf->Ln(3);
$pdf->SetFont('helvetica', 'I', 7);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 4, 'Dicetak pada: ' . date('d/m/Y H:i:s') . ' WIB', 0, 1, 'C');
$pdf->Cell(0, 4, 'Sistem Bantuan Sosial - Kantor Wali Nagari Ampang Gadang', 0, 1, 'C');
```

---

## 📤 STEP 8: OUTPUT PDF

```php
// ========================================
// OUTPUT PDF
// ========================================

// Set filename
$filename = 'Laporan_Kriteria_' . date('Ymd_His') . '.pdf';

// Output PDF
// 'I' = Inline (tampil di browser)
// 'D' = Download
// 'F' = Save to file
$pdf->Output($filename, 'I');

// Exit to prevent further output
exit;
```

---

## 🎨 COMPLETE CODE TEMPLATE

**File:** `pages/laporan.php` (COMPLETE REPLACEMENT)

```php
<?php
// ========================================
// LAPORAN PDF - DATA KRITERIA KELUARGA MISKIN
// Sistem Bantuan Sosial - WASPAS
// ========================================

require_once '../classes/Database.php';
require_once '../classes/Auth.php';
require_once '../classes/WASPAS.php';

// TCPDF Library - adjust path sesuai instalasi
// Biasanya di: vendor/tecnickcom/tcpdf/tcpdf.php
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

$auth = new Auth();
$auth->requireLogin();

$db = Database::getInstance();

// ========================================
// GET DATA
// ========================================

$tahun = $_GET['tahun'] ?? date('Y');
$periode = $_GET['periode'] ?? null;
$ids = isset($_GET['ids']) ? array_filter(explode(',', $_GET['ids'])) : null;

$waspas = new WASPAS();
$results = $waspas->calculate($tahun, $periode);

// Filter by selected IDs if specified
if ($ids && !empty($ids)) {
    $filtered = [];
    foreach ($results as $row) {
        if (in_array($row['penerima_id'], $ids)) {
            $filtered[] = $row;
        }
    }
    $results = $filtered;
}

if (empty($results)) {
    die('Tidak ada data untuk dicetak.');
}

// ========================================
// PDF SETUP
// ========================================

$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

$pdf->SetCreator('Sistem Bantuan Sosial');
$pdf->SetAuthor('Kantor Wali Nagari Ampang Gadang');
$pdf->SetTitle('Laporan Data Kriteria Keluarga Miskin');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);

$pdf->AddPage();

// ========================================
// HEADER
// ========================================

$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 8, 'LAPORAN DATA KRITERIA KELUARGA MISKIN', 0, 1, 'C');

$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetTextColor(0, 128, 0);
$pdf->Cell(0, 6, 'SISTEM PENDUKUNG KEPUTUSAN PENYALURAN BANTUAN SOSIAL', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 11);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 5, 'Kantor Wali Nagari Ampang Gadang', 0, 1, 'C');

$pdf->SetFont('helvetica', '', 9);
$pdf->SetTextColor(80, 80, 80);
$pdf->Cell(0, 5, 'Metode WASPAS (Weighted Aggregated Sum Product Assessment)', 0, 1, 'C');
$pdf->Cell(0, 5, 'Tanggal Cetak: ' . date('d/m/Y H:i') . ' WIB', 0, 1, 'C');

$pdf->Ln(2);
$pdf->SetLineWidth(0.5);
$pdf->SetDrawColor(0, 128, 0);
$pdf->Line(10, $pdf->GetY(), 287, $pdf->GetY());
$pdf->Ln(3);

// ========================================
// TABLE CONFIGURATION
// ========================================

$colWidths = [
    'no' => 8, 'rank' => 10, 'nama' => 35, 'nik' => 25, 
    'alamat' => 30, 'norek' => 20, 'bank' => 15, 'ket' => 15,
    'll' => 8, 'jl' => 8, 'jd' => 8, 'fb' => 8, 'sp' => 8, 
    'sa' => 8, 'bb' => 8, 'kp' => 8, 'pa' => 8, 'fm' => 8, 
    'kb' => 8, 'ph' => 8, 'pd' => 8, 'as' => 8,
];

$kriteriaSingkat = [
    'C1' => 'LL', 'C2' => 'JL', 'C3' => 'JD', 'C4' => 'FB',
    'C5' => 'SP', 'C6' => 'SA', 'C7' => 'BB', 'C8' => 'KP',
    'C9' => 'PA', 'C10' => 'FM', 'C11' => 'KB', 'C12' => 'PH',
    'C13' => 'PD', 'C14' => 'AS'
];

$costKriteria = ['C1', 'C2', 'C3', 'C4', 'C5', 'C6', 'C7', 'C12', 'C13'];
$benefitKriteria = ['C8', 'C9', 'C10', 'C11', 'C14'];

// ========================================
// TABLE HEADER
// ========================================

$pdf->SetFont('helvetica', 'B', 7);
$pdf->SetFillColor(220, 220, 220);
$pdf->SetTextColor(0, 0, 0);

$pdf->Cell($colWidths['no'], 8, 'No', 1, 0, 'C', true);
$pdf->Cell($colWidths['rank'], 8, 'Rank', 1, 0, 'C', true);
$pdf->Cell($colWidths['nama'], 8, 'Nama', 1, 0, 'C', true);
$pdf->Cell($colWidths['nik'], 8, 'NIK', 1, 0, 'C', true);
$pdf->Cell($colWidths['alamat'], 8, 'Alamat', 1, 0, 'C', true);
$pdf->Cell($colWidths['norek'], 8, 'No Rek', 1, 0, 'C', true);
$pdf->Cell($colWidths['bank'], 8, 'Bank', 1, 0, 'C', true);
$pdf->Cell($colWidths['ket'], 8, 'Ket', 1, 0, 'C', true);

$pdf->SetFillColor(254, 202, 202);
$pdf->SetTextColor(127, 29, 29);
foreach ($costKriteria as $kode) {
    $short = $kriteriaSingkat[$kode];
    $pdf->Cell($colWidths[strtolower($short)], 8, $short, 1, 0, 'C', true);
}

$pdf->SetFillColor(187, 247, 208);
$pdf->SetTextColor(21, 128, 61);
foreach ($benefitKriteria as $kode) {
    $short = $kriteriaSingkat[$kode];
    $pdf->Cell($colWidths[strtolower($short)], 8, $short, 1, 0, 'C', true);
}

$pdf->Ln();

// ========================================
// TABLE BODY
// ========================================

$pdf->SetFont('helvetica', '', 6);
$pdf->SetTextColor(0, 0, 0);

$no = 1;
foreach ($results as $row) {
    if ($row['rank'] <= 3) {
        $keterangan = 'Prioritas Utama';
        $fillColor = [255, 243, 205];
    } elseif ($row['rank'] <= 10) {
        $keterangan = 'Prioritas';
        $fillColor = [255, 255, 255];
    } elseif ($row['qi_waspas'] >= 0.5) {
        $keterangan = 'Layak';
        $fillColor = [255, 255, 255];
    } else {
        $keterangan = 'Pertimbangan';
        $fillColor = [255, 255, 255];
    }
    
    $pdf->SetFillColor($fillColor[0], $fillColor[1], $fillColor[2]);
    
    $penerimaData = $db->fetchOne(
        "SELECT alamat, no_rekening, nama_bank FROM penerima_bantuan WHERE id = ?",
        [$row['penerima_id']]
    );
    
    $pdf->Cell($colWidths['no'], 6, $no++, 1, 0, 'C', true);
    $pdf->Cell($colWidths['rank'], 6, $row['rank'], 1, 0, 'C', true);
    $pdf->Cell($colWidths['nama'], 6, substr($row['nama'], 0, 25), 1, 0, 'L', true);
    $pdf->Cell($colWidths['nik'], 6, $row['nik'], 1, 0, 'C', true);
    $pdf->Cell($colWidths['alamat'], 6, substr($penerimaData['alamat'] ?? '-', 0, 20), 1, 0, 'L', true);
    $pdf->Cell($colWidths['norek'], 6, $penerimaData['no_rekening'] ?? '-', 1, 0, 'C', true);
    $pdf->Cell($colWidths['bank'], 6, substr($penerimaData['nama_bank'] ?? '-', 0, 10), 1, 0, 'C', true);
    $pdf->Cell($colWidths['ket'], 6, $keterangan, 1, 0, 'C', true);
    
    $kriteriaData = $db->fetchOne(
        "SELECT c1, c2, c3, c4, c5, c6, c7, c8, c9, c10, c11, c12, c13, c14 
         FROM data_kriteria WHERE penerima_id = ?",
        [$row['penerima_id']]
    );
    
    foreach ($costKriteria as $kode) {
        $col = strtolower($kode);
        $nilai = number_format(floatval($kriteriaData[$col] ?? 0), 2);
        $short = $kriteriaSingkat[$kode];
        $pdf->Cell($colWidths[strtolower($short)], 6, $nilai, 1, 0, 'C', true);
    }
    
    foreach ($benefitKriteria as $kode) {
        $col = strtolower($kode);
        $nilai = number_format(floatval($kriteriaData[$col] ?? 0), 2);
        $short = $kriteriaSingkat[$kode];
        $pdf->Cell($colWidths[strtolower($short)], 6, $nilai, 1, 0, 'C', true);
    }
    
    $pdf->Ln();
}

// ========================================
// LEGEND
// ========================================

$pdf->Ln(3);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 5, 'KETERANGAN:', 0, 1, 'L');

$pdf->SetFont('helvetica', '', 7);
$pdf->SetTextColor(60, 60, 60);

$legends = [
    '• LL=Luas Lantai, JL=Jenis Lantai, JD=Jenis Dinding, FB=Fasilitas Buang Air, SP=Sumber Penerangan, SA=Sumber Air, BB=Bahan Bakar',
    '• KP=Konsumsi Protein, PA=Pakaian, FM=Frekuensi Makan, KB=Kemampuan Berobat, PH=Penghasilan, PD=Pendidikan KK, AS=Aset',
    '• COST = Kriteria biaya (nilai tinggi = prioritas) | BENEFIT = Kriteria keuntungan (nilai tinggi = baik)',
    '• Prioritas Utama: Rank 1-3 | Prioritas: Rank 4-10 | Layak: Qi≥0.5 | Pertimbangan: Evaluasi lebih lanjut',
];

foreach ($legends as $legend) {
    $pdf->Cell(0, 4, $legend, 0, 1, 'L');
}

$pdf->Ln(2);
$pdf->SetFont('helvetica', 'I', 7);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 4, 'Dicetak: ' . date('d/m/Y H:i:s') . ' | Sistem Bantuan Sosial - Kantor Wali Nagari Ampang Gadang', 0, 1, 'C');

// ========================================
// OUTPUT
// ========================================

$filename = 'Laporan_Kriteria_' . date('Ymd_His') . '.pdf';
$pdf->Output($filename, 'I');
exit;
?>
```

---

## ✅ VERIFICATION CHECKLIST

### Layout & Design
- [ ] PDF Landscape orientation (A4)
- [ ] All columns visible and proportional
- [ ] No text cut-off or truncation
- [ ] Readable font size (6-9pt)
- [ ] Proper margins (10mm all sides)

### Table Structure
- [ ] Header with COST (red) & BENEFIT (green) colors
- [ ] Rank column present
- [ ] Keterangan column present
- [ ] 14 kriteria columns (LL, JL, JD... AS)
- [ ] All 22 columns fit in one page width

### Data Display
- [ ] Top 3 highlighted (yellow background)
- [ ] Keterangan correct (Prioritas Utama, dst)
- [ ] Values displayed as decimal (0.33, 0.50, 1.00)
- [ ] NIK, Alamat, No Rek displayed correctly

### Content
- [ ] Header with title, subtitle, organization
- [ ] Metadata (method, print date)
- [ ] Legend explaining abbreviations
- [ ] Footer with timestamp & organization

### Functionality
- [ ] PDF generates without errors
- [ ] Opens in browser (Inline view)
- [ ] Downloadable
- [ ] Multiple pages handled correctly (if data > 1 page)
- [ ] Filter by IDs works (cetak terpilih)

---

## 🚨 TROUBLESHOOTING

### Issue 1: "TCPDF not found"
**Solution:**
```bash
# Install via Composer
composer require tecnickcom/tcpdf

# Or download manually
# https://github.com/tecnickcom/TCPDF
# Extract to vendor/tecnickcom/tcpdf/
```

Then update require path:
```php
require_once __DIR__ . '/../vendor/tecnickcom/tcpdf/tcpdf.php';
```

### Issue 2: Columns still cut-off
**Solution:**
- Reduce column widths further
- Increase page size to A3
- Reduce font size to 5pt
- Remove less important columns (e.g., Bank)

### Issue 3: Data not showing
**Solution:**
```php
// Add debug before PDF output
echo '<pre>';
print_r($results);
print_r($kriteriaData);
exit;
```

### Issue 4: Colors not showing
**Solution:**
```php
// Ensure RGB values correct
$pdf->SetFillColor(254, 202, 202); // Red
$pdf->SetFillColor(187, 247, 208); // Green

// And cell has fill parameter true
$pdf->Cell($width, $height, $text, 1, 0, 'C', true); // true = fill
```

### Issue 5: Multi-page issues
**Solution:**
```php
// Auto page break already set
$pdf->SetAutoPageBreak(TRUE, 10);

// For repeating header on new pages, use:
$pdf->setHeaderCallback(function($pdf) {
    // Draw header on each page
});
```

---

## 💡 BONUS TIPS

### Tip 1: Dynamic Column Widths
If you want to auto-adjust based on content:
```php
// Calculate remaining width after fixed columns
$fixedWidth = 158; // No, Rank, Nama, NIK, etc
$remainingWidth = 270 - $fixedWidth; // 112mm
$perKriteria = $remainingWidth / 14; // 8mm per kriteria ✓
```

### Tip 2: Better Text Truncation
```php
// Use MultiCell for auto-wrap
$pdf->MultiCell($width, $height, $text, 1, 'L', true, 0);

// Or truncate with ellipsis
$displayText = (strlen($text) > 25) ? substr($text, 0, 22) . '...' : $text;
```

### Tip 3: Add Page Numbers
```php
$pdf->setPrintFooter(true);
$pdf->setFooterCallback(function($pdf) {
    $pdf->SetY(-15);
    $pdf->SetFont('helvetica', 'I', 8);
    $pdf->Cell(0, 10, 'Halaman '.$pdf->getAliasNumPage().' dari '.$pdf->getAliasNbPages(), 0, 0, 'C');
});
```

### Tip 4: Optimize for Speed
```php
// Disable image cache for faster generation
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Reduce memory usage
ini_set('memory_limit', '256M');
```

---

**END OF FIX PDF PROMPT**

**Summary:** Complete redesign PDF dengan proper column widths, colors, legend, dan layout yang match dengan web view! 🎉
