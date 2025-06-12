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

// Comprobar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $plantel = $_POST['plantel'];
    
    // Validar que el plantel sea Tepotzotlán
    if ($plantel !== 'TEPOTZOTLÁN') {
        die("Error: Solo se permite el plantel Tepotzotlán.");
    }

    $tipo_tramite = $_POST['tipo_tramite'];

    // Consulta para insertar los datos en la tabla 'tramites'
    $stmt = $pdo->prepare("INSERT INTO tramites (plantel, numero_control, nombre, carrera, semestre, situacion_actual, tipo_tramite) VALUES (:plantel, :numero_control, :nombre, :carrera, :semestre, :situacion_actual, :tipo_tramite)");

    // Ejecutar la consulta
    try {
        $stmt->execute([
            'plantel' => $plantel,
            'numero_control' => $numero_control,
            'nombre' => $nombre,
            'carrera' => $carrera,
            'semestre' => $semestre,
            'situacion_actual' => $situacion_actual,
            'tipo_tramite' => $tipo_tramite
        ]);

        echo "Registro exitoso.";
    } catch (PDOException $e) {
        echo "Error al registrar: " . $e->getMessage();
    }
}
?>