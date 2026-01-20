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
$ultimocodigo = consultas::get_datos("SELECT COALESCE(MAX(id_compras),0)+1 AS ultimo FROM compras");
$ultimocuenta = consultas::get_datos("SELECT COALESCE(MAX(id_cuentapagar),0)+1 AS ultimo FROM cuentaspagar");
$proveedor = consultas::get_datos("SELECT * FROM v_proveedor WHERE estado = 'ACTIVO' ORDER BY id_proveedor ASC");



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
                                <h3 class="box-title">Facturas</h3>
                                <div class="box-tools">
                                    <a class="btn btn-primary pull-right btn-sm" onclick="history.back()" role="button" data-title="Volver" data-placement="top" rel="tooltip">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                </div>
                            </div>
                            <!--- FIN HEADER --->
                            <form action="control.php" method="post">
                                <!--- INICIO BODY --->
                                <div class="box-body">
                                    <div class="form-row">
                                        <input type="hidden" value="1" name="operacion">
                                        <div class="form-group col-md-4">
                                            <label>Codigo</label>
                                            <input type="text" class="form-control" value="<?php echo $ultimocodigo[0]['ultimo'] ?>" readonly name="vcodigo">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label>Estado</label>
                                            <input type="text" class="form-control" value="PENDIENTE" readonly>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label>Concepto</label>
                                            <input type="text" class="form-control" id="concepto" readonly name="vconcepto" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label>Fecha</label>
                                            <input type="date" class="form-control" id="fecha" name="vfecha" required onchange="verificarFechaTimbrado()" onclick="verificarFechaTimbrado()" onkeyup="verificarFechaTimbrado()">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Factura N°</label>
                                            <input type="text" class="form-control" name="vnrofactura" required onKeyDown="if(this.value.length==15 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Timbrado N°</label>
                                            <input type="number" min="1" class="form-control" name="vnrotimbrado" required onKeyDown="if(this.value.length==8 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Vencimiento</label>
                                            <input type="date" class="form-control" id="vencimiento" name="vventrimbrado" required onchange="verificarFechaTimbrado()" onclick="verificarFechaTimbrado()" onkeyup="verificarFechaTimbrado()">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <label>Condición de compra</label>
                                                <select class="form-control" name="vcondicion" id="cbx_condicion" required onchange="condicion();">
                                                    <option value="1">Contado</option>
                                                    <option value="2">Crédito</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="cantcuota">Cantidad de cuota</label>
                                                <input type="number" id="cantcuota" class="form-control" required min="1" name="vcuota" max="60" value="1" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 2 && event.keyCode != 8) return false;">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="intervalo">Intervalo</label>
                                                <input type="number" id="intervalo" class="form-control" required min="1" name="vintervalo" max="30" value="1" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 2 && event.keyCode != 8) return false;">
                                            </div>

                                        </div>
                                    </div>


                                    <div class="form-row">
                                        <div class="form-group col-md-9">
                                            <label>Proveedor</label>
                                            <select class="form-control select2" name="vproveedor" required id="proveedor" onclick="asociarOrden()" onchange="asociarOrden()">
                                                <?php if (!empty($proveedor)) { ?>
                                                    <option value="" disabled selected>Seleccione una opción</option>
                                                    <?php
                                                    foreach ($proveedor as $pro) {
                                                    ?>
                                                        <option value="<?php echo $pro['id_proveedor'] ?>"><?php echo $pro['razon_social']; ?></option>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <option value="">No existe ningun proveedor</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <input type="checkbox" id="check_remision" onchange="javascript:checkRemision()"> Con Nota de Remisión
                                        </div>
                                    </div>

                                    <!---INICIO ASOCIAR ORDEN DE COMPRA --->
                                    <div id="box_ordencompra">

                                    </div>
                                    <!---FIN ASOCIAR ORDEN DE COMPRA --->


                                    <!--- FIN BODY --->
                                </div>
                                <!--- FIN BOX --->
                                <!--- FIN CABEZERA --->



                                <!--- INICIO BOTONES --->
                                <!--- INICIO BOX --->
                                <div class="box">
                                    <div class="box-header with-border text-center">
                                        <button type="button" class="btn btn-danger" onclick="history.back()">Cancelar</button>
                                        <button type="submit" class="btn btn-success">Guardar</button>
                                    </div>
                                </div>
                                <!--- FIN BOX --->
                                <!--- FIN BOTONES --->
                            </form>
                        </div>

                    </div>

                </div>

            </div>
            <!--- FIN CONTENIDO --->
            <!--- INICIO MODAL --->


            <!--- FIN MODAL --->
        </div>
        <?php require '../../estilos/pie.ctp' ?>
</body>
<?php
require '../../estilos/js_lte.ctp';
require '../../estilos/js_creado.ctp';
?>
<script>
    // COMPROBAR CONDICION DE VENTA
    function condicion() {
        if (document.getElementById('cbx_condicion').value === "1") {
            document.getElementById('cantcuota').setAttribute('readonly', 'true');
            document.getElementById('cantcuota').value = '1';
            document.getElementById('intervalo').setAttribute('readonly', 'true');
            document.getElementById('intervalo').value = '1';
        } else {
            document.getElementById('cantcuota').removeAttribute('readonly');
            document.getElementById('cantcuota').value = '1';
            document.getElementById('intervalo').removeAttribute('readonly');
            document.getElementById('intervalo').value = '1';
        }
    }
    window.onload = condicion(), checkRemision();

    function asociarOrden() {
        $(document).ready(function() {
            $("#proveedor option:selected").each(function() {
                codigo = $(this).val();
                $.post("cargarOrden.php", {
                    codigo: codigo
                }, function(data) {
                    $("#box_ordencompra").html(data);
                });
            });
        });
    }

    function checkRemision() {
        check = document.getElementById("check_remision");
        if (check.checked) {
            document.getElementById('concepto').value = 'CON REMISIÓN';
        } else {
            document.getElementById('concepto').value = 'SIN REMISIÓN';
        }
    }

    //Validar fecha timbrado
    function verificarFechaTimbrado() {
        var fecha = $('#fecha').val();
        var vencimiento = $('#vencimiento').val();
        document.getElementById("vencimiento").setAttribute("min", fecha);
    }
</script>

</html>