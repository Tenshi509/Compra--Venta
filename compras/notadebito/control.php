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
$check = $_REQUEST['vcheck'];

switch ($operacion) {
    case 1: // GENERAR
        $vidnotadebitocompra = 0;
        $vidusuario = $_SESSION['id_usuario'];
        $vidcompras = $_REQUEST['vidcompra'];
        $vidmotivonota = $_REQUEST['vmotivo'];
        $vfecha = $_REQUEST['vfecha'];
        $vnro_comprobante = $_REQUEST['vnrocomprobante'];
        $vnro_timbrado = $_REQUEST['vnrotimbrado'];
        $vven_timbrado = $_REQUEST['vventrimbrado'];
        break;

    case 2: // CONFIRMAR
        $vidnotadebitocompra = $_REQUEST['vcodigo'];
        $vidusuario = 0;
        $vidcompras = $_REQUEST['vidcompra'];
        $vidmotivonota = 0;
        $vfecha = 0;
        $vnro_comprobante = 0;
        $vnro_timbrado = 0;
        $vven_timbrado = 0;
        break;

    case 3: // MODIFICAR MOTIVO
        $vidnotadebitocompra = $_REQUEST['vcodigo'];
        $vidusuario = 0;
        $vidcompras = $_REQUEST['vidcompra'];
        $vidmotivonota = $_REQUEST['vmotivo'];
        $vfecha = 0;
        $vnro_comprobante = 0;
        $vnro_timbrado = 0;
        $vven_timbrado = 0;
        break;
}

$sql = "SELECT sp_notadebitocompra(" . $operacion . "," .
    (!empty($vidnotadebitocompra) ? $vidnotadebitocompra : 0) . "," .
    (!empty($vidusuario) ? $vidusuario : 0) . "," .
    (!empty($vidcompras) ? $vidcompras : 0) . "," .
    (!empty($vidmotivonota) ? $vidmotivonota : 0) . ",'" .
    (!empty($vfecha) ? $vfecha : "01/01/2000") . "','" .
    (!empty($vnro_comprobante) ? $vnro_comprobante : "000-000-0000000") . "'," .
    (!empty($vnro_timbrado) ? $vnro_timbrado : 0) . ",'" .
    (!empty($vven_timbrado) ? $vven_timbrado : "01/01/2000") . "'," .
    (!empty($check) ? $check : 0) . ") AS notadebito;";
$resultado = consultas::get_datos($sql);

if ($resultado[0]['notadebito'] != NULL) {
    $valor = explode("*", $resultado[0]['notadebito']);
    $_SESSION['mensaje'] = $valor[0];
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidnotadebitocompra=" . $valor[2]);
    } else {
        header("location:" . $valor[1] . ".php?vidnotadebitocompra=" . $_REQUEST['vcodigo']);
    }
} else {
    $_SESSION['mensaje'] = 'Error:' . $sql;
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidnotadebitocompra=" . $_REQUEST['vcodigo']);
    } else {
        header("location:index.php");
    }
}
