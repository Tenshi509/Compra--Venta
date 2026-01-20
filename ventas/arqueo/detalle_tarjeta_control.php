<?php
require '../../conexion.php';
session_start();
if ($_SESSION == NULL) {
    $_SESSION['error'] = 'Debes iniciar sesión para usar el sistema';
    header('location:/graficanissei_taller/');
}

$operacion = $_REQUEST['operacion'];
$vidarqueo = $_REQUEST['vidarqueo'];
$vidtipotarjeta = $_REQUEST['vtipotarjeta'];
$vnrocomprobante = $_REQUEST['vnrocomprobante'];
$vmonto = $_REQUEST['vmonto'];

if (!empty($_REQUEST['vorden'])) {
    $vorden = intval(str_replace(".", "", $_REQUEST['vorden']));
} else {
    $vorden = 0;
}

$sql = "SELECT sp_arqueo_det_tarjetas(". $operacion . ",". 
        (!empty($vidarqueo) ? $vidarqueo:0).",".
        (!empty($vorden) ? $vorden:0).",".
        (!empty($vidtipotarjeta) ? $vidtipotarjeta:0).",'".
        (!empty($vnrocomprobante) ? $vnrocomprobante:0)."',".
        (!empty($vmonto) ? $vmonto:0).") AS detalle;";
$resultado = consultas::get_datos($sql);

if ($resultado[0]['detalle'] != NULL) {
    $valor = explode("*" , $resultado[0]['detalle']);
    $_SESSION['mensaje'] = $valor[0];
    header("location:". $valor[1].".php?vidarqueo=". $_REQUEST['vidarqueo']);
} else {
    $_SESSION['mensaje'] = 'Error:' . $sql;
    header("location:". $valor[1].".php?vidarqueo=". $_REQUEST['vidarqueo']);
}



