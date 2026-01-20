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
$ultimocodigo = consultas::get_datos("SELECT COALESCE(MAX(id_ordencompra),0)+1 AS ultimo FROM ordencompra");
$ultimonumero = consultas::get_datos("SELECT to_char(COALESCE(MAX(nro_orden),0)+1, 'FM0000000'::TEXT) AS uorden FROM ordencompra");
$proveedor = consultas::get_datos("SELECT * FROM v_proveedor WHERE estado = 'ACTIVO' ORDER BY id_proveedor ASC");



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
                                <i class="fa fa-exchange"></i>
                                <h3 class="box-title">Orden de Compra</h3>
                                <div class="box-tools">
                                    <a class="btn btn-primary pull-right btn-sm" onclick="history.back()" role="button" data-title="Volver" data-placement="top" rel="tooltip">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                </div>
                            </div>
                            <!--- FIN HEADER --->
                            <form action="control.php" method="post">
                                <!--- INICIO BODY --->
                                <div class="box-body">
                                    <div class="form-row">
                                        <input type="hidden" value="1" name="operacion">
                                        <div class="form-group col-md-3">
                                            <label>Codigo</label>
                                            <input type="text" class="form-control" value="<?php echo $ultimocodigo[0]['ultimo'] ?>" readonly name="vcodigo">
                                        </div>
                                        <div class="form-group col-md-1"></div>

                                        <div class="form-group col-md-4">
                                            <label>Orden de Compra N°</label>
                                            <input type="text" class="form-control" value="<?php echo $ultimonumero[0]['uorden'] ?>" readonly>
                                        </div>

                                        <div class="form-group col-md-1"></div>

                                        <div class="form-group col-md-3">
                                            <label>Estado</label>
                                            <input type="text" class="form-control" value="PENDIENTE" readonly>
                                        </div>
                                    </div>


                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Proveedor</label>
                                            <select class="form-control select2" name="vproveedor" required id="proveedor" onclick="asociarPresupuesto()" onchange="asociarPresupuesto()">
                                                <?php if (!empty($proveedor)) { ?>
                                                    <option value="" disabled selected>Seleccione una opción</option>
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

                                    <!---INICIO ASOCIAR PRESUPUESTO --->
                                    <div id="box_presupuesto">

                                    </div>
                                    <!---FIN ASOCIAR PRESUPUESTO --->

                                    <div class="form-row">
                                        <div class="form-group col-md-12"> </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label>Observación</label>
                                                <textarea class="form-control" rows="3" name="vobservacion"></textarea>
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
                                        <button type="submit" class="btn btn-success">Confirmar</button>
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
    function asociarPresupuesto() {
        $(document).ready(function() {
            $("#proveedor option:selected").each(function() {
                codigo = $(this).val();
                $.post("cargarPresupuesto.php", {
                    codigo: codigo
                }, function(data) {
                    $("#box_presupuesto").html(data);
                });
            });
        });
    }
</script>

</html>