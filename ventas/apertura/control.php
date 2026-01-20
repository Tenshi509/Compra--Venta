<?php
require '../../conexion.php';
session_start();
if ($_SESSION == NULL) {
    $_SESSION['error'] = 'Debes iniciar sesión para usar el sistema';
    header('location:/nova/');
}
date_default_timezone_set('America/Asuncion');
$fechaactual = date("Y-m-d");


$operacion = $_REQUEST['operacion'];



switch ($operacion) {
    case 1: // ABRIR
        $vidcaja = $_REQUEST['vidcaja'];
        $vidusuario = $_SESSION['id_usuario'];
        $vidempleado = $_REQUEST['vidcajero'];
        $vmontoinicial = $_REQUEST['vmontoinicial'];
        break;

    case 2: // MODIFICAR
        $vidaperturacierre = $_REQUEST['vidaperturacierre'];
        $vidcaja = $_REQUEST['vidcaja'];
        $vidusuario = $_SESSION['id_usuario'];
        $vidempleado = $_REQUEST['vidcajero'];
        $vmontoinicial = $_REQUEST['vmontoinicial'];
        break;

    case 3: // ANULAR
        $vidaperturacierre = $_REQUEST['vidaperturacierre'];
        break;
}


$sql = "SELECT sp_apertura(" . $operacion . "," .
    (!empty($vidaperturacierre) ? $vidaperturacierre : 0) . "," .
    (!empty($vidcaja) ? $vidcaja : 0) . "," .
    (!empty($vidusuario) ? $vidusuario : 0) . "," .
    (!empty($vidempleado) ? $vidempleado : 0) . "," .
    (!empty($vmontoinicial) ? $vmontoinicial : 0) . ") AS apertura;";
$resultado = consultas::get_datos($sql);

if ($resultado[0]['apertura'] != NULL) {
    $valor = explode("*", $resultado[0]['apertura']);
    $_SESSION['mensaje'] = $valor[0];
    header("location:" . $valor[1] . ".php");
} else {
    $_SESSION['mensaje'] = 'Error:' . $sql;
    header("location:index.php");
}
