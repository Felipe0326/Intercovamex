<?php
require('../lib/fpdf186/fpdf.php');
include('../db.php'); // Conexión a la base de datos
// Verificar si el folio fue enviado
if (!isset($_POST['folio']) || empty($_POST['folio'])) {
    die('Error: No se proporcionó un folio para generar el PDF.');
}

// Obtener el folio desde el formulario
$folio = htmlspecialchars($_POST['folio']);

// Clase personalizada para el PDF
class PDF extends FPDF
{
    // Cabecera del documento
    function Header()
    {
        // Centrar el logo
        $this->Image('../uploads/logolargo2.jpeg', 80, 10, 50); // Ajusta el logo según tu ruta
        $this->Ln(30); // Espacio debajo del logo
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Crear PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Mostrar el folio
$pdf->Cell(0, 10, 'Folio:', 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $folio, 0, 1, 'L');

// Salida del archivo PDF
$pdf->Output('D', 'Folio_' . $folio . '.pdf');
