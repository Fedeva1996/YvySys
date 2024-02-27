<?php
session_start();
require_once('../tcpdf/tcpdf.php'); //Llamando a la Libreria TCPDF
require_once('../db_connect.php'); //Llamando a la conexión para BD
date_default_timezone_set('America/Asuncion');

ob_end_clean(); //limpiar la memoria

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF
{

    //Page header
    public function Header()
    {
        // Logo
        $image_file = '../imagenes/logo.png';
        $this->Image($image_file, 65, 10, 65, '', 'PNG', '', 'C', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 16);
        $this->Ln(30); //Salto de Linea
        // Title
        $this->Cell(0, 15, "Instituto Tecnico Superior De Estudios Culturales Yvy Marãe'y", 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

//Iniciando un nuevo pdf
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, 'mm', 'Letter', true, 'ISO-8859-1', false, true);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//Informacion del PDF
$pdf->SetCreator('Admin');
$pdf->SetAuthor('Administrador');
$pdf->SetTitle('Ficha Academica');

//Agregando la primera página
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 10); //Tipo de fuente y tamaño de letra
$pdf->SetXY(20, 10);

$pdf->SetFont('helvetica', '', 10);

$pdf->Cell(40, 26, '', 0, 0, 'C');
$pdf->SetTextColor(34, 68, 136);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Ln(30); //Salto de Linea
$pdf->Cell(0, 6, 'Ficha academica', 0, 0, 'C');

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('helvetica', '', 11);

$inscripcion = $_POST['inscripcion'];

$sql = "INSERT INTO ficha_academica(
            inscripcion_cab_id)
            VALUES ($inscripcion)";

//SQL para consultas
$sqlHead = "SELECT * FROM ficha_academica_v WHERE inscripcion_cab_id = $inscripcion";

$sqlAno = "SELECT DISTINCT ano FROM pdf_ficha_academica_v
        WHERE inscripcion_cab_id = $inscripcion";

$sqlBody = "SELECT * FROM pdf_ficha_academica_v
        WHERE inscripcion_cab_id = $inscripcion";

$resultadosHead = pg_query($conn, $sqlHead);
$resultadosAno = pg_query($conn, $sqlAno);
$resultadosBody = pg_query($conn, $sqlBody);

$filaHead = pg_fetch_assoc($resultadosHead);
// create some HTML content
$html = '<br><br>
<b>Alumno:</b> ' . $filaHead['nombre'] . ' ' . $filaHead['apellido'] . '<br>
<b>Curso:</b> ' . $filaHead['descri'] . '<br>
<b>Ci:</b> ' . $filaHead['ci'] . '<br>
<b>Fecha de consulta:</b> ' . $filaHead['fecha_f'] . '<br>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');


while ($fila = pg_fetch_assoc($resultadosAno)) {
    $pdf->SetFont('helvetica', 'B', 12); //La B es para letras en Negritas
    $pdf->Cell(0, 6, $fila['ano'] . '° Año', 0, 1, 'C');
    //Almando la cabecera de la Tabla
    $pdf->SetFillColor(232, 232, 232);
    $pdf->SetFont('helvetica', 'B', 12); //La B es para letras en Negritas
    $pdf->Cell(10, 6, 'ID', 1, 0, 'C', 1);
    $pdf->Cell(75, 6, 'Modulo', 1, 0, 'C', 1);
    $pdf->Cell(25, 6, 'Procesos', 1, 0, 'C', 1);
    $pdf->Cell(25, 6, 'Examenes', 1, 0, 'C', 1);
    $pdf->Cell(15, 6, 'Total', 1, 0, 'C', 1);
    $pdf->Cell(25, 6, 'Calificacion', 1, 1, 'C', 1);
    while ($fila = pg_fetch_assoc($resultadosBody)) {
        $pdf->SetFont('helvetica', '', 11); //La B es para letras en Negritas
        $pdf->Cell(10, 6, is_null($fila['id_calificacion_det']) ? '-' : $fila['id_calificacion_det'], 1, 0, 'C');
        $pdf->Cell(75, 6, $fila['descri'], 1, 0, 'L');
        $pdf->Cell(25, 6, is_null($fila['procesos']) ? '-' : $fila['procesos'], 1, 0, 'C');
        $pdf->Cell(25, 6, is_null($fila['examenes']) ? '-' : $fila['examenes'], 1, 0, 'C');
        $pdf->Cell(15, 6, is_null($fila['total_hecho']) ? '-' : $fila['total_hecho'], 1, 0, 'C');
        $pdf->Cell(25, 6, is_null($fila['calificacion']) ? '-' : $fila['calificacion'], 1, 0, 'C');
    }
}


//$pdf->AddPage(); //Agregar nueva Pagina

$pdf->Output('ficha_academica_' . date('d_m_y') . '.pdf', 'I');
// Output funcion que recibe 2 parameros, el nombre del archivo, ver archivo o descargar,
// La D es para Forzar una descarga
