<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es un alumno
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'alumno') {
    header('Location: login.php'); // Redirigir a la página de inicio de sesión si no hay sesión activa o no es alumno
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/index.css">
    <title>Página Principal</title>
</head>
<body>

<header>
    <div class="header-content">
        <img src="../imagenes menu/logo del estado de mexico.png" alt="Imagen Izquierda">
        <h1>Bienvenido a tu página CONTROL ESCOLAR</h1>
        <img src="../imagenes menu/cecytem.logo.jfif" alt="Imagen Derecha">
    </div>
</header>

<div class="menu-container">
    <div class="menu-item">
        <img src="../imagenes menu/tramites.jfif" alt="Trámites">
        <a href="./tramite.php">Trámites</a>
    </div>

    <div class="menu-item">
        <img src="../imagenes menu/historial ..jfif" alt="Historial">
        <a href="./historial.php">Historial</a>
    </div>

    <div class="menu-item">
        <img src="../imagenes menu/historial de datos.jfif" alt="Historial de Datos">
        <a href="./historial_datos.php">Historial de Datos</a>
    </div>

    <div class="menu-item">
        <img src="../imagenes menu/documentacion.jfif" alt="Documentación">
        <a href="./documentacion.php">Documentación</a>
    </div>

    <div class="menu-item">
        <img src="../imagenes menu/horario.png" alt="Horario">
        <a href="./consul_horario.php">Horario</a>
    </div>

    <div class="menu-item">
        <img src="../imagenes menu/biblioteca.webp" alt="Biblioteca Digital">
        <a href="https://elibro.net/es/lc/cecytem/inicio" target="_blank">BIBLIOTECA DIGITAL EN ESPAÑOL</a>
    </div>

    <div class="menu-item">
        <img src="../imagenes menu/IMSS-Logo.png" alt="NSS">
        <a href="./seguro.php">NSS</a>
    </div>

    <div class="menu-item">
        <img src="../imagenes menu/manual.png" alt="Manual de Usuario">
        <a href="./manualito.php">Manual de Usuario</a>
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
