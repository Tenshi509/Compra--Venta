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
    case 1: // AGREGAR
        $vidcobro = 0;
        $vidusuario = $_SESSION['id_usuario'];
        $vidaperturacierre = $_REQUEST['vidaperturacierre'];
        $vidcaja = $_REQUEST['vidcaja'];
        $vidcliente = $_REQUEST['vcliente'];
        $vfecha = $fechaactual;
        $vefectivo = 0;
        $vvuelto = 0;
        break;
    case 2: // CONFIRMAR
        $vidcobro = $_REQUEST['vidcobro'];
        $vidusuario = 0;
        $vidaperturacierre = $_REQUEST['vidaperturacierre'];
        $vidcaja = $_REQUEST['vidcaja'];
        $vidcliente = 0;
        $vfecha = $fechaactual;
        $vefectivo = $_REQUEST['vefectivo'];
        $vvuelto = $_REQUEST['vvuelto'];
        break;
    case 3: // CANCELAR
        $vidcobro = $_REQUEST['vidcobro'];
        $vidusuario = 0;
        $vidaperturacierre = 0;
        $vidcaja = 0;
        $vidcliente = 0;
        $vfecha = 0;
        $vefectivo = 0;
        $vvuelto = 0;
        break;
}

$sql = "SELECT sp_cobros(" . $operacion . "," .
    (!empty($vidcobro) ? $vidcobro : 0) . "," .
    (!empty($vidusuario) ? $vidusuario : 0) . "," .
    (!empty($vidaperturacierre) ? $vidaperturacierre : 0) . "," .
    (!empty($vidcaja) ? $vidcaja : 0) . "," .
    (!empty($vidcliente) ? $vidcliente : 0) . ",'" .
    (!empty($vfecha) ? $vfecha : '01/01/2020') . "'," .
    (!empty($vefectivo) ? $vefectivo : 0) . "," .
    (!empty($vvuelto) ? $vvuelto : 0) . ") AS cobros;";
$resultado = consultas::get_datos($sql);

if ($operacion == 3) {
    if ($resultado[0]['cobros'] != NULL) {
        $valor = explode("*", $resultado[0]['cobros']);
        $_SESSION['mensaje'] = $valor[0];
        header("location:" . $valor[1] . ".php?vidcobro=" . $valor[2]);
    } else {
        $_SESSION['mensaje'] = 'Error:' . $sql;
        header("location:index.php");
    }
} else {
    if ($resultado[0]['cobros'] != NULL) {
        $valor = explode("*", $resultado[0]['cobros']);
        $_SESSION['mensaje'] = $valor[0];
        header("location:" . $valor[1] . ".php?vidcobro=" . $valor[2]);
    } else {
        $_SESSION['mensaje'] = 'Error:' . $sql;
        header("location:index.php");
    }
}
