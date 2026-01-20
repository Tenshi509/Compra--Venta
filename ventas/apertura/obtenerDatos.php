<?php
require('../../conexion.php');
session_start();

$id_aperturacierre = $_POST['id_aperturacierre'];


$apertura = consultas::get_datos("SELECT * FROM v_aperturacierre WHERE id_aperturacierre = $id_aperturacierre");
$cajas = consultas::get_datos("SELECT * FROM v_cajas WHERE id_caja NOT IN (SELECT id_caja FROM aperturacierre WHERE estado = 'ABIERTA')");
$cajeros = consultas::get_datos("SELECT * FROM v_empleado WHERE id_empleado NOT IN (SELECT id_empleado FROM aperturacierre WHERE estado = 'ABIERTA')");

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
        <input type="text" class="form-control" readonly value="<?php echo $apertura[0]['fecha_apertura'] ?>">
    </div>
    <div class="form-group col-md-3">
        <label>Hora</label>
        <input type="text" class="form-control" readonly value="<?php echo $apertura[0]['hora_apertura'] ?>">
    </div>
</div>

<div class="form-group col-md-12">
    <label>Caja</label>
    <select class="form-control select2" name="vidcaja" required style="width: 100%;">
        <option value="<?php echo $apertura[0]['id_caja'] ?>" selected><?php echo $apertura[0]['caja'] ?></option>
        <?php
        foreach ($cajas as $caj) {
        ?>
            <option value="<?php echo $caj['id_caja'] ?>"><?php echo $caj['descripcion']; ?></option>
        <?php } ?>

    </select>
</div>

<div class="form-group col-md-12">
    <label>Cajero</label>
    <select class="form-control select2" name="vidcajero" required style="width: 100%;">
        <option value="<?php echo $apertura[0]['id_empleado'] ?>" selected><?php echo $apertura[0]['persona_corta'] ?></option>
        <?php
        foreach ($cajeros as $cjr) {
        ?>
            <option value="<?php echo $cjr['id_empleado'] ?>"><?php echo $cjr['persona_corta']; ?></option>
        <?php
        } ?>
    </select>
</div>
<div class="form-group col-md-12">
    <label>Monto Inicial</label>
    <input type="number" class="form-control" min="1" name="vmontoinicial" required onKeyDown="if(this.value.length==9 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?php echo $apertura[0]['monto_inicial'] ?>">
</div>

<input type="hidden" name="vidaperturacierre" value="<?php echo $apertura[0]['id_aperturacierre'] ?>">