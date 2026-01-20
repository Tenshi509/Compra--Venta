<?php
require('../../conexion.php');

$codigo = $_REQUEST['vidpresupuestoproveedor'];
$cod =  $_REQUEST['codigo'];

if (!empty($codigo)) {
    $codigo = $_REQUEST['vidpresupuestoproveedor'];
} else {
    $codigo = 0;
}


$presupuestoproveedor = consultas::get_datos("SELECT * FROM v_presupuestoproveedor WHERE id_proveedor =  $cod AND estado = 'CONFIRMADO' ORDER BY id_presupuestoproveedor = $codigo DESC");

?>


<div class="form-row">
    <div class="form-group col-md-6">
        <label>Presupuesto del proveedor</label>
        <select class="form-control select2" name="vpresupuestoproveedor" id="cbx_presupuesto" required>

            <?php if (!empty($presupuestoproveedor)) { ?>
                <?php
                foreach ($presupuestoproveedor as $pro) {
                ?>
                    <option value="<?php echo $pro['id_presupuestoproveedor'] ?>"><?php echo $pro['nro_presupuesto']; ?></option>
                <?php
                }
            } else {
                ?>
                <option value="">No existe ningun presupuesto del proveedor</option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-6">
        <a class="btn btn-info text-bold" onclick="javascript:presupuestoDetalle()">Ver/Ocultar Detalle</a>
    </div>
</div>

<div class="form-row" id="detalles" style="display:block">

</div>

<script>
    $(".select2").select2();

    $(document).ready(function() {
        $("#cbx_presupuesto").change(function() {
            $("#cbx_presupuesto option:selected").each(function() {
                id_presupuesto = $(this).val();
                $.post("mostrarDetalles.php", {
                    id_presupuesto: id_presupuesto
                }, function(data) {
                    $("#detalles").html(data);
                });
            });
        })
    });

    function presupuestoDetalle() {
        $("#cbx_presupuesto option:selected").each(function() {
            id_presupuesto = $(this).val();
            $.post("mostrarDetalles.php", {
                id_presupuesto: id_presupuesto
            }, function(data) {
                $("#detalles").html(data);
            });
        });
        contenido = document.getElementById("detalles");
        if (contenido.style.display === "none") {
            contenido.style.display = 'block';
        } else {
            contenido.style.display = 'none';
        }
    }

    function pedidoDetalle() {
        contenido = document.getElementById("detalles");
        if (contenido.style.display === "none") {
            contenido.style.display = 'block';
        } else {
            contenido.style.display = 'none';
        }
    }
</script>