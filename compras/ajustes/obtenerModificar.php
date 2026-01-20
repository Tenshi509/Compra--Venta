<?php
require('../../conexion.php');

$id_materiaPrima = $_POST['id_materiaprima'];
$id_ajuste = $_POST['id_ajuste'];

$materiaprima = consultas::get_datos("SELECT * FROM stock_materiasprimas WHERE id_materiaprima =  $id_materiaPrima");
$detalle = consultas::get_datos("SELECT * FROM v_ajustestock_det WHERE id_materiaprima =  $id_materiaPrima AND id_ajustestock = $id_ajuste");


?>

<div class="form-group">
    <label>Materia Prima</label>
    <input type="text" class="form-control" readonly value="<?php echo $detalle[0]['materiaprima'] ?>">
    <input type="hidden" name="vmateriaprima" value="<?php echo $detalle[0]['id_materiaprima'] ?>">
</div>
<div class="form-group">
    <label>Motivo</label>
    <input type="text" class="form-control" readonly value="<?php echo $detalle[0]['motivoajuste'] ?>">
    <input type="hidden" name="vmotivo" value="<?php echo $detalle[0]['id_motivoajuste'] ?>">
</div>
<div class="form-group">
    <label>Cantidad Anterior</label>
    <input type="number" class="form-control" min="1" name="vcantidadanterior" readonly value="<?php echo $materiaprima[0]['cantidad'] ?>">
</div>
<div class="form-group">
    <label>Cantidad A Ajustar</label>
    <input type="number" class="form-control" min="1" name="vcantidadajustada" required onKeyDown="if(this.value.length==9 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?php echo $detalle[0]['cant_actual'] ?>">
</div>