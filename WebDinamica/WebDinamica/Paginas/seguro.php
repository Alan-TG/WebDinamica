<?php
session_start();

// Habilitar el reporte de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario ha iniciado sesión y es un alumno
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'alumno') {
    header('Location: login.php'); // Redirigir a la página de inicio de sesión si no hay sesión activa o no es alumno
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['nss_pdf'])) {
    $file = $_FILES['nss_pdf'];

    // Comprobar si hubo un error en la subida
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Validar el tipo de archivo (solo PDF)
        $fileType = mime_content_type($file['tmp_name']);
        if ($fileType == 'application/pdf') {
            // Verificar el tamaño del archivo
            if ($file['size'] > 0) {
                // Leer el contenido del archivo
                $fileContent = file_get_contents($file['tmp_name']);
                if ($fileContent === false) {
                    echo "Error al leer el contenido del archivo.";
                    exit();
                }

                // Mostrar el tamaño del archivo leído
                echo "Tamaño del archivo leído: " . strlen($fileContent) . " bytes.<br>";

                $username = $_SESSION['username']; // Usar el username almacenado en la sesión

                // Conectar a la base de datos
                $conn = new mysqli('localhost', 'root', '', 'institucion_educativa');

                // Comprobar la conexión
                if ($conn->connect_error) {
                    die("Conexión fallida: " . $conn->connect_error);
                }

                // Consulta SQL para insertar o actualizar el PDF
                $sql = "INSERT INTO segurines_facultativos (username, nss_pdf) VALUES ('$username', ?) ON DUPLICATE KEY UPDATE nss_pdf = ?";
                
                // Preparar la consulta
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    die("Error en la preparación de la consulta: " . $conn->error);
                }

                // Usar 'b' para el contenido del archivo
                $stmt->bind_param("bb", $fileContent, $fileContent); 

                // Ejecutar la consulta
                if ($stmt->execute()) {
                    echo "Archivo subido exitosamente.";
                } else {
                    echo "Error al subir el archivo: " . $stmt->error; // Muestra el error de la consulta
                }

                // Cerrar la conexión
                $stmt->close();
                $conn->close();
            } else {
                echo "El archivo está vacío.";
            }
        } else {
            echo "El archivo debe ser un PDF.";
        }
    } else {
        // Manejo de errores de carga
        switch ($file['error']) {
            case UPLOAD_ERR_INI_SIZE:
                echo "El archivo excede el tamaño máximo permitido en php.ini.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                echo "El archivo excede el tamaño máximo permitido en el formulario.";
                break;
            case UPLOAD_ERR_PARTIAL:
                echo "El archivo fue subido parcialmente.";
                break;
            case UPLOAD_ERR_NO_FILE:
                echo "No se subió ningún archivo.";
                break;
            default:
                echo "Error desconocido en la subida del archivo.";
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir NSS</title>
    <link rel="stylesheet" href="../css/seguro.css">
</head>
<body>
    <div class="container">
        <h1>Subir PDF de NSS</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="nss_pdf">Selecciona el PDF de tu NSS:</label>
            <input type="file" name="nss_pdf" id="nss_pdf" accept=".pdf" required>
            <button type="submit">Subir</button>
            <button class="back-button"><a href="./index.php" style="color:white; text-decoration:none;">Volver al Menú</a></button>
        </form>
    </div>
</body>
</html>