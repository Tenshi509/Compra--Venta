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
        $vidarqueo = $_REQUEST['vidarqueo'];
        $vidaperturacierre = $_REQUEST['vidaperturacierre'];
        $vidempleado = $_SESSION['id_empleado'];
        $vidusuario = $_SESSION['id_usuario'];
        break;


    case 2: // GUARDAR EFECTIVOS
            $vidarqueo = $_REQUEST['vidarqueo'];
            $vbi100 = $_REQUEST['vbi100'];
            $vbi50 = $_REQUEST['vbi50'];
            $vbi20 = $_REQUEST['vbi20'];
            $vbi10 = $_REQUEST['vbi10'];
            $vbi5 = $_REQUEST['vbi5']; 
            $vbi2 = $_REQUEST['vbi2'];
            $vmo1000 = $_REQUEST['vmo1000'];
            $vmo500 = $_REQUEST['vmo500'];
            $vmo100 = $_REQUEST['vmo100'];
            $vmo50 = $_REQUEST['vmo50'];
        break;

        case 3: // CONFIRMAR
            $vidarqueo = $_REQUEST['vidarqueo'];
            $vtotalefectivo = $_REQUEST['to_efectivo'];
            $vtotalcheque = $_REQUEST['tcheque'];
            $vtotaltarjeta = $_REQUEST['ttarjeta'];
        break;

        case 4: // MODIFICAR APERTURA
            $vidarqueo = $_REQUEST['vidarqueo'];
            $vidaperturacierre = $_REQUEST['vidaperturacierre'];
        break;

        case 5: // ANULAR
            $vidarqueo = $_REQUEST['vidarqueo'];
        break;




    case 200: // CONFIRMAR
        /*$vidarqueo = $_REQUEST['vidarqueo'];
        $vidaperturacierre = $_REQUEST['vidaperturacierre'];
        $vbi100 = $_REQUEST['vbi100'];
        $vbi50 = $_REQUEST['vbi50'];
        $vbi20 = $_REQUEST['vbi20'];
        $vbi10 = $_REQUEST['vbi10'];
        $vbi5 = $_REQUEST['vbi5']; 
        $vbi2 = $_REQUEST['vbi2'];
        $vmo1000 = $_REQUEST['vmo1000'];
        $vmo500 = $_REQUEST['vmo500'];
        $vmo100 = $_REQUEST['vmo100'];
        $vmo50 = $_REQUEST['vmo50'];
        $vidempleado = $_SESSION['id_empleado'];
        $vidusuario = $_SESSION['id_usuario'];*/

        break;
}


$sql = "SELECT sp_arqueo(" . $operacion . "," .
    (!empty($vidarqueo) ? $vidarqueo : 0) . "," .
    (!empty($vidaperturacierre) ? $vidaperturacierre : 0) . "," .
    (!empty($vbi100) ? $vbi100 : 0) . "," .
    (!empty($vbi50) ? $vbi50 : 0) . "," .
    (!empty($vbi20) ? $vbi20 : 0) . "," .
    (!empty($vbi10) ? $vbi10 : 0) . "," .
    (!empty($vbi5) ? $vbi5 : 0) . "," .
    (!empty($vbi2) ? $vbi2 : 0) . "," .
    (!empty($vmo1000) ? $vmo1000 : 0) . "," .
    (!empty($vmo500) ? $vmo500 : 0) . "," .
    (!empty($vmo100) ? $vmo100 : 0) . "," .
    (!empty($vmo50) ? $vmo50 : 0) . "," .
    (!empty($vidempleado) ? $vidempleado : 0) . "," .
    (!empty($vidusuario) ? $vidusuario : 0) . "," .
    (!empty($vtotalefectivo) ? $vtotalefectivo : 0) . "," .
    (!empty($vtotalcheque) ? $vtotalcheque : 0) . "," .
    (!empty($vtotaltarjeta) ? $vtotaltarjeta : 0) . ") AS arqueo;";
$resultado = consultas::get_datos($sql);

if ($resultado[0]['arqueo'] != NULL) {
    $valor = explode("*", $resultado[0]['arqueo']);
    $_SESSION['mensaje'] = $valor[0];
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidarqueo=" . $valor[2]);
    } else {
        header("location:" . $valor[1] . ".php?vidarqueo=" . $_REQUEST['vidarqueo']);
    }
} else {
    $_SESSION['mensaje'] = 'Error:' . $sql;
    if ($operacion = 1) { // MIESTRA SEA AGREGAR
        header("location:" . $valor[1] . ".php?vidarqueo=" . $_REQUEST['vidarqueo']);
    } else {
        header("location:index.php");
    }
}
