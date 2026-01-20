<?php
require('../../conexion.php');

$cod = $_POST['codigo'];
if (!empty($cod)) {
    $cod = $_POST['codigo'];
} else {
    $cod = 0;
}
$factura = consultas::get_datos("SELECT * FROM v_compras WHERE id_proveedor =  $cod AND estado = 'CONFIRMADO' AND concepto = 'CON REMISIÓN' ORDER BY id_compras ASC");

?>


<div class="form-row">
    <div class="form-group col-md-5">
        <label>Factura N°</label>
        <select class="form-control select2" name="vidcompra" id="cbx_factura" required>

            <?php if (!empty($factura)) {
            ?>
                <option value="" disabled selected>Seleccione una opción</option>
                <?php
                foreach ($factura as $pro) {
                ?>
                    <option value="<?php echo $pro['id_compras'] ?>"><?php echo $pro['nro_factura']; ?></option>
                <?php
                }
            } else {

                ?>
                <option value="">No existe ninguna factura</option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group col-md-2">
        <a class="btn btn-info text-bold <?php echo $desactivado ?>" onclick="javascript:facturaDetalle()">Ver/Ocultar Detalle</a>
    </div>
</div>

<div class="form-row" id="detalles" style="display:block">

</div>

<script>
    $(".select2").select2();

    $(document).ready(function() {
        $("#cbx_factura").change(function() {
            $("#cbx_factura option:selected").each(function() {
                id_compras = $(this).val();
                $.post("mostrarDetalles.php", {
                    id_compras: id_compras
                }, function(data) {
                    $("#detalles").html(data);
                });
            });
        })
    });

    function facturaDetalle() {
        $("#cbx_factura option:selected").each(function() {
            id_compras = $(this).val();
            $.post("mostrarDetalles.php", {
                id_compras: id_compras
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
</script>