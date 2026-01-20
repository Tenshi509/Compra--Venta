<?php
require('../../conexion.php');

$cod = $_POST['codigo'];
$departamento = consultas::get_datos("SELECT * FROM v_ciudad WHERE id_ciudad =  $cod");

?>


<div class="form-group col-md-3">
    <label>Departamento</label>
    <input type="text" class="form-control" value="<?php echo $departamento [0]['departamento']?>" readonly>
</div>