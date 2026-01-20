<?php
require('../../conexion.php');


$id_compras = $_POST['id_compras'];
if (!empty($id_compras)) {
    $id_compras = $_POST['id_compras'];
} else {
    $id_compras = 0;
}



$detalle = consultas::get_datos("SELECT * FROM v_compras_det WHERE id_compras = $id_compras");
$subtotal = consultas::get_datos("SELECT subtotal FROM v_compras WHERE id_compras = $id_compras");
$liquidacion = consultas::get_datos("SELECT SUM(iva10) AS diez, SUM(iva5) AS cinco FROM v_iva_compras WHERE id_compras = $id_compras");


if (!empty($detalle)) { ?>
    <h3 class="text-center text-bold col-md-12">Detalles</h3>
    <div class="col-xs-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Producto</th>
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
        <div class="form-group col-md-4">
            <label>Total Factura</label>
            <input type="text" class="form-control" value="<?php echo number_format($subtotal[0]['subtotal'], 0, ',', '.') ?>" readonly>
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
<div class="form-group col-md-4" id="grupo_descuento" >
    <label>Descuento (%)</label>
    <input type="number" 
           class="form-control" 
           id="descuento" 
           min="0" max="100" 
           value="0" 
           onchange="calcularTotalConDescuento()">
           
</div>

<div class="form-group col-md-4" id="grupo_total_descuento">
    <label>Total con Descuento</label>
    <input type="text" class="form-control" id="total_con_descuento" readonly>
</div>

    </div>
<?php } ?>