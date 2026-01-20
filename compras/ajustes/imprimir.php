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
$idajustestock = $_REQUEST['vidajustestock'];; // OBTENER EL ID

$sqlcabecera = "SELECT * FROM v_ajustestock WHERE id_ajustestock = $idajustestock";
$sqldetalle = "SELECT * FROM v_ajustestock_det WHERE id_ajustestock = $idajustestock";

$consultacabecera = consultas::get_datos($sqlcabecera);
$consultardetalles = consultas::get_datos($sqldetalle);


//FIN CONSULTAS EN BASE DE DATOS

//PDF
$pdf = new TCPDF('P', 'mm', 'FOLIO');
$pdf->SetMargins(15, 15, 18);
$pdf->SetTitle('Ajustes de Stock');
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

$pdf->AddPage();
$style6 = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0));

//LINEAS Y CUAROS
$pdf->RoundedRect(20, 10, 110, 20, 1, '1111', '', $style6, array(200, 200, 200)); // MEMBRETE
$pdf->RoundedRect(135, 10, 71, 20, 1, '1111', '', $style6, array(200, 200, 200)); // DOCUMENTO
$pdf->RoundedRect(20, 31, 186, 7, 1, '1111', '', $style6, array(200, 200, 200)); // FECHA



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
$pdf->MultiCell(71, 20, 'AJUSTE DE STOCK', 0, 'C', FALSE, 1, 136, 12, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(71, 20, '(USO INTERNO)', 0, 'C', FALSE, 1, 136, 17, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('aefurat', 'B', 17);
$pdf->MultiCell(71, 20, $consultacabecera[0]['nro_ajuste_larga'], 0, 'C', FALSE, 1, 136, 22, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);


//DETALLE
$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'FECHA DE EMISIÓN: ', 0, 'L', FALSE, 1, 21, 33, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(50, 20, $consultacabecera[0]['fecha_larga'], 0, 'L', FALSE, 1, 52, 33, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 8);


$pdf->RoundedRect(20, 40, 186, 91, 1, '1111', '', $style6, array(200, 200, 200));
$pdf->Line(85, 40, 85, 131, $style6);
$pdf->Line(106, 40, 106, 131, $style6);
$pdf->Line(127, 40, 127, 131, $style6);



$pdf->Line(20, 48, 206, 48, $style6);


$pdf->SetFont('Times', 'B', 9);
$pdf->MultiCell(65, 1, 'MATERIA PRIMA', 0, 'C', FALSE, 1, 20, 42, TRUE, 1, FALSE, TRUE, 0, 0, FALSE);
$pdf->MultiCell(21, 1, 'CANTIDAD ANTERIOR', 0, 'C', FALSE, 1, 85, 40, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(21, 1, 'CANTIDAD AJUSTADA', 0, 'C', FALSE, 1, 106, 40, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(79, 1, 'MOTIVO', 0, 'C', FALSE, 1, 127, 42, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);



$pdf->SetXY(20, 49);
foreach ($consultardetalles as $det) {
    $pdf->SetFont('Times', '', 9);
    $pdf->Cell(65, 1, $det['materiaprima'], 0, 0, 'C', 0);
    $pdf->Cell(21, 1, number_format(($det['cant_anterior']), 0, ',', '.'), 0, 0, 'C', 0);
    $pdf->Cell(21, 1, number_format(($det['cant_actual']), 0, ',', '.'), 0, 0, 'C', 0);
    $pdf->Cell(79, 1, $det['motivoajuste'], 0, 0, 'C', 0);

    $pdf->ln();
    $pdf->SetX(20);
}

$pdf->SetFont('Times', 'B', 12);
$pdf->MultiCell(30, 1, 'Observacion', 0, 'C', FALSE, 1, 20, 131, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->RoundedRect(20, 137, 186, 30, 1, '1111', '', $style6, array(200, 200, 200));
$pdf->SetFont('Times', '', 9);
$pdf->MultiCell(187, 30, $consultacabecera[0]['observacion'], 0, 'L', FALSE, 1, 20, 137, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);






$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(92, 1, '----------------------------------------------------', 0, 'C', FALSE, 1, 20, 218, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(92, 1, 'Firma del Encargado de Compra', 0, 'C', FALSE, 1, 20, 222, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->MultiCell(92, 1, '----------------------------------------------------', 0, 'C', FALSE, 1, 114, 218, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(92, 1, 'Aclaración de Firma', 0, 'C', FALSE, 1, 114, 222, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);





//SALIDA AL NAVEGADOR
$pdf->Output('AS-'.$consultacabecera[0]['nro_ajuste_larga'].'.pdf', 'I');
?>