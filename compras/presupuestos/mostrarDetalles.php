<?php
require('../../conexion.php');


$id_pedido = $_POST['id_pedido'];
$detalle = consultas::get_datos("SELECT * FROM v_pedidoscompras_det WHERE id_pedidocompra = $id_pedido");

if (!empty($detalle)) { ?>
    <h3 class="text-center text-bold col-md-12">Detalles</h3>
    <div class="col-xs-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Unidad de Medida</th>
                    <th class="text-center">Materia Prima</th>
                    <th class="text-center">Tipo Impuesto</th>

                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($detalle as $det) { ?>
                    <tr>
                        <td class="text-center"><?php echo number_format($det['cantidad'], 0, ',', '.') ?></td>
                        <td class="text-center"><?php echo $det['unidadmedida'] ?></td>
                        <td class="text-center"><?php echo $det['materiaprima'] ?></td>
                        <td class="text-center"><?php echo $det['tipoimpuesto'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php
} else { ?>
    <h3 class="text-center">El pedido no tiene detalle</h3>
<?php } ?>