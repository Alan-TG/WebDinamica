<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario ha iniciado sesión y es un alumno
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'administrativo') {
    header('Location: login.php'); // Redirigir a la página de inicio de sesión si no hay sesión activa o no es alumno
    exit();
}

$pdfFile = '../manuales/Manual_administrativo.pdf'; 

// Verificar si el archivo PDF existe
if (!file_exists($pdfFile)) {
    die('El archivo PDF no existe.');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual de Usuario</title>
    <link rel="stylesheet" href="../css_admin/manualote.css">
</head>
<body>
    
    <iframe src="<?php echo $pdfFile; ?>" allowfullscreen></iframe>
    
    <a class="back-button" href="./index_administrativo.php">Volver al Menú</a>
</body>
</html>