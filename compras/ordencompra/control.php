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
    case 1: // GENERAR
        $vid_ordencompra = 0;
        $vid_usuario = $_SESSION['id_usuario'];
        $vid_proveedor = $_REQUEST['vproveedor'];
        $vid_presupuestoproveedor = $_REQUEST['vpresupuestoproveedor'];
        $vfecha = $fechaactual;
        $vobservacion = $_REQUEST['vobservacion']; 
        break;

    case 2: // CONFIRMAR
        $vid_ordencompra = $_REQUEST['vidordencompra'];
        $vid_usuario = 0;
        $vid_proveedor = 0;
        $vid_presupuestoproveedor = $_REQUEST['vpresupuestoproveedor'];
        $vfecha = 0;
        $vobservacion = 0; 
        break;

    case 3: // MODIFICAR
        $vid_ordencompra = $_REQUEST['vidordencompra'];
        $vid_usuario = 0;
        $vid_proveedor = $_REQUEST['vproveedor'];;
        $vid_presupuestoproveedor = $_REQUEST['vpresupuestoproveedor'];
        $vfecha = 0;
        $vobservacion = $_REQUEST['vobservacion']; 
        break;

    case 4: // ANULAR
        $vid_ordencompra = $_REQUEST['vidordencompra'];
        $vid_usuario = 0;
        $vid_proveedor = 0;
        $vid_presupuestoproveedor = $_REQUEST['vpresupuestoproveedor'];
        $vfecha = 0;
        $vobservacion = 0; 
        break;
}

$sql = "SELECT sp_ordencompra(" . $operacion . "," .
    (!empty($vid_ordencompra) ? $vid_ordencompra : 0) . "," .
    (!empty($vid_usuario) ? $vid_usuario : 0) . "," .
    (!empty($vid_proveedor) ? $vid_proveedor : 0) . "," .
    (!empty($vid_presupuestoproveedor) ? $vid_presupuestoproveedor : 0) . ",'" .
    (!empty($vfecha) ? $vfecha : '01/01/1900') . "','" .
    (!empty($vobservacion) ? $vobservacion : NULL) . "') AS ordencompra;";
$resultado = consultas::get_datos($sql);

if ($resultado[0]['ordencompra'] != NULL) {
    $valor = explode("*", $resultado[0]['ordencompra']);
    $_SESSION['mensaje'] = $valor[0];
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidordencompra=" . $valor[2]);
    } else {
        header("location:" . $valor[1] . ".php?vidordencompra=" . $_REQUEST['vidordencompra']);
    }
} else {
    $_SESSION['mensaje'] = 'Error:' . $sql;
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidpresupuestoproveedor=" . $_REQUEST['vcodigo']);
    } else {
        header("location:index.php");
    }
}
