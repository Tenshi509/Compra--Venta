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
        $vidcompras = 0;
        $vidusuario = $_SESSION['id_usuario'];
        $vidordencompra = $_REQUEST['vidordencompra'];
        $vidproveedor = $_REQUEST['vproveedor'];
        $vfecha = $_REQUEST['vfecha'];
        $vnro_factura = $_REQUEST['vnrofactura'];
        $vnro_timbrado = $_REQUEST['vnrotimbrado'];
        $vven_timbrado = $_REQUEST['vventrimbrado'];
        $vidcondicionventa = $_REQUEST['vcondicion'];
        $vcuota = $_REQUEST['vcuota'];
        $vintervalo = $_REQUEST['vintervalo'];
        $vconcepto = $_REQUEST['vconcepto'];
        break;

    case 2: // CONFIRMAR
        $vidcompras = $_REQUEST['vcodigo'];
        $vidusuario = 0;
        $vidordencompra = $_REQUEST['vidordencompra'];
        $vidproveedor = 0;
        $vfecha = 0;
        $vnro_factura = 0;
        $vnro_timbrado = 0;
        $vven_timbrado = 0;
        $vidcondicionventa = 0;
        $vcuota = 0;
        $vintervalo = 0;
        $vconcepto = 0;
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

$sql = "SELECT sp_compras(" . $operacion . "," .
    (!empty($vidcompras) ? $vidcompras : 0) . "," .
    (!empty($vidusuario) ? $vidusuario : 0) . "," .
    (!empty($vidordencompra) ? $vidordencompra : 0) . "," .
    (!empty($vidproveedor) ? $vidproveedor : 0) . ",'" .
    (!empty($vfecha) ? $vfecha : "01/01/2000") . "','" .
    (!empty($vnro_factura) ? $vnro_factura : "000-000-0000000") . "'," .
    (!empty($vnro_timbrado) ? $vnro_timbrado : 0) . ",'" .
    (!empty($vven_timbrado) ? $vven_timbrado : "01/01/2000") . "'," .
    (!empty($vidcondicionventa) ? $vidcondicionventa : 0) . "," .
    (!empty($vcuota) ? $vcuota : 0) . "," .
    (!empty($vintervalo) ? $vintervalo : 0) . ",'" .
    (!empty($vconcepto) ? $vconcepto : NULL) . "') AS compras;";
$resultado = consultas::get_datos($sql);

if ($resultado[0]['compras'] != NULL) {
    $valor = explode("*", $resultado[0]['compras']);
    $_SESSION['mensaje'] = $valor[0];
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidcompra=" . $valor[2]);
    } else {
        header("location:" . $valor[1] . ".php?vidcompra=" . $_REQUEST['vcodigo']);
    }
} else {
    $_SESSION['mensaje'] = 'Error:' . $sql;
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidcompra=" . $_REQUEST['vcodigo']);
    } else {
        header("location:index.php");
    }
}
