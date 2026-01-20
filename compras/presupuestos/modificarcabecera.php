<?php
session_start();
if ($_SESSION == NULL) {
    $_SESSION['error'] = 'Debes iniciar sesión para usar el sistema';
    header('location:/graficanissei/');
}
$fechaactual = date("Y-m-d");

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta content="width=devicewidth, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php
    include '../../conexion.php';
    require '../../estilos/css_lte.ctp';
    ?>
</head>

<?php // CONSULTAS EN BASE DE DATOS 
$idpresupuestoproveedor = $_REQUEST['vidpresupuestoproveedor'];
$presupuestoproveedor = consultas::get_datos("SELECT * FROM v_presupuestoproveedor WHERE id_presupuestoproveedor =  $idpresupuestoproveedor");
$idproveedor = $presupuestoproveedor[0]['id_proveedor'];
$proveedor = consultas::get_datos("SELECT * FROM v_proveedor WHERE estado = 'ACTIVO' ORDER BY id_proveedor =  $idproveedor DESC");
if (!empty($presupuestoproveedor[0]['id_pedidocompra'])) {
    $codigopedido = $presupuestoproveedor[0]['id_pedidocompra'];
} else {
    $codigopedido = 0;
}
$pedidocompra = consultas::get_datos("SELECT * FROM v_pedidoscompras ORDER BY id_pedidocompra = $codigopedido DESC");


?>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper" style="background-color:#1e282c; ">
        <?php require '../../estilos/cabecera.ctp'; ?>
        <?php require '../../estilos/izquierda.ctp'; ?>
        <!--- INICIO CONTENIDO --->
        <div class="content-wrapper">
            <div class="content">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <!--- MENSAJE --->
                        <?php if (!empty($_SESSION['mensaje'])) { ?>
                            <?php
                            $mensaje = explode("/_", $_SESSION['mensaje']);
                            if (($mensaje[0] == 'NOTICIA_')) {
                                $class = "success";
                            } else {
                                $class = "danger";
                            }
                            ?>
                            <div class="alert alert-<?= $class; ?>" role="alert" id="mensaje">
                                <i class="ion ion-information-circled"></i>
                                <?php
                                echo $mensaje[1];
                                $_SESSION['mensaje'] = '';
                                ?>
                            </div>
                        <?php } ?>
                        <!--- MENSAJE --->

                        <!--- INICIO CABEZERA --->
                        <!--- INICIO BOX --->
                        <div class="box box-primary">
                            <!--- INICIO HEADER --->
                            <div class="box-header with-border">
                                <i class="fa fa-pencil"></i>
                                <h3 class="box-title">Modificar Presupuesto</h3>
                            </div>
                            <!--- FIN HEADER --->
                            <form action="control.php" method="post">
                                <!--- INICIO BODY --->
                                <div class="box-body">
                                    <div class="form-row">
                                        <input type="hidden" value="3" name="operacion">
                                        <div class="form-group col-md-3">
                                            <label>Codigo</label>
                                            <input type="text" class="form-control" value="<?php echo $presupuestoproveedor[0]['id_presupuestoproveedor'] ?>" readonly name="vidpresupuestoproveedor">
                                        </div>
                                        <div class="form-group col-md-6"></div>
                                        <div class="form-group col-md-3">
                                            <label>Estado</label>
                                            <input type="text" class="form-control" value="PENDIENTE" readonly>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Proveedor</label>
                                            <select class="form-control select2" name="vproveedor" required>
                                                <?php if (!empty($proveedor)) { ?>
                                                    <?php
                                                    foreach ($proveedor as $pro) {
                                                    ?>
                                                        <option value="<?php echo $pro['id_proveedor'] ?>"><?php echo $pro['razon_social']; ?></option>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <option value="">No existe ningun proveedor</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label>Fecha</label>
                                            <input type="date" class="form-control" name="vfecha" required value="<?php echo $presupuestoproveedor[0]['fecha'] ?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Presupuesto N°</label>
                                            <input type="text" class="form-control" name="vnropresupuesto" required onKeyDown="if(this.value.length==20 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?php echo $presupuestoproveedor[0]['nro_presupuesto'] ?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Validez (Días)</label>
                                            <input type="number" class="form-control" name="vvalidez" required onKeyDown="if(this.value.length==4 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?php echo $presupuestoproveedor[0]['validez'] ?>">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-12"> </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label>Observación</label>
                                                <textarea class="form-control" rows="3" name="vobservacion"><?php echo $presupuestoproveedor[0]['observacion'] ?></textarea>
                                            </div>
                                        </div>

                                    </div>
                                    <!--- FIN BODY --->
                                </div>
                                <!--- FIN BOX --->
                                <!--- FIN CABEZERA --->



                                <!--- INICIO BOTONES --->
                                <!--- INICIO BOX --->
                                <div class="box">
                                    <div class="box-header with-border text-center">
                                        <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
                                        <button type="submit" class="btn btn-success">Guardar</button>
                                    </div>
                                </div>
                                <!--- FIN BOX --->
                                <!--- FIN BOTONES --->
                            </form>
                        </div>

                    </div>

                </div>

            </div>
            <!--- FIN CONTENIDO --->
            <!--- INICIO MODAL --->


            <!--- FIN MODAL --->
        </div>
        <?php require '../../estilos/pie.ctp' ?>
</body>
<?php
require '../../estilos/js_lte.ctp';
require '../../estilos/js_creado.ctp';
?>
<script>
    function asociarPedido() {
        contenido = document.getElementById("box_pedido");
        check = document.getElementById("check_pedido");
        if (check.checked) {
            $(document).ready(function() {
                $('#box_pedido').load('cargarPedidoModificar.php?vidpresupuestoproveedor=<?php echo $idpresupuestoproveedor ?>&vidpedidocompra=<?php echo $codigopedido ?>'); 
            });

        } else {
            contenido.innerHTML = ""
        }
    }

    window.onload = asociarPedido();
</script>

</html>