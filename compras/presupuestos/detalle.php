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
$liquidacion = consultas::get_datos("SELECT SUM(iva10) AS diez, SUM(iva5) AS cinco  FROM v_iva_presupuestoproveedor WHERE id_presupuestoproveedor = $idpresupuestoproveedor");
$detalle = consultas::get_datos("SELECT * FROM v_presupuestoproveedor_det WHERE id_presupuestoproveedor = $idpresupuestoproveedor");
if (!empty($presupuestoproveedor[0]['id_pedidocompra'])) {
    $codigopedido = $presupuestoproveedor[0]['id_pedidocompra'];
} else {
    $codigopedido = 0;
}
$pedidocompra = consultas::get_datos("SELECT * FROM v_pedidoscompras WHERE id_pedidocompra = $codigopedido");



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
                                <i class="fa fa-file-text"></i>
                                <h3 class="box-title">Presupuesto del Proveedor</h3>
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
                                    <input type="text" class="form-control" value="<?php echo $presupuestoproveedor[0]['id_presupuestoproveedor'] ?>" readonly>
                                </div>

                                <div class="form-group col-md-6"></div>

                                <div class="form-group col-md-3">
                                    <label>Estado</label>
                                    <input type="text" class="form-control" value="<?php echo $presupuestoproveedor[0]['estado'] ?>" readonly>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Proveedor</label>
                                        <select class="form-control" disabled>
                                            <option value="<?php echo $presupuestoproveedor[0]['id_proveedor'] ?>"><?php echo $presupuestoproveedor[0]['razon_social']; ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Fecha</label>
                                        <input type="date" class="form-control" value="<?php echo $presupuestoproveedor[0]['fecha'] ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Presupuesto N°</label>
                                        <input type="text" class="form-control" value="<?php echo $presupuestoproveedor[0]['nro_presupuesto'] ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Validez (Días)</label>
                                        <input type="number" class="form-control" value="<?php echo $presupuestoproveedor[0]['validez'] ?>" readonly>
                                    </div>
                                    

                                </div>
                                <?php if (!empty($presupuestoproveedor[0]['id_pedidocompra'])) { ?>
                                    <div id="box_pedido">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Pedido de Compra</label>
                                                <select class="form-control" name="vpedido" disabled id="cbx_pedido">
                                                    <?php
                                                    foreach ($pedidocompra as $pco) {
                                                    ?>
                                                        <option value="<?php echo $pco['id_pedidocompra'] ?>"><?php echo $pco['nro_pedido_larga']; ?></option>
                                                    <?php
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <a class="btn btn-info text-bold" onclick="javascript:pedidoDetalle()">Ver/Ocultar Detalle</a>
                                            </div>
                                        </div>

                                        <div class="form-row" id="detalles" style="display:none">

                                        </div>
                                    </div>
                                <?php } ?>
                                <!---FIN ASOCIAR PEDIDO --->
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
                                <?php if ($presupuestoproveedor[0]['estado'] == 'PENDIENTE') { ?>
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
                                                <?php if ($presupuestoproveedor[0]['estado'] == 'PENDIENTE') { ?>
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
                                                    <?php if ($presupuestoproveedor[0]['estado'] == 'PENDIENTE') { ?>
                                                        <?php $materiaprimacod = $det['id_materiaprima']; ?>
                                                        <th class="text-center">
                                                            <a onclick="modificar('<?php echo  $idpresupuestoproveedor . '_' . $materiaprimacod ?>')" class="btn btn-sm btn-warning" role="button" data-title="Editar Cantidad" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modificar_detalle">
                                                                <span class="fa fa-pencil"></span>
                                                            </a>
                                                            <a onclick="borrar('<?php echo  $idpresupuestoproveedor . '_' . $materiaprimacod ?>')" class="btn btn-sm btn-danger" role="button" data-title="Borrar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modalBorrar">
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
                                        <label>Total Presupuesto</label>
                                        <input type="text" class="form-control" value="<?php echo number_format($presupuestoproveedor[0]['subtotal'], 0, ',', '.') ?>" readonly>
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
                                    <textarea class="form-control" rows="3" id="ob1" disabled><?php echo $presupuestoproveedor[0]['observacion'] ?></textarea>
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

                                <?php if ($presupuestoproveedor[0]['estado'] == 'PENDIENTE') {
                                    if (empty($detalle)) { ?>
                                        <a class="btn btn-success text-bold" disabled>Confirmar</a>
                                    <?php } else { ?>
                                        <a class="btn btn-success text-bold" data-toggle="modal" data-target="#confirmar_presupuesto" onclick="copiar()">Confirmar</a>
                                    <?php } ?>
<!-- <a href="modificarcabecera.php?vidpresupuestoproveedor=<?php echo $idpresupuestoproveedor ?>" class="btn btn-warning text-bold">Modificar Cabecera</a> -->
                                    <?php ?>


                                <?php } ?>

                                <?php if ($presupuestoproveedor[0]['estado'] == 'CONFIRMADO') { ?>
                                    <a href="#" class="btn btn-danger text-bold" data-toggle="modal" data-target="#anular_presupuesto">Anular</a>
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
        <!-- INICIO MODAL CONFIRMAR PRESUPUESTO -->
        <div class="modal fade" id="confirmar_presupuesto" tabindex="-1" role="dialog" aria-labelledby="ConfirmarPresupuesto" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Confirmar Presupuesto</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="POST" accept-charset="UTF-8">
                            <input type="hidden" value="<?php echo $idpresupuestoproveedor; ?>" name="vidpresupuestoproveedor">
                            <input type="hidden" value="2" name="operacion">
                            <input type="hidden" name="vpedido" value="<?php echo $presupuestoproveedor[0]['id_pedidocompra'] ?>">
                            <h3 class="text-center">¿Deseas confirmar el presupuesto del proveedor?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Si</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL CONFIRMAR PRESUPUESTO -->


        <!-- INICIO MODAL ANULAR PRESUPUESTO -->
        <div class="modal fade" id="anular_presupuesto" tabindex="-1" role="dialog" aria-labelledby="AnularPresupuesto" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Anular Presupuesto</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="POST" accept-charset="UTF-8">
                            <input type="hidden" value="<?php echo $idpresupuestoproveedor; ?>" name="vidpresupuestoproveedor">
                            <input type="hidden" value="4" name="operacion">
                            <input type="hidden" name="vpedido" value="<?php echo $presupuestoproveedor[0]['id_pedidocompra'] ?>">
                            <h3 class="text-center">¿Deseas anular el presupuesto del proveedor?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-danger">Si</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL ANULAR PRESUPUESTO -->

        <!-- INICIO MODAL AGREGAR DETALLE -->
        <?php
        $materiaprima = consultas::get_datos("SELECT * FROM v_materiasprimas WHERE id_materiaprima NOT IN (SELECT id_materiaprima FROM v_presupuestoproveedor_det WHERE id_presupuestoproveedor =  $idpresupuestoproveedor)");
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
                            <input type="hidden" name="vidpresupuestoproveedor" value="<?php echo $idpresupuestoproveedor ?>">
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
                            <div class="form-group">
                                <label>Cantidad</label>
                                <input type="number" class="form-control" min="1" id="cant_agregar" name="vcantidad" required onKeyDown="if(this.value.length==9 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onchange="calcularmontoagregar()" onclick="calcularmontoagregar()" onkeyup="calcularmontoagregar()">
                            </div>
                            <div class="form-group">
                                <label>Precio Unitario</label>
                                <input type="number" class="form-control" min="1" id="precio_agregar" name="vpreciounitario" required onKeyDown="if(this.value.length==9 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onchange="calcularmontoagregar()" onclick="calcularmontoagregar()" onkeyup="calcularmontoagregar()">
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
                            <input type="hidden" name="vidpresupuestoproveedor" value="<?php echo $idpresupuestoproveedor ?>">
                            <div class="row" id="modificar_cantidad">

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
<!-- <button type="submit" class="btn btn-warning">Modificar</button> -->
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
        $("#cbx_pedido").change(function() {
            $("#cbx_pedido option:selected").each(function() {
                id_pedido = $(this).val();
                $.post("mostrarDetalles.php", {
                    id_pedido: id_pedido
                }, function(data) {
                    $("#detalles").html(data);
                });
            });
        })
    });

    function pedidoDetalle() {
        $("#cbx_pedido option:selected").each(function() {
            id_pedido = $(this).val();
            $.post("mostrarDetalles.php", {
                id_pedido: id_pedido
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
        id_presupuestoproveedor = dat[0];
        id_materiaprima = dat[1];
        $.post("obtenercantidad.php", {
            id_presupuestoproveedor: id_presupuestoproveedor,
            id_materiaprima: id_materiaprima,
        }, function(data) {
            $("#modificar_cantidad").html(data);
            calcularmontomodificar();
        });

    }

    function borrar(datos) {
        var dat = datos.split("_");
        $('#si').attr('href', 'control_detalle.php?vidpresupuestoproveedor=' + dat[0] + '&vmateriaprima=' + dat[1] + '&operacion=3');
        $('#confirmacion').html('<h3 class="text-center">¿Deseas borrar la materia prima del detalle?</h3>')
    }
</script>

</html>