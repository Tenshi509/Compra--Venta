<?php
session_start();
if ($_SESSION == NULL) {
    $_SESSION['error'] = 'Debes iniciar sesión para usar el sistema';
    header('location:/graficanissei/');
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
$consultarapertura = consultas::get_datos("SELECT * FROM v_aperturacierre ORDER BY id_aperturacierre DESC");


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
                                <i class="fa fa-money"></i>
                                <h3 class="box-title">Cierre de Caja</h3>
                            </div>
                            <!--- FIN HEADER --->
                            <!--- INICIO BODY --->
                            <div class="box-body">
                                <table id="listadofactura" class="table table-bordered table-striped es-tb">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Apertura N°</th>
                                            <th class="text-center">Fecha Apertura</th>
                                            <th class="text-center">Hora Apertura</th>
                                            <th class="text-center">Fecha Cierre</th>
                                            <th class="text-center">Hora Cierre</th>
                                            <th class="text-center">Caja</th>
                                            <th class="text-center">Cajero</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($consultarapertura)) {
                                            foreach ($consultarapertura as $c) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $c['id_aperturacierre'] ?></td>
                                                    <td class="text-center"><?php echo $c['nro_aperturacierre_larga'] ?></td>
                                                    <td class="text-center"><?php echo $c['fecha_apertura_corta'] ?></td>
                                                    <td class="text-center"><?php echo $c['hora_apertura'] ?></td>
                                                    <td class="text-center"><?php echo $c['fecha_cierre_corta'] ?></td>
                                                    <td class="text-center"><?php echo $c['hora_cierre'] ?></td>
                                                    <td class="text-center"><?php echo $c['caja'] ?></td>
                                                    <td class="text-center"><?php echo $c['persona_corta'] ?></td>
                                                    <td class="text-center"><?php echo $c['estado'] ?></td>
                                                    <td class="text-center">

                                                        <?php
                                                        $aperturacod = $c['id_aperturacierre'];
                                                        $verificarcorbos =  consultas::get_datos("SELECT * FROM cobros WHERE id_aperturacierre = $aperturacod");
                                                        $codapertura = $c['id_aperturacierre'];

                                                        if ($c['estado'] == 'ABIERTA') { ?>
                                                            <a onclick="cerrar('<?php echo  $codapertura ?>')" class="btn btn-sm btn-danger" role="button" data-title="Cerrar Caja" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#cerrar_caja">
                                                                <span class="fa fa-ban"></span>
                                                            </a>
                                                        <?php } ?>

                                                        <?php if ($c['estado'] == 'CERRADA') { ?>
                                                            <a href="imprimir.php?vidaperturacierre=<?php echo $c['id_aperturacierre']; ?>" class="btn btn-primary btn-sm" role="button" data-title="Imprimir" rel="tooltip" data-placement="top">
                                                                <span class="fa fa-print"></span>
                                                            </a>
                                                        <?php } ?>

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

        <!-- INICIO MODAL CERRAR CAJA -->
        <div class="modal fade" id="cerrar_caja" role="dialog" aria-labelledby="CerrarCaja" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Cerrar Caja</strong></h4>
                    </div>
                    <form action="control.php" method="post" accept-charset="UTF-8">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="1">
                            <div class="row" id="obtener_datos">

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Cerrar Caja</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL CERRAR CAJA -->



        <!--- FIN MODAL --->
    </div>
    <?php require '../../estilos/pie.ctp' ?>
</body>
<?php
require '../../estilos/js_lte.ctp';
require '../../estilos/js_creado.ctp';
?>
<script>
    function cerrar(datos) {
        var dat = datos.split("_");
        id_aperturacierre = dat[0];
        $.post("obtenerDatos.php", {
            id_aperturacierre: id_aperturacierre,
        }, function(data) {
            $("#obtener_datos").html(data);
        });

    }
</script>


</html>