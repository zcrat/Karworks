<?php

include("mc_table.php");

define('FPDF_FONTPATH', 'font/');

$pdf = new PDF_Mc_Table('L', 'mm', 'A4');
$pdf->AddPage();

$pdf->SetTextColor(0, 0, 0);
$pdf->SetDrawColor(80, 80, 80);
$pdf->SetLineWidth(0.2);

// 12 anchos para 12 columnas
$pdf->SetWidths([
    20, // Orden Servicio
    22, // Seguimiento
    28, // Taller
    20, // Tipo
    25, // Ubicación
    18, // Económico
    18, // Placa
    20, // Marca
    20, // Modelo
    23, // Entrada
    45, // Fallas
    18  // Estatus
]);

// Encabezados
$headers = [
    'Orden Servicio',
    'Seguimiento',
    'Taller',
    'Tipo',
    'Ubicación',
    'Económico',
    'Placa',
    'Marca',
    'Modelo',
    'Entrada',
    'Fallas',
    'Estatus'
];

// Color de fondo de los títulos
$pdf->SetFillColor(40, 90, 140);

// Texto blanco
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 7);

// El segundo parámetro activa el fondo
$pdf->Row(array_map('pdfText', $headers), true);

// Restaurar estilo para los datos
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', '', 7);

foreach ($elements as $element) {
    $pdf->Row(array_map('pdfText', [
        $element['OrdenServicio'] ?? '',
        $element['OrdenSeguimiento'] ?? '',
        $element['taller'] ?? '',
        $element['subcontrato'] ?? '',
        $element['Ubicacion'] ?? '',
        $element['Economico'] ?? '',
        $element['Placa'] ?? '',
        $element['Marca'] ?? '',
        $element['Modelo'] ?? '',
        $element['Entrada'] ?? '',
        $element['fallas'] ?? '',
        $element['estatus'] ?? $element['estaus'] ?? ''
    ]));
}

$pdf->Output('I', 'ordenes_servicio.pdf');
exit;

function pdfText($value)
{
    return iconv(
        'UTF-8',
        'windows-1252//TRANSLIT',
        (string) $value
    );
}