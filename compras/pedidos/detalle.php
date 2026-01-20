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
$idpedidocompra = $_REQUEST['vidpedidocompra'];
$consultarpedidoscompras = consultas::get_datos("SELECT * FROM v_pedidoscompras WHERE id_pedidocompra = $idpedidocompra");
$detalle = consultas::get_datos("SELECT * FROM v_pedidoscompras_det WHERE id_pedidocompra = $idpedidocompra");



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
                                <i class="fa fa-shopping-cart"></i>
                                <h3 class="box-title">Pedidos de Compras</h3>
                                <div class="box-tools">
                                    <a class="btn btn-primary pull-right btn-sm" onclick="history.back()" role="button" data-title="Volver" data-placement="top" rel="tooltip">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                </div>
                            </div>
                            <!--- FIN HEADER --->
                            <!--- INICIO BODY --->
                            <div class="box-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Fecha de Pedido</th>
                                            <th class="text-center">Pedido N°</th>
                                            <th class="text-center">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($consultarpedidoscompras)) {
                                            foreach ($consultarpedidoscompras as $c) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $c['id_pedidocompra'] ?></td>
                                                    <td class="text-center"><?php echo $c['fecha_corta'] ?></td>
                                                    <td class="text-center"><?php echo $c['nro_pedido_larga'] ?></td>
                                                    <td class="text-center"><?php echo $c['estado'] ?></td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>

                                </table>

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
                                <?php if ($c['estado'] == 'PENDIENTE' or $c['estado'] == 'MODIFICACION') { ?>
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
                                                <th class="text-center">Cantidad Disponible</th>
                                                <th class="text-center">Cantidad Solicitada</th>
                                                <th class="text-center">Unidad de Medida</th>
                                                <th class="text-center">Producto</th>
                                                <?php if ($c['estado'] == 'PENDIENTE' or $c['estado'] == 'MODIFICACION') { ?>
                                                    <th class="text-center">Acciones</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($detalle as $det) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo number_format($det['cantidad_disponible'], 0, ',', '.') ?></td>
                                                    <td class="text-center"><?php echo number_format($det['cantidad'], 0, ',', '.') ?></td>
                                                    <td class="text-center"><?php echo $det['unidadmedida'] ?></td>
                                                    <td class="text-center"><?php echo $det['materiaprima'] ?></td>
                                                    <?php if ($c['estado'] == 'PENDIENTE' or $c['estado'] == 'MODIFICACION') { ?>
                                                        <?php $materiaprimacod = $det['id_materiaprima']; ?>
                                                        <th class="text-center">
                                                            <a onclick="modificar('<?php echo  $idpedidocompra . '_' . $materiaprimacod ?>')" class="btn btn-sm btn-warning" role="button" data-title="Editar Cantidad" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modificar_detalle">
                                                                <span class="fa fa-pencil"></span>
                                                            </a>
                                                            <a onclick="borrar('<?php echo  $idpedidocompra . '_' . $materiaprimacod ?>')" class="btn btn-sm btn-danger" role="button" data-title="Borrar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modalBorrar">
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
                      
                            <!--- INICIO HEADER --->
                        
                            <!--- FIN HEADER --->
                            <!--- INICIO BODY --->
                         
                            <!--- FIN BODY --->
                        
                        <!--- FIN OBSERVACION -->

                        <!--- FIN DETALLES --->

                        <!--- INICIO BOTONES --->
                        <!--- INICIO BOX --->
                        <div class="box">
                            <div class="box-header with-border text-center">

                                <?php if ($c['estado'] == 'PENDIENTE' or $c['estado'] == 'MODIFICACION') {
                                    if (empty($detalle)) { ?>
                                        <a class="btn btn-success text-bold" disabled>Confirmar</a>
                                    <?php } else { ?>
                                        <a class="btn btn-success text-bold" data-toggle="modal" data-target="#confirmar_pedido" onclick="copiar()">Confirmar</a>
                                    <?php }

                                    ?>


                                <?php } ?>

                                <?php if ($c['estado'] == 'CONFIRMADO') { ?>
                                    <a href="#" class="btn btn-warning text-bold" data-toggle="modal" data-target="#modificar_pedido">Modificar Pedido</a>
                                    <a href="#" class="btn btn-danger text-bold" data-toggle="modal" data-target="#anular_pedido">Anular</a>
                                    <a href="imprimir.php?vidpedidocompra=<?php echo $idpedidocompra ?>" class="btn btn-primary text-bold">Imprimir</a>
                                <?php } ?>

                                <?php if ($c['estado'] == 'FINALIZADO' OR $c['estado'] == 'PRESUPUESTADO') { ?>
                                    <a href="imprimir.php?vidpedidocompra=<?php echo $idpedidocompra ?>" class="btn btn-primary text-bold" >Imprimir</a>
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
        <!-- INICIO MODAL CONFIRMAR PEDIDO -->
        <div class="modal fade" id="confirmar_pedido" tabindex="-1" role="dialog" aria-labelledby="ConfirmarPedido" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Confirmar Pedido</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="POST" accept-charset="UTF-8">
                            <input type="hidden" value="<?php echo $idpedidocompra; ?>" name="vidpedidocompra">
                            <input type="hidden" value="2" name="operacion">
                            <input type="hidden" name="vobservacion" id="ob2">
                            <h3 class="text-center">¿Deseas confirmar el pedido de compra?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Si</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL CONFIRMAR PEDIDO -->

        <!-- INICIO MODAL MODIFICAR PEDIDO -->
        <div class="modal fade" id="modificar_pedido" tabindex="-1" role="dialog" aria-labelledby="ModificarPedido" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Modificar Pedido</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="POST" accept-charset="UTF-8">
                            <input type="hidden" value="<?php echo $idpedidocompra; ?>" name="vidpedidocompra">
                            <input type="hidden" value="3" name="operacion">
                            <h3 class="text-center">¿Deseas modificar el pedido de compra?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Si</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL MODIFICAR PEDIDO -->

        <!-- INICIO MODAL ANULAR PEDIDO -->
        <div class="modal fade" id="anular_pedido" tabindex="-1" role="dialog" aria-labelledby="AnularPedido" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Anular Pedido</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="POST" accept-charset="UTF-8">
                            <input type="hidden" value="<?php echo $idpedidocompra; ?>" name="vidpedidocompra">
                            <input type="hidden" value="4" name="operacion">
                            <h3 class="text-center">¿Deseas anular el pedido de compra?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-danger">Si</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL ANULAR PEDIDO -->

        <!-- INICIO MODAL AGREGAR DETALLE -->
        <?php
        $materiaprima = consultas::get_datos("SELECT * FROM v_materiasprimas WHERE id_materiaprima NOT IN (SELECT id_materiaprima FROM v_pedidoscompras_det WHERE id_pedidocompra =  $idpedidocompra) AND estado = 'ACTIVO'");
        ?>
        <div class="modal fade" id="agregar_detalle" role="dialog" aria-labelledby="AgregarDetalle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Agregar producto</strong></h4>
                    </div>
                    <form action="control_detalle.php" method="post" accept-charset="UTF-8">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="1">
                            <input type="hidden" name="vidpedidocompra" value="<?php echo $idpedidocompra ?>">
                            <div class="form-group">
                                <label>Producto</label>
                                <select class="form-control select2" style="width: 100%;" name="vmateriaprima" id="cbx_materiaprima" required>
                                    <?php if (!empty($materiaprima)) { ?>
                                        <option value="" disabled="" selected="">Seleccione un producto</option>
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
                            <div class="row" id="stock_materia">

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
        <div class="modal fade" id="modificar_detalle" tabindex="-1" role="dialog" aria-labelledby="ModificarDetalle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Modificar Materia Prima</strong></h4>
                    </div>
                    <form action="control_detalle.php" method="post" accept-charset="UTF-8">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="2">
                            <input type="hidden" name="vidpedidocompra" value="<?php echo $idpedidocompra ?>">
                            <div class="row" id="modificar_cantidad">

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
    function copiar() {
        var copia = document.getElementById("ob1");
        var mover = document.getElementById("ob2");
        mover.value = copia.value;
    }

    $(document).ready(function() {
        $("#cbx_materiaprima").change(function() {
            $("#cbx_materiaprima option:selected").each(function() {
                id_materiaprima = $(this).val();
                $.post("obtenerstock.php", {
                    id_materiaprima: id_materiaprima,
                }, function(data) {
                    $("#stock_materia").html(data);
                });
            });
        })
    });

    function modificar(datos) {
        var dat = datos.split("_");
        id_pedidocompra = dat[0];
        id_materiaprima = dat[1];
        $.post("obtenercantidad.php", {
            id_pedidocompra: id_pedidocompra,
            id_materiaprima: id_materiaprima,
        }, function(data) {
            $("#modificar_cantidad").html(data);
        });

    }

    function borrar(datos) {
        var dat = datos.split("_");
        $('#si').attr('href', 'control_detalle.php?vidpedidocompra=' + dat[0] + '&vmateriaprima=' + dat[1] + '&operacion=3');
        $('#confirmacion').html('<h3 class="text-center">¿Deseas borrar la materia prima del detalle?</h3>')
    }
</script>

</html>