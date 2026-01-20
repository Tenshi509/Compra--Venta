<?php
require('../../conexion.php');


$idbancocheque = $_POST['idbancocheque'];
$ordencheque = $_POST['ordencheque'];
$idarqueo = $_POST['idarqueo'];



$datos = consultas::get_datos("SELECT * FROM arqueo_det_cheques WHERE id_arqueo = $idarqueo AND orden = $ordencheque");
$bancos = consultas::get_datos("SELECT * FROM bancos ORDER BY id_banco = $idbancocheque DESC")
?>
<input type="hidden" name="vorden" value="<?php echo $ordencheque; ?>">
<div class="form-group col-xs-12">
    <label>Banco</label>
    <select class="form-control select2" name="vbanco" style="width: 100%;" id="cbx_bancos_editar" required>
        <?php if (!empty($bancos)) { ?>
            <?php
            foreach ($bancos as $ban) {
            ?>
                <option value="<?php echo $ban['id_banco'] ?>"><?php echo $ban['descripcion']; ?></option>
            <?php
            }
        } else {
            ?>
            <option value="">No existe ningun banco</option>
        <?php } ?>
    </select>
</div>


<div class="form-group col-xs-12">
    <?php
    $idchequerecibido = $datos[0]['id_tipocheque'];
    $tipocheque = consultas::get_datos("SELECT * FROM tipocheque ORDER BY id_tipocheque = $idchequerecibido DESC") ?>
    <label>Tipo de cheque</label>
    <select class="form-control" id="cbx_tipocheque" name="vtipocheque" required>
        <?php if (!empty($tipocheque)) { ?>
            <?php
            foreach ($tipocheque as $tpch) {
            ?>
                <option value="<?php echo $tpch['id_tipocheque'] ?>"><?php echo $tpch['descripcion']; ?></option>
            <?php
            }
        } else {
            ?>
            <option value="">No existe ningun tipo</option>
        <?php } ?>
    </select>
</div>
<div class="form-group col-xs-12">
    <label>N° de Cheque</label>
    <input type="text" class="form-control" name="vnrocheque" maxlength="100" value="<?php echo $datos[0]['nro_cheque'] ?>" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 15 && event.keyCode != 8) return false;">
</div>
<div class="form-group col-xs-12">
    <label>Monto</label>
    <input type="text" class="form-control" name="vmonto" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 15 && event.keyCode != 8) return false;" value="<?php echo $datos[0]['monto'] ?>">
</div>