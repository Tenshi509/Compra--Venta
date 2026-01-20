<?php
require('../../conexion.php');
session_start();

$id_aperturacierre = $_POST['id_aperturacierre'];


$apertura = consultas::get_datos("SELECT * FROM v_aperturacierre WHERE id_aperturacierre = $id_aperturacierre");

//OBTENER TOTAL EFECTIVO
$efectivocaja = $apertura[0]['monto_inicial'] + $apertura[0]['cierre_efectivo'];
$chequediferido = $apertura[0]['cierre_cheque'] - $apertura[0]['cierre_cheque_dia'];
$totaldepositar = $efectivocaja + $apertura[0]['cierre_cheque_dia'];
$totalcierre = $apertura[0]['monto_inicial'] + $apertura[0]['cierre_efectivo'] + $apertura[0]['cierre_tarjeta'] + $apertura[0]['cierre_transferencia'] + $apertura[0]['cierre_cheque'];

?>

<div class="form-row">
    <div class="form-group col-md-3">
        <label>ID</label>
        <input type="text" class="form-control" readonly value="<?php echo $apertura[0]['id_aperturacierre'] ?>">
    </div>
    <div class="form-group col-md-3">
        <label>Apertura N°</label>
        <input type="text" class="form-control" readonly value="<?php echo $apertura[0]['nro_aperturacierre_larga'] ?>">
    </div>
    <div class="form-group col-md-3">
        <label>Fecha</label>
        <input type="text" class="form-control" readonly value="<?php echo $apertura[0]['fecha_apertura_corta'] ?>">
    </div>
    <div class="form-group col-md-3">
        <label>Hora</label>
        <input type="text" class="form-control" readonly value="<?php echo $apertura[0]['hora_apertura'] ?>">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-3">
        <label>Caja</label>
        <input type="text" class="form-control" readonly value="<?php echo $apertura[0]['caja'] ?>">
    </div>
    <div class="form-group col-md-6">
        <label>Cajero</label>
        <input type="text" class="form-control" readonly value="<?php echo $apertura[0]['persona_corta'] ?>">
    </div>
    <div class="form-group col-md-3">
        <label>Monto Inicial</label>
        <input type="text" class="form-control" readonly value="<?php echo number_format($apertura[0]['monto_inicial'], 0, ',', '.') ?>">
    </div>
</div>


<div class="form-group col-md-6">
    <h4 class="text-center text-bold">Recaudaciones de la Caja</h4>
</div>
<div class="form-group col-md-6">
    <h4 class="text-center text-bold">Recaudaciones a depositar</h4>
</div>
<div class="text-center">
    <div class="form-group col-md-2">
        <p class="text-bold">Efectivo</p>
    </div>
    <div class="form-group col-md-4">
        <input type="text" class="form-control" readonly value="<?php echo number_format($efectivocaja, 0, ',', '.') ?>">
        <input type="hidden" value="<?php echo $efectivocaja ?>" name="vefectivo">
    </div>

    <div class="form-group col-md-2">
        <p class="text-bold">Efectivo</p>
    </div>
    <div class="form-group col-md-4">
        <input type="text" class="form-control" readonly value="<?php echo number_format($efectivocaja, 0, ',', '.') ?>">
    </div>
</div>
<p class="row"></p>
<div class="text-center">
    <div class="form-group col-md-2">
        <p class="text-bold">Cheques al Dia</p>
    </div>
    <div class="form-group col-md-4">
        <input type="text" class="form-control" readonly value="<?php echo number_format($apertura[0]['cierre_cheque_dia'], 0, ',', '.') ?>">
    </div>

    <div class="form-group col-md-2">
        <p class="text-bold">Cheques al Dia</p>
    </div>
    <div class="form-group col-md-4">
        <input type="text" class="form-control" readonly value="<?php echo number_format($apertura[0]['cierre_cheque_dia'], 0, ',', '.') ?>">
        <input type="hidden" value="<?php echo $apertura[0]['cierre_cheque_dia'] ?>" name="vcheque">
    </div>
</div>
<p class="row"></p>
<div class="text-center">
    <div class="form-group col-md-2">
        <p class="text-bold">Cheques diferidos</p>
    </div>
    <div class="form-group col-md-4">
        <input type="text" class="form-control" readonly value="<?php echo number_format($chequediferido, 0, ',', '.') ?>">
    </div>

    <div class="form-group col-md-2">
        <p class="text-bold">Total</p>
    </div>
    <div class="form-group col-md-4">
        <input type="text" class="form-control" readonly value="<?php echo number_format($totaldepositar, 0, ',', '.') ?>">
    </div>
</div>
<p class="row"></p>
<div class="text-center">
    <div class="form-group col-md-2">
        <p class="text-bold">Tarjetas</p>
    </div>
    <div class="form-group col-md-4">
        <input type="text" class="form-control" readonly value="<?php echo number_format($apertura[0]['cierre_tarjeta'], 0, ',', '.') ?>">
    </div>
</div>
<p class="row"></p>
<div class="text-center">
    <div class="form-group col-md-2">
        <p class="text-bold">Transferencia</p>
    </div>
    <div class="form-group col-md-4">
        <input type="text" class="form-control" readonly value="<?php echo number_format($apertura[0]['cierre_transferencia'], 0, ',', '.') ?>">
    </div>
</div>
<p class="row"></p>
<div class="text-center">
    <div class="form-group col-md-2">
        <p class="text-bold">Total</p>
    </div>
    <div class="form-group col-md-4">
        <input type="text" class="form-control" readonly value="<?php echo number_format($totalcierre, 0, ',', '.') ?>">
    </div>
</div>

<input type="hidden" name="vidaperturacierre" value="<?php echo $apertura[0]['id_aperturacierre'] ?>">