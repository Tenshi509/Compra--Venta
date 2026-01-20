<?php
require('../../conexion.php');


$ordentarjeta = $_POST['ordentarjeta'];
$idarqueo = $_POST['idarqueo'];



$datos = consultas::get_datos("SELECT * FROM arqueo_det_tarjetas WHERE id_arqueo = $idarqueo AND orden = $ordentarjeta");
$tipotarjetacod = $datos[0]['id_tipotarjeta'];
?>
<input type="hidden" name="vorden" value="<?php echo $ordentarjeta; ?>">

<div class="row">
    <div class="form-group col-xs-12">
        <?php $tipotarjeta = consultas::get_datos("SELECT * FROM tipotarjeta ORDER BY id_tipotarjeta = $tipotarjetacod DESC") ?>
        <label>Tipo de tarjeta</label>
        <select class="form-control" id="cbx_tipotarjeta" name="vtipotarjeta" required>
            <?php if (!empty($tipotarjeta)) { ?>
                <?php
                foreach ($tipotarjeta as $tj) {
                ?>
                    <option value="<?php echo $tj['id_tipotarjeta'] ?>"><?php echo $tj['descripcion']; ?></option>
                <?php
                }
            } else {
                ?>
                <option value="">No existe ningun tipo</option>
            <?php } ?>
        </select>
    </div>
</div>
<div class="row">
    <div class="form-group col-xs-12">
        <label>N° de Comprobante</label>
        <input type="text" class="form-control" name="vnrocomprobante" maxlength="100" value="<?php echo $datos[0]['nro_comprobante'] ?>" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 15 && event.keyCode != 8) return false;" value="<?php echo $datos[0]['monto'] ?>">
    </div>
    <div class="form-group col-xs-12">
        <label>Monto</label>
        <input type="text" class="form-control" name="vmonto" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 15 && event.keyCode != 8) return false;" value="<?php echo $datos[0]['monto'] ?>">
    </div>
</div>