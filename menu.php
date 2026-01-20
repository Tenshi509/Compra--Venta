<?php
session_start();
if ($_SESSION == NULL) {
    $_SESSION['error'] = 'Debes iniciar sesión para usar el sistema';
    header('location:index.php');
}


date_default_timezone_set('America/Asuncion');
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta content="width=devicewidth, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php
    include 'conexion.php';
    require 'estilos/css_lte.ctp';
    ?>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper" style="background-color:#1e282c; ">
        <?php require 'estilos/cabecera.ctp'; ?>
        <?php require 'estilos/izquierda.ctp'; ?>
        <div class="content-wrapper">
            <section class="content-header">
                <h3 class="text-center text-bold">
                    Bienvenido al Sistema <?php echo '- ' . $_SESSION['nick']; ?>
                </h3>
            </section>
            <section class="content">
                <h2 class="page-header text-bold text-center">Compra</h2>
                <div class="row">
                    <!-- COMPRAS CONFIRMADA -->
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <?php $cantidadcompras = consultas::get_datos("SELECT COUNT(estado) AS conteo FROM compras WHERE estado = 'CONFIRMADO'"); ?>
                                <h3><?php echo $cantidadcompras[0]['conteo']; ?></h3>
                                <p>Compras Confirmadas</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                            <?php $moduloventa = consultas::get_datos("SELECT * FROM v_permisos WHERE id_grupo =" . $_SESSION['id_grupo'] . " AND id_pagina = 204 AND id_modulo = 2"); ?>
                            <?php if (!empty($moduloventa)) { ?>
                                <a href="/nova/compras/facturas/index.php" class="small-box-footer">
                                    Mas info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- COMPRAS CONFIRMADA -->

                    <!-- PEDIDOS -->
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <?php $cantidadpedidoscompra = consultas::get_datos("SELECT COUNT(estado) AS conteo FROM pedidoscompras WHERE estado = 'CONFIRMADO'"); ?>
                                <h3><?php echo $cantidadpedidoscompra[0]['conteo']; ?></h3>
                                <p>Pedidos Confirmados</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-file-text-o"></i>
                            </div>
                            <?php $modulocompra = consultas::get_datos("SELECT * FROM v_permisos WHERE id_grupo =" . $_SESSION['id_grupo'] . " AND id_pagina = 201 AND id_modulo = 2"); ?>
                            <?php if (!empty($modulocompra)) { ?>
                                <a href="/nova/compras/pedidos/index.php" class="small-box-footer">
                                    Mas info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- PEDIDOS -->

                    <!-- NOTA DE REMISION -->
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <?php $cantidadremisioncompra = consultas::get_datos("SELECT COUNT(id_notaremisioncompra) AS conteo FROM notaremisioncompra"); ?>
                                <h3><?php echo $cantidadremisioncompra[0]['conteo']; ?></h3>
                                <p>Notas de Remisión</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-truck"></i>
                            </div>
                            <?php $modulocompra = consultas::get_datos("SELECT * FROM v_permisos WHERE id_grupo =" . $_SESSION['id_grupo'] . " AND id_pagina = 207 AND id_modulo = 2"); ?>
                            <?php if (!empty($modulocompra)) { ?>
                                <a href="/nova/compras/notaremision/index.php" class="small-box-footer">
                                    Mas info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- NOTA DE REMISION -->


                    <!-- NOTA CREDITO -->
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-red">
                            <div class="inner">
                                <?php $cantidadcreditocompra = consultas::get_datos("SELECT COUNT(id_notacreditocompra) AS conteo FROM notacreditocompra"); ?>
                                <h3><?php echo $cantidadcreditocompra[0]['conteo']; ?></h3>
                                <p>Notas de Créditos</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-ban"></i>
                            </div>
                            <?php $modulocompra = consultas::get_datos("SELECT * FROM v_permisos WHERE id_grupo =" . $_SESSION['id_grupo'] . " AND id_pagina = 205 AND id_modulo = 2"); ?>
                            <?php if (!empty($modulocompra)) { ?>
                                <a href="/nova/compras/notacredito/index.php" class="small-box-footer">
                                    Mas info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- NOTA CREDITO -->


                </div>


                <h2 class="page-header text-bold text-center">Ventas</h2>
                <div class="row">
                    <!-- VENTAS CONFIRMADA -->
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <?php $cantidadventas = consultas::get_datos("SELECT COUNT(estado) AS conteo FROM ventas WHERE estado = 'CONFIRMADO'"); ?>
                                <h3><?php echo $cantidadventas[0]['conteo']; ?></h3>
                                <p>Ventas Confirmadas</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                            <?php $moduloventa = consultas::get_datos("SELECT * FROM v_permisos WHERE id_grupo =" . $_SESSION['id_grupo'] . " AND id_pagina = 403 AND id_modulo = 4"); ?>
                            <?php if (!empty($moduloventa)) { ?>
                                <a href="/nova/ventas/ventas/index.php" class="small-box-footer">
                                    Mas info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- VENTAS CONFIRMADA -->

                    <!-- COBROS -->
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <?php $cantidadcobros = consultas::get_datos("SELECT COUNT(estado) AS conteo FROM cobros WHERE estado = 'CONFIRMADO'"); ?>
                                <h3><?php echo $cantidadcobros[0]['conteo']; ?></h3>
                                <p>Cobros Confirmados</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-file-text-o"></i>
                            </div>
                            <?php $moduloventa = consultas::get_datos("SELECT * FROM v_permisos WHERE id_grupo =" . $_SESSION['id_grupo'] . " AND id_pagina = 404 AND id_modulo = 4"); ?>
                            <?php if (!empty($moduloventa)) { ?>
                                <a href="/nova/ventas/cobros/index.php" class="small-box-footer">
                                    Mas info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- COBROS -->

                    <!-- COBROS -->
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <?php $cantidadremision = consultas::get_datos("SELECT COUNT(id_notaremisionventa) AS conteo FROM notaremisionventa"); ?>
                                <h3><?php echo $cantidadremision[0]['conteo']; ?></h3>
                                <p>Notas de Remisión</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-truck"></i>
                            </div>
                            <?php $moduloventa = consultas::get_datos("SELECT * FROM v_permisos WHERE id_grupo =" . $_SESSION['id_grupo'] . " AND id_pagina = 407 AND id_modulo = 4"); ?>
                            <?php if (!empty($moduloventa)) { ?>
                                <a href="/nova/ventas/notaremision/index.php" class="small-box-footer">
                                    Mas info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- COBROS -->


                    <!-- NOTA CREDITO -->
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-red">
                            <div class="inner">
                                <?php $cantidadcredito = consultas::get_datos("SELECT COUNT(id_notacreditoventa) AS conteo FROM notacreditoventa"); ?>
                                <h3><?php echo $cantidadcredito[0]['conteo']; ?></h3>
                                <p>Notas de Créditos</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-ban"></i>
                            </div>
                            <?php $moduloventa = consultas::get_datos("SELECT * FROM v_permisos WHERE id_grupo =" . $_SESSION['id_grupo'] . " AND id_pagina = 405 AND id_modulo = 4"); ?>
                            <?php if (!empty($moduloventa)) { ?>
                                <a href="/nova/ventas/notacredito/index.php" class="small-box-footer">
                                    Mas info <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- NOTA CREDITO -->


                </div>
            </section>




        </div>
    </div>
    <?php require 'estilos/pie.ctp' ?>
</body>
<?php require 'estilos/js_lte.ctp'; ?>

</html>