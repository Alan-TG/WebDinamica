<?php
session_start();

// Verificar si el usuario ha iniciado sesión como alumno
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
    die("Error de conexión: " . $e->getMessage());
}

// Obtener el semestre y el turno del alumno desde la base de datos
$numero_control = $_SESSION['username']; // Asumiendo que el username en la sesión es el numero_control

$stmt = $pdo->prepare("SELECT semestre, horario FROM alumnos WHERE numero_control = ?");
$stmt->execute([$numero_control]);
$alumno = $stmt->fetch(PDO::FETCH_ASSOC);

if ($alumno) {
    $semestre = $alumno['semestre'];
    $turno = $alumno['horario'];
} else {
    // Manejar el caso en que no se encuentra el alumno
    die("No se encontró información del alumno.");
}

// Obtener el horario del alumno
$stmt = $pdo->prepare("SELECT * FROM horarios WHERE semestre = ? AND turno = ?");
$stmt->execute([$semestre, $turno]);
$horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Horario</title>
    <link rel="stylesheet" href="../css/consul_horario.css">
</head>
<body>
    <h1>Tu Horario</h1>
    <?php if (count($horarios) > 0): ?>
        <table>
            <tr>
                <th>Asignatura</th>
                <th>Día</th>
                <th>Hora de Inicio</th>
                <th>Hora de Fin</th>
            </tr>
            <?php foreach ($horarios as $horario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($horario['asignatura']); ?></td>
                    <td><?php echo htmlspecialchars($horario['dia']); ?></td>
                    <td><?php echo htmlspecialchars($horario['hora_inicio']); ?></td>
                    <td><?php echo htmlspecialchars($horario['hora_fin']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No tienes horarios asignados para este semestre y turno.</p>
    <?php endif; ?>
    <div class="back-button">
        <a href="index.php">Volver</a>
    </div>
</body>
</html>