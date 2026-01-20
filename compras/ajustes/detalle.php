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
$idajustestock = $_REQUEST['vidajustestock'];
$ajustestock = consultas::get_datos("SELECT * FROM V_ajustestock WHERE id_ajustestock =  $idajustestock");
$detalle = consultas::get_datos("SELECT * FROM V_ajustestock_det WHERE id_ajustestock = $idajustestock");



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
                                <i class="fa fa-cubes"></i>
                                <h3 class="box-title">Ajustes de Stock</h3>
                                <div class="box-tools">
                                    <a class="btn btn-primary pull-right btn-sm" onclick="history.back()" role="button" data-title="Volver" data-placement="top" rel="tooltip">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                </div>
                            </div>
                            <!--- FIN HEADER --->
                            <!--- INICIO BODY --->
                            <div class="box-body">
                                <div class="form-group col-md-2">
                                    <label>Codigo</label>
                                    <input type="text" class="form-control" value="<?php echo $ajustestock[0]['id_ajustestock'] ?>" readonly>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Ajuste N°</label>
                                    <input type="text" class="form-control" value="<?php echo $ajustestock[0]['nro_ajuste_larga'] ?>" readonly>
                                </div>

                                <div class="form-group col-md-2">
                                    <label>Fecha</label>
                                    <input type="text" class="form-control" value="<?php echo $ajustestock[0]['fecha_corta'] ?>" readonly>
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Estado</label>
                                    <input type="text" class="form-control" value="<?php echo $ajustestock[0]['estado'] ?>" readonly>
                                </div>





                            </div>
                            <!--- FIN BODY --->
                        </div>
                        <!--- FIN BOX --->
                        <!--- FIN CABEZERA --->

                        <!--- INICIO DETALLES --->
                        <!--- INICIO BOX --->
                        <div class="box box-success">
                            <!--- INICIO HEADER --->
                            <div class="box-header with-border text-center ">
                                <h3 class="box-title text-bold">DETALLES</h3>
                                <?php if ($ajustestock[0]['estado'] == 'PENDIENTE') { ?>
                                    <div class="box-tools">
                                        <button type="button" class="btn btn btn-success text-bold" data-toggle="modal" data-target="#agregar_detalle">Agregar</button>
                                    </div>
                                <?php } ?>
                            </div>
                            <!--- FIN HEADER --->
                            <!--- INICIO BODY --->
                            <div class="box-body">
                                <?php if (!empty($detalle)) { ?>

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Producto</th>
                                                <th class="text-center">Cantidad Anterior</th>
                                                <th class="text-center">Cantidad Actual</th>
                                                <th class="text-center">Motivo</th>
                                                <?php if ($ajustestock[0]['estado'] == 'PENDIENTE') { ?>
                                                    <th class="text-center">Acciones</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($detalle as $det) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $det['materiaprima'] ?></td>
                                                    <td class="text-center"><?php echo number_format($det['cant_anterior'], 0, ',', '.') ?></td>
                                                    <td class="text-center"><?php echo number_format($det['cant_actual'], 0, ',', '.') ?></td>
                                                    <td class="text-center"><?php echo $det['motivoajuste'] ?></td>
                                                    <?php if ($ajustestock[0]['estado'] == 'PENDIENTE') { ?>
                                                        <?php $materiaprimacod = $det['id_materiaprima']; ?>
                                                        <th class="text-center">
                                                            <a onclick="modificar('<?php echo  $idajustestock . '_' . $materiaprimacod ?>')" class="btn btn-sm btn-warning" role="button" data-title="Editar Cantidad" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modificar_detalle">
                                                                <span class="fa fa-pencil"></span>
                                                            </a>
                                                            <a onclick="borrar('<?php echo  $idajustestock . '_' . $materiaprimacod ?>')" class="btn btn-sm btn-danger" role="button" data-title="Borrar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modalBorrar">
                                                                <span class="glyphicon glyphicon-trash"></span>
                                                            </a>
                                                        </th>
                                                    <?php } ?>

                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                <?php } else { ?>
                                    <div class="alert alert-info flat">
                                        <span class="glyphicon glyphicon-info-sign"></span>
                                        No existen ninguna datos en el detalle
                                    </div>
                                <?php } ?>
                            </div>
                            <!--- FIN BODY --->
                        </div>
                        <!--- FIN BOX --->

                        <!--- INICIO OBSERVACION -->
                        <div class="box box-success">
                            <!--- INICIO HEADER --->
                            <div class="box-header with-border">
                                <h3 class="box-title text-bold">Observacion</h3>
                            </div>
                            <!--- FIN HEADER --->
                            <!--- INICIO BODY --->
                            <div class="box-body">
                                <div class="form-group">
                                    <textarea class="form-control" rows="3" id="ob1" disabled><?php echo $ajustestock[0]['observacion'] ?></textarea>
                                </div>
                            </div>
                            <!--- FIN BODY --->
                        </div>
                        <!--- FIN OBSERVACION -->

                        <!--- FIN DETALLES --->

                        <!--- INICIO BOTONES --->
                        <!--- INICIO BOX --->
                        <div class="box">
                            <div class="box-header with-border text-center">

                                <?php if ($ajustestock[0]['estado'] == 'PENDIENTE') {
                                    if (empty($detalle)) { ?>
                                        <a class="btn btn-success text-bold" disabled>Confirmar</a>
                                    <?php } else { ?>
                                        <a class="btn btn-success text-bold" data-toggle="modal" data-target="#confirmar_ajuste">Confirmar</a>
                                    <?php } ?>
                                    <a class="btn btn-warning text-bold" data-toggle="modal" data-target="#modificar_ajuste">Modificar Cabecera</a>
                                    <?php ?>


                                <?php } ?>

                                <?php if ($ajustestock[0]['estado'] == 'CONFIRMADO' or $ajustestock[0]['estado'] == 'FINALIZADO') { ?>
                                    <a href="#" class="btn btn-danger text-bold" data-toggle="modal" data-target="#anular_ajuste">Anular</a>
                                    <a href="imprimir.php?vidajustestock=<?php echo $idajustestock ?>" class="btn btn-primary text-bold">Imprimir</a>
                                <?php } ?>

                            </div>
                        </div>
                        <!--- FIN BOX --->
                        <!--- FIN BOTONES --->
                    </div>

                </div>

            </div>

        </div>
        <!--- FIN CONTENIDO --->
        <!--- INICIO MODAL --->
        <!-- INICIO MODAL CONFIRMAR AJUSTE -->
        <div class="modal fade" id="confirmar_ajuste" tabindex="-1" role="dialog" aria-labelledby="ConfirmarAjuste" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Confirmar Ajuste</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="POST" accept-charset="UTF-8">
                            <input type="hidden" value="<?php echo $idajustestock; ?>" name="vidajustestock">
                            <input type="hidden" value="2" name="operacion">
                            <h3 class="text-center">¿Deseas confirmar el ajuste de stock?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Si</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL CONFIRMAR AJUSTE DE STOCK -->

        <!--- INICIO MODIFICAR AJUSTE --->
        <div class="modal fade" id="modificar_ajuste" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Modificar</strong></h4>
                    </div>

                    <form action="control.php" method="post" accept-charset="UTF-8">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="3">
                            <input type="hidden" value="<?php echo $idajustestock; ?>" name="vidajustestock">
                            <div class="row">
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Observación</label>
                                        <textarea class="form-control" rows="3" name="vobservacion"><?php echo $ajustestock[0]['observacion'] ?></textarea>
                                    </div>
                                </div>

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
        <!--- FIN MODIFICAR AJUSTE --->


        <!-- INICIO MODAL ANULAR AJUSTE DE STOCK -->
        <div class="modal fade" id="anular_ajuste" tabindex="-1" role="dialog" aria-labelledby="AnularAjuste" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Anular Ajuste</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="POST" accept-charset="UTF-8">
                            <input type="hidden" value="<?php echo $idajustestock; ?>" name="vidajustestock">
                            <input type="hidden" value="4" name="operacion">
                            <h3 class="text-center">¿Deseas anular el ajuste de stock?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-danger">Si</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL ANULAR AJUSTE DE STOCK -->

        <!-- INICIO MODAL AGREGAR DETALLE -->
        <?php
        $materiaprima = consultas::get_datos("SELECT * FROM v_materiasprimas WHERE id_materiaprima NOT IN (SELECT id_materiaprima FROM v_ajustestock_det WHERE id_ajustestock =  $idajustestock) AND estado = 'ACTIVO'");
        $motivo = consultas::get_datos("SELECT * FROM motivoajuste WHERE estado = 'ACTIVO'");

        ?>
        <div class="modal fade" id="agregar_detalle" role="dialog" aria-labelledby="AgregarDetalle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Agregar Producto</strong></h4>
                    </div>
                    <form action="control_detalle.php" method="post" accept-charset="UTF-8">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="1">
                            <input type="hidden" name="vidajustestock" value="<?php echo $idajustestock ?>">
                            <div class="form-group">
                                <label>Producto</label>
                                <select class="form-control select2" style="width: 100%;" name="vmateriaprima" id="cbx_materiaprima" required>
                                    <?php if (!empty($materiaprima)) { ?>
                                        <option value="" disabled="" selected="">Seleccione una Producto</option>
                                        <?php
                                        foreach ($materiaprima as $ma) {
                                        ?>
                                            <option value="<?php echo $ma['id_materiaprima']; ?>"><?php echo $ma['descripcion']; ?></option>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <option value="">No existe ningún producto</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Motivo</label>
                                <select class="form-control select2" style="width: 100%;" name="vmotivo" id="cbx_materiaprima" required>
                                    <?php if (!empty($motivo)) { ?>
                                        <option value="" disabled="" selected="">Seleccione un motivo</option>
                                        <?php
                                        foreach ($motivo as $mo) {
                                        ?>
                                            <option value="<?php echo $mo['id_motivoajuste']; ?>"><?php echo $mo['descripcion']; ?></option>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <option value="">No existe ningún motivo</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div id="datos">
                                <div class="form-group">
                                    <label>Cantidad Anterior</label>
                                    <input type="number" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Cantidad A Ajustar</label>
                                    <input type="number" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Agregar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL AGREGAR DETALLE -->

        <!-- INICIO MODAL MODIFICAR DETALLE -->
        <div class="modal fade" id="modificar_detalle" role="dialog" aria-labelledby="ModificarDetalle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Modificar Producto</strong></h4>
                    </div>
                    <form action="control_detalle.php" method="post" accept-charset="UTF-8">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="2">
                            <input type="hidden" name="vidajustestock" value="<?php echo $idajustestock ?>">
                            <div class="form-group" id="modificar_cantidad">

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
        <!-- FIN MODAL MODIFICAR DETALLE -->

        <!-- INICIO MODAL DE BORRAR -->
        <div class="modal fade" id="modalBorrar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" arial-label="Close">X</button>
                        <h4 class="modal-title custom_align" id="Heading"><strong>Confirmar Borrado</strong></h4>
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
    $(document).ready(function() {
        $("#cbx_materiaprima").change(function() {
            $("#cbx_materiaprima option:selected").each(function() {
                id_materiaPrima = $(this).val();
                $.post("cargarDatos.php", {
                    id_materiaPrima: id_materiaPrima
                }, function(data) {
                    $("#datos").html(data);
                });
            });
        })
    });

    function modificar(datos) {
        var dat = datos.split("_");
        id_ajuste = dat[0];
        id_materiaprima = dat[1];
        $.post("obtenerModificar.php", {
            id_ajuste: id_ajuste,
            id_materiaprima: id_materiaprima,
        }, function(data) {
            $("#modificar_cantidad").html(data);
            calcularmontomodificar();
        });

    }

    function borrar(datos) {
        var dat = datos.split("_");
        $('#si').attr('href', 'control_detalle.php?vidajustestock=' + dat[0] + '&vmateriaprima=' + dat[1] + '&operacion=3');
        $('#confirmacion').html('<h3 class="text-center">¿Deseas borrar el producto del detalle?</h3>')
    }
</script>

</html>