<?php
require('../../conexion.php');

$id_materiaPrima = $_POST['id_materiaPrima'];

$materiaprima = consultas::get_datos("SELECT * FROM stock_materiasprimas WHERE id_materiaprima =  $id_materiaPrima");

?>


<div class="form-group">
    <label>Cantidad Anterior</label>
    <input type="number" class="form-control" min="1" name="vcantidadanterior" readonly value="<?php echo $materiaprima[0]['cantidad'] ?>">
</div>
<div class="form-group">
    <label>Cantidad A Ajustar</label>
    <input type="number" class="form-control" min="1" name="vcantidadajustada" required onKeyDown="if(this.value.length==9 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
</div>