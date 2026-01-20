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
    case 1: // REGISTRAR
        $vidnotaremisioncompra = 0;
        $vidusuario = $_SESSION['id_usuario'];
        $vidcompra = $_REQUEST['vidcompra'];
        $vnrocomprobante = $_REQUEST['vnrocomprobante'];
        $vfechaemision = $_REQUEST['vfechaemision'];
        $vfechainiciotraslado = $_REQUEST['vfechainiciotraslado'];
        $vfechafintraslado = $_REQUEST['vfechafintraslado'];
        $vdirsalida = $_REQUEST['vdirsalida'];
        $vciudad = $_REQUEST['vciudad'];
        $vkm = $_REQUEST['vkm'];
        $vmarca = $_REQUEST['vmarca'];
        $vchapa = $_REQUEST['vchapa'];
        $vchaparemolque = $_REQUEST['vchaparemolque']; 
        $vconductor = $_REQUEST['vconductor']; 
        $vrucconductor = $_REQUEST['vrucconductor']; 
        $vdireccionconductor = $_REQUEST['vdireccionconductor'];
        $vnrotimbrado = $_REQUEST['vnrotimbrado'];
        $vventimbrado = $_REQUEST['vventrimbrado'];
        break;

    case 2: // CONFIRMAR
        $vidnotaremisioncompra = $_REQUEST['vcodigo'];
        $vidcompra = $_REQUEST['vidcompra'];
        break;

    case 3: // MODIFICAR MOTIVO
        $vidnotaremisioncompra = $vidnotaremisioncompra = $_REQUEST['vcodigo'];
        $vidusuario = $_SESSION['id_usuario'];
        $vidcompra = $_REQUEST['vidcompra'];
        $vnrocomprobante = $_REQUEST['vnrocomprobante'];
        $vfechaemision = $_REQUEST['vfechaemision'];
        $vfechainiciotraslado = $_REQUEST['vfechainiciotraslado'];
        $vfechafintraslado = $_REQUEST['vfechafintraslado'];
        $vdirsalida = $_REQUEST['vdirsalida'];
        $vciudad = $_REQUEST['vciudad'];
        $vkm = $_REQUEST['vkm'];
        $vmarca = $_REQUEST['vmarca'];
        $vchapa = $_REQUEST['vchapa'];
        $vchaparemolque = $_REQUEST['vchaparemolque']; 
        $vconductor = $_REQUEST['vconductor']; 
        $vrucconductor = $_REQUEST['vrucconductor']; 
        $vdireccionconductor = $_REQUEST['vdireccionconductor'];
        $vnrotimbrado = $_REQUEST['vnrotimbrado'];
        $vventimbrado = $_REQUEST['vventrimbrado'];
        break;
}

$sql = "SELECT sp_notaremisioncompra(" . $operacion . "," .
    (!empty($vidnotaremisioncompra) ? $vidnotaremisioncompra : 0) . "," .
    (!empty($vidusuario) ? $vidusuario : 0) . "," .
    (!empty($vidcompra) ? $vidcompra : 0) . ",'" .
    (!empty($vnrocomprobante) ? $vnrocomprobante : "000-000-0000000") . "','" .
    (!empty($vfechaemision) ? $vfechaemision : "01/01/2000") . "','" .
    (!empty($vfechainiciotraslado) ? $vfechainiciotraslado : "01/01/2000") . "','" .
    (!empty($vfechafintraslado) ? $vfechafintraslado : "01/01/2000") . "','" .
    (!empty($vdirsalida) ? $vdirsalida : "DIRECCION") . "'," .
    (!empty($vciudad) ? $vciudad : 0) . "," .
    (!empty($vkm) ? $vkm : "NULL") . ",'" .
    (!empty($vmarca) ? $vmarca : "MARCA") . "','" .
    (!empty($vchapa) ? $vchapa : "CHAPA") . "','" .
    (!empty($vchaparemolque) ? $vchaparemolque : " ") . "','" .
    (!empty($vconductor) ? $vconductor : "CONDUCTOR") . "','" .
    (!empty($vrucconductor) ? $vrucconductor : "8000000") . "','" .
    (!empty($vdireccionconductor) ? $vdireccionconductor : " ") . "'," .
    (!empty($vnrotimbrado) ? $vnrotimbrado : "NULL") . ",'" .
    (!empty($vventimbrado) ? $vventimbrado : "01/01/2000") . "') AS remision;";
$resultado = consultas::get_datos($sql);

if ($resultado[0]['remision'] != NULL) {
    $valor = explode("*", $resultado[0]['remision']);
    $_SESSION['mensaje'] = $valor[0];
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidnotaremisioncompra=" . $valor[2]);
    } else {
        header("location:" . $valor[1] . ".php?vidnotaremisioncompra=" . $_REQUEST['vcodigo']);
    }
} else {
    $_SESSION['mensaje'] = 'Error:' . $sql;
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidnotaremisioncompra=" . $_REQUEST['vcodigo']);
    } else {
        header("location:index.php");
    }
}
