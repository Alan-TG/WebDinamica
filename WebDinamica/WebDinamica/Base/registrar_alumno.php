<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es personal administrativo
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'administrativo') {
    header('Location: login.html'); // Redirigir a la página de inicio de sesión si no hay sesión activa o no es administrativo
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

// Comprobar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar que los campos obligatorios no estén vacíos
    $numero_control = trim($_POST['numero_control']);
    $curp = trim($_POST['curp']);
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $semestre = $_POST['semestre'];
    $carrera = trim($_POST['carrera']);
    $situacion_actual = trim($_POST['situacion_actual']);
    $correo_electronico = trim($_POST['correo_electronico']);
    $domicilio_institucion = trim($_POST['domicilio_institucion']);
    $historial_academico = trim($_POST['historial_academico']);
    $inasistencias = $_POST['inasistencias'];
    $horario = trim($_POST['horario']);

    // Comprobar que los campos requeridos no estén vacíos
    if (empty($numero_control) || empty($curp) || empty($nombre) || empty($apellidos) || empty($fecha_nacimiento) || empty($semestre) || empty($carrera) || empty($situacion_actual) || empty($correo_electronico) || empty($domicilio_institucion)) {
        echo "Por favor, complete todos los campos obligatorios.";
    } else {
        // Preparar la consulta para insertar el nuevo alumno
        $stmt = $pdo->prepare("INSERT INTO alumnos (numero_control, curp, nombre, apellidos, fecha_nacimiento, semestre, carrera, situacion_actual, correo_electronico, domicilio_institucion, historial_academico, inasistencias, horario) VALUES (:numero_control, :curp, :nombre, :apellidos, :fecha_nacimiento, :semestre, :carrera, :situacion_actual, :correo_electronico, :domicilio_institucion, :historial_academico, :inasistencias, :horario)");

        // Ejecutar la consulta
        try {
            $stmt->execute([
                'numero_control' => $numero_control,
                'curp' => $curp,
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'fecha_nacimiento' => $fecha_nacimiento,
                'semestre' => $semestre,
                'carrera' => $carrera,
                'situacion_actual' => $situacion_actual,
                'correo_electronico' => $correo_electronico,
                'domicilio_institucion' => $domicilio_institucion,
                'historial_academico' => $historial_academico,
                'inasistencias' => $inasistencias,
                'horario' => $horario
            ]);
            // Redirigir después de un registro exitoso
            header("Location: ../Paginas/alumnos.php"); // Cambia esto a la página que desees
            exit();
        } catch ( PDOException $e) {
            echo "Error al registrar el alumno: " . $e->getMessage();
        }
    }
}
?>