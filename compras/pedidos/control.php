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
        $vid_pedidocompra = 0;
        $vid_usuario = $_SESSION['id_usuario'];
        $vnro_pedido = 0;
        $vfecha = $fechaactual;
        $vobservacion = NULL;
        break;

    case 2: // CONFIRMAR
        $vid_pedidocompra = $_REQUEST['vidpedidocompra'];
        $vid_usuario = 0;
        $vnro_pedido = 0;
        $vfecha = $fechaactual;
        $vobservacion = $_REQUEST['vobservacion'];
        break;

    case 3: // MODIFICAR
        $vid_pedidocompra = $_REQUEST['vidpedidocompra'];
        $vid_usuario = 0;
        $vnro_pedido = 0;
        $vfecha = $fechaactual;
        $vobservacion = NULL;
        break;

    case 4: // ANULAR
        $vid_pedidocompra = $_REQUEST['vidpedidocompra'];
        $vid_usuario = 0;
        $vnro_pedido = 0;
        $vfecha = $fechaactual;
        $vobservacion = NULL;
        break;
}


$sql = "SELECT sp_pedidoscompras(" . $operacion . "," .
    (!empty($vid_pedidocompra) ? $vid_pedidocompra : 0) . "," .
    (!empty($vid_usuario) ? $vid_usuario : 0) . "," .
    (!empty($vnro_pedido) ? $vnro_pedido : 0) . ",'" .
    (!empty($vfecha) ? $vfecha : '01/01/1900') . "','" .
    (!empty($vobservacion) ? $vobservacion : NULL) . "') AS pedido;";
$resultado = consultas::get_datos($sql);

if ($resultado[0]['pedido'] != NULL) {
    $valor = explode("*", $resultado[0]['pedido']);
    $_SESSION['mensaje'] = $valor[0];
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidpedidocompra=" . $valor[2]);
    } else {
        header("location:" . $valor[1] . ".php?vidpedidocompra=" . $_REQUEST['vidpedidocompra']);
    }
} else {
    $_SESSION['mensaje'] = 'Error:' . $sql;
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidpedidocompra=" . $_REQUEST['vidpedidocompra']);
    } else {
        header("location:index.php");
    }
}
