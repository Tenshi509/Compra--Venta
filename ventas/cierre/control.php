<?php
require '../../conexion.php';
session_start();
if ($_SESSION == NULL) {
    $_SESSION['error'] = 'Debes iniciar sesión para usar el sistema';
    header('location:/graficanissei/');
}
date_default_timezone_set('America/Asuncion');
$fechaactual = date("Y-m-d");


$operacion = $_REQUEST['operacion'];



switch ($operacion) {
    case 1: // CERRAR caJA
        $vidaperturacierre = $_REQUEST['vidaperturacierre'];
        $vcheque = $_REQUEST['vcheque'];
        $vefectivo = $_REQUEST['vefectivo'];
        break;
}


$sql = "SELECT sp_cierre(" . $operacion . "," .
    (!empty($vidaperturacierre) ? $vidaperturacierre : 0) . "," .
    (!empty($vcheque) ? $vcheque : 0) . "," .
    (!empty($vefectivo) ? $vefectivo : 0) . ") AS cierre;";
$resultado = consultas::get_datos($sql);

if ($resultado[0]['cierre'] != NULL) {
    $valor = explode("*", $resultado[0]['cierre']);
    $_SESSION['mensaje'] = $valor[0];
    header("location:" . $valor[1] . ".php");
} else {
    $_SESSION['mensaje'] = 'Error:' . $sql;
    header("location:index.php");
}
