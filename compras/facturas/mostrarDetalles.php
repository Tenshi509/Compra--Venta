<?php
require('../../conexion.php');


$id_ordencompra = $_POST['id_ordencompra'];
if (!empty($id_ordencompra)) {
    $id_ordencompra = $_POST['id_ordencompra'];
} else {
    $id_ordencompra = 0;
}



$detalle = consultas::get_datos("SELECT * FROM v_ordencompra_det WHERE id_ordencompra = $id_ordencompra");
$subtotal = consultas::get_datos("SELECT subtotal FROM v_ordencompra WHERE id_ordencompra = $id_ordencompra");
$liquidacion = consultas::get_datos("SELECT SUM(iva10) AS diez, SUM(iva5) AS cinco FROM v_iva_ordencompra WHERE id_ordencompra = $id_ordencompra");



if (!empty($detalle)) { ?>
    <h3 class="text-center text-bold col-md-12">Detalles</h3>
    <div class="col-xs-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Materia Prima</th>
                    <th class="text-center">Precio Unitario</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Tipo Impuesto</th>

                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($detalle as $det) { ?>
                    <tr>
                        <td class="text-center"><?php echo number_format($det['cantidad'], 0, ',', '.') ?></td>
                        <td class="text-center"><?php echo $det['materiaprima'] ?></td>
                        <td class="text-center"><?php echo number_format($det['precio_unitario'], 0, ',', '.') ?></td>
                        <td class="text-center"><?php echo number_format($det['subtotal'], 0, ',', '.') ?></td>
                        <td class="text-center"><?php echo $det['tipoimpuesto'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="form-row">
        <div class="form-group">
            <div class="form-group col-md-4">
                <label for="cantcuota">Total Orden de Compra</label>
                <input type="text" class="form-control" value="<?php echo number_format($subtotal[0]['subtotal'], 0, ',', '.'); ?>" readonly>
            </div>
            <div class="form-group col-md-2">
                <label>IVA 10%</label>
                <input type="text" class="form-control" value="<?php echo number_format($liquidacion[0]['diez'], 0, ',', '.') ?>" readonly>
            </div>
            <div class="form-group col-md-2">
                <label>IVA 5%</label>
                <input type="text" class="form-control" value="<?php echo number_format($liquidacion[0]['cinco'], 0, ',', '.') ?>" readonly>
            </div>

            <div class="form-group col-md-4">
                <label>Total IVA</label>
                <input type="text" class="form-control" value="<?php echo number_format($liquidacion[0]['diez'] + $liquidacion[0]['cinco'], 0, ',', '.') ?>" readonly>
            </div>
        </div>
    </div>
<?php } ?>