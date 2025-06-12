<?php
// Datos de conexión
$servidor = "localhost";
$usuario = "tu_usuario";
$contraseña = "tu_contraseña";
$base_datos = "nombre_de_tu_base_de_datos";

// Crear conexión
$conexion = new mysqli($servidor, $usuario, $contraseña, $base_datos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
echo "Conexión exitosa";
?>
