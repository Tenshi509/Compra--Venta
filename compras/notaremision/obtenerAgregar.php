<?php
require('../../conexion.php');
session_start();

$id_materiaprima = $_POST['id_materiaprima'];
$id_compras = $_POST['id_compras'];
$id_notaremisioncompra = $_POST['id_notaremisioncompra'];

$compras = consultas::get_datos("SELECT * FROM v_compras_det WHERE id_compras = $id_compras AND id_materiaprima = $id_materiaprima");
$remision = consultas::get_datos("SELECT * FROM v_notaremisioncompra_det WHERE id_notaremisioncompra = $id_notaremisioncompra AND id_materiaprima = $id_materiaprima");
$materiaprima = consultas::get_datos("SELECT * FROM v_materiasprimas WHERE id_materiaprima = $id_materiaprima");


?>
<div class="form-group">
    <label>Unidad de Medida</label>
    <input type="text" class="form-control" value="<?php echo  $materiaprima[0]['unidadmedida'];?>"  readonly>
</div>
<?php $cantidadfactura = $compras[0]['cantidad'];
if (!empty($cantidadfactura)) {
    $cantidadfactura = $compras[0]['cantidad'];
} else {
    $cantidadfactura = 0;
}

?>

<div class="form-group">
    <label>Cantidad en Factura</label>
    <input type="number" class="form-control" value="<?php echo $cantidadfactura ?>" readonly>
</div>

<div class="form-group">
    <label>Cantidad en Remision</label>
    <input type="number" class="form-control" name="vcantidad" value="<?php echo $cantidadfactura ?>" required onKeyDown="if(this.value.length==10 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
</div>