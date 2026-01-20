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
$idordencompra = $_REQUEST['vidordencompra'];
$ordencompra = consultas::get_datos("SELECT * FROM v_ordencompra WHERE id_ordencompra =  $idordencompra");
$liquidacion = consultas::get_datos("SELECT SUM(iva10) AS diez, SUM(iva5) AS cinco FROM v_iva_ordencompra WHERE id_ordencompra = $idordencompra");
$detalle = consultas::get_datos("SELECT * FROM v_ordencompra_det WHERE id_ordencompra = $idordencompra");
if (!empty($ordencompra[0]['id_presupuestoproveedor'])) {
    $codigopedido = $ordencompra[0]['id_presupuestoproveedor'];
} else {
    $codigopedido = 0;
}
$presupuestoproveedor = consultas::get_datos("SELECT * FROM v_presupuestoproveedor WHERE id_presupuestoproveedor = $codigopedido");



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
                            <!--- INICIO BODY --->
                            <div class="box-body">
                                <div class="form-group col-md-3">
                                    <label>Codigo</label>
                                    <input type="text" class="form-control" value="<?php echo $ordencompra[0]['id_ordencompra'] ?>" readonly>
                                </div>
                                <div class="form-group col-md-1"></div>

                                <div class="form-group col-md-4">
                                    <label>Orden de Compra N°</label>
                                    <input type="text" class="form-control" value="<?php echo $ordencompra[0]['nro_orden_larga'] ?>" readonly>
                                </div>

                                <div class="form-group col-md-1"></div>

                                <div class="form-group col-md-3">
                                    <label>Estado</label>
                                    <input type="text" class="form-control" value="<?php echo $ordencompra[0]['estado'] ?>" readonly>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Proveedor</label>
                                        <select class="form-control" disabled>
                                            <option value="<?php echo $ordencompra[0]['id_proveedor'] ?>"><?php echo $ordencompra[0]['razon_social']; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <?php if (!empty($ordencompra[0]['id_presupuestoproveedor'])) { ?>
                                    <div id="box_pedido">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Presupuesto del proveedor</label>
                                                <select class="form-control" name="vpedido" disabled id="cbx_presupuesto">
                                                    <?php
                                                    foreach ($presupuestoproveedor as $pco) {
                                                    ?>
                                                        <option value="<?php echo $pco['id_presupuestoproveedor'] ?>"><?php echo $pco['nro_presupuesto']; ?></option>
                                                    <?php
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <a class="btn btn-info text-bold" onclick="javascript:presupuestoDetalle()">Ver/Ocultar Detalle</a>
                                            </div>
                                        </div>

                                        <div class="form-row" id="detalles" style="display:none">

                                        </div>
                                    </div>
                                <?php } ?>
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
                                <?php if ($ordencompra[0]['estado'] == 'PENDIENTE') { ?>
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
                                                <th class="text-center">Cantidad</th>
                                                <th class="text-center">Materia Prima</th>
                                                <th class="text-center">Precio Unitario</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">IVA</th>
                                                <?php if ($ordencompra[0]['estado'] == 'PENDIENTE') { ?>
                                                    <th class="text-center">Acciones</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($detalle as $det) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo number_format($det['cantidad'], 0, ',', '.') ?></td>
                                                    <td class="text-center"><?php echo $det['materiaprima'] ?></td>
                                                    <td class="text-center"><?php echo number_format($det['precio_unitario'], 0, ',', '.') ?></td>
                                                    <td class="text-center"><?php echo number_format($det['subtotal'], 0, ',', '.') ?></td>
                                                    <td class="text-center"><?php echo $det['tipoimpuesto'] ?></td>
                                                    <?php if ($ordencompra[0]['estado'] == 'PENDIENTE') { ?>
                                                        <?php $materiaprimacod = $det['id_materiaprima']; ?>
                                                        <th class="text-center">
                                                            <a onclick="modificar('<?php echo  $idordencompra . '_' . $materiaprimacod ?>')" class="btn btn-sm btn-warning" role="button" data-title="Editar Cantidad" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modificar_detalle">
                                                                <span class="fa fa-pencil"></span>
                                                            </a>
                                                            <a onclick="borrar('<?php echo  $idordencompra . '_' . $materiaprimacod ?>')" class="btn btn-sm btn-danger" role="button" data-title="Borrar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modalBorrar">
                                                                <span class="glyphicon glyphicon-trash"></span>
                                                            </a>
                                                        </th>
                                                    <?php } ?>

                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <br>
                                    <div class="form-group col-md-4">
                                        <label>Total Orden de Compra</label>
                                        <input type="text" class="form-control" value="<?php echo number_format($ordencompra[0]['subtotal'], 0, ',', '.') ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label>IVA 10%</label>
                                        <input type="text" class="form-control" value="<?php echo number_format($liquidacion[0]['diez'], 0, ',', '.') ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>IVA 5%</label>
                                        <input type="text" class="form-control" value="<?php echo number_format($liquidacion[0]['cinco'], 0, ',', '.') ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Total IVA</label>
                                        <input type="text" class="form-control" value="<?php echo number_format($liquidacion[0]['diez'] + $liquidacion[0]['cinco'], 0, ',', '.') ?>" readonly>
                                    </div>
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
                                    <textarea class="form-control" rows="3" id="ob1" disabled><?php echo $ordencompra[0]['observacion'] ?></textarea>
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

                                <?php if ($ordencompra[0]['estado'] == 'PENDIENTE') {
                                    if (empty($detalle)) { ?>
                                        <a class="btn btn-success text-bold" disabled>Confirmar</a>
                                    <?php } else { ?>
                                        <a class="btn btn-success text-bold" data-toggle="modal" data-target="#confirmar_ordencompra" onclick="copiar()">Confirmar</a>
                                    <?php } ?>
                                    <a href="modificarcabecera.php?vidordencompra=<?php echo $idordencompra ?>" class="btn btn-warning text-bold">Modificar Cabecera</a>
                                    <?php ?>


                                <?php } ?>

                                <?php if ($ordencompra[0]['estado'] == 'CONFIRMADO') { ?>
                                    <a href="#" class="btn btn-danger text-bold" data-toggle="modal" data-target="#anular_ordencompra">Anular</a>
                                    <a href="imprimir.php?vidordencompra=<?php echo $idordencompra ?>" class="btn btn-primary text-bold">Imprimir</a>
                                <?php } ?>

                                <?php if ($ordencompra[0]['estado'] == 'FINALIZADO') { ?>
                                    <a href="imprimir.php?vidordencompra=<?php echo $idordencompra ?>" class="btn btn-primary text-bold">Imprimir</a>
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
        <!-- INICIO MODAL CONFIRMAR ORDEN DE COMPRA -->
        <div class="modal fade" id="confirmar_ordencompra" tabindex="-1" role="dialog" aria-labelledby="ConfirmarOrden" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Confirmar Orden de Compra</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="POST" accept-charset="UTF-8">
                            <input type="hidden" value="<?php echo $idordencompra; ?>" name="vidordencompra">
                            <input type="hidden" value="2" name="operacion">
                            <input type="hidden" name="vpresupuestoproveedor" value="<?php echo $ordencompra[0]['id_presupuestoproveedor'] ?>">
                            <h3 class="text-center">¿Deseas confirmar la orden de compra?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Si</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL CONFIRMAR ORDEN DE COMPRA -->


        <!-- INICIO MODAL ANULAR ORDEN DE COMPRA -->
        <div class="modal fade" id="anular_ordencompra" tabindex="-1" role="dialog" aria-labelledby="AnularOrden" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Anular Orden de Compra</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="POST" accept-charset="UTF-8">
                            <input type="hidden" value="<?php echo $idordencompra; ?>" name="vidordencompra">
                            <input type="hidden" value="4" name="operacion">
                            <input type="hidden" name="vpresupuestoproveedor" value="<?php echo $ordencompra[0]['id_presupuestoproveedor'] ?>">
                            <h3 class="text-center">¿Deseas anular la orden de compra?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-danger">Si</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL ANULAR ORDEN DE COMPRA -->

        <!-- INICIO MODAL AGREGAR DETALLE -->
        <?php
        $materiaprima = consultas::get_datos("SELECT * FROM v_materiasprimas WHERE id_materiaprima NOT IN (SELECT id_materiaprima FROM v_ordencompra_det WHERE id_ordencompra =  $idordencompra) AND estado = 'ACTIVO'");
        ?>
        <div class="modal fade" id="agregar_detalle" role="dialog" aria-labelledby="AgregarDetalle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Agregar Materia Prima</strong></h4>
                    </div>
                    <form action="control_detalle.php" method="post" accept-charset="UTF-8">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="1">
                            <input type="hidden" name="vidordencompra" value="<?php echo $idordencompra ?>">
                            <div class="form-group">
                                <label>Materia Prima</label>
                                <select class="form-control select2" style="width: 100%;" name="vmateriaprima" id="cbx_materiaprima" required>
                                    <?php if (!empty($materiaprima)) { ?>
                                        <option value="" disabled="" selected="">Seleccione una materia prima</option>
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
                            <div id="cargar">
                                <div class="form-group">
                                    <label>Precio Unitario</label>
                                    <input type="number" class="form-control" min="1" id="precio_agregar" name="vpreciounitario" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Cantidad a agregar</label>
                                <input type="number" class="form-control" min="1" id="cant_agregar" name="vcantidad" required onKeyDown="if(this.value.length==9 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onchange="calcularmontoagregar()" onclick="calcularmontoagregar()" onkeyup="calcularmontoagregar()">
                            </div>
                            <div class="form-group">
                                <label>Total</label>
                                <input type="text" class="form-control" id="subtotal_agregar" readonly>
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
                        <h4 class="modal-title text-center"><strong>Modificar Materia Prima</strong></h4>
                    </div>
                    <form action="control_detalle.php" method="post" accept-charset="UTF-8">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="2">
                            <input type="hidden" name="vidordencompra" value="<?php echo $idordencompra ?>">
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
    $(document).ready(function() {
        $("#cbx_materiaprima").change(function() {
            $("#cbx_materiaprima option:selected").each(function() {
                id_materiaprima = $(this).val();
                $.post("obtenerPrecio.php", {
                    id_materiaprima: id_materiaprima
                }, function(data) {
                    $("#cargar").html(data);
                    calcularmontoagregar();
                });
            });
        })
    });

    $(document).ready(function() {
        $("#cbx_presupuesto").change(function() {
            $("#cbx_presupuesto option:selected").each(function() {
                id_presupuesto = $(this).val();
                $.post("mostrarDetalles.php", {
                    id_presupuesto: id_presupuesto
                }, function(data) {
                    $("#detalles").html(data);
                });
            });
        })
    });

    function presupuestoDetalle() {
        $("#cbx_presupuesto option:selected").each(function() {
            id_presupuesto = $(this).val();
            $.post("mostrarDetalles.php", {
                id_presupuesto: id_presupuesto
            }, function(data) {
                $("#detalles").html(data);
            });
        });
        contenido = document.getElementById("detalles");
        if (contenido.style.display === "none") {
            contenido.style.display = 'block';
        } else {
            contenido.style.display = 'none';
        }
    }

    function calcularmontoagregar() {
        var cantidad = parseInt($('#cant_agregar').val());
        var preciounitario = parseInt($('#precio_agregar').val());
        var total = cantidad * preciounitario;
        $('#subtotal_agregar').val(new Intl.NumberFormat('es-PY').format(total));

    };

    function calcularmontomodificar() {
        var cantidad = parseInt($('#cant_modificar').val());
        var preciounitario = parseInt($('#precio_modificar').val());
        var total = cantidad * preciounitario;
        $('#subtotal_modificar').val(new Intl.NumberFormat('es-PY').format(total));

    };

    function modificar(datos) {
        var dat = datos.split("_");
        id_ordencompra = dat[0];
        id_materiaprima = dat[1];
        $.post("obtenercantidad.php", {
            id_ordencompra: id_ordencompra,
            id_materiaprima: id_materiaprima,
        }, function(data) {
            $("#modificar_cantidad").html(data);
            calcularmontomodificar();
        });

    }

    function borrar(datos) {
        var dat = datos.split("_");
        $('#si').attr('href', 'control_detalle.php?vidordencompra=' + dat[0] + '&vmateriaprima=' + dat[1] + '&operacion=3');
        $('#confirmacion').html('<h3 class="text-center">¿Deseas borrar la materia prima del detalle?</h3>')
    }
</script>

</html>