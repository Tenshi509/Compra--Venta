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
        $vid_presupuestoproveedor = $_REQUEST['vidpresupuestoproveedor'];
        $vid_materiaprima = $_REQUEST['vmateriaprima'];
        $vcantidad = $_REQUEST['vcantidad'];
        $vpreciounitario = $_REQUEST['vpreciounitario'];
        break;

    case 2: // MODIFICAR
        $vid_presupuestoproveedor = $_REQUEST['vidpresupuestoproveedor'];
        $vid_materiaprima = $_REQUEST['vmateriaprima'];
        $vcantidad = $_REQUEST['vcantidad'];
        $vpreciounitario = $_REQUEST['vpreciounitario'];
        break;

    case 3: // BORRAR
        $vid_presupuestoproveedor = $_REQUEST['vidpresupuestoproveedor'];
        $vid_materiaprima = $_REQUEST['vmateriaprima'];
        $vcantidad = 0;
        $vpreciounitario = $_REQUEST['vpreciounitario'];
        break;
}


$sql = "SELECT sp_presupuestoproveedor_det(" . $operacion . "," .
    (!empty($vid_presupuestoproveedor) ? $vid_presupuestoproveedor : 0) . "," .
    (!empty($vid_materiaprima) ? $vid_materiaprima : 0) . "," .
    (!empty($vcantidad) ? $vcantidad : 0) . "," .
    (!empty($vpreciounitario) ? $vpreciounitario : 0) . ") AS detalle;";
$resultado = consultas::get_datos($sql);

if ($resultado[0]['detalle'] != NULL) {
    $valor = explode("*", $resultado[0]['detalle']);
    $_SESSION['mensaje'] = $valor[0];
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidpresupuestoproveedor=" . $_REQUEST['vidpresupuestoproveedor']);
    } else {
        header("location:" . $valor[1] . ".php?vidpresupuestoproveedor=" . $_REQUEST['vidpresupuestoproveedor']);
    }
} else {
    $_SESSION['mensaje'] = 'Error:' . $sql;
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidpresupuestoproveedor=" . $_REQUEST['vidpresupuestoproveedor']);
    } else {
        header("location:index.php");
    }
}
