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
$ultimocodigo = consultas::get_datos("SELECT COALESCE(MAX(id_notaremisioncompra),0)+1 AS ultimo FROM notaremisioncompra");
$proveedor = consultas::get_datos("SELECT * FROM v_proveedor WHERE estado = 'ACTIVO' ORDER BY id_proveedor ASC");
$ciudad = consultas::get_datos("SELECT * FROM v_ciudad ORDER BY id_ciudad ASC");



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
                            <form action="control.php" method="post">
                                <!--- INICIO BODY --->
                                <div class="box-body">
                                    <div class="form-row">
                                        <input type="hidden" value="1" name="operacion">
                                        <div class="form-group col-md-3">
                                            <label>Codigo</label>
                                            <input type="text" class="form-control" value="<?php echo $ultimocodigo[0]['ultimo'] ?>" readonly name="vcodigo">
                                        </div>

                                        <div class="form-group col-md-6">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Estado</label>
                                            <input type="text" class="form-control" value="PENDIENTE" readonly>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label>Fecha de Emisión</label>
                                            <input type="date" class="form-control" id="fecha" name="vfechaemision" required onchange="verificarFechaTimbrado()" onclick="verificarFechaTimbrado()" onkeyup="verificarFechaTimbrado()">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Comprobante N°</label>
                                            <input type="text" class="form-control" name="vnrocomprobante" required onKeyDown="if(this.value.length==15 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
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
                                        <div class="form-group col-md-3">
                                            <label>Fecha de Inicio de Traslado</label>
                                            <input type="date" class="form-control" id="iniciotraslado" name="vfechainiciotraslado" required onchange="verificarFechaTraslado()" onclick="verificarFechaTraslado()" onkeyup="verificarFechaTraslado()">
                                        </div>

                                        <div class="form-group col-md-6">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Fecha de Fin de Traslado</label>
                                            <input type="date" class="form-control" id="fintraslado" name="vfechafintraslado" required onchange="verificarFechaTraslado()" onclick="verificarFechaTraslado()" onkeyup="verificarFechaTraslado()">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Dirección de Salida</label>
                                            <input type="text" class="form-control" name="vdirsalida" required maxlength="100">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Ciudad</label>
                                            <select class="form-control select2" name="vciudad" required id="ciudad" onclick="cargarDepartamento()" onchange="cargarDepartamento()">
                                                <?php if (!empty($ciudad)) { ?>
                                                    <option value="" disabled selected>Seleccione una opción</option>
                                                    <?php
                                                    foreach ($ciudad as $ciu) {
                                                    ?>
                                                        <option value="<?php echo $ciu['id_ciudad'] ?>"><?php echo $ciu['descripcion']; ?></option>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <option value="">No existe ninguna ciudad</option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div id="div_departamento">
                                            <div class="form-group col-md-3">
                                                <label>Departamento</label>
                                                <input type="text" class="form-control" readonly>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label>Km Estimado</label>
                                            <input type="number" class="form-control" name="vkm" onKeyDown="if(this.value.length==20 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Marca</label>
                                            <input type="text" class="form-control soloLetras" name="vmarca" required maxlength="50">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Chapa Vehiculo</label>
                                            <input type="text" class="form-control" name="vchapa" required maxlength="10">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Chapa Remolque</label>
                                            <input type="text" class="form-control" name="vchaparemolque"  maxlength="10">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-9">
                                            <label>Conductor</label>
                                            <input type="text" class="form-control" name="vconductor" required>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>N° Documento</label>
                                            <input type="number" class="form-control" name="vrucconductor" required onKeyDown="if(this.value.length==10 && event.keyCode!=8) return false;" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Dirección del Conductor</label>
                                            <input type="text" class="form-control" name="vdireccionconductor" maxlength="100">
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label>Proveedor</label>
                                            <select class="form-control select2" name="vproveedor" required id="proveedor" onclick="asociarFactura()" onchange="asociarFactura()">
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
                                    </div>


                                    <!---INICIO ASOCIAR FACTURA --->
                                    <div id="box_factura">

                                    </div>
                                    <!---FIN ASOCIAR FACTURA --->

                                </div>
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
    function asociarFactura() {
        $(document).ready(function() {
            $("#proveedor option:selected").each(function() {
                codigo = $(this).val();
                $.post("cargarFactura.php", {
                    codigo: codigo
                }, function(data) {
                    $("#box_factura").html(data);
                });
            });
        });
    }

    function cargarDepartamento() {
        $(document).ready(function() {
            $("#ciudad option:selected").each(function() {
                codigo = $(this).val();
                $.post("cargarDepartamento.php", {
                    codigo: codigo
                }, function(data) {
                    $("#div_departamento").html(data);
                });
            });
        });
    }
    //Validar fecha timbrado
    function verificarFechaTimbrado() {
        var fecha = $('#fecha').val();
        var vencimiento = $('#vencimiento').val();
        document.getElementById("vencimiento").setAttribute("min", fecha);
    }

    //Validar fecha traslado
    function verificarFechaTraslado() {
        var inicio = $('#iniciotraslado').val();
        var fin = $('#fintraslado').val();
        document.getElementById("fintraslado").setAttribute("min", inicio);
    }
</script>

</html>