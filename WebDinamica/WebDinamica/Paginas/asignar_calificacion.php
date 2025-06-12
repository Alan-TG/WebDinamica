<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es personal administrativo
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'administrativo') {
    header('Location: login.php'); // Redirigir a la página de inicio de sesión si no hay sesión activa o no es administrativo
    exit();
}

// Conexión a la base de datos
$host = 'localhost';
$dbname = 'institucion_educativa';  // Nombre de tu base de datos
$user = 'root';  // Usuario de tu base de datos
$pass = '';  // Contraseña de tu base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Procesar el formulario si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_control = $_POST['numero_control'];
    $asignatura = $_POST['asignatura'];
    $calificacion = $_POST['calificacion'];

    // Validar que la calificación esté en un rango aceptable
    if ($calificacion < 0 || $calificacion > 100) {
        echo "<p style='color: red;'>La calificación debe estar entre 0 y 100.</p>";
    } else {
        // Guardar en la base de datos
        $stmt = $pdo->prepare("INSERT INTO calificaciones (numero_control, asignatura, calificacion) VALUES (?, ?, ?)");
        $stmt->execute([$numero_control, $asignatura, $calificacion]);
        echo "<p style='color: green;'>Calificación guardada exitosamente.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Calificación</title>
    <link rel="stylesheet" href="../css_admin/asignar_calificacion.css"> <!-- Enlaza tu archivo CSS aquí -->
</head>
<body>

    <div class="container">
        <h1>Asignar Calificación</h1>
        <form method="POST" action="">
            <label for="numero_control">Número de Control:</label>
            <input type="text" id="numero_control" name="numero_control" required>

            <label for="asignatura">Asignatura:</label>
            <input type="text" id="asignatura" name="asignatura" required>

            <label for="calificacion">Calificación (0-100):</label>
            <input type="number" id="calificacion" name="calificacion" min="0" max="100" step="0.01" required>

            <input type="submit" value="Guardar Calificación">
        </form>

        <div class="back-button">
            <button><a href="./index_administrativo.php" style="color:white; text-decoration:none;">Volver al Menú</a></button>
        </div>
    </div>

</body>
</html>