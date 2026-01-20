<?php
require('../../conexion.php');
session_start();

$id_materiaprima = $_POST['id_materiaprima'];
$id_compras = $_POST['id_compras'];

$compras = consultas::get_datos("SELECT * FROM v_compras_det WHERE id_compras = $id_compras AND id_materiaprima = $id_materiaprima");

?>
<div class="form-group">
    <label>Precio Unitario</label>
    <input type="number" class="form-control" min="1" id="precio_agregar" name="vpreciounitario" readonly value="<?php echo $compras[0]['precio_unitario'] ?>">
</div>
<div class="form-group">
    <label>Cantidad a agregar</label>
    <input type="number" class="form-control" min="1" max="<?php echo $compras[0]['cantidad'] ?>" id="cant_agregar" name="vcantidad" required onKeyDown="if(this.value.length==9 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onchange="calcularmontoagregar()" onclick="calcularmontoagregar()" onkeyup="calcularmontoagregar()">
</div>