<?php
require('../../conexion.php');
session_start();

$id_compras = $_POST['id_compras'];
$id_notadebitocompra = $_POST['id_notadebitocompra'];
$id_materiaprima = $_POST['id_materiaprima'];


$materiaprima = consultas::get_datos("SELECT * FROM v_materiasprimas WHERE id_materiaprima = $id_materiaprima");
$cantidad = consultas::get_datos("SELECT * FROM v_compras_det WHERE id_compras = $id_compras AND id_materiaprima = $id_materiaprima");
$notacredito = consultas::get_datos("SELECT * FROM v_notadebitocompra_det WHERE id_notadebitocompra = $id_notadebitocompra AND id_materiaprima = $id_materiaprima");

?>

<div class="form-group col-xs-12">
    <label>Materia Prima</label>
    <input type="text" class="form-control" value="<?php echo $materiaprima[0]['descripcion'] ?>" readonly>
    <input type="hidden" name="vmateriaprima" value="<?php echo $id_materiaprima ?>">
</div>

<div class="form-group col-xs-6">
    <label>Cantidad Actual</label>
    <input type="number" class="form-control" value="<?php echo $notacredito[0]['cantidad'] ?>" readonly>
</div>
<div class="form-group col-xs-6">
    <label>Nuevo</label>
    <input type="number" class="form-control"  min="1" value="<?php echo $notacredito[0]['cantidad'] ?>" name="vcantidad" required id="cant_modificar" onKeyDown="if(this.value.length==9 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onchange="calcularmontomodificar()" onclick="calcularmontomodificar()" onkeyup="calcularmontomodificar()">
</div>
<div class="form-group col-xs-12">
    <label>Precio Unitario</label>
    <input type="number" class="form-control" min="1" value="<?php echo $cantidad[0]['precio_unitario'] ?>" id="precio_modificar" name="vpreciounitario" required onKeyDown="if(this.value.length==9 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onchange="calcularmontomodificar()" onclick="calcularmontomodificar()" onkeyup="calcularmontomodificar()" readonly>
</div>
<div class="form-group col-xs-12">
    <label>Total</label>
    <input type="text" class="form-control" id="subtotal_modificar" readonly>
</div>