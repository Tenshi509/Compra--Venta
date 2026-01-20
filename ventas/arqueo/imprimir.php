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
$idarqueo = $_REQUEST['vidarqueo'];; // OBTENER EL ID

$sqlcabecera = "SELECT * FROM v_arqueo WHERE id_arqueo = $idarqueo";

$consultacabecera = consultas::get_datos($sqlcabecera);

$consultardetalles_cheque = consultas::get_datos("SELECT * FROM v_arqueo_det_cheques WHERE id_arqueo = $idarqueo");
$consultardetalles_tarjeta = consultas::get_datos("SELECT * FROM v_arqueo_det_tarjetas WHERE id_arqueo = $idarqueo");



//FIN CONSULTAS EN BASE DE DATOS

//PDF
$pdf = new TCPDF('P', 'mm', 'FOLIO');
$pdf->SetMargins(15, 15, 18);
$pdf->SetTitle('Arqueo de Caja');
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);

$pdf->AddPage();
$style6 = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0));

//LINEAS Y CUAROS
$pdf->RoundedRect(20, 10, 110, 20, 1, '1111', '', $style6, array(200, 200, 200)); // MEMBRETE
$pdf->RoundedRect(135, 10, 71, 20, 1, '1111', '', $style6, array(200, 200, 200)); // DOCUMENTO
$pdf->RoundedRect(20, 40, 186, 14, 1, '1111', '', $style6, array(200, 200, 200)); // FECHA



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
$pdf->MultiCell(71, 20, 'ARQUEO DE CAJA', 0, 'C', FALSE, 1, 136, 12, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(71, 20, '(USO INTERNO)', 0, 'C', FALSE, 1, 136, 17, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('aefurat', 'B', 17);
$pdf->MultiCell(71, 20, $consultacabecera[0]['nro_arqueo_larga'], 0, 'C', FALSE, 1, 136, 22, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);


//DETALLE

$pdf->SetFont('Times', 'B', 12);

$pdf->MultiCell(185, 1, 'RESUMEN DEL ARQUEO DE CAJA ', 0, 'C', FALSE, 1, 21, 33, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'FECHA DE EMISIÓN: ', 0, 'L', FALSE, 1, 21, 42, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(50, 20, $consultacabecera[0]['fecha_larga'], 0, 'L', FALSE, 1, 52, 42, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'HORA: ', 0, 'L', FALSE, 1, 150, 42, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(50, 20, $consultacabecera[0]['hora'], 0, 'L', FALSE, 1, 161, 42, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);



$pdf->Line(20, 47, 206, 47, $style6);


$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'APERTURA N°: ', 0, 'L', FALSE, 1, 21, 49, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(250, 20, $consultacabecera[0]['nro_aperturacierre_larga'] , 0, 'L', FALSE, 1, 52, 49, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(40, 20, 'CAJA: ', 0, 'L', FALSE, 1, 150, 49, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 8);
$pdf->MultiCell(50, 20, $consultacabecera[0]['caja'], 0, 'L', FALSE, 1, 161, 49, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->RoundedRect(20, 72, 92, 42, 1, '1111', '', $style6, array(200, 200, 200));//BILLETES
$pdf->Line(50.6, 72, 50.6, 108, $style6); //COL
$pdf->Line(81.2, 72, 81.2, 114, $style6); //COL
$pdf->Line(20, 78, 112, 78, $style6); //FILA
$pdf->Line(20, 84, 112, 84, $style6); //FILA
$pdf->Line(20, 90, 112, 90, $style6); //FILA
$pdf->Line(20, 96, 112, 96, $style6); //FILA
$pdf->Line(20, 102, 112, 102, $style6); //FILA
$pdf->Line(20, 108, 112, 108, $style6); //FILA


$pdf->RoundedRect(114, 72, 92, 30, 1, '1111', '', $style6, array(200, 200, 200));//MONEDAS
$pdf->Line(144.6, 72, 144.6, 96, $style6); //COL
$pdf->Line(175.2, 72, 175.2, 102, $style6); //COL
$pdf->Line(114, 78, 206, 78, $style6); //FILA
$pdf->Line(114, 84, 206, 84, $style6); //FILA
$pdf->Line(114, 90, 206, 90, $style6); //FILA
$pdf->Line(114, 96, 206, 96, $style6); //FILA







$pdf->SetFont('Times', 'B', 12);
$pdf->MultiCell(92, 1, 'BILLETES', 0, 'C', FALSE, 1, 20, 60, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(92, 1, 'MONEDAS', 0, 'C', FALSE, 1, 114, 60, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->MultiCell(30.6, 1, 'Valor', 0, 'C', FALSE, 1, 20, 66, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, 'Cantidad', 0, 'C', FALSE, 1, 50.6, 66, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, 'Total', 0, 'C', FALSE, 1, 81.2, 66, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->MultiCell(30.6, 1, 'Valor', 0, 'C', FALSE, 1, 114, 66, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, 'Cantidad', 0, 'C', FALSE, 1, 144.6, 66, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, 'Total', 0, 'C', FALSE, 1, 175.2, 66, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

// BILLETES
$pdf->MultiCell(30.6, 1, '100.000 Gs', 0, 'C', FALSE, 1, 20, 72, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, '50.000 Gs', 0, 'C', FALSE, 1, 20, 78, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, '20.000 Gs', 0, 'C', FALSE, 1, 20, 84, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, '10.000 Gs', 0, 'C', FALSE, 1, 20, 90, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, '5.000 Gs', 0, 'C', FALSE, 1, 20, 96, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, '2.000 Gs', 0, 'C', FALSE, 1, 20, 102, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['bi_100']), 0, ',', '.'), 0, 'C', FALSE, 1, 50.6, 72, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['bi_50']), 0, ',', '.'), 0, 'C', FALSE, 1, 50.6, 78, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['bi_20']), 0, ',', '.'), 0, 'C', FALSE, 1, 50.6, 84, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['bi_10']), 0, ',', '.'), 0, 'C', FALSE, 1, 50.6, 90, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['bi_5']), 0, ',', '.'), 0, 'C', FALSE, 1, 50.6, 96, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['bi_2']), 0, ',', '.'), 0, 'C', FALSE, 1, 50.6, 102, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('Times', 'B', 12);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['bi_100'] * 100000), 0, ',', '.'), 0, 'C', FALSE, 1, 81.2, 72, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['bi_50'] * 50000), 0, ',', '.'), 0, 'C', FALSE, 1, 81.2, 78, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['bi_20'] * 20000), 0, ',', '.'), 0, 'C', FALSE, 1, 81.2, 84, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['bi_10'] * 10000), 0, ',', '.'), 0, 'C', FALSE, 1, 81.2, 90, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['bi_5'] * 5000), 0, ',', '.'), 0, 'C', FALSE, 1, 81.2, 96, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['bi_100'] * 2000), 0, ',', '.'), 0, 'C', FALSE, 1, 81.2, 102, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);


$totalbilletes = ($consultacabecera[0]['bi_100'] * 100000) + ($consultacabecera[0]['bi_50'] * 50000) + ($consultacabecera[0]['bi_20'] * 20000) + ($consultacabecera[0]['bi_10'] * 10000) + ($consultacabecera[0]['bi_5'] * 5000) + ($consultacabecera[0]['bi_100'] * 2000);
$pdf->MultiCell(61.2, 1, 'Total Billetes', 0, 'C', FALSE, 1, 20, 108, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($totalbilletes), 0, ',', '.'), 0, 'C', FALSE, 1, 81.2, 108, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);



//MONEDAS
$pdf->MultiCell(30.6, 1, '1.000 Gs', 0, 'C', FALSE, 1, 114, 72, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, '500 Gs', 0, 'C', FALSE, 1, 114, 78, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, '100 Gs', 0, 'C', FALSE, 1, 114, 84, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, '50 Gs', 0, 'C', FALSE, 1, 114, 90, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['mo_1000']), 0, ',', '.'), 0, 'C', FALSE, 1, 144.6, 72, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['mo_500']), 0, ',', '.'), 0, 'C', FALSE, 1, 144.6, 78, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['mo_100']), 0, ',', '.'), 0, 'C', FALSE, 1, 144.6, 84, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['mo_50']), 0, ',', '.'), 0, 'C', FALSE, 1, 144.6, 90, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('Times', 'B', 12);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['mo_1000'] * 1000), 0, ',', '.'), 0, 'C', FALSE, 1, 175.2, 72, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['mo_500'] * 500), 0, ',', '.'), 0, 'C', FALSE, 1, 175.2, 78, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['mo_100'] * 100), 0, ',', '.'), 0, 'C', FALSE, 1, 175.2, 84, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($consultacabecera[0]['mo_50'] * 50), 0, ',', '.'), 0, 'C', FALSE, 1, 175.2, 90, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);


$totalmonedas = ($consultacabecera[0]['mo_1000'] * 1000) + ($consultacabecera[0]['mo_500'] * 500) + ($consultacabecera[0]['mo_100'] * 100) + ($consultacabecera[0]['mo_50'] * 50);


$pdf->MultiCell(61.2, 1, 'Total Monedas', 0, 'C', FALSE, 1, 114, 96, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(30.6, 1, number_format(($totalmonedas), 0, ',', '.'), 0, 'C', FALSE, 1, 175.2, 96, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);





//DETALLES CEHQUES
$pdf->SetFont('Times', 'B', 12);
$pdf->MultiCell(185, 1, 'DETALLES CHEQUES ', 0, 'C', FALSE, 1, 21, 116, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('Times', 'B', 8);

$pdf->RoundedRect(20, 122, 186, 43, 1, '1111', '', $style6, array(200, 200, 200));
$pdf->Line(20, 127, 206, 127, $style6); // FILA 1

$pdf->Line(55, 122, 55, 160, $style6); // COLUMMNA 1
$pdf->Line(100, 122, 100, 160, $style6); // COLUMMNA 2
$pdf->Line(140, 122, 140, 165, $style6); // COLUMNA 3



$pdf->SetFont('Times', 'B', 9);
$pdf->MultiCell(35, 1, 'TIPO DE CHEQUE', 0, 'C', FALSE, 1, 20, 122, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(45, 1, 'BANCO', 0, 'C', FALSE, 0, 55, 122, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(40, 1, 'NUMERO DE CHEQUE', 0, 'C', FALSE, 1, 100, 122, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(66, 1, 'TOTAL', 0, 'C', FALSE, 1, 140, 122, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);


$pdf->SetXY(20, 127);
if (!empty($consultardetalles_cheque)) {
    foreach ($consultardetalles_cheque as $detc) {
        $pdf->SetFont('Times', '', 9);
        $pdf->Cell(35, 1, $detc['tipocheque'], 0, 0, 'C', 0, '', 1);
        $pdf->Cell(45, 1, $detc['banco'], 0, 0, 'C', 0, '', 1);
        $pdf->Cell(40, 1, $detc['nro_cheque'], 0, 0, 'C', 0, '', 1);
        $pdf->Cell(66, 1, number_format(($detc['monto']), 0, ',', '.'), 0, 0, 'C', 0, '', 1);

        $pdf->ln();
        $pdf->SetX(20);
    }
}

$pdf->Line(20, 160, 206, 160, $style6); // FILA 2
$pdf->SetFont('Times', 'B', 9);
$pdf->MultiCell(120, 1, 'TOTAL CHEQUE', 0, 'C', FALSE, 1, 20, 160, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 9);
$sqltotalcheque = consultas::get_datos("SELECT COALESCE(SUM(monto),0) AS total FROM arqueo_det_cheques WHERE id_arqueo = $idarqueo");
$totalpagarcheque = $sqltotalcheque[0]['total'];
$pdf->MultiCell(66, 1, number_format(($totalpagarcheque), 0, ',', '.'), 0, 'C', FALSE, 1, 140, 160, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);



// DETALLES TARJETAS
$pdf->SetFont('Times', 'B', 12);
$pdf->MultiCell(185, 1, 'DETALLES TARJETAS ', 0, 'C', FALSE, 1, 21, 166, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->SetFont('Times', 'B', 8);

$pdf->RoundedRect(20, 172, 186, 43, 1, '1111', '', $style6, array(200, 200, 200));
$pdf->Line(20, 177, 206, 177, $style6); // FILA 1

$pdf->Line(55, 172, 55, 210, $style6); // COLUMMNA 2
$pdf->Line(140, 172, 140, 215, $style6); // COLUMNA 4



$pdf->SetFont('Times', 'B', 8);
$pdf->MultiCell(35, 1, 'TIPO', 0, 'C', FALSE, 1, 20, 172, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(85, 1, 'N° COMPROBANTE', 0, 'C', FALSE, 0, 55, 172, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(66, 1, 'TOTAL', 0, 'C', FALSE, 1, 140, 172, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);


$pdf->SetXY(20, 177);
if (!empty($consultardetalles_tarjeta)) {
    foreach ($consultardetalles_tarjeta as $dett) {
        $pdf->SetFont('Times', '', 8);
        $pdf->Cell(35, 1, $dett['tipotarjeta'], 0, 0, 'C', 0);
        $pdf->Cell(85, 1, $dett['nro_comprobante'], 0, 0, 'C', 0);
        $pdf->Cell(66, 1, number_format(($dett['monto']), 0, ',', '.'), 0, 0, 'C', 0);

        $pdf->ln();
        $pdf->SetX(20);
    }
}

$pdf->Line(20, 210, 206, 210, $style6); // FILA 2
$pdf->SetFont('Times', 'B', 9);
$pdf->MultiCell(120, 1, 'TOTAL TARJETA', 0, 'C', FALSE, 1, 20, 210, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 9);
$sqltotaltarjeta = consultas::get_datos("SELECT COALESCE(SUM(monto),0) AS total FROM arqueo_det_tarjetas WHERE id_arqueo = $idarqueo");
$totalpagartarjeta = $sqltotaltarjeta[0]['total'];
$pdf->MultiCell(66, 1, number_format(($totalpagartarjeta), 0, ',', '.'), 0, 'C', FALSE, 1, 140, 210, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

// TOTAL A PAGAR
$pdf->SetFont('Times', 'B', 12);
$pdf->MultiCell(50, 1, 'TOTAL A EFECTIVO: ', 0, 'R', FALSE, 1, 50, 220, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(50, 1, number_format(($totalbilletes + $totalmonedas), 0, ',', '.'), 0, 'L', FALSE, 1, 100, 220, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 12);
$pdf->MultiCell(50, 1, 'TOTAL CHEQUE: ', 0, 'R', FALSE, 1, 50, 225, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(50, 1, number_format(($totalpagarcheque), 0, ',', '.'), 0, 'L', FALSE, 1, 100, 225, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 12);
$pdf->MultiCell(50, 1, 'TOTAL TARJETA: ', 0, 'R', FALSE, 1, 50, 230, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(50, 1, number_format(($totalpagartarjeta), 0, ',', '.'), 0, 'L', FALSE, 1, 100, 230, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 12);
$pdf->MultiCell(50, 1, 'TOTAL GENERAL: ', 0, 'R', FALSE, 1, 50, 235, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(50, 1, number_format(($totalbilletes + $totalmonedas + $totalpagarcheque + $totalpagartarjeta), 0, ',', '.'), 0, 'L', FALSE, 1, 100, 235, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->SetFont('Times', 'B', 12);


$pdf->SetFont('Times', '', 12);
$pdf->MultiCell(92, 1, '----------------------------------------------------', 0, 'C', FALSE, 1, 20, 270, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(92, 1, 'Firma del Encargado de Venta', 0, 'C', FALSE, 1, 20, 274, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);

$pdf->MultiCell(92, 1, '----------------------------------------------------', 0, 'C', FALSE, 1, 114, 270, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);
$pdf->MultiCell(92, 1, 'Aclaración de Firma', 0, 'C', FALSE, 1, 114, 274, TRUE, 0, FALSE, TRUE, 0, 'T', FALSE);



//SALIDA AL NAVEGADOR
$pdf->Output('AR-'.$consultacabecera[0]['nro_arqueo_larga'].'.pdf', 'I');
?>