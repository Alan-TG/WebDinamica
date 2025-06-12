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
    $semestre = $_POST['semestre'];
    $turno = $_POST['turno'];

    // Verificar que los campos requeridos existan
    if (isset($_POST['asignatura'], $_POST['dia'], $_POST['hora_inicio'], $_POST['hora_fin'])) {
        $asignatura = $_POST['asignatura'];
        $dia = $_POST['dia'];
        $hora_inicio = $_POST['hora_inicio'];
        $hora_fin = $_POST['hora_fin'];

        // Guardar en la base de datos
        $stmt = $pdo->prepare("INSERT INTO horarios (semestre, turno, asignatura, dia, hora_inicio, hora_fin) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$semestre, $turno, $asignatura, $dia, $hora_inicio, $hora_fin]);

        echo "<p style='color: green;'>Horario guardado exitosamente.</p>";
    } else {
        echo "<p style='color: red;'>Por favor, completa todos los campos requeridos.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Establecer Horario</title>
    <link rel="stylesheet" href="../css_admin/horarios_re.css">
</head>
<body>
    <div class="container">
        <h1>Establecer Horario</h1>
        <form method="post">
            <label for="semestre">Semestre:</label>
            <input type="text" id="semestre" name="semestre" required>

            <label for="turno">Turno:</label>
            <select id="turno" name="turno" required>
                <option value="matutino">Matutino</option>
                <option value="vespertino">Vespertino</option>
            </select>

            <label for="asignatura">Asignatura:</label>
            <input type="text" name="asignatura" required>

            <label for="dia">Día:</label>
            <select name="dia" required>
                <option value="Lunes">Lunes</option>
                <option value="Martes">Martes</option>
                <option value="Miércoles">Miércoles</option>
                <option value="Jueves">Jueves</option>
                <option value="Viernes">Viernes</option>
            </select>

            <label for="hora_inicio">Hora de Inicio:</label>
            <input type="time" name="hora_inicio" required>

            <label for="hora_fin">Hora de Fin:</label>
            <input type="time" name="hora_fin" required>

            <input type="submit" value="Guardar Horario">
        </form>
        <div class="back-button">
            <button><a href="index_administrativo.php" style="color: white; text-decoration: none;">Volver</a></button>
        </div>
    </div>
</body>
</html>