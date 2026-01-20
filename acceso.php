<?php
session_start();
date_default_timezone_set('America/Asuncion');
require 'conexion.php';

$usuario = $_POST['usuario'] ?? '';
$clave = $_POST['clave'] ?? '';

$sql = "SELECT * FROM v_usuario WHERE nick = '" . $usuario . "'";
$resultado = consultas::get_datos($sql);

if (isset($resultado[0]['id_usuario'])) { // Si existe el usuario

    if ($resultado[0]['estado'] === "BLOQUEADO") {
        $_SESSION['error'] = 'El usuario se encuentra bloqueado, contactar con el administrador';
        header('location:index.php');
        exit;
    }

    $hash = $resultado[0]['clave'];

    // Verificar contraseña
    $login_correcto = false;

    // 1️⃣ Verificar bcrypt
    if (password_verify($clave, $hash)) {
        $login_correcto = true;
    }
    // 2️⃣ Verificar MD5 antiguo
    elseif ($hash === md5($clave)) {
        $login_correcto = true;

        // Actualizar a bcrypt automáticamente
        $nuevo_hash = password_hash($clave, PASSWORD_DEFAULT);
        consultas::ejecutar_sql("UPDATE usuarios SET clave = '$nuevo_hash' WHERE id_usuario = " . $resultado[0]['id_usuario']);
    }

    if ($login_correcto) {
        // Reiniciar contador de intentos
        consultas::ejecutar_sql("UPDATE usuarios SET intentos = 0 WHERE id_usuario = " . $resultado[0]['id_usuario']);

        // Guardar la fecha del último acceso
        $fecha = date('Y-m-d');
        consultas::ejecutar_sql("UPDATE usuarios SET ultimo_acceso = '$fecha' WHERE id_usuario = " . $resultado[0]['id_usuario']);

        // Sesiones
        $_SESSION['id_usuario'] = $resultado[0]['id_usuario'];
        $_SESSION['nick'] = $resultado[0]['nick'];
        $_SESSION['ultimo_acceso'] = $fecha;
        $_SESSION['id_empleado'] = $resultado[0]['id_empleado'];
        $_SESSION['foto'] = $resultado[0]['foto'];
        $_SESSION['nombre'] = $resultado[0]['nombre'];
        $_SESSION['apellido'] = $resultado[0]['apellido'];
        $_SESSION['persona'] = $resultado[0]['persona_corta'];
        $_SESSION['id_grupo'] = $resultado[0]['id_grupo'];
        $_SESSION['grupo'] = $resultado[0]['grupo'];

        header('location:menu.php');
        exit;
    } else {
        // Incrementar intentos fallidos
        consultas::ejecutar_sql("UPDATE usuarios SET intentos = intentos + 1 WHERE id_usuario = " . $resultado[0]['id_usuario']);
        $intentos = consultas::get_datos("SELECT intentos FROM usuarios WHERE id_usuario = " . $resultado[0]['id_usuario']);

        if ($intentos[0]['intentos'] >= 3) {
            consultas::ejecutar_sql("UPDATE usuarios SET estado = 'BLOQUEADO' WHERE id_usuario = " . $resultado[0]['id_usuario']);
            $_SESSION['error'] = 'Se ha bloqueado el usuario por intentos fallidos';
        } else {
            $_SESSION['error'] = 'La contraseña es incorrecta';
        }
        header('location:index.php');
        exit;
    }

} else { // Usuario no existe
    $_SESSION['error'] = 'El usuario no existe';
    header('location:index.php');
    exit;
}