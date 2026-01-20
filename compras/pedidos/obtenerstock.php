<?php
require('../../conexion.php');
session_start();

$id_producto = $_POST['id_materiaprima'];


$materiaprima = consultas::get_datos("SELECT * FROM v_materiasprimas WHERE id_materiaprima = $id_producto");


?>

<p class="text-bold text-center">(Cantidad en Stock: <?php echo $materiaprima[0]['cantidad']; ?>)</p>

<div class="form-group col-xs-12">
    <label>Cantidad</label>
    <input type="number" name="vcantidad"  class="form-control" min="1" required id="cantidad" onKeyDown="if(this.value.length==9 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
</div>