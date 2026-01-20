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
$consultarnotaremision = consultas::get_datos("SELECT * FROM v_notaremisioncompra
 ORDER BY id_notaremisioncompra DESC");
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
                                <i class="fa fa-truck"></i>
                                <h3 class="box-title">Nota de Remisión</h3>
                                <div class="box-tools">
                                    <a href="registrar.php" class="btn btn-success text-bold" >Registrar Nota de Remisión</a>
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
                                            <th class="text-center">Comprobante N°</th>
                                            <th class="text-center">Timbrado N°</th>
                                            <th class="text-center">Proveedor</th>
                                            <th class="text-center">RUC</th>
                                            <th class="text-center">Factura N°</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($consultarnotaremision)) {
                                            foreach ($consultarnotaremision as $nr) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $nr['id_notaremisioncompra'] ?></td>
                                                    <td class="text-center"><?php echo $nr['fecha_emision_corta'] ?></td>
                                                    <td class="text-center"><?php echo $nr['nro_comprobante'] ?></td>
                                                    <td class="text-center"><?php echo $nr['nro_timbrado'] ?></td>
                                                    <td class="text-center"><?php echo $nr['razon_social'] ?></td>
                                                    <td class="text-center"><?php echo $nr['nro_ruc'] ?></td>
                                                    <td class="text-center"><?php echo $nr['nro_factura'] ?></td>
                                                    <td class="text-center"><?php echo $nr['estado'] ?></td>
                                                    <td class="text-center">
                                                        <a href="detalle.php?vidnotaremisioncompra=<?php echo $nr['id_notaremisioncompra']; ?>" class="btn btn-primary btn-sm" role="button" data-title="Ver Detalle" rel="tooltip" data-placement="top">
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


        <!--- FIN MODAL --->
    </div>
    <?php require '../../estilos/pie.ctp' ?>
</body>
<?php
require '../../estilos/js_lte.ctp';
require '../../estilos/js_creado.ctp';
?>


</html>