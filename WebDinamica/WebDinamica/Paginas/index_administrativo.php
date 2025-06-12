<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es personal administrativo
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'administrativo') {
    header('Location: login.php'); // Redirigir a la página de inicio de sesión si no hay sesión activa o no es administrativo
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Administrativa</title>
    <link rel="stylesheet" href="../css_admin/index_administrativo.css">
</head>
<body>

<header>
    <div class="header-content">
        <img src="../imagenes menu/logo del estado de mexico.png" alt="Imagen Izquierda">
        <h1>Bienvenido a la Página Administrativa</h1>
        <img src="../imagenes menu/cecytem.logo.jfif" alt="Imagen Derecha">
    </div>
</header>

<div class="menu-container">
    <div class="menu-item">
        <img src="../imagenes menu/registrar_alumno.jpg" alt="Registrar Alumno">
        <a href="./registrar_alumnos.html">Registrar Alumno</a>
    </div>

    <div class="menu-item">
        <img src="../imagenes menu/alumnos_regis.jpg" alt="Alumnos Registrados">
        <a href="./alumnos.php">Alumnos Registrados</a>
    </div>

    <div class="menu-item">
        <img src="../imagenes menu/calificaciones.jpg" alt="Alumnos Registrados">
        <a href="./asignar_calificacion.php">Asignar Calificacion</a>
    </div>

    <div class="menu-item">
        <img src="../imagenes menu/registrar_horarios.jpg" alt="Registrar Horarios">
        <a href="./horarios_re.php">Registrar Horarios</a>
    </div>

    <div class="menu-item">
        <img src="../imagenes menu/nss_registrados.jpg" alt="Números de Seguridad Social">
        <a href="./ver_seguros.php">Números de Seguridad Social Registrados</a>
    </div>

    <div class="menu-item">
        <img src="../imagenes menu/manual_personal.png" alt="Manual de Usuario">
        <a href="./manualote.php">Manual de Usuario</a>
    </div>

    <div class="menu-item">
        <img src="../imagenes menu/cerrar sesion.jfif" alt="Cerrar Sesión">
        <a href="../Base/logout.php">Cerrar Sesión</a>
    </div>
</div>

<footer>
    &copy; Todos los derechos reservados a CDTA
</footer>

<img src="../imagenes menu/cecyto.jfif" alt="Logo Esquina" class="corner-logo">
</body>
</html>