<?php
session_start();
if ($_SESSION == NULL) {
    $_SESSION['error'] = 'Debes iniciar sesión para usar el sistema';
    header('location:/nova/');
}
?>
<?php

include '../../librerias/tcpdf/tcpdf.php';
require '../../conexion.php';


//INICIO CONSULTAS EN BASE DE DATOS
$idpedidocompra = $_REQUEST['vidpedidocompra'];; // OBTENER EL ID

$sqlcabecera = "SELECT * FROM v_pedidoscompras WHERE id_pedidocompra = $idpedidocompra";
$sqldetalle = "SELECT * FROM v_pedidoscompras_det WHERE id_pedidocompra = $idpedidocompra";

$consultacabecera = consultas::get_datos($sqlcabecera);
$consultardetalles = consultas::get_datos($sqldetalle);

//FIN CONSULTAS EN BASE DE DATOS

//PDF
$pdf = new TCPDF('P', 'mm', 'FOLIO');
$pdf->SetMargins(15, 15, 18);
$pdf->SetTitle('Pedido de Compra');
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
$pdf->MultiCell(71, 20, 'PEDIDO DE COMPRA', 0, 'C', FALSE, 1, 136, 12, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(71, 20, '(USO INTERNO)', 0, 'C', FALSE, 1, 136, 17, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('aefurat', 'B', 17);
$pdf->MultiCell(71, 20, $consultacabecera[0]['nro_pedido_larga'], 0, 'C', FALSE, 1, 136, 22, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);


//DETALLE
$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'FECHA DE EMISIÓN: ', 0, 'L', FALSE, 1, 21, 33, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(50, 20, $consultacabecera[0]['fecha_larga'], 0, 'L', FALSE, 1, 52, 33, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 12);

$pdf->MultiCell(400, 20, 'Solicita la compra de las siguientes mercaderias ', 0, 'L', FALSE, 1, 21, 40, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('Times', 'B', 8);

$pdf->RoundedRect(20, 47, 186, 91, 1, '1111', '', $style6, array(200, 200, 200));
$pdf->Line(50, 47, 50, 138, $style6);
$pdf->Line(128, 47, 128, 138, $style6);


$pdf->Line(20, 51, 206, 51, $style6);


$pdf->SetFont('Times', 'B', 9);
$pdf->MultiCell(30, 1, 'CANTIDAD', 0, 'C', FALSE, 1, 20, 47, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(78, 1, 'UNIDAD DE MEDIDA', 0, 'C', FALSE, 1, 50, 47, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(78, 1, 'MATERIA PRIMA', 0, 'C', FALSE, 1, 128, 47, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);


$pdf->SetXY(20, 51);
foreach ($consultardetalles as $det) {
    $pdf->SetFont('Times', '', 9);
    $pdf->Cell(30, 1, number_format(($det['cantidad']), 0, ',', '.'), 0, 0, 'C', 0);
    $pdf->Cell(78, 1, $det['unidadmedida'], 0, 0, 'C', 0);
    $pdf->Cell(78, 1, $det['materiaprima'], 0, 0, 'C', 0);

    $pdf->ln();
    $pdf->SetX(20);
}

$pdf->SetFont('Times', 'B', 12);
$pdf->MultiCell(30, 1, 'Observacion', 0, 'C', FALSE, 1, 20, 140, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->RoundedRect(20, 145, 186, 30, 1, '1111', '', $style6, array(200, 200, 200));
$pdf->SetFont('Times', '', 9);
$pdf->MultiCell(187, 30, $consultacabecera[0]['observacion'], 0, 'L', FALSE, 1, 20, 145, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->RoundedRect(20, 178, 92, 50, 1, '1111', '', $style6, array(200, 200, 200));
$pdf->RoundedRect(114, 178, 92, 50, 1, '1111', '', $style6, array(200, 200, 200));

$pdf->SetFont('Times', 'B', 12);
$pdf->MultiCell(92, 1, '  SOLICITADO POR', 0, 'L', FALSE, 1, 20, 180, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(92, 1, '  APROBADO POR', 0, 'L', FALSE, 1, 114, 180, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);


$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(92, 1, '----------------------------------------------------', 0, 'C', FALSE, 1, 20, 208, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(92, 1, 'Firma del Personal de Compra', 0, 'C', FALSE, 1, 20, 212, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(92, 1, 'Aclaracion:', 0, 'L', FALSE, 1, 20, 221, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(92, 1, '----------------------------------------------------', 0, 'C', FALSE, 1, 114, 208, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(92, 1, 'Firma del Gerente de Compra', 0, 'C', FALSE, 1, 114, 212, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(92, 1, 'Aclaracion:', 0, 'L', FALSE, 1, 114, 221, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);







//SALIDA AL NAVEGADOR
$pdf->Output('PC-'.$consultacabecera[0]['nro_pedido_larga'].'.pdf', 'I');
?>