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
    case 1: // GENERAR
        $vidnotacreditocompra = 0;
        $vidusuario = $_SESSION['id_usuario'];
        $vidcompras = $_REQUEST['vidcompra'];
        $vidmotivonota = $_REQUEST['vmotivo'];
        $vfecha = $_REQUEST['vfecha'];
        $vnro_comprobante = $_REQUEST['vnrocomprobante'];
        $vnro_timbrado = $_REQUEST['vnrotimbrado'];
        $vven_timbrado = $_REQUEST['vventrimbrado'];
        break;

    case 2: // CONFIRMAR
        $vidnotacreditocompra = $_REQUEST['vcodigo'];
        $vidusuario = 0;
        $vidcompras = $_REQUEST['vidcompra'];
        $vidmotivonota = 0;
        $vfecha = 0;
        $vnro_comprobante = 0;
        $vnro_timbrado = 0;
        $vven_timbrado = 0;
        break;

    case 3: // MODIFICAR MOTIVO
        $vidnotacreditocompra = $_REQUEST['vcodigo'];
        $vidusuario = 0;
        $vidcompras = $_REQUEST['vidcompra'];
        $vidmotivonota = $_REQUEST['vmotivo'];
        $vfecha = 0;
        $vnro_comprobante = 0;
        $vnro_timbrado = 0;
        $vven_timbrado = 0;
        break;
}

$sql = "SELECT sp_notacreditocompra(" . $operacion . "," .
    (!empty($vidnotacreditocompra) ? $vidnotacreditocompra : 0) . "," .
    (!empty($vidusuario) ? $vidusuario : 0) . "," .
    (!empty($vidcompras) ? $vidcompras : 0) . "," .
    (!empty($vidmotivonota) ? $vidmotivonota : 0) . ",'" .
    (!empty($vfecha) ? $vfecha : "01/01/2000") . "','" .
    (!empty($vnro_comprobante) ? $vnro_comprobante : "000-000-0000000") . "'," .
    (!empty($vnro_timbrado) ? $vnro_timbrado : 0) . ",'" .
    (!empty($vven_timbrado) ? $vven_timbrado : "01/01/2000") . "') AS notacredito;";
$resultado = consultas::get_datos($sql);

if ($resultado[0]['notacredito'] != NULL) {
    $valor = explode("*", $resultado[0]['notacredito']);
    $_SESSION['mensaje'] = $valor[0];
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidnotacreditocompra=" . $valor[2]);
    } else {
        header("location:" . $valor[1] . ".php?vidnotacreditocompra=" . $_REQUEST['vcodigo']);
    }
} else {
    $_SESSION['mensaje'] = 'Error:' . $sql;
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidnotacreditocompra=" . $_REQUEST['vcodigo']);
    } else {
        header("location:index.php");
    }
}
