<?php
require('../../lib/fpdf186/fpdf.php');
include('../../db.php'); // Conexión a la base de datos

// Verificar si se recibió el folio por POST
if (!isset($_POST['folio']) || empty($_POST['folio'])) {
    echo "Error: Folio no recibido.";
    exit;
}

$folio = $_POST['folio'];

// Consultar los datos del folio desde la base de datos
$query = "
    SELECT c.Dia, c.Hora, 
           CONCAT('Parte: ', e.NParte, ' - Modelo: ', e.Modelo, ' - Marca: ', e.Marca, ' - Serie: ', e.NSerie) AS Equipo,
           emp.Nombre AS Empleado, 
           cli.Nombre AS Cliente,
           c.TituloContacto, 
           c.ServicioInteres, 
           empresa.NombreEmpresa AS Empresa
    FROM Citas c
    LEFT JOIN Equipos e ON c.EquipoId = e.Id
    LEFT JOIN Empleados emp ON c.EmpleadoId = emp.Id
    LEFT JOIN Contacto cli ON c.ContactoId = cli.Id
    LEFT JOIN Empresa empresa ON c.EmpresaId = empresa.Id
    LEFT JOIN Folio f ON c.FolioIdC = f.Id
    WHERE f.CodigoFolio = ?
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 's', $folio);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Verificar si se encontraron resultados
$data = mysqli_fetch_assoc($result);
if (!$data) {
    echo "Error: No se encontraron datos para el folio proporcionado.";
    exit;
}

// Crear el PDF
class PDF extends FPDF
{
    // Cabecera
    function Header()
    {
        // Agregar la imagen como fondo cubriendo toda la página
        $this->Image('../../uploads/hojamembretada_page-0001.jpg', 0, 0, 210, 297); // Imagen de fondo cubriendo todo el tamaño A4
        $this->Ln(10); // Espacio después de la imagen para los textos
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Mostrar datos en el PDF
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, "Folio: $folio", 0, 1, 'L');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, utf8_decode("Día: " . $data['Dia']), 0, 1, 'L');
$pdf->Cell(0, 10, utf8_decode("Hora: " . $data['Hora']), 0, 1, 'L');
$pdf->Cell(0, 10, utf8_decode("Equipo: " . $data['Equipo']), 0, 1, 'L');
$pdf->Cell(0, 10, utf8_decode("Empleado: " . $data['Empleado']), 0, 1, 'L');
$pdf->Cell(0, 10, utf8_decode("Cliente: " . $data['Cliente']), 0, 1, 'L');
$pdf->Cell(0, 10, utf8_decode("Título de Contacto: " . $data['TituloContacto']), 0, 1, 'L');
$pdf->Cell(0, 10, utf8_decode("Servicio de Interés: " . $data['ServicioInteres']), 0, 1, 'L');
$pdf->Cell(0, 10, utf8_decode("Empresa: " . $data['Empresa']), 0, 1, 'L');

// Salida del archivo
$pdf->Output('D', 'Folio_' . $folio . '.pdf');
?>
