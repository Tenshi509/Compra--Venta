<?php
require '../../conexion.php';
session_start();
if ($_SESSION == NULL) {
    $_SESSION['error'] = 'Debes iniciar sesión para usar el sistema';
    header('location:/graficanissei_taller/');
}

$operacion = $_REQUEST['operacion'];
$vidcobro = $_REQUEST['vidcobro'];
$vbanco = $_REQUEST['vbanco'];
$vtipocheque = $_REQUEST['vtipocheque'];
$vnrocheque = $_REQUEST['vnrocheque'];
$vmonto = $_REQUEST['vmonto'];

if (!empty($_REQUEST['vorden'])) {
    $vorden = intval(str_replace(".", "", $_REQUEST['vorden']));
} else {
    $vorden = 0;
}


$sql = "SELECT sp_cobros_det_cheques(". $operacion . ",". 
        (!empty($vidcobro) ? $vidcobro:0).",".
        (!empty($vbanco) ? $vbanco:0).",".
        (!empty($vorden) ? $vorden:0).",".
        (!empty($vtipocheque) ? $vtipocheque:0).",'".
        (!empty($vnrocheque) ? $vnrocheque:0)."',".
        (!empty($vmonto) ? $vmonto:0).") AS detalle;";
$resultado = consultas::get_datos($sql);

if ($resultado[0]['detalle'] != NULL) {
    $valor = explode("*" , $resultado[0]['detalle']);
    $_SESSION['mensaje'] = $valor[0];
    header("location:". $valor[1].".php?vidcobro=". $_REQUEST['vidcobro']);
} else {
    $_SESSION['mensaje'] = 'Error:' . $sql;
    header("location:". $valor[1].".php?vidcobro=". $_REQUEST['vidcobro']);
}



