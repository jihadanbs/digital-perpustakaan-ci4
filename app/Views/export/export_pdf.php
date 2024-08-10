<?php
require_once('vendor/autoload.php'); // Jika menggunakan Composer, pastikan path-nya benar

use TCPDF;

// Buat instance TCPDF
$pdf = new TCPDF();

// Set informasi dokumen
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nama Anda');
$pdf->SetTitle('Judul PDF');
$pdf->SetSubject('Subjek');

// Set margin
$pdf->SetMargins(10, 10, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

// Set font
$pdf->SetFont('dejavusans', '', 12);

// Add a page
$pdf->AddPage();

// Menambahkan gambar ke PDF
$imageFile = site_url(['file_cover_buku']);
$pdf->Image($imageFile, 10, 20, 60, 60, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

// Menambahkan teks ke PDF
$pdf->SetY(100);
$pdf->Cell(0, 10, 'Teks di bawah gambar', 0, 1, 'C');

// Output PDF
$pdf->Output('example.pdf', 'I');
