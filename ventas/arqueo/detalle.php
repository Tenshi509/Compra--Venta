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
$idarqueo = $_REQUEST['vidarqueo'];
$arqueo = consultas::get_datos("SELECT * FROM v_arqueo WHERE id_arqueo = $idarqueo");
$detalle_cheque = consultas::get_datos("SELECT * FROM v_arqueo_det_cheques WHERE id_arqueo = $idarqueo");
$detalle_tarjeta = consultas::get_datos("SELECT * FROM v_arqueo_det_tarjetas WHERE id_arqueo = $idarqueo");



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
                                <h3 class="box-title">Arqueo de Caja</h3>
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
                                    <div class="form-group col-md-2">
                                        <label>Codigo</label>
                                        <input type="text" class="form-control" value="<?php echo $idarqueo ?>" readonly name="vcodigo">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Arqueo N°</label>
                                        <input type="text" class="form-control" value="<?php echo $arqueo[0]['nro_arqueo_larga'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label>Hora</label>
                                        <input type="text" class="form-control" value="<?php echo $arqueo[0]['hora'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label>Fecha</label>
                                        <input type="text" class="form-control" value="<?php echo $arqueo[0]['fecha_corta'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label>Estado</label>
                                        <input type="text" class="form-control" value="<?php echo $arqueo[0]['estado'] ?>" readonly>
                                    </div>


                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>Apertura N°</label>
                                        <input type="text" class="form-control" value="<?php echo $arqueo[0]['nro_aperturacierre_larga'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Caja</label>
                                        <input type="text" class="form-control" value="<?php echo $arqueo[0]['caja'] ?>" readonly>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Cajero</label>
                                        <input type="text" class="form-control" value="<?php echo $arqueo[0]['cajero'] ?>" readonly>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Monto Inicial</label>
                                        <input type="text" class="form-control" value="<?php echo number_format($arqueo[0]['monto_inicial'], 0, ',', '.') ?>" readonly>
                                    </div>
                                </div>

                            </div>
                            <!--- FIN BODY --->
                        </div>
                        <!--- FIN BOX --->
                        <!--- FIN CABEZERA --->


                        <!--- INICIO EFECTIVOS --->
                        <!--- INICIO BOX --->
                        <div class="box">
                            <!--- INICIO HEADER --->
                            <div class="box-header with-border text-center ">
                                <h3 class="box-title text-bold">Efectivo</h3>
                            </div>
                            <!--- FIN HEADER --->
                            <form action="control.php" method="GET">
                                <input type="hidden" value="2" name="operacion">
                                <input type="hidden" name="vidarqueo" value="<?php echo $idarqueo ?>">

                                <!--- INICIO BODY --->
                                <?php if ($arqueo[0]['estado'] == 'PENDIENTE') {
                                    $desactivar = NULL;
                                } else {
                                    $desactivar = 'readonly';
                                }
                                ?>
                                <div class="box-body">

                                    <div class="form-group col-md-6">
                                        <h4 class="text-center text-bold">Billetes</h4>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <h4 class="text-center text-bold">Monedas</h4>
                                    </div>

                                    <div class="text-center">
                                        <div class="form-group col-md-2">
                                            <p class="text-bold">Valor</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <p class="text-bold">Cantidad</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <p class="text-bold">Total</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <p class="text-bold">Valor</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <p class="text-bold">Cantidad</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <p class="text-bold">Total</p>
                                        </div>
                                    </div>
                                    <p class="row">
                                    <div class="text-center">
                                        <div class="form-group col-md-2">
                                            <p class="text-bold">100.000 Gs</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="number" <?php echo $desactivar ?> name="vbi100" id="bi100" class="form-control restablecer" min="0" max="500" value="<?php echo $arqueo[0]['bi_100'] ?>" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 3 && event.keyCode != 8) return false;" onKeyDown="if (this.value.length == 15 && event.keyCode != 8) return false;" onchange="arqueoCaja()" onclick="arqueoCaja()" onkeyup="arqueoCaja()">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" id="totalbi100" class="form-control" readonly>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <p class="text-bold">1.000 Gs</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="number" <?php echo $desactivar ?> name="vmo1000" id="mo1000" class="form-control restablecer" min="0" max="500" value="<?php echo $arqueo[0]['mo_1000'] ?>" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 3 && event.keyCode != 8) return false;" onchange="arqueoCaja()" onclick="arqueoCaja()" onkeyup="arqueoCaja()">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" id="totalmo1000" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <p class="row"></p>
                                    <div class="text-center">
                                        <div class="form-group col-md-2">
                                            <p class="text-bold">50.000 Gs</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="number" <?php echo $desactivar ?> name="vbi50" id="bi50" class="form-control restablecer" min="0" max="500" value="<?php echo $arqueo[0]['bi_50'] ?>" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 3 && event.keyCode != 8) return false;" onchange="arqueoCaja()" onclick="arqueoCaja()" onkeyup="arqueoCaja()">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" id="totalbi50" class="form-control" readonly>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <p class="text-bold">500 Gs</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="number" <?php echo $desactivar ?> name="vmo500" id="mo500" class="form-control restablecer" min="0" max="500" value="<?php echo $arqueo[0]['mo_500'] ?>" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 3 && event.keyCode != 8) return false;" onchange="arqueoCaja()" onclick="arqueoCaja()" onkeyup="arqueoCaja()">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" id="totalmo500" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <p class="row"></p>
                                    <div class="text-center">
                                        <div class="form-group col-md-2">
                                            <p class="text-bold">20.000 Gs</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="number" <?php echo $desactivar ?> name="vbi20" id="bi20" class="form-control restablecer" min="0" max="500" value="<?php echo $arqueo[0]['bi_20'] ?>" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 3 && event.keyCode != 8) return false;" onchange="arqueoCaja()" onclick="arqueoCaja()" onkeyup="arqueoCaja()">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" id="totalbi20" class="form-control" readonly>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <p class="text-bold">100 Gs</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="number" <?php echo $desactivar ?> name="vmo100" id="mo100" class="form-control restablecer" min="0" max="500" value="<?php echo $arqueo[0]['mo_100'] ?>" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 3 && event.keyCode != 8) return false;" onchange="arqueoCaja()" onclick="arqueoCaja()" onkeyup="arqueoCaja()">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" id="totalmo100" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <p class="row"></p>
                                    <div class="text-center">
                                        <div class="form-group col-md-2">
                                            <p class="text-bold">10.000 Gs</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="number" <?php echo $desactivar ?> name="vbi10" id="bi10" class="form-control restablecer" min="0" max="500" value="<?php echo $arqueo[0]['bi_10'] ?>" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 3 && event.keyCode != 8) return false;" onchange="arqueoCaja()" onclick="arqueoCaja()" onkeyup="arqueoCaja()">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" id="totalbi10" class="form-control" readonly>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <p class="text-bold">50 Gs</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="number" <?php echo $desactivar ?> name="vmo50" id="mo50" class="form-control restablecer" min="0" max="500" value="<?php echo $arqueo[0]['mo_50'] ?>" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 3 && event.keyCode != 8) return false;" onchange="arqueoCaja()" onclick="arqueoCaja()" onkeyup="arqueoCaja()">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" id="totalmo50" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <p class="row"></p>
                                    <div class="text-center">
                                        <div class="form-group col-md-2">
                                            <p class="text-bold">5.000 Gs</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="number" <?php echo $desactivar ?> name="vbi5" id="bi5" class="form-control restablecer" min="0" max="500" value="<?php echo $arqueo[0]['bi_5'] ?>" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 3 && event.keyCode != 8) return false;" onchange="arqueoCaja()" onclick="arqueoCaja()" onkeyup="arqueoCaja()">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" id="totalbi5" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <p class="row"></p>
                                    <div class="text-center">
                                        <div class="form-group col-md-2">
                                            <p class="text-bold">2.000 Gs</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="number" <?php echo $desactivar ?> name="vbi2" id="bi2" class="form-control restablecer" min="0" max="500" value="<?php echo $arqueo[0]['bi_2'] ?>" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 3 && event.keyCode != 8) return false;" onchange="arqueoCaja()" onclick="arqueoCaja()" onkeyup="arqueoCaja()">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" id="totalbi2" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <p class="row"></p>
                                    <div class="text-center">
                                        <div class="form-group col-md-4">
                                            <p class="text-bold">Total Billetes</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" id="totalbilletes" class="form-control" readonly>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <p class="text-bold">Total Monedas</p>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <input type="text" id="totalmoneda" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <!--- FIN BODY --->
                                <!-- INICIO FOOTER -->
                                <div class="box-footer text-center">
                                    <?php if ($arqueo[0]['estado'] == 'PENDIENTE') { ?>
                                        <button type="submit" class="btn btn-success text-bold">Guardar</button>
                                    <?php } ?>
                                </div>
                                <!-- FIN FOOTER -->
                            </form>
                        </div>
                        <!--- FIN BOX --->
                        <!--- FIN EFECTIVOS --->


                        <!--- INICIO DETALLE CHEQUE --->
                        <div class="box" id="box_cheque">
                            <div class="box-header with-border">
                                <h3 class="box-title text-bold">Cheque</h3>
                                <?php if ($arqueo[0]['estado'] == 'PENDIENTE') { ?>
                                    <div class="box-tools">
                                        <button type="button" class="btn btn btn-success text-bold" data-toggle="modal" data-target="#agregar_detalle_cheque">Agregar Cheque</button>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="box-body">
                                <?php if (!empty($detalle_cheque)) { ?>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Tipo de Cheque</th>
                                                <th class="text-center">Banco</th>
                                                <th class="text-center">N° de Cheque</th>
                                                <th class="text-center">Monto</th>
                                                <?php if ($arqueo[0]['estado'] == 'PENDIENTE') { ?>
                                                    <th class="text-center">Acciones</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($detalle_cheque as $dch) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $dch['tipocheque'] ?></td>
                                                    <td class="text-center"><?php echo $dch['banco'] ?></td>
                                                    <td class="text-center"><?php echo $dch['nro_cheque'] ?></td>
                                                    <td class="text-center"><?php echo number_format($dch['monto'], 0, ',', '.') ?></td>
                                                    <?php if ($arqueo[0]['estado'] == 'PENDIENTE') { ?>
                                                        <th class="text-center">
                                                            <?php
                                                            $ordencheque = $dch['orden'];
                                                            $idbancocheque = $dch['id_banco'];
                                                            ?>
                                                            <a onclick="editarCheque('<?php echo $idbancocheque . '_' . $ordencheque . '_' . $idarqueo; ?>')" class="btn btn-sm btn-warning" role="button" data-title="Editar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#editarCheque">
                                                                <span class="fa fa-pencil"></span>
                                                            </a>
                                                            <a onclick="borrarCheque('<?php echo $ordencheque . '_' . $idarqueo; ?>')" class="btn btn-sm btn-danger" role="button" data-title="Borrar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modalBorrar">
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
                                        No existen nigun cheque en el detalle
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <!--- FIN DETALLE CHEQUE --->

                        <!--- INICIO DETALLE TARJETA --->
                        <div class="box" id="box_tarjeta">
                            <div class="box-header with-border">
                                <h3 class="box-title text-bold">Tarjetas</h3>
                                <?php if ($arqueo[0]['estado'] == 'PENDIENTE') { ?>
                                    <div class="box-tools">
                                        <button type="button" class="btn btn btn-success text-bold" data-toggle="modal" data-target="#agregar_detalle_tarjetas">Agregar Tarjeta</button>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="box-body">
                                <?php if (!empty($detalle_tarjeta)) { ?>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Tipo de Tarjeta</th>
                                                <th class="text-center">Comprobante</th>
                                                <th class="text-center">Monto</th>
                                                <?php if ($arqueo[0]['estado'] == 'PENDIENTE') { ?>
                                                    <th class="text-center">Acciones</th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($detalle_tarjeta as $dtj) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $dtj['tipotarjeta'] ?></td>
                                                    <td class="text-center"><?php echo $dtj['nro_comprobante'] ?></td>
                                                    <td class="text-center"><?php echo number_format($dtj['monto'], 0, ',', '.') ?></td>
                                                    <?php if ($arqueo[0]['estado'] == 'PENDIENTE') { ?>
                                                        <th class="text-center">
                                                            <?php
                                                            $ordentarjeta = $dtj['orden'];
                                                            ?>
                                                            <a onclick="editarTarjeta('<?php echo $ordentarjeta . '_' . $idarqueo; ?>')" class="btn btn-sm btn-warning" role="button" data-title="Editar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#editarTarjeta">
                                                                <span class="fa fa-pencil"></span>
                                                            </a>
                                                            <a onclick="borrarTarjeta('<?php echo $ordentarjeta . '_' . $idarqueo; ?>')" class="btn btn-sm btn-danger" role="button" data-title="Borrar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modalBorrar">
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
                                        No existen ninguna tarjeta en el detalle
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <!--- FIN DETALLE TARJETA --->
                        <!--- INICIO BOX SUBTOTAL --->
                        <div class="box">
                            <div class="box-body text-center">
                                <div class="form-group col-xs-3">
                                    <label>Total Efectivo</label>
                                    <input type="text" class="form-control" readonly id="total_efectivo">

                                </div>
                                <div class="form-group col-xs-3">
                                    <?php
                                    $sqltotalcheque = consultas::get_datos("SELECT COALESCE(SUM(monto),0) AS total FROM arqueo_det_cheques WHERE id_arqueo = $idarqueo");
                                    $totalpagarcheque = $sqltotalcheque[0]['total'];
                                    ?>
                                    <label>Total Cheque </label>
                                    <input type="text" class="form-control" value="<?php echo number_format($totalpagarcheque, 0, ',', '.') ?>" readonly>
                                </div>
                                <div class="form-group col-xs-3">
                                    <?php
                                    $sqltotaltarjeta = consultas::get_datos("SELECT COALESCE(SUM(monto),0) AS total FROM arqueo_det_tarjetas WHERE id_arqueo = $idarqueo");
                                    $totalpagartarjeta = $sqltotaltarjeta[0]['total'];
                                    ?>
                                    <label>Total Tarjeta</label>
                                    <input type="text" class="form-control" value="<?php echo number_format($totalpagartarjeta, 0, ',', '.') ?>" readonly>
                                </div>
                                <div class="form-group col-xs-3">
                                    <label>Total General</label>
                                    <input type="text" class="form-control" id="total_general" readonly>
                                </div>
                            </div>
                        </div>
                        <!--- FIN BOX SUBTOTAL--->

                        <!--- INICIO BOTONES --->
                        <!--- INICIO BOX --->
                        <div class="box">
                            <div class="box-header with-border text-center">
                                <?php if ($arqueo[0]['estado'] == 'PENDIENTE') { ?>
                                    <a onclick="confirmar(<?php echo $idarqueo ?>)" class="btn btn-success text-bold" data-toggle="modal" data-target="#confirmar_arqueo">Confirmar</a>
                                    <a class="btn btn-warning text-bold" data-toggle="modal" data-target="#modificar_apertura">Modificar Apertura</a>
                                <?php } ?>



                                <?php if ($arqueo[0]['estado'] == 'CONFIRMADO' ) { ?>
                                    <a href="imprimir.php?vidarqueo=<?php echo $idarqueo ?>" class="btn btn-primary text-bold">Imprimir</a>
                                    <a class="btn btn-danger text-bold" data-toggle="modal" data-target="#anular_arqueo">Anular</a>
                                    
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
        <!-- INICIO MODAL DETALLE CHEQUE -->
        <!-- INICIO MODAL AGREGAR DETALLE -->
        <?php $bancos = consultas::get_datos("SELECT * FROM bancos WHERE estado = 'ACTIVO' ORDER BY id_banco") ?>
        <div class="modal fade" id="agregar_detalle_cheque" role="dialog" aria-labelledby="DetalleCheque" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Agregar Cheque</strong></h4>
                    </div>
                    <form action="detalle_cheque_control.php" method="post" accept-charset="UTF-8">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="1">
                            <input type="hidden" name="vidarqueo" value="<?php echo $idarqueo ?>">
                            <div class="form-group">
                                <label>Banco</label>
                                <select class="form-control select2" name="vbanco" style="width: 100%;" id="cbx_bancos" required>
                                    <?php if (!empty($bancos)) { ?>
                                        <option value="" disabled="" selected="">Seleccione un Banco</option>
                                        <?php
                                        foreach ($bancos as $ban) {
                                        ?>
                                            <option value="<?php echo $ban['id_banco'] ?>"><?php echo $ban['descripcion']; ?></option>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <option value="">No existe ningun banco</option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="form-group col-xs-12">
                                    <?php $tipocheque = consultas::get_datos("SELECT * FROM tipocheque") ?>
                                    <label>Tipo de cheque</label>
                                    <select class="form-control" id="cbx_tipocheque" name="vtipocheque" required>
                                        <?php if (!empty($tipocheque)) { ?>
                                            <option value="" disabled="" selected="">Seleccione</option>
                                            <?php
                                            foreach ($tipocheque as $tpch) {
                                            ?>
                                                <option value="<?php echo $tpch['id_tipocheque'] ?>"><?php echo $tpch['descripcion']; ?></option>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <option value="">No existe ningun tipo</option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-xs-12">
                                    <label>N° de Cheque</label>
                                    <input type="text" class="form-control" name="vnrocheque" maxlength="100" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 15 && event.keyCode != 8) return false;">
                                </div>
                                <div class="form-group col-xs-12">
                                    <label>Monto</label>
                                    <input type="text" class="form-control" name="vmonto" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 15 && event.keyCode != 8) return false;">
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
        <!-- INICIO MODAL DE EDITAR -->
        <div class="modal fade" id="editarCheque" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" arial-label="Close">X</button>
                        <h4 class="modal-title custom_align" id="Heading"><strong>Editar Cheque</strong></h4>
                    </div>
                    <form action="detalle_cheque_control.php" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="2">
                            <input type="hidden" name="vidarqueo" value="<?php echo $idarqueo ?>">
                            <div class="row" id="editar_cheque_cobros">

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
        <!-- FIN MODAL DE EDITAR -->
        <!-- FIN MODAL DETALLE CHEQUE -->

        <!-- INICIO MODAL DETALLE TARJETA -->
        <!-- INICIO MODAL AGREGAR DETALLE -->
        <div class="modal fade" id="agregar_detalle_tarjetas" role="dialog" aria-labelledby="DetalleTarjeta" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Agregar Tarjetas</strong></h4>
                    </div>
                    <form action="detalle_tarjeta_control.php" method="post" accept-charset="UTF-8">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="1">
                            <input type="hidden" name="vidarqueo" value="<?php echo $idarqueo ?>">
                            <div class="row">
                                <div class="form-group col-xs-12">
                                    <?php $tipotarjeta = consultas::get_datos("SELECT * FROM tipotarjeta") ?>
                                    <label>Tipo de tarjeta</label>
                                    <select class="form-control" id="cbx_tipotarjeta" name="vtipotarjeta" required>
                                        <?php if (!empty($tipotarjeta)) { ?>
                                            <option value="" disabled="" selected="">Seleccione</option>
                                            <?php
                                            foreach ($tipotarjeta as $tj) {
                                            ?>
                                                <option value="<?php echo $tj['id_tipotarjeta'] ?>"><?php echo $tj['descripcion']; ?></option>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <option value="">No existe ningun tipo</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-xs-12">
                                    <label>N° de Comprobante</label>
                                    <input type="text" class="form-control" name="vnrocomprobante" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 15 && event.keyCode != 8) return false;">
                                </div>
                                <div class="form-group col-xs-12">
                                    <label>Monto</label>
                                    <input type="text" class="form-control" name="vmonto" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 15 && event.keyCode != 8) return false;">
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
        <!-- INICIO MODAL DE EDITAR -->
        <div class="modal fade" id="editarTarjeta" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" arial-label="Close">X</button>
                        <h4 class="modal-title custom_align" id="Heading"><strong>Editar Tarjeta</strong></h4>
                    </div>
                    <form action="detalle_tarjeta_control.php" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="2">
                            <input type="hidden" name="vidarqueo" value="<?php echo $idarqueo ?>">
                            <div id="editar_tarjeta_cobros">

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
        <!-- FIN MODAL DE EDITAR -->
        <!-- FIN MODAL DETALLE TARJETA -->
        <!-- INICIO MODAL DE BORRAR -->
        <div class="modal fade" id="modalBorrar" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" arial-label="Close">X</button>
                        <h4 class="modal-title custom_align" id="Heading"><strong>¡Atención!</strong></h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" id="confirmacion"></div>
                    </div>
                    <div class="modal-footer">
                        <a id="si" role="button" class="btn btn-primary">
                            <span class="glyphicon glyphicon-ok-sign"></span>Si
                        </a>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <span class="glyphicon glyphicon-remove"></span>No
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIN MODAL DE BORRAR -->

        <!-- INICIO MODAL CONFIRMAR ARQUEO -->
        <div class="modal fade" id="confirmar_arqueo" tabindex="-1" role="dialog" aria-labelledby="ConfirmarArqueo" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Confirmar Arqueo</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="post" accept-charset="UTF-8">
                            <input type="hidden" value="3" name="operacion">
                            <input type="hidden" name="vidarqueo" value="<?php echo $idarqueo ?>">
                            <input type="hidden" id="to_efectivo">
                            <input type="hidden" name="tcheque" value="<?php echo $totalpagarcheque ?>" id="total_cheque">
                            <input type="hidden" name="ttarjeta" value="<?php echo $totalpagartarjeta ?>" id="total_tarjeta">
                            <input type="hidden" name="tgeneral" id="total_general_enviar">

                            <h3 class="text-center">¿Deseas confirmar el arqueo?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-success">Si</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL CONFIRMAR ARQUEO -->
        <!--- INICIO MODIFICAR APERTURA --->
        <div class="modal fade" id="modificar_apertura" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Modificar Apertura</strong></h4>
                    </div>

                    <form action="control.php" method="post" accept-charset="UTF-8">
                        <div class="modal-body">
                            <input type="hidden" name="operacion" value="4">
                            <input type="hidden" name="vidarqueo" value="<?php echo $idarqueo ?>">
                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group">
                                        <?php
                                        $aperturacaja = consultas::get_datos("SELECT * FROM v_aperturacierre WHERE estado = 'ABIERTA' AND id_aperturacierre NOT IN (SELECT id_aperturacierre FROM arqueo WHERE id_arqueo = $idarqueo)") ?>
                                        <div class="form-group col-md-12">
                                            <label>Apertura N°</label>
                                            <select class="form-control select2" style="width: 100%;" name="vidaperturacierre" id="cbx_aperturacierre" required>
                                                <option value="<?php echo $arqueo[0]['id_aperturacierre']; ?>" selected=""><?php echo $arqueo[0]['nro_aperturacierre_larga'] . " (" . $arqueo[0]['caja'] . ")" ?></option>
                                                <?php
                                                foreach ($aperturacaja as $apt) {
                                                ?>
                                                    <option value="<?php echo $apt['id_aperturacierre']; ?>"><?php echo $apt['nro_aperturacierre_larga'] . " (" . $apt['caja'] . ")" ?></option>
                                                <?php
                                                } ?>
                                            </select>
                                        </div>

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
        <!--- FIN MODIFICAR APERTURA --->
        <!-- INICIO MODAL ANULAR PEDIDO -->
        <div class="modal fade" id="anular_arqueo" tabindex="-1" role="dialog" aria-labelledby="AnularArqueo" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center"><strong>Anular Arqueo</strong></h4>
                    </div>
                    <div class="modal-body">
                        <form action="control.php" method="POST" accept-charset="UTF-8">
                            <input type="hidden" value="<?php echo $idarqueo; ?>" name="vidarqueo">
                            <input type="hidden" value="5" name="operacion">
                            <h3 class="text-center">¿Deseas anular el arqueo de caja?</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-danger">Si</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- FIN MODAL ANULAR ARQUEO -->

        <!--- FIN MODAL --->
    </div>
    <?php require '../../estilos/pie.ctp' ?>
</body>
<?php
require '../../estilos/js_lte.ctp';
require '../../estilos/js_creado.ctp';
?>

<script>
    // ARQUEO DE CAJA
    function arqueoCaja() {
        //Billetes
        var bcien = parseInt($('#bi100').val());
        var bcincuenta = parseInt($('#bi50').val());
        var bveinte = parseInt($('#bi20').val());
        var bdiez = parseInt($('#bi10').val());
        var bcinco = parseInt($('#bi5').val());
        var bdos = parseInt($('#bi2').val());

        //Total Billetes
        var totalbcien = bcien * 100000;
        var totalbcincuenta = bcincuenta * 50000;
        var totalbveinte = bveinte * 20000;
        var totalbdiez = bdiez * 10000;
        var totalbcinco = bcinco * 5000;
        var totalbdos = bdos * 2000;
        var totalbillete = (totalbcien + totalbcincuenta + totalbveinte + totalbdiez + totalbcinco + totalbdos);

        // Datos
        $('#totalbi100').val(new Intl.NumberFormat('es-PY').format(totalbcien));
        $('#totalbi50').val(new Intl.NumberFormat('es-PY').format(totalbcincuenta));
        $('#totalbi20').val(new Intl.NumberFormat('es-PY').format(totalbveinte));
        $('#totalbi10').val(new Intl.NumberFormat('es-PY').format(totalbdiez));
        $('#totalbi5').val(new Intl.NumberFormat('es-PY').format(totalbcinco));
        $('#totalbi2').val(new Intl.NumberFormat('es-PY').format(totalbdos));
        $('#totalbilletes').val(new Intl.NumberFormat('es-PY').format(totalbillete));


        //Monedas
        var mmil = parseInt($('#mo1000').val());
        var mquinientos = parseInt($('#mo500').val());
        var mcien = parseInt($('#mo100').val());
        var mcincuenta = parseInt($('#mo50').val());

        //Total Monedas
        var totalmmil = mmil * 1000;
        var totalmquinientos = mquinientos * 500;
        var totalmcien = mcien * 100;
        var totalmcincuenta = mcincuenta * 50;
        var totalmoneda = (totalmmil + totalmquinientos + totalmcien + totalmcincuenta + totalmcincuenta);

        //Datos 
        $('#totalmo1000').val(new Intl.NumberFormat('es-PY').format(totalmmil));
        $('#totalmo500').val(new Intl.NumberFormat('es-PY').format(totalmquinientos));
        $('#totalmo100').val(new Intl.NumberFormat('es-PY').format(totalmcien));
        $('#totalmo50').val(new Intl.NumberFormat('es-PY').format(totalmcincuenta));
        $('#totalmoneda').val(new Intl.NumberFormat('es-PY').format(totalmoneda));


        // Calcular valores
        var efectivoreal = totalbillete + totalmoneda;
        $('#total_efectivo').val(new Intl.NumberFormat('es-PY').format(efectivoreal));
        $('#to_efectivo').val(efectivoreal);

        var tcheque = parseInt($('#total_cheque').val());
        var ttarjeta = parseInt($('#total_tarjeta').val());

        var totalgeneral = efectivoreal + tcheque + ttarjeta;
        $('#total_general').val(new Intl.NumberFormat('es-PY').format(totalgeneral));
        $('#total_general_enviar').val(totalgeneral);



    };

    window.onload = arqueoCaja();
</script>

<script>
    // DETALLE CHEQUES
    function editarCheque(datos) {
        var dat = datos.split("_");
        idbancocheque = dat[0];
        ordencheque = dat[1];
        idarqueo = dat[2];
        $.post("editarCheque.php", {
            idbancocheque: idbancocheque,
            ordencheque: ordencheque,
            idarqueo: idarqueo
        }, function(data) {
            $("#editar_cheque_cobros").html(data);
            $('.select2').select2();
            $("#cbx_bancos_editar").select2({
                dropdownParent: parentElement
            });
        });
    }

    // DETALLE TARJETAS
    function editarTarjeta(datos) {
        var dat = datos.split("_");
        ordentarjeta = dat[0];
        idarqueo = dat[1];
        $.post("editarTarjeta.php", {
            ordentarjeta: ordentarjeta,
            idarqueo: idarqueo
        }, function(data) {
            $("#editar_tarjeta_cobros").html(data);
            $('.select2').select2();
            $("#cbx_bancostj").select2({
                dropdownParent: parentElement
            });
        });
    }

    function borrarCheque(datos) {
        var dat = datos.split("_");
        $('#si').attr('href', 'detalle_cheque_control.php?vorden=' + dat[0] + '&vidarqueo=' + dat[1] + '&operacion=3');
        $('#confirmacion').html('<span class="glyphicon glyphicon-warning-sign"></span> ¿Deseas quitar el cheque del detalle?')
    }

    function borrarTarjeta(datos) {
        var dat = datos.split("_");
        $('#si').attr('href', 'detalle_tarjeta_control.php?vorden=' + dat[0] + '&vidarqueo=' + dat[1] + '&operacion=3');
        $('#confirmacion').html('<span class="glyphicon glyphicon-warning-sign"></span> ¿Deseas quitar la tarjeta del detalle?')
    }
</script>

</html>