<?php
session_start();
if ($_SESSION == NULL) {
  $_SESSION['error'] = 'Debes iniciar sesión para usar el sistema';
  header('location:/graficanissei/');
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
$idcajero = $_SESSION['nick'];
$apertura = consultas::get_datos("SELECT * FROM v_aperturacierre WHERE nick = '$idcajero' AND estado = 'ABIERTA'");
$cuentascobrar = consultas::get_datos("SELECT * FROM v_cuentascobrar ORDER BY factura_completa DESC");
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
            <?php if (!empty($apertura)) { ?>
              <div class="box box-primary">
                <div class="box-header with-border">
                  <i class="fa fa-money"></i>
                  <h3 class="box-title">Todas las cuentas a cobrar</h3>
                </div>

                <div class="box-body">
                  <table id="listadocuentas" class="table table-bordered table-striped es-tb">
                    <thead>
                      <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">N° de Factura</th>
                        <th class="text-center">Cliente</th>
                        <th class="text-center">Monto</th>
                        <th class="text-center">Saldo</th>
                        <th class="text-center">Cantidad de Cuotas</th>
                        <th class="text-center">Monto Cuotas</th>
                        <th class="text-center">Cuotas Pagadas</th>
                        <th class="text-center">Cuotas Restantes</th>
                        <th class="text-center">Estado</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($cuentascobrar)) {
                        foreach ($cuentascobrar as $ctc) { ?>
                          <tr>
                            <td class="text-center"><?php echo $ctc['id_cuentacobrar'] ?></td>
                            <td class="text-center"><?php echo $ctc['factura_completa'] ?></td>
                            <td class="text-center"><?php echo $ctc['razon_social'] ?></td>
                            <td class="text-center"><?php echo number_format($ctc['monto'], 0, ',', '.') ?></td>
                            <td class="text-center"><?php echo number_format($ctc['saldo'], 0, ',', '.') ?></td>
                            <td class="text-center"><?php echo number_format($ctc['cant_cuota'], 0, ',', '.') ?></td>
                            <td class="text-center"><?php echo number_format($ctc['monto_cuota'], 0, ',', '.') ?></td>
                            <td class="text-center"><?php echo number_format($ctc['cuota_pagadas'], 0, ',', '.') ?></td>
                            <td class="text-center"><?php echo number_format($ctc['cuota_restante'], 0, ',', '.') ?></td>
                            <td class="text-center"><?php echo $ctc['estado'] ?></td>
                          </tr>
                      <?php }
                      } ?>
                    </tbody>

                  </table>

                </div>
              </div>
            <?php } else { ?>
              <div class="alert alert-info flat">
                <span class="glyphicon glyphicon-info-sign"></span>
                Para iniciar un cobro, debes realizar la apertura de una caja.
              </div>
            <?php } ?>
            <?php // FIN CONTENIDO 
            ?>


          </div>
        </div>
      </div>

    </div>
    <?php require '../../estilos/pie.ctp' ?>;
</body>
<?php require '../../estilos/js_lte.ctp'; ?>
<script>
  $("#mensaje").delay(5000).slideUp(200, function() {
    $(this).alert('close');
  });

  $(function() {
    $("#listadocuentas").DataTable();
  });

</script>

</html>