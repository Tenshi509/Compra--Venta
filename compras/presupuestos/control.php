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
        $vid_presupuestoproveedor = $_REQUEST['vidpresupuestoproveedor'];
        $vid_usuario = $_SESSION['id_usuario'];
        $vid_proveedor = $_REQUEST['vproveedor'];
        $vid_pedidocompra = $_REQUEST['vpedido'];;
        $vnro_presupuesto = $_REQUEST['vnropresupuesto'];
        $vfecha = $_REQUEST['vfecha'];
        $vvalidez = $_REQUEST['vvalidez']; 
        $vobservacion = $_REQUEST['vobservacion']; 
        break;

    case 2: // CONFIRMAR
        $vid_presupuestoproveedor = $_REQUEST['vidpresupuestoproveedor'];
        $vid_usuario = 0;
        $vid_proveedor = 0;
        $vid_pedidocompra = $_REQUEST['vpedido'];;
        $vnro_presupuesto = 0;
        $vfecha = 0;
        $vvalidez = 0; 
        $vobservacion = 0; 
        break;

    case 3: // MODIFICAR
        $vid_presupuestoproveedor = $_REQUEST['vidpresupuestoproveedor'];
        $vid_usuario = $_SESSION['id_usuario'];
        $vid_proveedor = $_REQUEST['vproveedor'];
        $vid_pedidocompra = $_REQUEST['vpedido'];;
        $vnro_presupuesto = $_REQUEST['vnropresupuesto'];
        $vfecha = $_REQUEST['vfecha'];
        $vvalidez = $_REQUEST['vvalidez']; 
        $vobservacion = $_REQUEST['vobservacion']; 
        break;

    case 4: // ANULAR
        $vid_presupuestoproveedor = $_REQUEST['vidpresupuestoproveedor'];
        $vid_usuario = 0;
        $vid_proveedor = 0;
        $vid_pedidocompra = $_REQUEST['vpedido'];;
        $vnro_presupuesto = 0;
        $vfecha = 0;
        $vvalidez = 0; 
        $vobservacion = 0; 
        break;
}

$sql = "SELECT sp_presupuestoproveedor(" . $operacion . "," .
    (!empty($vid_presupuestoproveedor) ? $vid_presupuestoproveedor : 0) . "," .
    (!empty($vid_usuario) ? $vid_usuario : 0) . "," .
    (!empty($vid_proveedor) ? $vid_proveedor : 0) . "," .
    (!empty($vid_pedidocompra) ? $vid_pedidocompra : 0) . ",'" .
    (!empty($vnro_presupuesto) ? $vnro_presupuesto : NULL) . "','" .
    (!empty($vfecha) ? $vfecha : '01/01/1900') . "'," .
    (!empty($vvalidez) ? $vvalidez : 0) . ",'" .
    (!empty($vobservacion) ? $vobservacion : NULL) . "') AS presupuesto;";
$resultado = consultas::get_datos($sql);

if ($resultado[0]['presupuesto'] != NULL) {
    $valor = explode("*", $resultado[0]['presupuesto']);
    $_SESSION['mensaje'] = $valor[0];
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidpresupuestoproveedor=" . $valor[2]);
    } else {
        header("location:" . $valor[1] . ".php?vidpresupuestoproveedor=" . $_REQUEST['vcodigo']);
    }
} else {
    $_SESSION['mensaje'] = 'Error:' . $sql;
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidpresupuestoproveedor=" . $_REQUEST['vcodigo']);
    } else {
        header("location:index.php");
    }
}
