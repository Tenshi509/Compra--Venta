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
    case 1: // REGISTRAR
        $vidajustestock = 0;
        $vidusuario = $_SESSION['id_usuario'];
        $vnroajuste = 0;
        $vfecha = $fechaactual;
        $vobservacion = $_REQUEST['vobservacion'];
        break;

    case 2: // CONFIRMAR
        $vidajustestock = $_REQUEST['vidajustestock'];
        break;

    case 3: // MODIFICAR
        $vidajustestock = $_REQUEST['vidajustestock'];
        $vobservacion = $_REQUEST['vobservacion'];
        break;

    case 4: // ANULAR
        $vidajustestock = $_REQUEST['vidajustestock'];
        break;
}


$sql = "SELECT sp_ajustestock(" . $operacion . "," .
    (!empty($vidajustestock) ? $vidajustestock : 0) . "," .
    (!empty($vidusuario) ? $vidusuario : 0) . "," .
    (!empty($vnroajuste) ? $vnroajuste : 0) . ",'" .
    (!empty($vfecha) ? $vfecha : '01/01/1900') . "','" .
    (!empty($vobservacion) ? $vobservacion : NULL) . "') AS ajuste;";
$resultado = consultas::get_datos($sql);

if ($resultado[0]['ajuste'] != NULL) {
    $valor = explode("*", $resultado[0]['ajuste']);
    $_SESSION['mensaje'] = $valor[0];
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidajustestock=" . $valor[2]);
    } else {
        header("location:" . $valor[1] . ".php?vidajustestock=" . $_REQUEST['vidajustestock']);
    }
} else {
    $_SESSION['mensaje'] = 'Error:' . $sql;
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidpedidocompra=" . $_REQUEST['vidpedidocompra']);
    } else {
        header("location:index.php");
    }
}
