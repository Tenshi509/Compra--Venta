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
        $vid_pedidocompra = $_REQUEST['vidpedidocompra'];
        $vid_materiaprima = $_REQUEST['vmateriaprima'];
        $vcantidad = $_REQUEST['vcantidad'];
        break;

    case 2: // MODIFICAR
        $vid_pedidocompra = $_REQUEST['vidpedidocompra'];
        $vid_materiaprima = $_REQUEST['vmateriaprima'];
        $vcantidad = $_REQUEST['vcantidad'];
        break;

    case 3: // BORRAR
        $vid_pedidocompra = $_REQUEST['vidpedidocompra'];
        $vid_materiaprima = $_REQUEST['vmateriaprima'];
        $vcantidad = 0;
        break;
}


$sql = "SELECT sp_pedidoscompras_det(" . $operacion . "," .
    (!empty($vid_pedidocompra) ? $vid_pedidocompra : 0) . "," .
    (!empty($vid_materiaprima) ? $vid_materiaprima : 0) . "," .
    (!empty($vcantidad) ? $vcantidad : 0) . ") AS detalle;";
$resultado = consultas::get_datos($sql);

if ($resultado[0]['detalle'] != NULL) {
    $valor = explode("*", $resultado[0]['detalle']);
    $_SESSION['mensaje'] = $valor[0];
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidpedidocompra=" . $_REQUEST['vidpedidocompra']);
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
