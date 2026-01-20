<?php
require('../../conexion.php');

$id_compras = $_POST['id_compras'];
if (!empty($id_compras)) {
    $id_compras = $_POST['id_compras'];
} else {
    $id_compras = 0;
}


$detalle = consultas::get_datos("SELECT * FROM v_compras_det WHERE id_compras = $id_compras");


if (!empty($detalle)) { ?>
    <h3 class="text-center text-bold col-md-12">Detalles</h3>
    <div class="col-xs-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Unidad de Medida</th>
                    <th class="text-center">Materia Prima</th>

                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($detalle as $det) { ?>
                    <tr>
                        <td class="text-center"><?php echo number_format($det['cantidad'], 0, ',', '.') ?></td>
                        <td class="text-center"><?php echo $det['unidadmedida'] ?></td>
                        <td class="text-center"><?php echo $det['materiaprima'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php } ?>