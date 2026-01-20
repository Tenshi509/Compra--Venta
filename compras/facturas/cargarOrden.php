<?php
require('../../conexion.php');

$cod = $_POST['codigo'];
if (!empty($cod)) {
    $cod = $_POST['codigo'];
} else {
    $cod = 0;
}
$ordencompra = consultas::get_datos("SELECT * FROM v_ordencompra WHERE id_proveedor =  $cod AND estado = 'CONFIRMADO' ORDER BY id_ordencompra ASC");

?>


<div class="form-row">
    <div class="form-group col-md-6">
        <label>Orden de compra</label>
        <select class="form-control select2" name="vidordencompra" id="cbx_ordencompra" required>

            <?php if (!empty($ordencompra)) { ?>
                <option value="" disabled selected>Seleccione una opción</option>
                <?php
                foreach ($ordencompra as $pro) {
                ?>
                    <option value="<?php echo $pro['id_ordencompra'] ?>"><?php echo $pro['nro_orden_larga']; ?></option>
                <?php
                }
            } else {
                ?>
                <option value="">No existe ninguna orden de compra</option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-6">
        <a class="btn btn-info text-bold" onclick="javascript:ordenDetalle()">Ver/Ocultar Detalle</a>
    </div>
</div>

<div class="form-row" id="detalles" style="display:block">

</div>

<script>
    $(".select2").select2();

    $(document).ready(function() {
        $("#cbx_ordencompra").change(function() {
            $("#cbx_ordencompra option:selected").each(function() {
                id_ordencompra = $(this).val();
                $.post("mostrarDetalles.php", {
                    id_ordencompra: id_ordencompra
                }, function(data) {
                    $("#detalles").html(data);
                });
            });
        })
    });

    function ordenDetalle() {
        $("#cbx_ordencompra option:selected").each(function() {
            id_ordencompra = $(this).val();
            $.post("mostrarDetalles.php", {
                id_ordencompra: id_ordencompra
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