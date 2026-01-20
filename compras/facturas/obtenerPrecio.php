<?php
require('../../conexion.php');
session_start();

$id_materiaprima = $_POST['id_materiaprima'];


$materiaprima = consultas::get_datos("SELECT * FROM v_materiasprimas WHERE id_materiaprima = $id_materiaprima");

?>
<div class="form-group">
    <label>Precio Unitario</label>
    <input type="number" class="form-control" min="1" id="precio_agregar" name="vpreciounitario"  value="<?php echo $materiaprima[0]['precio_compra'] ?>" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onchange="calcularmontoagregar()" onclick="calcularmontoagregar()" onkeyup="calcularmontoagregar()">
</div>