<?php
session_start();
if ($_SESSION == NULL) {
  $_SESSION['error'] = 'Debes iniciar sesión para usar el sistema';
  header('location:/graficanissei_taller/');
}
date_default_timezone_set('America/Asuncion');
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
$idcobro = $_REQUEST['vidcobro'];
$idcajero = $_SESSION['nick'];
$apertura = consultas::get_datos("SELECT * FROM v_aperturacierre WHERE nick = '$idcajero' AND estado = 'ABIERTA'");
$cobros = consultas::get_datos("SELECT * FROM v_cobros WHERE id_cobro = $idcobro ");
$detallecobro = consultas::get_datos("SELECT * FROM v_cobros_det WHERE id_cobro = $idcobro ");
$detallecheques = consultas::get_datos("SELECT * FROM v_cobros_det_cheques WHERE id_cobro = $idcobro ORDER BY orden ASC");
$detalletarjetas = consultas::get_datos("SELECT * FROM v_cobros_det_tarjeta WHERE id_cobro = $idcobro ORDER BY orden ASC");
$detalletransferencia = consultas::get_datos("SELECT * FROM v_cobros_det_transferencia WHERE id_cobro = $idcobro ORDER BY orden ASC");

$idcliente = $cobros[0]['id_cliente'];


?>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper" style="background-color:#1e282c; ">
    <?php require '../../estilos/cabecera.ctp'; ?>
    <?php require '../../estilos/izquierda.ctp'; ?>
    <div class="content-wrapper" style="background-color: rgb(241, 231, 254);">
      <div class="content">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-xs-12">
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

            <?php // INICIO CONTENIDO 
            ?>
            <!--- INICIO CABEZERA --->
            <div class="box box-primary">
              <div class="box-header with-border">
                <i class="fa fa-shopping-cart"></i>
                <h3 class="box-title">Ventas</h3>
                <div class="box-tools">
                  <a class="btn btn-primary pull-right btn-sm" onclick="history.back()" role="button" data-title="Volver" data-placement="top" rel="tooltip">
                    <i class="fa fa-arrow-left"></i>
                  </a>
                </div>
              </div>

              <div class="box-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th class="text-center">ID</th>
                      <th class="text-center">Fecha</th>
                      <th class="text-center">Cobro N°</th>
                      <th class="text-center">Cliente</th>
                      <th class="text-center">RUC</th>
                      <th class="text-center">Cajero</th>
                      <th class="text-center">Total</th>
                      <th class="text-center">Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($cobros as $c) { ?>
                      <tr>
                        <td class="text-center"><?php echo $c['id_cobro'] ?></td>
                        <td class="text-center"><?php echo $c['fecha_corta'] ?></td>
                        <td class="text-center"><?php echo $c['nro_cobro_larga'] ?></td>
                        <td class="text-center"><?php echo $c['razon_social'] ?></td>
                        <td class="text-center"><?php echo $c['nro_ruc'] ?></td>
                        <td class="text-center"><?php echo $c['usuario'] ?></td>
<td class="text-center">
  <?php echo $c['total_factura'] !== null 
    ? number_format($c['total_factura'], 0, ',', '.') 
    : '0'; ?>
</td>
                        <td class="text-center"><?php echo $c['estado'] ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!--- FIN CABEZERA --->

            <!--- INICIO DETALLE --->
            <!--- INICIO DETALLE COBRO --->
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title text-bold">Detalle de Cobro</h3>
                <?php if ($c['estado'] == 'PENDIENTE') { ?>
                  <div class="box-tools">
                    <button type="button" class="btn btn btn-success text-bold" data-toggle="modal" data-target="#agregar_detalle_cobro">Agregar</button>
                  </div>
                <?php } ?>
              </div>
              <div class="box-body">
                <?php if (!empty($detallecobro)) { ?>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="text-center">N° de Factura</th>
                        <th class="text-center">Cantidad de Cuota</th>
                        <th class="text-center">Total</th>
                        <?php if ($c['estado'] == 'PENDIENTE') { ?>
                          <th class="text-center">Acciones</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($detallecobro as $dc) { ?>
                        <tr>
                          <td class="text-center"><?php echo $dc['factura_completa'] ?></td>
                          <td class="text-center"><?php echo $dc['cant_cuota'] ?></td>
                          <td class="text-center"><?php echo number_format($dc['subtotal'], 0, ',', '.') ?></td>
                          <?php if ($c['estado'] == 'PENDIENTE') { ?>
                            <th class="text-center">
                              <?php
                              $idcuentacobrar = $dc['id_cuentacobrar'];
                              $idventa = $dc['id_venta'];
                              ?>
                              <a onclick="editarFactura('<?php echo $idcuentacobrar . '_' . $idventa . '_' . $idcobro; ?>')" class="btn btn-sm btn-warning" role="button" data-title="Editar Factura" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#editarFactura">
                                <span class="fa fa-pencil"></span>
                              </a>
                              <a onclick="borrarFactura('<?php echo $idcuentacobrar . '_' . $idventa . '_' . $idcobro; ?>')" class="btn btn-sm btn-danger" role="button" data-title="Borrar Factura" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modalBorrar">
                                <span class="glyphicon glyphicon-trash"></span>
                              </a>
                            </th>
                          <?php } ?>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                  <h4 class="box-title text-center text-bold">Forma de cobro</h4>
                  <div class="form-group text-center text-bold">
                    <div class="col-xs-3">
                      <input type="checkbox" name="" id="check_efectivo" checked disabled> Efectivo
                    </div>
                    <div class="col-xs-3">
                      <input type="checkbox" value="ccheque" name="ccheque" id="check_cheque" onchange="javascript:mostrarCheque()" <?php if (empty(!$detallecheques)) {
                                                                                                                                      echo 'checked disabled';
                                                                                                                                    } ?>> Cheque
                    </div>
                    <div class="col-xs-3">
                      <input type="checkbox" name="" id="check_tarjeta" onchange="javascript:mostrarTarjeta()" <?php if (empty(!$detalletarjetas)) {
                                                                                                                  echo 'checked disabled';
                                                                                                                } ?>> Tarjeta
                    </div>
                    <div class="col-xs-3">
                      <input type="checkbox" name="" id="check_transferencia" onchange="javascript:mostrarTransferencia()" <?php if (empty(!$detalletransferencia)) {
                                                                                                                              echo 'checked disabled';
                                                                                                                            } ?>> Transferencia
                    </div>
                  </div>
                <?php } else { ?>
                  <div class="alert alert-info flat">
                    <span class="glyphicon glyphicon-info-sign"></span>
                    No existen ninguna factura en el detalle
                  </div>
                <?php } ?>
              </div>
            </div>
            <!--- FIN DETALLE COBRO --->
            <!--- INICIO DETALLE CHEQUE --->
            <div class="box" id="box_cheque" style="display: <?php if (empty(!$detallecheques)) {
                                                                echo 'display';
                                                              } else {
                                                                echo 'none';
                                                              } ?>;">
              <div class="box-header with-border">
                <h3 class="box-title text-bold">Cheque</h3>
                <?php if ($c['estado'] == 'PENDIENTE') { ?>
                  <div class="box-tools">
                    <button type="button" class="btn btn btn-success text-bold" data-toggle="modal" data-target="#agregar_detalle_cheque">Agregar</button>
                  </div>
                <?php } ?>
              </div>
              <div class="box-body">
                <?php if (!empty($detallecheques)) { ?>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="text-center">Tipo de Cheque</th>
                        <th class="text-center">Banco</th>
                        <th class="text-center">N° de Cheque</th>
                        <th class="text-center">Monto</th>
                        <?php if ($c['estado'] == 'PENDIENTE') { ?>
                          <th class="text-center">Acciones</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($detallecheques as $dch) { ?>
                        <tr>
                          <td class="text-center"><?php echo $dch['tipocheque'] ?></td>
                          <td class="text-center"><?php echo $dch['banco'] ?></td>
                          <td class="text-center"><?php echo $dch['nro_cheque'] ?></td>
                          <td class="text-center"><?php echo number_format($dch['monto'], 0, ',', '.') ?></td>
                          <?php if ($c['estado'] == 'PENDIENTE') { ?>
                            <th class="text-center">
                              <?php
                              $idbancocheque = $dch['id_banco'];
                              $ordencheque = $dch['orden'];
                              ?>
                              <a onclick="editarCheque('<?php echo $idbancocheque . '_' . $ordencheque . '_' . $idcobro; ?>')" class="btn btn-sm btn-warning" role="button" data-title="Editar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#editarCheque">
                                <span class="fa fa-pencil"></span>
                              </a>
                              <a onclick="borrarCheque('<?php echo $idbancocheque . '_' . $ordencheque . '_' . $idcobro; ?>')" class="btn btn-sm btn-danger" role="button" data-title="Borrar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modalBorrar">
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
            <div class="box" id="box_tarjeta" style="display: <?php if (empty(!$detalletarjetas)) {
                                                                echo 'display';
                                                              } else {
                                                                echo 'none';
                                                              } ?>;">
              <div class="box-header with-border">
                <h3 class="box-title text-bold">Tarjetas</h3>
                <?php if ($c['estado'] == 'PENDIENTE') { ?>
                  <div class="box-tools">
                    <button type="button" class="btn btn btn-success text-bold" data-toggle="modal" data-target="#agregar_detalle_tarjetas">Agregar</button>
                  </div>
                <?php } ?>
              </div>
              <div class="box-body">
                <?php if (!empty($detalletarjetas)) { ?>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="text-center">Tipo de Tarjeta</th>
                        <th class="text-center">Marca</th>
                        <th class="text-center">Banco</th>
                        <th class="text-center">Comprobante</th>
                        <th class="text-center">Monto</th>
                        <?php if ($c['estado'] == 'PENDIENTE') { ?>
                          <th class="text-center">Acciones</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($detalletarjetas as $dtj) { ?>
                        <tr>
                          <td class="text-center"><?php echo $dtj['tipotarjeta'] ?></td>
                          <td class="text-center"><?php echo $dtj['marcatarjeta'] ?></td>
                          <td class="text-center"><?php echo $dtj['banco'] ?></td>
                          <td class="text-center"><?php echo $dtj['nro_comprobante'] ?></td>
                          <td class="text-center"><?php echo number_format($dtj['monto'], 0, ',', '.') ?></td>
                          <?php if ($c['estado'] == 'PENDIENTE') { ?>
                            <th class="text-center">
                              <?php
                              $idbancotarjeta = $dtj['id_banco'];
                              $ordentarjeta = $dtj['orden'];
                              ?>
                              <a onclick="editarTarjeta('<?php echo $idbancotarjeta . '_' . $ordentarjeta . '_' . $idcobro; ?>')" class="btn btn-sm btn-warning" role="button" data-title="Editar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#editarTarjeta">
                                <span class="fa fa-pencil"></span>
                              </a>
                              <a onclick="borrarTarjeta('<?php echo $idbancotarjeta . '_' . $ordentarjeta . '_' . $idcobro; ?>')" class="btn btn-sm btn-danger" role="button" data-title="Borrar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modalBorrar">
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

            <!--- INICIO DETALLE TRANSFERENCIA --->
            <div class="box" id="box_transferencia" style="display: <?php if (empty(!$detalletransferencia)) {
                                                                      echo 'display';
                                                                    } else {
                                                                      echo 'none';
                                                                    } ?>;">
              <div class="box-header with-border">
                <h3 class="box-title text-bold">Transferencia</h3>
                <?php if ($c['estado'] == 'PENDIENTE') { ?>
                  <div class="box-tools">
                    <button type="button" class="btn btn btn-success text-bold" data-toggle="modal" data-target="#agregar_detalle_transferencia">Agregar</button>
                  </div>
                <?php } ?>
              </div>
              <div class="box-body">
                <?php if (!empty($detalletransferencia)) { ?>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="text-center">Banco</th>
                        <th class="text-center">Comprobante</th>
                        <th class="text-center">Monto</th>
                        <?php if ($c['estado'] == 'PENDIENTE') { ?>
                          <th class="text-center">Acciones</th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($detalletransferencia as $dtf) { ?>
                        <tr>
                          <td class="text-center"><?php echo $dtf['banco'] ?></td>
                          <td class="text-center"><?php echo $dtf['nro_transferencia'] ?></td>
                          <td class="text-center"><?php echo number_format($dtf['monto'], 0, ',', '.') ?></td>
                          <?php if ($c['estado'] == 'PENDIENTE') { ?>
                            <th class="text-center">
                              <?php
                              $idbancotransferencia = $dtf['id_banco'];
                              $ordentransferencia = $dtf['orden'];
                              ?>
                              <a onclick="editarTransferencia('<?php echo $idbancotransferencia . '_' . $ordentransferencia . '_' . $idcobro; ?>')" class="btn btn-sm btn-warning" role="button" data-title="Editar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#editarTransferencia">
                                <span class="fa fa-pencil"></span>
                              </a>
                              <a onclick="borrarTransferencia('<?php echo $idbancotransferencia . '_' . $ordentransferencia . '_' . $idcobro; ?>')" class="btn btn-sm btn-danger" role="button" data-title="Borrar" data-placement="top" rel="tooltip" data-toggle="modal" data-target="#modalBorrar">
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
                    No existen ninguna transferencia en el detalle
                  </div>
                <?php } ?>
              </div>
            </div>
            <!--- FIN DETALLE TRANSFERENCIA --->
            <!--- FIN DETALLE --->

            <!--- INICIO TOTAL A PAGAR --->
            <div class="box">
              <div class="box-body text-center">
                <div class="form-group col-xs-4">
                  <?php
                  $sqltotalpagar = consultas::get_datos("SELECT SUM(subtotal) AS total FROM v_cobros_det WHERE id_cobro = $idcobro");
                  $totalpagar = $sqltotalpagar[0]['total'];
                  $totalefectivo = $cobros[0]['efectivo'];
                  ?>
                  <label>Total a Pagar</label>
                  <input type="text" class="form-control" value="<?php echo number_format($c['total_factura'] ?? 0, 0, ',', '.');
 ?>" readonly>
                  <input type="hidden" id="monto_totalpagar" value="<?php echo $totalpagar ?>">
                </div>
                <div class="form-group col-xs-4">
                  <label>Efectivo</label>
                  <?php if ($c['estado'] == 'PENDIENTE') { ?>
                    <input type="text" class="form-control" id="monto_efectivo" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 15 && event.keyCode != 8) return false;" onchange="calcularvuelto()" onclick="calcularvuelto()" onkeyup="calcularvuelto()" value="0">
                  <?php } else { ?>
                    <input type="text" class="form-control" value="<?php echo number_format($totalefectivo, 0, ',', '.') ?>" readonly>
                    <input type="hidden" id="monto_efectivo" value="<?php echo $totalefectivo ?>">
                  <?php } ?>
                </div>
                <div class="form-group col-xs-4">
                  <label>Vuelto</label>
                  <input type="text" class="form-control" id="monto_vuelto" readonly>
                  <input type="hidden" name="vvuelto" id="monto_vuelto_enviar">
                </div>
                <div class="form-group col-xs-4">
                  <?php
                  $sqltotalcheque = consultas::get_datos("SELECT COALESCE(SUM(monto),0) AS total FROM cobros_det_cheques WHERE id_cobro = $idcobro");
                  $totalpagarcheque = $sqltotalcheque[0]['total'];
                  ?>
                  <label>Cheque</label>
                  <input type="text" class="form-control" value="<?php echo number_format($totalpagarcheque, 0, ',', '.') ?>" readonly>
                  <input type="hidden" id="monto_cheque" value="<?php echo $totalpagarcheque ?>">
                </div>
                <div class="form-group col-xs-4">
                  <?php
                  $sqltotaltarjeta = consultas::get_datos("SELECT COALESCE(SUM(monto),0) AS total FROM cobros_det_tarjeta WHERE id_cobro = $idcobro");
                  $totalpagartarjeta = $sqltotaltarjeta[0]['total'];
                  ?>
                  <label>Tarjeta</label>
                  <input type="text" class="form-control" value="<?php echo number_format($totalpagartarjeta, 0, ',', '.') ?>" readonly>
                  <input type="hidden" id="monto_tarjeta" value="<?php echo $totalpagartarjeta ?>">
                </div>
                <div class="form-group col-xs-4">
                  <?php
                  $sqltotaltransferencia = consultas::get_datos("SELECT COALESCE(SUM(monto),0) AS total FROM cobros_det_transferencia WHERE id_cobro = $idcobro");
                  $totalpagartransferencia = $sqltotaltransferencia[0]['total'];
                  ?>
                  <label>Transferencia</label>
                  <input type="text" class="form-control" value="<?php echo number_format($totalpagartransferencia, 0, ',', '.') ?>" readonly>
                  <input type="hidden" id="monto_transferencia" value="<?php echo $totalpagartransferencia ?>">
                </div>
              </div>


              <div class="box-footer text-center">
                <?php if ($c['estado'] == 'PENDIENTE') { ?>
                  <a id="bonton_confirmar" class="btn btn-success text-bold" data-toggle="modal" data-target="#confirmar_cobro">Confirmar Cobro</a>
                  <a class="btn btn-danger text-bold" data-toggle="modal" data-target="#anular_cobro">Anular Cobro</a>
                <?php } ?>
                <?php if ($c['estado'] == 'CONFIRMADO') { ?>
                  <a href="imprimir.php?vidcobro=<?php echo $idcobro ?>" class="btn btn-primary text-bold">Imprimir Recibo</a> <?php } ?>
              </div>

            </div>
            <!--- FIN TOTAL A PAGAR --->







            <?php // FIN CONTENIDO 
            ?>

          </div>
        </div>
      </div>


      <!-- INICIO MODAL DETALLE COBROS -->
      <!-- INICIO MODAL AGREGAR DETALLE -->
      <?php $cuentascobrar = consultas::get_datos("SELECT * FROM v_cuentascobrar WHERE (estado = 'PENDIENTE' OR estado = 'PAGO PARCIAL') AND id_cliente = $idcliente AND id_cuentacobrar NOT IN (SELECT id_cuentacobrar FROM v_cobros_det WHERE cuota_restante < 0) AND id_cuentacobrar NOT IN (SELECT id_cuentacobrar FROM v_cobros_det WHERE id_cobro = $idcobro) ORDER BY factura_completa") ?>
      <div class="modal fade" id="agregar_detalle_cobro" tabindex="-1" role="dialog" aria-labelledby="DetalleCobro" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center"><strong>Agregar Factura</strong></h4>
            </div>
            <form action="detalle_cobro_control.php" method="post" accept-charset="UTF-8">
              <div class="modal-body">
                <input type="hidden" name="operacion" value="1">
                <input type="hidden" name="vidcobro" value="<?php echo $idcobro ?>">
                <div class="form-group">
                  <label>Facturas</label>
                  <select class="form-control select2" style="width: 100%;" id="cbx_cuentacobrar" required>
                    <?php if (!empty($cuentascobrar)) { ?>
                      <option value="" disabled="" selected="">Seleccione una factura</option>
                      <?php
                      foreach ($cuentascobrar as $cutcob) {
                      ?>
                        <option value="<?php echo $cutcob['id_cuentacobrar'] . '_' . $cutcob['id_venta']; ?>"><?php echo $cutcob['factura_completa']; ?></option>
                      <?php
                      }
                    } else {
                      ?>
                      <option value="">No existe ninguna factura</option>
                    <?php } ?>
                  </select>
                </div>

                <div class="row" id="datos_factura">

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
      <div class="modal fade" id="editarFactura" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" arial-label="Close">X</button>
              <h4 class="modal-title custom_align" id="Heading"><strong>Editar Factura</strong></h4>
            </div>
            <form action="detalle_cobro_control.php" method="post">
              <div class="modal-body">
                <input type="hidden" name="operacion" value="2">
                <input type="hidden" name="vidcobro" value="<?php echo $idcobro ?>">
                <div class="row" id="editar_cantidad_cobros">

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
      <!-- FIN MODAL DETALLE COBROS -->


      <!-- INICIO MODAL DETALLE CHEQUE -->
      <!-- INICIO MODAL AGREGAR DETALLE -->
      <?php $bancos = consultas::get_datos("SELECT * FROM bancos ORDER BY id_banco") ?>
      <div class="modal fade" id="agregar_detalle_cheque" role="dialog" aria-labelledby="DetalleCheque" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center"><strong>Agregar Cheque</strong></h4>
            </div>
            <form action="detalle_cheque_control.php" method="post" accept-charset="UTF-8">
              <div class="modal-body">
                <input type="hidden" name="operacion" value="1">
                <input type="hidden" name="vidcobro" value="<?php echo $idcobro ?>">
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
                  <div class="form-group col-xs-4">
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
                  <div class="form-group col-xs-4">
                    <label>N° de Cheque</label>
                    <input type="text" class="form-control" name="vnrocheque" maxlength="100" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 15 && event.keyCode != 8) return false;">
                  </div>
                  <div class="form-group col-xs-4">
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
                <input type="hidden" name="vidcobro" value="<?php echo $idcobro ?>">
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
      <?php $bancostj = consultas::get_datos("SELECT * FROM bancos ORDER BY id_banco") ?>
      <div class="modal fade" id="agregar_detalle_tarjetas" role="dialog" aria-labelledby="DetalleTarjeta" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center"><strong>Agregar Tarjetas</strong></h4>
            </div>
            <form action="detalle_tarjeta_control.php" method="post" accept-charset="UTF-8">
              <div class="modal-body">
                <input type="hidden" name="operacion" value="1">
                <input type="hidden" name="vidcobro" value="<?php echo $idcobro ?>">
                <div class="form-group">
                  <label>Banco</label>
                  <select class="form-control select2" name="vbanco" style="width: 100%;" id="cbx_bancostj" required>
                    <?php if (!empty($bancostj)) { ?>
                      <option value="" disabled="" selected="">Seleccione un Banco</option>
                      <?php
                      foreach ($bancostj as $bantj) {
                      ?>
                        <option value="<?php echo $bantj['id_banco'] ?>"><?php echo $bantj['descripcion']; ?></option>
                      <?php
                      }
                    } else {
                      ?>
                      <option value="">No existe ningun banco</option>
                    <?php } ?>
                  </select>
                </div>

                <div class="row">
                  <div class="form-group col-xs-6">
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
                  <div class="form-group col-xs-6">
                    <?php $marcaTarjeta = consultas::get_datos("SELECT * FROM marcatarjeta") ?>
                    <label>Marca Tarjeta</label>
                    <select class="form-control select2" id="cbx_marcatarjeta" style="width: 100%;" name="vmarcatarjeta" required>
                      <?php if (!empty($marcaTarjeta)) { ?>
                        <option value="" disabled="" selected="">Seleccione una marca</option>
                        <?php
                        foreach ($marcaTarjeta as $mtj) {
                        ?>
                          <option value="<?php echo $mtj['id_marcatarjeta'] ?>"><?php echo $mtj['descripcion']; ?></option>
                        <?php
                        }
                      } else {
                        ?>
                        <option value="">No existe ninguna marca</option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-xs-6">
                    <label>N° de Comprobante</label>
                    <input type="text" class="form-control" name="vnrocomprobante" maxlength="100" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 15 && event.keyCode != 8) return false;">
                  </div>
                  <div class="form-group col-xs-6">
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
                <input type="hidden" name="vidcobro" value="<?php echo $idcobro ?>">
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

      <!-- INICIO MODAL DETALLE TRANSFERENCIA -->
      <!-- INICIO MODAL AGREGAR DETALLE -->
      <?php $bancos = consultas::get_datos("SELECT * FROM bancos ORDER BY id_banco") ?>
      <div class="modal fade" id="agregar_detalle_transferencia" role="dialog" aria-labelledby="DetalleTransferencia" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-center"><strong>Agregar Transferencia</strong></h4>
            </div>
            <form action="detalle_transferencia_control.php" method="post" accept-charset="UTF-8">
              <div class="modal-body">
                <input type="hidden" name="operacion" value="1">
                <input type="hidden" name="vidcobro" value="<?php echo $idcobro ?>">
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
                  <div class="form-group col-xs-6">
                    <label>N° de Transferencia</label>
                    <input type="text" class="form-control" name="vnrotransferencia" maxlength="100" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onKeyDown="if (this.value.length == 15 && event.keyCode != 8) return false;">
                  </div>
                  <div class="form-group col-xs-6">
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
      <div class="modal fade" id="editarTransferencia" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" arial-label="Close">X</button>
              <h4 class="modal-title custom_align" id="Heading"><strong>Editar Transferencia</strong></h4>
            </div>
            <form action="detalle_transferencia_control.php" method="post">
              <div class="modal-body">
                <input type="hidden" name="operacion" value="2">
                <input type="hidden" name="vidcobro" value="<?php echo $idcobro ?>">
                <div id="editar_transferencia_cobros">

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
      <!-- FIN MODAL DETALLE TRANSFERENCIA -->











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

      <!-- INICIO MODAL BOTONES -->
      <!-- INICIO MODAL ANULAR COBRO -->
      <div class="modal fade" id="anular_cobro" tabindex="-1" role="dialog" aria-labelledby="AnularCobro" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form action="cobros_control.php" method="post">
              <div class="modal-header">
                <h4 class="modal-title text-center"><strong>Anular Cobro</strong></h4>
              </div>
              <div class="modal-body">
                <input type="hidden" value="<?php echo $idcobro; ?>" name="vidcobro">
                <input type="hidden" value="3" name="operacion">
                <h3 class="text-center">¿Estás seguro de que deseas anular el cobro?</h3>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger">Si</button>
              </div>
            </form>
          </div>
          </form>
        </div>
      </div>
      <!-- FIN MODAL ANULAR COBRO -->
      <!-- INICIO MODAL CONFIRMAR COBRO -->
      <div class="modal fade" id="confirmar_cobro" tabindex="-1" role="dialog" aria-labelledby="ConfirmarCobro" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form action="cobros_control.php" method="post">
              <div class="modal-header">
                <h4 class="modal-title text-center"><strong>Confirmar Cobro</strong></h4>
              </div>
              <div class="modal-body">
                <input type="hidden" value="<?php echo $idcobro; ?>" name="vidcobro">
                <input type="hidden" value="2" name="operacion">
                <input type="hidden" id="enviarefectivo" value="" name="vefectivo">
                <input type="hidden" id="enviarvuelto" value="" name="vvuelto">
                <input type="hidden" name="vidaperturacierre" value="<?php echo $apertura[0]['id_aperturacierre'] ?>">
                <input type="hidden" name="vidcajaregistradora" value="<?php echo $apertura[0]['id_caja'] ?>">
                <h3 class="text-center">¿Estás seguro de que deseas confirmar el cobro?</h3>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-success">Si</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- FIN MODAL CONFIRMAR COBRO -->
      <!-- FIN MODAL BOTONES -->

    </div>
    <?php require '../../estilos/pie.ctp'; ?>
</body>
<?php require '../../estilos/js_lte.ctp'; ?>
<script>
  $("#mensaje").delay(5000).slideUp(200, function() {
    $(this).alert('close');
  });

  // DETALLE COBROS
  function borrarFactura(datos) {
    var dat = datos.split("_");
    $('#si').attr('href', 'detalle_cobro_control.php?vidcuentacobrar=' + dat[0] + '&vidventa=' + dat[1] + '&vidcobro=' + dat[2] + '&operacion=3');
    $('#confirmacion').html('<span class="glyphicon glyphicon-warning-sign"></span> ¿Deseas quitar la factura del detalle?')
  }

  function editarFactura(datos) {
    var dat = datos.split("_");
    cuentacobrar = dat[0];
    idventa = dat[1];
    idcobro = dat[2];
    $.post("editarFactura.php", {
      cuentacobrar: cuentacobrar,
      idventa: idventa,
      idcobro: idcobro
    }, function(data) {
      $("#editar_cantidad_cobros").html(data);
      totalfactura();
    });

  }
  // DETALLE CHEQUES
  function editarCheque(datos) {
    var dat = datos.split("_");
    idbancocheque = dat[0];
    ordencheque = dat[1];
    idcobro = dat[2];
    $.post("editarCheque.php", {
      idbancocheque: idbancocheque,
      ordencheque: ordencheque,
      idcobro: idcobro
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
    idbancotarjeta = dat[0];
    ordentarjeta = dat[1];
    idcobro = dat[2];
    $.post("editarTarjeta.php", {
      idbancotarjeta: idbancotarjeta,
      ordentarjeta: ordentarjeta,
      idcobro: idcobro
    }, function(data) {
      $("#editar_tarjeta_cobros").html(data);
      $('.select2').select2();
      $("#cbx_bancostj").select2({
        dropdownParent: parentElement
      });
    });
  }

  // DETALLE TRANSFERENCIA
  function editarTransferencia(datos) {
    var dat = datos.split("_");
    idbancotransferencia = dat[0];
    ordentransferencia = dat[1];
    idcobro = dat[2];
    $.post("editarTransferencia.php", {
      idbancotransferencia: idbancotransferencia,
      ordentransferencia: ordentransferencia,
      idcobro: idcobro
    }, function(data) {
      $("#editar_transferencia_cobros").html(data);
      $('.select2').select2();
      $("#cbx_bancos_transferencia").select2({
        dropdownParent: parentElement
      });
    });
  }






  function borrarCheque(datos) {
    var dat = datos.split("_");
    $('#si').attr('href', 'detalle_cheque_control.php?vbanco=' + dat[0] + '&vorden=' + dat[1] + '&vidcobro=' + dat[2] + '&operacion=3');
    $('#confirmacion').html('<span class="glyphicon glyphicon-warning-sign"></span> ¿Deseas quitar el cheque del detalle?')
  }

  function borrarTarjeta(datos) {
    var dat = datos.split("_");
    $('#si').attr('href', 'detalle_tarjeta_control.php?vbanco=' + dat[0] + '&vorden=' + dat[1] + '&vidcobro=' + dat[2] + '&operacion=3');
    $('#confirmacion').html('<span class="glyphicon glyphicon-warning-sign"></span> ¿Deseas quitar la tarjeta del detalle?')
  }

  function borrarTransferencia(datos) {
    var dat = datos.split("_");
    $('#si').attr('href', 'detalle_transferencia_control.php?vbanco=' + dat[0] + '&vorden=' + dat[1] + '&vidcobro=' + dat[2] + '&operacion=3');
    $('#confirmacion').html('<span class="glyphicon glyphicon-warning-sign"></span> ¿Deseas quitar la transferencia del detalle?')
  }







  $(document).ready(function() {
    $("#cbx_cuentacobrar").change(function() {
      $("#cbx_cuentacobrar option:selected").each(function() {
        valorestomado = $(this).val();
        var valret = valorestomado.split("_");
        $.post("obtenerDatosFactura.php", {
          idcuentacobrar: valret[0],
          idventa: valret[1],
        }, function(data) {
          $("#datos_factura").html(data);
        });
      });
    })
  });



  // PARA QUE FUNCIONE EL SELECT2 DENTRO DE UN MODAL
  $(document).ready(function() {
    $("#cbx_cuentacobrar").select2({
      dropdownParent: $("#agregar_detalle_cobro")
    });
  });


  function totalfactura() {
    var cuota = parseInt($('#cuota_valor').val());
    var cantidad = parseInt($('#cantidad_pagar').val());
    var total = cantidad * cuota;
    $('#totalcuota').val(new Intl.NumberFormat('es-PY').format(total));
  };

  function mostrarCheque() {
    contenido = document.getElementById("box_cheque");
    check = document.getElementById("check_cheque");
    if (check.checked) {
      contenido.style.display = 'block';
    } else {
      contenido.style.display = 'none';
    }
  }

  function mostrarTarjeta() {
    contenido = document.getElementById("box_tarjeta");
    check = document.getElementById("check_tarjeta");
    if (check.checked) {
      contenido.style.display = 'block';
    } else {
      contenido.style.display = 'none';
    }
  }

  function mostrarTransferencia() {
    contenido = document.getElementById("box_transferencia");
    check = document.getElementById("check_transferencia");
    if (check.checked) {
      contenido.style.display = 'block';
    } else {
      contenido.style.display = 'none';
    }
  }


  function calcularvuelto() {
    var totalpagar = parseInt($('#monto_totalpagar').val());
    var efectivo = parseInt($('#monto_efectivo').val());
    var cheque = parseInt($('#monto_cheque').val());
    var tarjeta = parseInt($('#monto_tarjeta').val());
    var transferencia = parseInt($('#monto_transferencia').val());
    var digital = (cheque + tarjeta + transferencia);
    var restante = totalpagar - digital;
    var vuelto = efectivo - restante;
    var pagado = cheque + tarjeta + transferencia + efectivo;
    $('#monto_vuelto').val(new Intl.NumberFormat('es-PY').format(vuelto));
    $('#monto_vuelto_enviar').val(vuelto);

    $('#enviarefectivo').val(efectivo);
    $('#enviarvuelto').val(vuelto);


    if (pagado >= totalpagar) {
      document.getElementById("bonton_confirmar").classList.remove('disabled');
    } else {
      document.getElementById("bonton_confirmar").classList.add('disabled');
    }

  };

  function desactivarBotonConfirmar() {

  }

  window.onload = calcularvuelto();
</script>

</html>