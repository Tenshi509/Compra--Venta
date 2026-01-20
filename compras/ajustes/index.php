<?php
session_start();
if ($_SESSION == NULL) {
    $_SESSION['error'] = 'Debes iniciar sesión para usar el sistema';
    header('location:/nova/');
}
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
$consultarajustes = consultas::get_datos("SELECT * FROM v_ajustestock ORDER BY id_ajustestock DESC");
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

                        <!--- INICIO BOX --->
                        <div class="box box-primary">
                            <!--- INICIO HEADER --->
                            <div class="box-header with-border">
                                <i class="fa fa-cubes"></i>
                                <h3 class="box-title">Ajustes de Stock</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success text-bold" role="button" data-toggle="modal" data-target="#registrar_ajuste">Registrar Ajustes de Stock</a>
                                </div>
                            </div>
                            <!--- FIN HEADER --->
                            <!--- INICIO BODY --->
                            <div class="box-body">
                                <table class="table table-bordered table-striped es-tb">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Fecha</th>
                                            <th class="text-center">Ajuste N°</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($consultarajustes)) {
                                            foreach ($consultarajustes as $c) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $c['id_ajustestock'] ?></td>
                                                    <td class="text-center"><?php echo $c['fecha_corta'] ?></td>
                                                    <td class="text-center"><?php echo $c['nro_ajuste_larga'] ?></td>
                                                   
                                                    <td class="text-center"><?php echo $c['estado'] ?></td>
                                                    <td class="text-center">
                                                        <a href="detalle.php?vidajustestock=<?php echo $c['id_ajustestock']; ?>" class="btn btn-primary btn-sm" role="button" data-title="Ver Detalle" rel="tooltip" data-placement="top">
                                                            <span class="fa fa-list"></span>
                                                        </a>
                                                    </td>

                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>

                                </table>

                            </div>
                            <!--- FIN BODY --->
                        </div>
                        <!--- FIN BOX --->
                    </div>

                </div>

            </div>

        </div>
        <!--- FIN CONTENIDO --->
        <!--- INICIO MODAL --->
        <!--- INICIO REGISTRAR PEDIDO --->
        <div class="modal fade" id="registrar_ajuste" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Registrar</strong></h4>
                    </div>

                    <form action="control.php" method="post" accept-charset="UTF-8">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="1">
                            <div class="row">
                                <input type="hidden" value="1" name="operacion">
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Observación</label>
                                        <textarea class="form-control" rows="3" name="vobservacion"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Registrar</button>
                        </div>
                    </form>

                </div>

            </div>

        </div>
        <!--- FIN REGISTRAR PEDIDO --->


        <!--- FIN MODAL --->
    </div>
    <?php require '../../estilos/pie.ctp' ?>
</body>
<?php
require '../../estilos/js_lte.ctp';
require '../../estilos/js_creado.ctp';
?>


</html>