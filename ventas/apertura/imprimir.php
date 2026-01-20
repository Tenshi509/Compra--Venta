<?php
session_start();
if ($_SESSION == NULL) {
    $_SESSION['error'] = 'Debes iniciar sesión para usar el sistema';
    header('location:/graficanissei/');
}
?>
<?php

include '../../librerias/tcpdf/tcpdf.php';
require '../../conexion.php';


//INICIO CONSULTAS EN BASE DE DATOS
$idaperturacierre = $_REQUEST['vidaperturacierre'];; // OBTENER EL ID

$sqlcabecera = "SELECT * FROM v_aperturacierre WHERE id_aperturacierre = $idaperturacierre";

$consultacabecera = consultas::get_datos($sqlcabecera);


//FIN CONSULTAS EN BASE DE DATOS

//PDF
$pdf = new TCPDF('P', 'mm', 'FOLIO');
$pdf->SetMargins(15, 15, 18);
$pdf->SetTitle('Apertura de Caja');
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

$pdf->AddPage();
$style6 = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0));

//LINEAS Y CUAROS
$pdf->RoundedRect(20, 10, 110, 20, 1, '1111', '', $style6, array(200, 200, 200)); // MEMBRETE
$pdf->RoundedRect(135, 10, 71, 20, 1, '1111', '', $style6, array(200, 200, 200)); // DOCUMENTO
$pdf->RoundedRect(20, 40, 186, 35, 1, '1111', '', $style6, array(200, 200, 200)); // FECHA



// CABECERA LOGO
$pdf->Ln(1);
$pdf->SetFont('Times', 'B', 12);
$pdf->MultiCell(30, 20, 'GRAFICA NISSEI', 0, 'C', FALSE, 1, 21, 14, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 12);
$pdf->MultiCell(55, 30, 'Servicios de Impresiónes', 0, 'R', FALSE, 1, 70, 13, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Courier', '', 8);
$pdf->MultiCell(55, 30, 'Ytororó c/ Fulgencio R Moreno', 0, 'R', FALSE, 1, 72, 19, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(55, 30, 'Cel: (0981) 123 456', 0, 'R', FALSE, 1, 72, 22, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(55, 30, 'Fernando de la Mora - Paraguay', 0, 'R', FALSE, 1, 72, 25, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

// CABECERA DOCUMENTO
$pdf->SetFont('Times', 'B', 13);
$pdf->MultiCell(71, 20, 'APERTURA DE CAJA', 0, 'C', FALSE, 1, 136, 12, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(71, 20, '(USO INTERNO)', 0, 'C', FALSE, 1, 136, 17, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('aefurat', 'B', 17);
$pdf->MultiCell(71, 20, $consultacabecera[0]['nro_aperturacierre_larga'], 0, 'C', FALSE, 1, 136, 22, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);


//DETALLE

$pdf->SetFont('Times', 'B', 12);

$pdf->MultiCell(185, 1, 'RESUMEN DE LA APERTURA DE CAJA ', 0, 'C', FALSE, 1, 21, 33, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'FECHA DE EMISIÓN: ', 0, 'L', FALSE, 1, 21, 42, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(50, 20, $consultacabecera[0]['fecha_apertura_larga'], 0, 'L', FALSE, 1, 52, 42, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'HORA: ', 0, 'L', FALSE, 1, 150, 42, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(50, 20, $consultacabecera[0]['hora_apertura'], 0, 'L', FALSE, 1, 161, 42, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);



$pdf->Line(20, 47, 206, 47, $style6);
$pdf->Line(20, 54, 206, 54, $style6);
$pdf->Line(20, 61, 206, 61, $style6);
$pdf->Line(20, 68, 206, 68, $style6);

$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'CAJERO: ', 0, 'L', FALSE, 1, 21, 49, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(250, 20, $consultacabecera[0]['nombre'] . ' ' . $consultacabecera[0]['apellido'], 0, 'L', FALSE, 1, 52, 49, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'CAJA: ', 0, 'L', FALSE, 1, 21, 56, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(50, 20, $consultacabecera[0]['caja'], 0, 'L', FALSE, 1, 52, 56, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'MONTO INICIAL: ', 0, 'L', FALSE, 1, 21, 63, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(50, 20, number_format(($consultacabecera[0]['monto_inicial']), 0, ',', '.'), 0, 'L', FALSE, 1, 52, 63, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'ESTADO: ', 0, 'L', FALSE, 1, 21, 70, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(50, 20, $consultacabecera[0]['estado'], 0, 'L', FALSE, 1, 52, 70, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);













$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(92, 1, '----------------------------------------------------', 0, 'C', FALSE, 1, 20, 218, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(92, 1, 'Firma del Encargado de Venta', 0, 'C', FALSE, 1, 20, 222, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->MultiCell(92, 1, '----------------------------------------------------', 0, 'C', FALSE, 1, 114, 218, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(92, 1, 'Aclaración de Firma', 0, 'C', FALSE, 1, 114, 222, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);





//SALIDA AL NAVEGADOR
$pdf->Output('AS-'.$consultacabecera[0]['nro_aperturacierre_larga'].'.pdf', 'I');
?>