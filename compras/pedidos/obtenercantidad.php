<?php
require('../../conexion.php');
session_start();

$id_materiaprima = $_POST['id_pedidocompra'];
$id_materiaprima = $_POST['id_materiaprima'];


$materiaprima = consultas::get_datos("SELECT * FROM v_materiasprimas WHERE id_materiaprima = $id_materiaprima");
$cantidad = consultas::get_datos("SELECT * FROM pedidoscompras_det WHERE id_pedidocompra = $id_pedidocompra AND id_materiaprima = $id_materiaprima");

?>

<div class="form-group col-xs-12">
    <label>Materia Prima</label>
    <input type="text" class="form-control" value="<?php echo $materiaprima[0]['descripcion'] ?>" readonly>
</div>

<p class="text-bold text-center">(Cantidad en Stock: <?php echo $materiaprima[0]['cantidad']; ?>)</p>

<div class="form-group col-xs-6">
    <label>Cantidad Actual</label>
    <input type="text" class="form-control" readonly value="<?php echo $cantidad[0]['cantidad'] ?>">
</div>
<div class="form-group col-xs-6">
    <label>Nuevo</label>
    <input type="number" name="vcantidad"  class="form-control" min="1" required id="cantidad" onKeyDown="if(this.value.length==9 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
</div>

<input type="hidden" name="vproducto" value="<?php echo $id_producto ?>" id="precio_compra">
