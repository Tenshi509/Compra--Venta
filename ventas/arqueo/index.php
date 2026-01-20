<?php
session_start();
if ($_SESSION == NULL) {
    $_SESSION['error'] = 'Debes iniciar sesión para usar el sistema';
    header('location:/nova/');
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
$arqueo = consultas::get_datos("SELECT * FROM v_arqueo ORDER BY fecha DESC");

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
                        <!-- CONSULTAR APERTURA CIERRE -->
                        <div class="box box-primary">
                            <!--- INICIO HEADER --->
                            <div class="box-header with-border">
                                <i class="fa fa-shopping-cart"></i>
                                <h3 class="box-title">Arqueo de Caja</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success text-bold" role="button" data-toggle="modal" data-target="#generar_arqueo">Generar Arqueo</a>
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
                                            <th class="text-center">Hora</th>
                                            <th class="text-center">Arqueo N°</th>
                                            <th class="text-center">Apertura N°</th>
                                            <th class="text-center">Total Efectivo</th>
                                            <th class="text-center">Total Cheque</th>
                                            <th class="text-center">Total Tarjeta</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($arqueo)) {
                                            foreach ($arqueo as $a) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $a['id_arqueo'] ?></td>
                                                    <td class="text-center"><?php echo $a['fecha_corta'] ?></td>
                                                    <td class="text-center"><?php echo $a['hora'] ?></td>
                                                    <td class="text-center"><?php echo $a['nro_arqueo_larga'] ?></td>
                                                    <td class="text-center"><?php echo $a['nro_aperturacierre_larga'] ?></td>
                                                    <td class="text-center"><?php echo number_format($a['total_efectivo'], 0, ',', '.') ?></td>
                                                    <td class="text-center"><?php echo number_format($a['total_cheque'], 0, ',', '.') ?></td>
                                                    <td class="text-center"><?php echo number_format($a['total_tarjeta'], 0, ',', '.') ?></td>
                                                    <td class="text-center"><?php echo number_format($a['total_general'], 0, ',', '.') ?></td>
                                                    <td class="text-center"><?php echo $a['estado'] ?></td>
                                                    <td class="text-center">
                                                        <a href="detalle.php?vidarqueo=<?php echo $a['id_arqueo']; ?>" class="btn btn-primary btn-sm" role="button" data-title="Ver Detalle" rel="tooltip" data-placement="top">
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
        <!--- INICIO GENERAR ARQUEO --->
        <div class="modal fade" id="generar_arqueo" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Generar Arqueo</strong></h4>
                    </div>

                    <form action="control.php" method="post" accept-charset="UTF-8">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="1">
                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group">
                                        <?php
                                        $aperturacaja = consultas::get_datos("SELECT * FROM v_aperturacierre WHERE estado = 'ABIERTA'") ?>
                                        <div class="form-group col-md-12">
                                            <label>Apertura N°</label>
                                            <select class="form-control select2" style="width: 100%;" name="vidaperturacierre" id="cbx_aperturacierre" required>
                                                <?php if (!empty($aperturacaja)) { ?>
                                                    <option value="" disabled="" selected="">Seleccione una apertura de caja</option>
                                                    <?php
                                                    foreach ($aperturacaja as $apt) {
                                                    ?>
                                                        <option value="<?php echo $apt['id_aperturacierre']; ?>"><?php echo $apt['nro_aperturacierre_larga'] . " (" . $apt['caja'] . ")" ?></option>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <option value="">No existe ningúna apertura de caja con estado abierta</option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Generar</button>
                        </div>
                    </form>

                </div>

            </div>

        </div>
        <!--- FIN GENERAR ARQUEO --->
        <!--- FIN MODAL --->
    </div>
    <?php require '../../estilos/pie.ctp' ?>
</body>
<?php
require '../../estilos/js_lte.ctp';
require '../../estilos/js_creado.ctp';
?>

<script>

</script>

</html>