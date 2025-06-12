<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Verificar si el usuario ha iniciado sesión y es personal administrativo
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'administrativo') {
    header('Location: login.php');
    exit();
}

// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'institucion_educativa');

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del PDF
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Consultar el archivo PDF
    $stmt = $conn->prepare("SELECT nss_pdf FROM seguros_facultativos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($pdfContent);
    
    if ($stmt->fetch()) {
        // Establecer las cabeceras para la visualización del PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="nss.pdf"');
        header('Content-Length: ' . strlen($pdfContent)); // Establecer la longitud del contenido
        echo $pdfContent; // Mostrar el contenido del PDF
    } else {
        echo "No se encontró el archivo PDF.";
    }
    
    $stmt->close();
} else {
    echo "ID no especificado.";
}

// Cerrar la conexión
$conn->close();
?>