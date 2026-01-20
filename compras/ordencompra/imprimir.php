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
$idordencompra = $_REQUEST['vidordencompra'];; // OBTENER EL ID

$sqlcabecera = "SELECT * FROM v_ordencompra WHERE id_ordencompra = $idordencompra";
$sqldetalle = "SELECT * FROM v_ordencompra_det WHERE id_ordencompra = $idordencompra";

$consultacabecera = consultas::get_datos($sqlcabecera);
$consultardetalles = consultas::get_datos($sqldetalle);


$id_proveedor = $consultacabecera[0]['id_proveedor']; 
$sqlproveedor = "SELECT * FROM v_proveedor WHERE id_proveedor = $id_proveedor";
$consultarproveedor = consultas::get_datos($sqlproveedor);

$total= consultas::get_datos("SELECT sp_numero_letras((SELECT subtotal FROM v_ordencompra WHERE id_ordencompra = $idordencompra))");
$numeroletra = $total[0]['sp_numero_letras']; 

//FIN CONSULTAS EN BASE DE DATOS

//PDF
$pdf = new TCPDF('P', 'mm', 'FOLIO');
$pdf->SetMargins(15, 15, 18);
$pdf->SetTitle('Orden de Compra');
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

$pdf->AddPage();
$style6 = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0));

//LINEAS Y CUAROS
$pdf->RoundedRect(20, 10, 110, 20, 1, '1111', '', $style6, array(200, 200, 200)); // MEMBRETE
$pdf->RoundedRect(135, 10, 71, 20, 1, '1111', '', $style6, array(200, 200, 200)); // DOCUMENTO
$pdf->RoundedRect(20, 31, 186, 21, 1, '1111', '', $style6, array(200, 200, 200)); // FECHA



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
$pdf->MultiCell(71, 20, 'ORDEN DE COMPRA', 0, 'C', FALSE, 1, 136, 12, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(71, 20, '', 0, 'C', FALSE, 1, 136, 17, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('aefurat', 'B', 17);
$pdf->MultiCell(71, 20, $consultacabecera[0]['nro_orden_larga'], 0, 'C', FALSE, 1, 136, 22, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);


//DETALLE
$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'FECHA: ', 0, 'L', FALSE, 1, 21, 33, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(50, 20, $consultacabecera[0]['fecha_larga'], 0, 'L', FALSE, 1, 33, 33, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'Proveedor: ', 0, 'L', FALSE, 1, 21, 40, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(50, 20, $consultacabecera[0]['razon_social'], 0, 'L', FALSE, 1, 36, 40, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'RUC: ', 0, 'L', FALSE, 1, 150, 40, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(50, 20, $consultacabecera[0]['nro_ruc'], 0, 'L', FALSE, 1, 158, 40, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'Telefono: ', 0, 'L', FALSE, 1, 150, 47, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(50, 20, $consultarproveedor[0]['telefono'], 0, 'L', FALSE, 1, 162, 47, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);



$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'Dirección: ', 0, 'L', FALSE, 1, 21, 47, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(110, 1, $consultarproveedor[0]['direccion'], 0, 'L', FALSE, 1, 35, 47, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);



$pdf->SetFont('Times', 'B', 12);

$pdf->MultiCell(186, 1, 'Por este medio solicito las siguientes mercaderias', 0, 'L', FALSE, 1, 20, 55, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('Times', 'B', 8);

$pdf->RoundedRect(20, 62, 186, 91, 1, '1111', '', $style6, array(200, 200, 200));
$pdf->Line(40, 62, 40, 153, $style6);
$pdf->Line(116, 62, 116, 153, $style6);
$pdf->Line(146, 62, 146, 153, $style6);
$pdf->Line(176, 62, 176, 153, $style6);



$pdf->Line(20, 66, 206, 66, $style6);


$pdf->SetFont('Times', 'B', 9);
$pdf->MultiCell(20, 1, 'CANTIDAD', 0, 'C', FALSE, 1, 20, 62, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(76, 1, 'MATERIA PRIMA', 0, 'C', FALSE, 1, 40, 62, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 8.5);
$pdf->MultiCell(30, 1, 'PRECIO UNITARIO', 0, 'C', FALSE, 1, 116, 62, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 9);
$pdf->MultiCell(30, 1, 'TOTAL', 0, 'C', FALSE, 1, 146, 62, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30, 1, 'IVA', 0, 'C', FALSE, 1, 176, 62, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);



$pdf->SetXY(20, 67);
foreach ($consultardetalles as $det) {
    $pdf->SetFont('Times', '', 9);
    $pdf->Cell(20, 1, number_format(($det['cantidad']), 0, ',', '.'), 0, 0, 'C', 0);
    $pdf->Cell(76, 1, $det['materiaprima'], 0, 0, 'C', 0);
    $pdf->Cell(30, 1, number_format(($det['precio_unitario']), 0, ',', '.'), 0, 0, 'C', 0);
    $pdf->Cell(30, 1, number_format(($det['subtotal']), 0, ',', '.'), 0, 0, 'C', 0);
    $pdf->Cell(30, 1, $det['tipoimpuesto'], 0, 0, 'C', 0);



    $pdf->ln();
    $pdf->SetX(20);
}


$pdf->RoundedRect(20, 155, 186, 7, 1, '1111', '', $style6, array(200, 200, 200)); // TOTAL

$pdf->SetFont('Times', 'B', 12);
$pdf->MultiCell(45, 1, 'TOTAL (En Letras): ', 0, 'C', FALSE, 1, 20, 156, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(110, 1, $numeroletra, 0, 'L', FALSE, 1, 61, 157, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->Line(171, 162, 171, 155, $style6);
$pdf->SetFont('Times', 'B', 9);
$pdf->MultiCell(35, 1, number_format(($consultacabecera[0]['subtotal']), 0, ',', '.'), 0, 'C', FALSE, 1, 171, 157, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);




$pdf->SetFont('Times', 'B', 12);
$pdf->MultiCell(30, 1, 'Observacion', 0, 'C', FALSE, 1, 20, 163, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->RoundedRect(20, 169, 186, 30, 1, '1111', '', $style6, array(200, 200, 200));
$pdf->SetFont('Times', '', 9);
$pdf->MultiCell(187, 30, $consultacabecera[0]['observacion'], 0, 'L', FALSE, 1, 20, 170, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);





$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(92, 1, '----------------------------------------------------', 0, 'C', FALSE, 1, 20, 238, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(92, 1, 'Firma del Gerente de Compra', 0, 'C', FALSE, 1, 20, 242, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->MultiCell(92, 1, '----------------------------------------------------', 0, 'C', FALSE, 1, 114, 238, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(92, 1, 'Aclaración de Firma', 0, 'C', FALSE, 1, 114, 242, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);





//SALIDA AL NAVEGADOR
$pdf->Output('PC-'.$consultacabecera[0]['nro_presupuesto'].'.pdf', 'I');
?>