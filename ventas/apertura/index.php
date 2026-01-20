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

$ultimocodigo = consultas::get_datos("SELECT COALESCE(MAX(id_aperturacierre),0)+1 AS ultimo FROM aperturacierre");
$ultimonumero = consultas::get_datos("SELECT to_char(COALESCE(MAX(nro_aperturacierre),0)+1, 'FM0000000'::TEXT) AS uorden FROM aperturacierre");

$cajas = consultas::get_datos("SELECT * FROM v_cajas WHERE id_caja NOT IN (SELECT id_caja FROM aperturacierre WHERE estado = 'ABIERTA')");
$cajeros = consultas::get_datos("SELECT * FROM v_empleado WHERE id_empleado NOT IN (SELECT id_empleado FROM aperturacierre WHERE estado = 'ABIERTA')");
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
                                <h3 class="box-title">Aperturas de Caja</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success text-bold" role="button" data-toggle="modal" data-target="#abrir_caja">Abrir Caja</a>
                                </div>
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
                                            <th class="text-center">Caja</th>
                                            <th class="text-center">Cajero</th>
                                            <th class="text-center">Monto Inicial</th>
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
                                                    <td class="text-center"><?php echo $c['caja'] ?></td>
                                                    <td class="text-center"><?php echo $c['persona_corta'] ?></td>
                                                    <td class="text-center"><?php echo number_format($c['monto_inicial'], 0, ',', '.') ?></td>
                                                    <td class="text-center"><?php echo $c['estado'] ?></td>
                                                    <td class="text-center">

                                                        <?php
                                                        $aperturacod = $c['id_aperturacierre'];
                                                        $verificarcorbos =  consultas::get_datos("SELECT * FROM cobros WHERE id_aperturacierre = $aperturacod");
                                                        $codapertura = $c['id_aperturacierre'];

                                                        if ($c['estado'] == 'ABIERTA' and empty($verificarcorbos)) { ?>
                                                               
                                                          
                                                        <?php } ?>

                                                        <?php if ($c['estado'] == 'ABIERTA' or $c['estado'] == 'CERRADA') { ?>
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

        <!--- INICIO MODAL ABRIR CAJA --->
        <div class="modal fade" id="abrir_caja" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Abrir Caja</strong></h4>
                    </div>

                    <form action="control.php" method="post" accept-charset="UTF-8">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="1">
                            <div class="row">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>ID</label>
                                        <input type="text" class="form-control" readonly value="<?php echo $ultimocodigo[0]['ultimo'] ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Apertura N°</label>
                                        <input type="text" class="form-control" readonly value="<?php echo $ultimonumero[0]['uorden'] ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Fecha</label>
                                        <input type="text" class="form-control" readonly value="<?php echo date("d/m/Y") ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Hora</label>
                                        <input type="text" class="form-control" readonly value="<?php echo date("H:i") ?>">
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Caja</label>
                                    <select class="form-control select2" name="vidcaja" required style="width: 100%;">
                                        <?php if (!empty($cajas)) { ?>
                                            <option value="" disabled selected>Seleccione una caja</option>
                                            <?php
                                            foreach ($cajas as $caj) {
                                            ?>
                                                <option value="<?php echo $caj['id_caja'] ?>"><?php echo $caj['descripcion']; ?></option>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <option value="">No existe ninguna caja disponible</option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Cajero</label>
                                    <select class="form-control select2" name="vidcajero" required style="width: 100%;">
                                        <?php if (!empty($cajeros)) { ?>
                                            <option value="" disabled selected>Seleccione un cajero</option>
                                            <?php
                                            foreach ($cajeros as $cjr) {
                                            ?>
                                                <option value="<?php echo $cjr['id_empleado'] ?>"><?php echo $cjr['persona_corta']; ?></option>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <option value="">No existe ningun cajero disponible</option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Monto Inicial</label>
                                    <input type="number" class="form-control" min="1" name="vmontoinicial" required onKeyDown="if(this.value.length==9 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Abrir</button>
                        </div>
                    </form>

                </div>

            </div>

        </div>
        <!--- FIN MODAL ABRIR CAJA --->

        <!-- INICIO MODAL MODIFICAR -->
        <div class="modal fade" id="modificar_apertura" role="dialog" aria-labelledby="ModificarApertura" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Modificar</strong></h4>
                    </div>
                    <form action="control.php" method="post" accept-charset="UTF-8">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="2">
                            <div class="row" id="obtener_datos">

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning">Modificar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL MODIFICAR -->

        <!-- INICIO MODAL DE BORRAR -->
        <div class="modal fade" id="anular_apertura" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" arial-label="Close">X</button>
                        <h4 class="modal-title custom_align" id="Heading"><strong>Anular</strong></h4>
                    </div>
                    <div class="modal-body">
                        <div id="confirmacion"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
                        <a id="si" role="button" class="btn btn-danger">Si</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN MODAL DE BORRAR -->



        <!--- FIN MODAL --->
    </div>
    <?php require '../../estilos/pie.ctp' ?>
</body>
<?php
require '../../estilos/js_lte.ctp';
require '../../estilos/js_creado.ctp';
?>
<script>
    function modificar(datos) {
        var dat = datos.split("_");
        id_aperturacierre = dat[0];
        $.post("obtenerDatos.php", {
            id_aperturacierre: id_aperturacierre,
        }, function(data) {
            $("#obtener_datos").html(data);
        });

    }

    function anular(datos) {
        var dat = datos.split("_");
        $('#si').attr('href', 'control.php?vidaperturacierre=' + dat[0] + '&operacion=3');
        $('#confirmacion').html('<h3 class="text-center">¿Deseas anular la apertura de caja?</h3>')
    }
</script>


</html>