<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario ha iniciado sesión y es un alumno
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'alumno') {
    header('Location: login.php'); // Redirigir a la página de inicio de sesión si no hay sesión activa o no es alumno
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
    die("Error al conectar con la base de datos: " . $e->getMessage());
}

// Obtener el número de control del usuario autenticado
$numero_control = $_SESSION['username']; // Aquí se asume que username es el número de control

// Consultar la información del alumno desde la base de datos
$stmt = $pdo->prepare("SELECT numero_control, nombre, carrera, semestre, situacion_actual FROM alumnos WHERE numero_control = :numero_control");
$stmt->execute(['numero_control' => $numero_control]);
$alumno = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si se encontró el alumno
if (!$alumno) {
    die("Error: No se encontró información del alumno.");
}

// Almacenar los datos del alumno en variables
$nombre = $alumno['nombre'];
$carrera = $alumno['carrera'];
$semestre = $alumno['semestre'];
$situacion_actual = $alumno['situacion_actual'];

// Definir el plantel fijo
$plantel = "Tepotzotlán";

// Consultar las calificaciones del alumno
$stmt_calificaciones = $pdo->prepare("SELECT asignatura, calificacion FROM calificaciones WHERE numero_control = :numero_control");
$stmt_calificaciones->execute(['numero_control' => $numero_control]);
$calificaciones = $stmt_calificaciones->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentación del Alumno</title>
    <link rel="stylesheet" href="../css/documentacion.css">
</head>
<body>

    <div class="container">
        <h1>Documentación del Alumno</h1>
        <!-- Información de Semestre -->
        <div class="section">
            <h2>Semestre Actual</h2>
            <table>
                <tr>
                    <th>Semestre</th>
                    <td><?php echo htmlspecialchars($semestre); ?></td>
                </tr>
            </table>
        </div>

        <!-- Información de Carrera o Especialidad -->
        <div class="section">
            <h2>Carrera / Especialidad</h2>
            <table>
                <tr>
                    <th>Carrera</th>
                    <td><?php echo htmlspecialchars($carrera); ?></td>
                </tr>
            </table>
        </div>

        <!-- Información del Plantel -->
        <div class="section">
            <h2>Plantel</h2>
            <table>
                <tr>
                    <th>Nombre del Plantel</th>
                    <td><?php echo htmlspecialchars($plantel); ?></td>
                </tr>
                <tr>
                    <th>Dirección</th>
                    <td>Av. Emiliano Zapata, Col. Ejidal, Tepotzotlán, Estado de México</td>
                </tr>
            </table>
        </div>

        <!-- Información de Calificaciones -->
        <div class="section">
            <h2>Calificaciones</h2>
            <table>
                <tr>
                    <th>Asignatura</th>
                    <th>Calificación</th>
                </tr>
                <?php if (count($calificaciones) > 0): ?>
                    <?php foreach ($calificaciones as $calificacion): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($calificacion['asignatura']); ?></td>
                            <td><?php echo htmlspecialchars($calificacion['calificacion']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">No hay calificaciones asignadas.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <!-- Botón para volver al menú -->
        <div class="back-button">
            <button><a href="./index.php" style="color:white; text-decoration:none;">Volver al Menú</a></button>
        </div>
    </div>

</body>
</html>