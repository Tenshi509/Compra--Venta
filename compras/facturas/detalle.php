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
$idcompras = $_REQUEST['vidcompra'];
$compra = consultas::get_datos("SELECT * FROM v_compras WHERE id_compras =  $idcompras");
$nrocuenta = consultas::get_datos("SELECT * FROM cuentaspagar WHERE id_compras =  $idcompras");
$liquidacion = consultas::get_datos("SELECT SUM(iva10) AS diez, SUM(iva5) AS cinco FROM v_iva_compras WHERE id_compras = $idcompras");
$detalle = consultas::get_datos("SELECT * FROM v_compras_det WHERE id_compras = $idcompras");
if (!empty($compra[0]['id_ordencompra'])) {
    $codigoorden = $compra[0]['id_ordencompra'];
} else {
    $codigoorden = 0;
}
$ordencompra = consultas::get_datos("SELECT * FROM v_ordencompra WHERE id_ordencompra = $codigoorden");



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
                                <h3 class="box-title">Factura</h3>
                                <div class="box-tools">
                                    <a class="btn btn-primary pull-right btn-sm" onclick="history.back()" role="button" data-title="Volver" data-placement="top" rel="tooltip">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                </div>
                            </div>
                            <!--- FIN HEADER --->
                            <!--- INICIO BODY --->
                            <div class="box-body">
                                <div class="form-row">
                                    <input type="hidden" value="1" name="operacion">
                                    <div class="form-group col-md-4">
                                        <label>Codigo</label>
                                        <input type="text" class="form-control" value="<?php echo $idcompras ?>" readonly name="vcodigo">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Estado</label>
                                        <input type="text" class="form-control" value="<?php echo $compra[0]['estado'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Concepto</label>
                                        <input type="text" class="form-control" id="concepto" value="<?php echo $compra[0]['concepto'] ?>" readonly name="vconcepto" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Fecha</label>
                                        <input type="date" class="form-control" id="fecha" name="vfecha" value="<?php echo $compra[0]['fecha'] ?>" required readonly onchange="verificarFechaTimbrado()" onclick="verificarFechaTimbrado()" onkeyup="verificarFechaTimbrado()">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Factura N°</label>
                                        <input type="text" class="form-control" name="vnrofactura" value="<?php echo $compra[0]['nro_factura'] ?>" required readonly onKeyDown="if(this.value.length==15 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Timbrado N°</label>
                                        <input type="number" min="1" class="form-control" name="vnrotimbrado" value="<?php echo $compra[0]['nro_timbrado'] ?>" required readonly onKeyDown="if(this.value.length==8 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Vencimiento</label>
                                        <input type="date" class="form-control" id="vencimiento" name="vventrimbrado" value="<?php echo $compra[0]['ven_timbrado'] ?>" required readonly onchange="verificarFechaTimbrado()" onclick="verificarFechaTimbrado()" onkeyup="verificarFechaTimbrado()">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?php
                                            if ($compra[0]['id_condicionventa'] == 1) { // SI ES CONTADO
                                                $selectedcontado = 'selected';
                                                $selectedcredito = '';
                                            } else {
                                                $selectedcontado = '';
                                                $selectedcredito = 'selected';
                                            }

                                            ?>
                                            <label>Condición de compra</label>
                                            <select class="form-control" name="vcondicion" id="cbx_condicion" required disabled>
                                                <option value="1" <?php echo  $selectedcontado ?>>Contado</option>
                                                <option value="2" <?php echo  $selectedcredito ?>>Crédito</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="cantcuota">Cantidad de cuota</label>
                                            <input type="number" id="cantcuota" class="form-control" value="<?php echo $compra[0]['cuota'] ?>" required readonly min="1" name="vcuota" max="60" value="1" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 2 && event.keyCode != 8) return false;">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="intervalo">Intervalo</label>
                                            <input type="number" id="intervalo" class="form-control" value="<?php echo $compra[0]['intervalo'] ?>" required readonly min="1" name="vintervalo" max="30" value="1" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 2 && event.keyCode != 8) return false;">
                                        </div>

                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Proveedor</label>
                                        <select class="form-control" disabled>
                                            <option value="<?php echo $compra[0]['id_proveedor'] ?>"><?php echo $compra[0]['razon_social']; ?></option>
                                        </select>
                                    </div>
                                    
                                </div>
                                <?php if (!empty($compra[0]['id_ordencompra'])) { ?>
                                    <div id="box_pedido">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Orden de compra</label>
                                                <select class="form-control" name="vpedido" disabled id="cbx_ordencompra">
                                                    <?php
                                                    foreach ($ordencompra as $odn) {
                                                    ?>
                                                        <option value="<?php echo $odn['id_ordencompra'] ?>"><?php echo $odn['nro_orden_larga']; ?></option>
                                                    <?php
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <a class="btn btn-info text-bold" onclick="javascript:ordenDetalle()">Ver/Ocultar Detalle</a>
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
                                <?php if ($compra[0]['estado'] == 'PENDIENTE') { ?>
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
                                                <th class="text-center">Producto</th>
                                                <th class="text-center">Precio Unitario</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">IVA</th>
                                                <?php if ($compra[0]['estado'] == 'PENDIENTE') { ?>
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
                                                    <?php if ($compra[0]['estado'] == 'PENDIENTE') { ?>
                                                        <?php $materiaprimacod = $det['id_materiaprima']; ?>
                                                        <th class="text-center">
                                                            <a onclick="modificar('<?php echo  $idcompras . '_' . $materiaprimacod ?>')" class="btn btn-sm btn-warning" role="button" data-title="Editar Cantidad" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modificar_detalle">
                                                                <span class="fa fa-pencil"></span>
                                                            </a>
                                                            <a onclick="borrar('<?php echo  $idcompras . '_' . $materiaprimacod ?>')" class="btn btn-sm btn-danger" role="button" data-title="Borrar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modalBorrar">
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
                                        <label>Total Factura</label>
                                        <input type="text" class="form-control" value="<?php echo number_format($compra[0]['subtotal'], 0, ',', '.') ?>" readonly>
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


                        <!--- FIN DETALLES --->

                        <!--- INICIO BOTONES --->
                        <!--- INICIO BOX --->
                        <div class="box">
                            <div class="box-header with-border text-center">

                                <?php if ($compra[0]['estado'] == 'PENDIENTE') {
                                    if (empty($detalle)) { ?>
                                        <a class="btn btn-success text-bold" disabled>Confirmar</a>
                                    <?php } else { ?>
                                        <a class="btn btn-success text-bold" data-toggle="modal" data-target="#confirmar_factura" onclick="copiar()">Confirmar</a>
                                    <?php } ?>


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
        <!-- INICIO MODAL CONFIRMAR FACTURA -->
        <div class="modal fade" id="confirmar_factura" tabindex="-1" role="dialog" aria-labelledby="ConfirmarFactura" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Confirmar FActura</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="POST" accept-charset="UTF-8">
                            <input type="hidden" value="<?php echo $idcompras; ?>" name="vcodigo">
                            <input type="hidden" value="2" name="operacion">
                            <input type="hidden" name="vidordencompra" value="<?php echo $compra[0]['id_ordencompra'] ?>">
                            <h3 class="text-center">¿Deseas confirmar la factura?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Si</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL CONFIRMAR FACTURA -->

        <!-- INICIO MODAL AGREGAR DETALLE -->
        <?php
        $materiaprima = consultas::get_datos("SELECT * FROM v_materiasprimas WHERE id_materiaprima NOT IN (SELECT id_materiaprima FROM v_compras_det WHERE id_compras =  $idcompras) AND estado = 'ACTIVO'");
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
                            <input type="hidden" name="vcodigo" value="<?php echo $idcompras ?>">
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
                                    <input type="number" class="form-control" min="1" id="precio_agregar" name="vpreciounitario" required onKeyDown="if(this.value.length==15 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onchange="calcularmontoagregar()" onclick="calcularmontoagregar()" onkeyup="calcularmontoagregar()">
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
                            <input type="hidden" name="vcodigo" value="<?php echo $idcompras ?>">
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
        $("#cbx_ordencompra").change(function() {
            $("#cbx_ordencompra option:selected").each(function() {
                id_ordencompra = $(this).val();
                $.post("mostrarDetalles.php", {
                    id_ordencompra: id_ordencompra
                }, function(data) {
                    $("#detalles").html(data);
                });
            });
        })
    });

    function ordenDetalle() {
        $("#cbx_ordencompra option:selected").each(function() {
            id_ordencompra = $(this).val();
            $.post("mostrarDetalles.php", {
                id_ordencompra: id_ordencompra
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
        id_compras = dat[0];
        id_materiaprima = dat[1];
        $.post("obtenercantidad.php", {
            id_compras: id_compras,
            id_materiaprima: id_materiaprima,
        }, function(data) {
            $("#modificar_cantidad").html(data);
            calcularmontomodificar();
        });

    }

    function borrar(datos) {
        var dat = datos.split("_");
        $('#si').attr('href', 'control_detalle.php?vcodigo=' + dat[0] + '&vmateriaprima=' + dat[1] + '&operacion=3');
        $('#confirmacion').html('<h3 class="text-center">¿Deseas borrar la materia prima del detalle?</h3>')
    }
</script>

</html>