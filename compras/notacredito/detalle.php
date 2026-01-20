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
$idnotacredito = $_REQUEST['vidnotacreditocompra'];
$notacreditocompra = consultas::get_datos("SELECT * FROM v_notacreditocompra WHERE id_notacreditocompra =  $idnotacredito");
//$nrocuenta = consultas::get_datos("SELECT * FROM cuentaspagar WHERE id_compras =  $idnotacredito");
$liquidacion = consultas::get_datos("SELECT SUM(iva10) AS diez, SUM(iva5) AS cinco FROM v_iva_notacreditocompra WHERE id_notacreditocompra = $idnotacredito");
$detalle = consultas::get_datos("SELECT * FROM v_notacreditocompra_det WHERE id_notacreditocompra = $idnotacredito");
$idcompras = $notacreditocompra[0]['id_compras'];
$factura = consultas::get_datos("SELECT * FROM v_compras WHERE id_compras = $idcompras");



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
                                <i class="fa fa-sticky-note"></i>
                                <h3 class="box-title">Nota de Credito</h3>
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
                                    <div class="form-group col-md-3">
                                        <label>Codigo</label>
                                        <input type="text" class="form-control" value="<?php echo $notacreditocompra[0]['id_notacreditocompra'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-6">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Estado</label>
                                        <input type="text" class="form-control" value="<?php echo $notacreditocompra[0]['estado'] ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Fecha</label>
                                        <input type="date" class="form-control" value="<?php echo $notacreditocompra[0]['fecha'] ?>" id="fecha" name="vfecha" required readonly>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Comprobante N°</label>
                                        <input type="text" class="form-control" name="vnrocomprobante" value="<?php echo $notacreditocompra[0]['nro_comprobante'] ?>" required readonly>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Timbrado N°</label>
                                        <input type="number" min="1" class="form-control" name="vnrotimbrado" value="<?php echo $notacreditocompra[0]['nro_timbrado'] ?>" required readonly>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Vencimiento</label>
                                        <input type="date" class="form-control" id="vencimiento" value="<?php echo $notacreditocompra[0]['ven_timbrado'] ?>" name="vventrimbrado" required readonly>
                                    </div>
                                </div>
                                <div class="from-row">

                                    <?php
                                    if ($notacreditocompra[0]['id_motivonota'] == 1) { // SI ES ANULACION
                                        $motivoanulacion = 'selected';
                                        $motivodevolucion = '';
                                    } else {
                                        $motivoanulacion = '';
                                        $motivodevolucion = 'selected';
                                    }

                                    ?>
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <label>Motivo</label>
                                            <select class="form-control" name="vmotivo" disabled>
                                                <option value="1" <?php echo  $motivoanulacion ?>>Anulación</option>
                                                <option value="2" <?php echo  $motivodevolucion ?>>Devolución</option>
                                                <option value="3" <?php echo  $motivodevolucion ?>>Descuento</option>


                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <div class="alert alert-info flat">
                                                <b>Devolución: </b>Habilita botones para agregar y borrar detalle
                                                <br>
                                                <b>Anulación: </b>Todos los productos son agregado al detalle y no es posible borrar.
                                                 <br>
                                                <b>Descuento: </b>Se aplican descuentos al producto
                                                <br>
                                  
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                <div class="form-row">
                                    <div class="form-group col-md-9">
                                        <label>Proveedor</label>
                                        <select class="form-control" disabled>
                                            <option value="<?php echo $notacreditocompra[0]['id_proveedor'] ?>"><?php echo $notacreditocompra[0]['razon_social']; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div id="box_factura">
                                    <div class="form-row">
                                        <div class="form-group col-md-5">
                                            <label>Factura N°</label>
                                            <select class="form-control" name="vidcompra" id="cbx_factura" required disabled>
                                                <option value="<?php echo $factura[0]['id_compras'] ?>"><?php echo $factura[0]['nro_factura']; ?></option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <a class="btn btn-info text-bold" onclick="javascript:facturaDetalle()">Ver/Ocultar Detalle</a>
                                        </div>
                                    </div>

                                    <div class="form-row" id="detalles" style="display:none">

                                    </div>
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
                                <?php if (($notacreditocompra[0]['estado'] == 'PENDIENTE') and ($notacreditocompra[0]['id_motivonota'] == '2')) { ?>
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
                                                <?php if (($notacreditocompra[0]['estado'] == 'PENDIENTE') and ($notacreditocompra[0]['id_motivonota'] == '2')) { ?>
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
                                                    <?php if (($notacreditocompra[0]['estado'] == 'PENDIENTE') and ($notacreditocompra[0]['id_motivonota'] == '2')) { ?>
                                                        <?php $materiaprimacod = $det['id_materiaprima']; ?>
                                                        <th class="text-center">
                                                            <a onclick="modificar('<?php echo  $idnotacredito . '_' . $materiaprimacod ?>')" class="btn btn-sm btn-warning" role="button" data-title="Editar Cantidad" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modificar_detalle">
                                                                <span class="fa fa-pencil"></span>
                                                            </a>
                                                            <a onclick="borrar('<?php echo  $idnotacredito . '_' . $materiaprimacod ?>')" class="btn btn-sm btn-danger" role="button" data-title="Borrar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modalBorrar">
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
                                        <label>Total Credito</label>
                                        <input type="text" class="form-control" value="<?php echo number_format($notacreditocompra[0]['subtotal'], 0, ',', '.') ?>" readonly>
                                    </div>

                               <div class="form-group col-md-2">
    <label>IVA 10%</label>
    <input type="text" class="form-control" id="iva10" value="<?php echo number_format($liquidacion[0]['diez'] ?? 0, 0, ',', '.'); ?>" readonly>
</div>
<div class="form-group col-md-2">
    <label>IVA 5%</label>
    <input type="text" class="form-control" id="iva5" value="<?php echo number_format($liquidacion[0]['cinco'] ?? 0, 0, ',', '.'); ?>" readonly>
</div>
<div class="form-group col-md-4">
    <label>Total IVA</label>
    <input type="text" class="form-control" id="iva_total" value="<?php echo number_format(($liquidacion[0]['diez'] ?? 0) + ($liquidacion[0]['cinco'] ?? 0), 0, ',', '.'); ?>" readonly>
</div>

                                    <div class="form-group col-md-2">
    <label>Descuento (%)</label>
    <input type="number" 
           class="form-control" 
           id="descuento" 
           min="0" max="100" 
           value="0" 
           onchange="calcularTotalConDescuento()">
</div>

<div class="form-group col-md-4">
    <label>Total con Descuento</label>
    <input type="text" class="form-control" id="total_con_descuento" readonly>
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

                                <?php if ($notacreditocompra[0]['estado'] == 'PENDIENTE') {
                                    if (empty($detalle)) { ?>
                                        <a class="btn btn-success text-bold" disabled>Confirmar</a>
                                    <?php } else { ?>
                                        <a class="btn btn-success text-bold" data-toggle="modal" data-target="#confirmar_credito" onclick="copiar()">Confirmar</a>
                                    <?php } ?>
                                    <a class="btn btn-warning text-bold" data-toggle="modal" data-target="#modificar_motivo">Modificar Motivo</a>


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
        <!-- INICIO MODAL CONFIRMAR NOTA CREDITO -->
        <div class="modal fade" id="confirmar_credito" tabindex="-1" role="dialog" aria-labelledby="ConfirmarCredito" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Confirmar Nota de Credito</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="POST" accept-charset="UTF-8">
                            <input type="hidden" value="<?php echo $idnotacredito; ?>" name="vcodigo">
                            <input type="hidden" value="2" name="operacion">
                            <input type="hidden" name="vidcompra" value="<?php echo $idcompras ?>">
                            <h3 class="text-center">¿Deseas confirmar la nota de credito?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Si</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL CONFIRMAR NOTA DE CREDITO -->

        <!-- INICIO MODAL MODIFICAR MOTIVO -->
        <div class="modal fade" id="modificar_motivo" tabindex="-1" role="dialog" aria-labelledby="ModificarMotivo" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Modificar Motivo</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="POST" accept-charset="UTF-8">
                            <input type="hidden" value="<?php echo $idnotacredito; ?>" name="vcodigo">
                            <input type="hidden" value="3" name="operacion">
                            <input type="hidden" name="vidcompra" value="<?php echo $idcompras ?>">
                            <div>
                                <label>Motivo</label>
                                <select class="form-control" name="vmotivo" id="cbx_motivo" onchange="mostrardevolucion()">
                                    <option value="1">Anulación</option>
                                    <option value="2">Devolución</option>
                                    <option value="3">Descuento</option>

                                </select>
                            </div>
                            <br>
                            <div class="" id="mensajedevolucion" style="display: none;">
                                <div class="alert alert-info flat">
                                    <b>Devolución: </b>Habilita botones para agregar y borrar los productos
                                </div>
                            </div>
                            <div class="" id="mensajeanulacion" style="display: none;">
                                <div class="alert alert-warning flat">
                                    <b>Anulación: </b>Todos los productos seran agregado al detalle y no es posible borrar.
                                </div>
                            </div>
                               <div class="" id="mensajedescuento" style="display: none;">
                                <div class="alert alert-warning flat">
                                    <b>Descuento: </b>Se aplican descueentos al productos
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
        <!-- FIN MODAL MODIFICAR MOTIVO -->

        <!-- INICIO MODAL AGREGAR DETALLE -->
        <?php
        $materiaprima = consultas::get_datos("SELECT * FROM v_materiasprimas WHERE EXISTS (SELECT id_materiaprima FROM v_compras_det WHERE id_materiaprima = v_materiasprimas.id_materiaprima AND id_compras = $idcompras) AND id_materiaprima NOT IN (SELECT id_materiaprima FROM v_notacreditocompra_det WHERE id_notacreditocompra =  $idnotacredito) ");
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
                            <input type="hidden" name="vcodigo" value="<?php echo $idnotacredito ?>">
                            <input type="hidden" name="vidcompras" value="<?php echo $idcompras ?>">
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

                                <div class="form-group">
                                    <label>Cantidad a agregar</label>
                                    <input type="number" class="form-control" readonly min="1" id="cant_agregar" name="vcantidad" required onKeyDown="if(this.value.length==9 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onchange="calcularmontoagregar()" onclick="calcularmontoagregar()" onkeyup="calcularmontoagregar()">
                                </div>
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
                            <input type="hidden" name="vcodigo" value="<?php echo $idnotacredito ?>">
                            <input type="hidden" name="vidcompras" value="<?php echo $idcompras ?>">
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
                id_compras = <?php echo $idcompras ?>;
                $.post("obtenerPrecio.php", {
                    id_materiaprima: id_materiaprima,
                    id_compras: id_compras
                }, function(data) {
                    $("#cargar").html(data);
                    calcularmontoagregar();
                });
            });
        })
    });

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
function calcularTotalConDescuento() {
    var totalCredito = parseInt("<?php echo $notacreditocompra[0]['subtotal'] ?? 0; ?>".replace(/\./g,'')); 
    var iva10 = parseInt("<?php echo $liquidacion[0]['diez'] ?? 0; ?>".replace(/\./g,'')); 
    var iva5 = parseInt("<?php echo $liquidacion[0]['cinco'] ?? 0; ?>".replace(/\./g,'')); 

    var descuento = parseInt($('#descuento').val());
    if (isNaN(descuento)) descuento = 0;

    // Aplicar descuento sobre subtotal
    var totalConDescuento = totalCredito - (totalCredito * descuento / 100);

    // Recalcular IVA proporcionalmente
    var iva10Desc = iva10 - (iva10 * descuento / 100);
    var iva5Desc = iva5 - (iva5 * descuento / 100);
    var ivaTotalDesc = iva10Desc + iva5Desc;
    // Función para calcular el total con descuento


// Ejecutar al cambiar motivo
document.getElementById('cbx_motivo').addEventListener('change', mostrardevolucion);

// Capturar Enter en el campo descuento
document.getElementById('descuento').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault(); // Evita submit
        calcularTotalConDescuento(); // Calcula total con descuento
    }
});

    // Mostrar en pantalla
    $('#total_con_descuento').val(new Intl.NumberFormat('es-PY').format(totalConDescuento));

    // Si quieres mostrar también el IVA actualizado:
    $('#iva10').val(new Intl.NumberFormat('es-PY').format(iva10Desc));
    $('#iva5').val(new Intl.NumberFormat('es-PY').format(iva5Desc));
    $('#iva_total').val(new Intl.NumberFormat('es-PY').format(ivaTotalDesc));
}

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
        id_notacreditocompra = dat[0];
        id_materiaprima = dat[1];
        id_compras = <?php echo $idcompras ?>;
        $.post("obtenercantidad.php", {
            id_notacreditocompra: id_notacreditocompra,
            id_materiaprima: id_materiaprima,
            id_compras: id_compras,
        }, function(data) {
            $("#modificar_cantidad").html(data);
            calcularmontomodificar();
        });

    }

    function borrar(datos) {
        var dat = datos.split("_");
        $('#si').attr('href', 'control_detalle.php?vcodigo=' + dat[0] + '&vmateriaprima=' + dat[1] + '&operacion=3&vidcompras=<?php echo $idcompras ?>');
        $('#confirmacion').html('<h3 class="text-center">¿Deseas borrar la materia prima del detalle?</h3>')
    }
</script>

<script>
    $(document).ready(function() {
        $("#cbx_factura").change(function() {
            $("#cbx_factura option:selected").each(function() {
                id_compras = $(this).val();
                $.post("mostrarDetalles.php", {
                    id_compras: id_compras
                }, function(data) {
                    $("#detalles").html(data);
                });
            });
        })
    });

    function facturaDetalle() {
        $("#cbx_factura option:selected").each(function() {
            id_compras = $(this).val();
            $.post("mostrarDetalles.php", {
                id_compras: id_compras
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

function mostrardevolucion() {
    let contDevolucion = document.getElementById("mensajedevolucion");
    let contAnulacion  = document.getElementById("mensajeanulacion");
    let contDescuento  = document.getElementById("mensajedescuento");
    let inputDescuento = document.getElementById("descuento"); 
    inputDescuento.parentElement.style.display = 'none';


    let valor = document.getElementById("cbx_motivo").value;

    if (valor === "2") { 
        contDevolucion.style.display = 'block';
        contAnulacion.style.display  = 'none';
        contDescuento.style.display  = 'none';
        inputDescuento.parentElement.style.display = 'none';
        inputDescuento.value = 0;
        inputDescuento.disabled = true;
    } 
    else if (valor === "3") { 
        contDescuento.style.display  = 'block';
        contAnulacion.style.display  = 'none';
        contDevolucion.style.display = 'none';
        inputDescuento.parentElement.style.display = 'block';
        inputDescuento.disabled = false;
    } 
    else { 
        contAnulacion.style.display  = 'block';
        contDevolucion.style.display = 'none';
        contDescuento.style.display  = 'none';
        inputDescuento.parentElement.style.display = 'none';
        inputDescuento.value = 0;
        inputDescuento.disabled = true;
    }
}






    window.onload = mostrardevolucion();
</script>

</html>