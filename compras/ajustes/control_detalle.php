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
    case 1: // AGREGAR
        $vidajustestock = $_REQUEST['vidajustestock'];
        $vidmateriaprima = $_REQUEST['vmateriaprima'];
        $vcantidadanterior = $_REQUEST['vcantidadanterior'];
        $vcantidadactual = $_REQUEST['vcantidadajustada'];
        $vmotivo = $_REQUEST['vmotivo'];
        break;

    case 2: // MODIFICAR
        $vidajustestock = $_REQUEST['vidajustestock'];
        $vidmateriaprima = $_REQUEST['vmateriaprima'];
        $vcantidadanterior = $_REQUEST['vcantidadanterior'];
        $vcantidadactual = $_REQUEST['vcantidadajustada'];
        $vmotivo = $_REQUEST['vmotivo'];
        break;

    case 3: // BORRAR
        $vidajustestock = $_REQUEST['vidajustestock'];
        $vidmateriaprima = $_REQUEST['vmateriaprima'];
        break;
}


$sql = "SELECT sp_ajustestock_det(" . $operacion . "," .
    (!empty($vidajustestock) ? $vidajustestock : 0) . "," .
    (!empty($vidmateriaprima) ? $vidmateriaprima : 0) . "," .
    (!empty($vcantidadanterior) ? $vcantidadanterior : 0) . "," .
    (!empty($vcantidadactual) ? $vcantidadactual : 0) . "," .
    (!empty($vmotivo) ? $vmotivo : 0) . ") AS detalle;";
$resultado = consultas::get_datos($sql);

if ($resultado[0]['detalle'] != NULL) {
    $valor = explode("*", $resultado[0]['detalle']);
    $_SESSION['mensaje'] = $valor[0];
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidajustestock=" . $_REQUEST['vidajustestock']);
    } else {
        header("location:" . $valor[1] . ".php?vidajustestock=" . $_REQUEST['vidajustestock']);
    }
} else {
    $_SESSION['mensaje'] = 'Error:' . $sql;
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidajustestock=" . $_REQUEST['vidajustestock']);
    } else {
        header("location:index.php");
    }
}
