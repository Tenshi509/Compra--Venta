
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
$idnotaremision = $_REQUEST['vidnotaremisioncompra'];
$notaremisioncompra = consultas::get_datos("SELECT * FROM v_notaremisioncompra WHERE id_notaremisioncompra =  $idnotaremision");
$detalle = consultas::get_datos("SELECT * FROM v_notaremisioncompra_det WHERE id_notaremisioncompra = $idnotaremision");
$idcompras = $notaremisioncompra[0]['id_compras'];
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
                                <i class="fa fa-truck"></i>
                                <h3 class="box-title">Nota de Remisión</h3>
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
                                    <div class="form-group col-md-3">
                                        <label>Codigo</label>
                                        <input type="text" class="form-control" value="<?php echo $notaremisioncompra[0]['id_notaremisioncompra'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-6">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Estado</label>
                                        <input type="text" class="form-control" value="<?php echo $notaremisioncompra[0]['estado'] ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Fecha de Emisión</label>
                                        <input type="date" class="form-control" value="<?php echo $notaremisioncompra[0]['fecha_emision'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Comprobante N°</label>
                                        <input type="text" class="form-control" value="<?php echo $notaremisioncompra[0]['nro_comprobante'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Timbrado N°</label>
                                        <input type="number" class="form-control" value="<?php echo $notaremisioncompra[0]['nro_timbrado'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Vencimiento</label>
                                        <input type="date" class="form-control" value="<?php echo $notaremisioncompra[0]['ven_timbrado'] ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Fecha de Inicio de Traslado</label>
                                        <input type="date" class="form-control" value="<?php echo $notaremisioncompra[0]['fecha_inicio_traslado'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-6">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Fecha de Fin de Traslado</label>
                                        <input type="date" class="form-control" value="<?php echo $notaremisioncompra[0]['fecha_fin_traslado'] ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Dirección de Salida</label>
                                        <input type="text" class="form-control" value="<?php echo $notaremisioncompra[0]['direccion_salida'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Ciudad</label>
                                        <select class="form-control" disabled>
                                            <option value="<?php echo $notaremisioncompra[0]['id_ciudad'] ?>"><?php echo $notaremisioncompra[0]['ciudad']; ?></option>
                                        </select>
                                    </div>

                                    <div id="div_departamento">
                                        <div class="form-group col-md-3">
                                            <label>Departamento</label>
                                            <input type="text" class="form-control" value="<?php echo $notaremisioncompra[0]['departamento'] ?>" readonly>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Km Estimado</label>
                                        <input type="number" class="form-control" value="<?php echo $notaremisioncompra[0]['km_estimado'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Marca</label>
                                        <input type="text" class="form-control soloLetras" value="<?php echo $notaremisioncompra[0]['marca_vehiculo'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Chapa Vehiculo</label>
                                        <input type="text" class="form-control" value="<?php echo $notaremisioncompra[0]['chapa_vehiculo'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Chapa Remolque</label>
                                        <input type="text" class="form-control" value="<?php echo $notaremisioncompra[0]['chapa_remolque'] ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-9">
                                        <label>Conductor</label>
                                        <input type="text" class="form-control" value="<?php echo $notaremisioncompra[0]['nombre_conductor'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>N° Documento</label>
                                        <input type="number" class="form-control" value="<?php echo $notaremisioncompra[0]['ruc_conductor'] ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label>Dirección del Conductor</label>
                                        <input type="text" class="form-control" value="<?php echo $notaremisioncompra[0]['direccion_conductor'] ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>Proveedor</label>
                                        <select class="form-control" disabled>
                                            <option value="<?php echo $notaremisioncompra[0]['id_proveedor'] ?>"><?php echo $notaremisioncompra[0]['razon_social']; ?></option>
                                        </select>
                                    </div>
                                </div>

                                <!---INICIO ASOCIAR FACTURA --->
                                <div class="form-row">
                                    <div class="form-group col-md-5">
                                        <label>Factura N°</label>
                                        <select class="form-control" name="vidcompra" id="cbx_factura" disabled>
                                            <option value="<?php echo $factura[0]['id_compras'] ?>"><?php echo $factura[0]['nro_factura']; ?></option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <a class="btn btn-info text-bold" onclick="javascript:facturaDetalle()">Ver/Ocultar Detalle</a>
                                    </div>
                                </div>

                                <div class="form-row" id="detalles" style="display:none">

                                </div>
                                <!---FIN ASOCIAR FACTURA --->

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
                                <?php if (($notaremisioncompra[0]['estado'] == 'PENDIENTE')) { ?>
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
                                                <th class="text-center">Unidad de Medida</th>
                                                <th class="text-center">Materia Prima</th>
                                                <?php if (($notaremisioncompra[0]['estado'] == 'PENDIENTE')) { ?>
                                                    <th class="text-center">Acciones</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($detalle as $det) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo number_format($det['cantidad'], 0, ',', '.') ?></td>
                                                    <td class="text-center"><?php echo $det['unidadmedida'] ?></td>
                                                    <td class="text-center"><?php echo $det['materiaprima'] ?></td>
                                                    <?php if (($notaremisioncompra[0]['estado'] == 'PENDIENTE')) { ?>
                                                        <?php $materiaprimacod = $det['id_materiaprima']; ?>
                                                        <th class="text-center">
                                                            <a onclick="modificar('<?php echo  $idnotaremision . '_' . $materiaprimacod ?>')" class="btn btn-sm btn-warning" role="button" data-title="Editar Cantidad" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modificar_detalle">
                                                                <span class="fa fa-pencil"></span>
                                                            </a>
                                                            <a onclick="borrar('<?php echo  $idnotaremision . '_' . $materiaprimacod ?>')" class="btn btn-sm btn-danger" role="button" data-title="Borrar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modalBorrar">
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


                        <!--- FIN DETALLES --->

                        <!--- INICIO BOTONES --->
                        <!--- INICIO BOX --->
                        <div class="box">
                            <div class="box-header with-border text-center">

                                <?php if ($notaremisioncompra[0]['estado'] == 'PENDIENTE') {
                                    if (empty($detalle)) { ?>
                                        <a class="btn btn-success text-bold" disabled>Confirmar</a>
                                    <?php } else { ?>
                                        <a class="btn btn-success text-bold" data-toggle="modal" data-target="#confirmar_remision" onclick="copiar()">Confirmar</a>
                                    <?php } ?>
                                    <a href="modificar.php?vidnotaremisioncompra=<?php echo $idnotaremision ?>" class="btn btn-warning text-bold">Modificar Cabecera</a>

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
        <!-- INICIO MODAL CONFIRMAR NOTA DE REMISION -->
        <div class="modal fade" id="confirmar_remision" tabindex="-1" role="dialog" aria-labelledby="ConfirmarRemision" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Confirmar Nota de Remision</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="POST" accept-charset="UTF-8">
                            <input type="hidden" value="<?php echo $idnotaremision; ?>" name="vcodigo">
                            <input type="hidden" value="2" name="operacion">
                            <input type="hidden" name="vidcompra" value="<?php echo $idcompras ?>">
                            <h3 class="text-center">¿Deseas confirmar la nota de remisión?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Si</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL CONFIRMAR NOTA DE REMISION -->
        <!-- INICIO MODAL AGREGAR DETALLE -->
        <?php
        $materiaprima = consultas::get_datos("SELECT * FROM v_materiasprimas WHERE estado = 'ACTIVO' AND id_materiaprima NOT IN (SELECT id_materiaprima FROM v_notaremisioncompra_det WHERE id_notaremisioncompra =  $idnotaremision)");
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
                            <input type="hidden" name="vcodigo" value="<?php echo $idnotaremision ?>">
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
                                    <label>Unidad de Medida</label>
                                    <input type="text" class="form-control" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Cantidad en Factura</label>
                                    <input type="number" class="form-control" readonly>
                                </div>

                                <div class="form-group">
                                    <label>Cantidad en Remision</label>
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
                        <h4 class="modal-title text-center"><strong>Modificar Materia Prima</strong></h4>
                    </div>
                    <form action="control_detalle.php" method="post" accept-charset="UTF-8">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="2">
                            <input type="hidden" name="vcodigo" value="<?php echo $idnotaremision ?>">
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
                id_materiaprima = $(this).val();
                id_compras = <?php echo $idcompras ?>;
                id_notaremisioncompra = <?php echo $idnotaremision ?>;
                $.post("obtenerAgregar.php", {
                    id_materiaprima: id_materiaprima,
                    id_compras: id_compras,
                    id_notaremisioncompra: id_notaremisioncompra
                }, function(data) {
                    $("#cargar").html(data);
                });
            });
        })
    });




    function modificar(datos) {
        var dat = datos.split("_");
        id_notaremisioncompra = dat[0];
        id_materiaprima = dat[1];
        id_compras = <?php echo $idcompras ?>;
        $.post("obtenerModificar.php", {
            id_notaremisioncompra: id_notaremisioncompra,
            id_materiaprima: id_materiaprima,
            id_compras: id_compras,
        }, function(data) {
            $("#modificar_cantidad").html(data);
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
</script>

</html>