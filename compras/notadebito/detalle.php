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
$idnotadebito = $_REQUEST['vidnotadebitocompra'];
$notadebitocompra = consultas::get_datos("SELECT * FROM v_notadebitocompra WHERE id_notadebitocompra =  $idnotadebito");
//$nrocuenta = consultas::get_datos("SELECT * FROM cuentaspagar WHERE id_compras =  $idnotadebito");
$liquidacion = consultas::get_datos("SELECT SUM(iva10) AS diez, SUM(iva5) AS cinco FROM v_iva_notadebitocompra WHERE id_notadebitocompra = $idnotadebito");
$detalle = consultas::get_datos("SELECT * FROM v_notadebitocompra_det WHERE id_notadebitocompra = $idnotadebito");
$idcompras = $notadebitocompra[0]['id_compras'];
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
                                <h3 class="box-title">Nota de Debito</h3>
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
                                        <input type="text" class="form-control" value="<?php echo $notadebitocompra[0]['id_notadebitocompra'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-6">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Estado</label>
                                        <input type="text" class="form-control" value="<?php echo $notadebitocompra[0]['estado'] ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Fecha</label>
                                        <input type="date" class="form-control" value="<?php echo $notadebitocompra[0]['fecha'] ?>" id="fecha" name="vfecha" required readonly>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Comprobante N°</label>
                                        <input type="text" class="form-control" name="vnrocomprobante" value="<?php echo $notadebitocompra[0]['nro_comprobante'] ?>" required readonly>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Timbrado N°</label>
                                        <input type="number" min="1" class="form-control" name="vnrotimbrado" value="<?php echo $notadebitocompra[0]['nro_timbrado'] ?>" required readonly>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Vencimiento</label>
                                        <input type="date" class="form-control" id="vencimiento" value="<?php echo $notadebitocompra[0]['ven_timbrado'] ?>" name="vventrimbrado" required readonly>
                                    </div>
                                </div>
                                <div class="from-row">

                                    <?php
                                    $checkrecibido = $notadebitocompra[0]['id_motivonota'];
                                    switch ($checkrecibido) {
                                        case 3:
                                            $motivointereses = 'selected';
                                            $motivocimisiones = '';
                                            $motivocorrecion = '';
                                            break;
                                        case 4:
                                            $motivointereses = '';
                                            $motivocimisiones = 'selected';
                                            $motivocorrecion = '';
                                            break;
                                        case 5:
                                            $motivointereses = '';
                                            $motivocimisiones = '';
                                            $motivocorrecion = 'selected';
                                            break;
                                    }

                                    ?>
                                    <div class="form-group col-md-6">
                                        <div class="">
                                            <label>Motivo</label>
                                            <select class="form-control" name="vmotivo" disabled>
                                                <option value="3" <?php echo  $motivointereses ?>>Intereses</option>
                                                <option value="4" <?php echo  $motivocimisiones ?>>Comisiones</option>
                                                <option value="5" <?php echo  $motivocorrecion ?>>Correcion</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-row">
                                    <div class="form-group col-md-9">
                                        <label>Proveedor</label>
                                        <select class="form-control" disabled>
                                            <option value="<?php echo $notadebitocompra[0]['id_proveedor'] ?>"><?php echo $notadebitocompra[0]['razon_social']; ?></option>
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
                                        <div class="form-group col-md-2">
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
                                <?php if (($notadebitocompra[0]['estado'] == 'PENDIENTE')) { ?>
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
                                                <?php if (($notadebitocompra[0]['estado'] == 'PENDIENTE')) { ?>
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
                                                    <?php if (($notadebitocompra[0]['estado'] == 'PENDIENTE')) { ?>
                                                        <?php $materiaprimacod = $det['id_materiaprima']; ?>
                                                        <th class="text-center">
                                                            <a onclick="modificar('<?php echo  $idnotadebito . '_' . $materiaprimacod ?>')" class="btn btn-sm btn-warning" role="button" data-title="Editar Cantidad" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modificar_detalle">
                                                                <span class="fa fa-pencil"></span>
                                                            </a>
                                                            <a onclick="borrar('<?php echo  $idnotadebito . '_' . $materiaprimacod ?>')" class="btn btn-sm btn-danger" role="button" data-title="Borrar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modalBorrar">
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
                                        <input type="text" class="form-control" value="<?php echo number_format($notadebitocompra[0]['subtotal'], 0, ',', '.') ?>" readonly>
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

                                <?php if ($notadebitocompra[0]['estado'] == 'PENDIENTE') {
                                    if (empty($detalle)) { ?>
                                        <a class="btn btn-success text-bold" disabled>Confirmar</a>
                                    <?php } else { ?>
                                        <a class="btn btn-success text-bold" data-toggle="modal" data-target="#confirmar_debito" onclick="copiar()">Confirmar</a>
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
        <!-- INICIO MODAL CONFIRMAR NOTA DE DEBITO -->
        <div class="modal fade" id="confirmar_debito" tabindex="-1" role="dialog" aria-labelledby="ConfirmarDebito" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Confirmar Nota de Debito</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="POST" accept-charset="UTF-8">
                            <input type="hidden" value="<?php echo $idnotadebito; ?>" name="vcodigo">
                            <input type="hidden" value="2" name="operacion">
                            <input type="hidden" name="vidcompra" value="<?php echo $idcompras ?>">
                            <h3 class="text-center">¿Deseas confirmar la nota de debito?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Si</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL CONFIRMAR NOTA DE DEBITO -->

        <!-- INICIO MODAL MODIFICAR MOTIVO -->
        <div class="modal fade" id="modificar_motivo" tabindex="-1" role="dialog" aria-labelledby="ModificarMotivo" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Modificar Motivo</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="POST" accept-charset="UTF-8">
                            <input type="hidden" value="<?php echo $idnotadebito; ?>" name="vcodigo">
                            <input type="hidden" value="3" name="operacion">
                            <input type="hidden" name="vidcompra" value="<?php echo $idcompras ?>">
                            <div>
                                <label>Motivo</label>
                                <select class="form-control" name="vmotivo" id="cbx_motivo" onchange="mostrardevolucion()">
                                    <option value="3">Intereses</option>
                                    <option value="4">Comisiones</option>
                                    <option value="5">Correcion</option>
                                </select>
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
        $materiaprima = consultas::get_datos("SELECT * FROM v_materiasprimas WHERE EXISTS (SELECT id_materiaprima FROM v_compras_det WHERE id_materiaprima = v_materiasprimas.id_materiaprima AND id_compras = $idcompras) AND id_materiaprima NOT IN (SELECT id_materiaprima FROM v_notadebitocompra_det WHERE id_notadebitocompra =  $idnotadebito) ");
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
                            <input type="hidden" name="vcodigo" value="<?php echo $idnotadebito ?>">
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
                            <input type="hidden" name="vcodigo" value="<?php echo $idnotadebito ?>">
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
        id_notadebitocompra = dat[0];
        id_materiaprima = dat[1];
        id_compras = <?php echo $idcompras ?>;
        $.post("obtenercantidad.php", {
            id_notadebitocompra: id_notadebitocompra,
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
                $.post("mostrarDetalles2.php", {
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
            $.post("mostrarDetalles2.php", {
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
        contenidodos = document.getElementById("mensajedevolucion");
        contenidouno = document.getElementById("mensajeanulacion");
        valor = document.getElementById("cbx_motivo").value;
        if (valor == 2) {
            contenidodos.style.display = 'block';
            contenidouno.style.display = 'none';
        } else {
            contenidodos.style.display = 'none';
            contenidouno.style.display = 'block';
        };
    }

    window.onload = mostrardevolucion();
</script>

</html>