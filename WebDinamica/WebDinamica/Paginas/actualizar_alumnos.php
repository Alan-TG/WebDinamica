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

// Obtener el ID del alumno a actualizar
if (!isset($_GET['id'])) {
    die("ID no especificado.");
}

$id = $_GET['id'];

// Obtener los datos del alumno
$stmt = $pdo->prepare("SELECT * FROM alumnos WHERE id = :id");
$stmt->execute(['id' => $id]);
$alumno = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$alumno) {
    die("Alumno no encontrado.");
}

// Manejo de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación de datos
    $numero_control = trim($_POST['numero_control']);
    $curp = trim($_POST['curp']);
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $fecha_nacimiento = trim($_POST['fecha_nacimiento']);
    $semestre = trim($_POST['semestre']);
    $correo_electronico = trim($_POST['correo_electronico']);
    $domicilio_institucion = trim($_POST['domicilio_institucion']);
    $historial_academico = trim($_POST['historial_academico']);
    $inasistencias = trim($_POST['inasistencias']);
    $horario = trim($_POST['horario']);
    
    // Validar que todos los campos necesarios estén llenos
    if (empty($numero_control) || empty($curp) || empty($nombre) || empty($apellidos) || empty($fecha_nacimiento) || empty($semestre)) {
        $error = "Todos los campos son obligatorios.";
    } else {
        // Actualizar el alumno en la base de datos
        $stmt = $pdo->prepare("UPDATE alumnos SET 
            numero_control = :numero_control, 
            curp = :curp, 
            nombre = :nombre, 
            apellidos = :apellidos, 
            fecha_nacimiento = :fecha_nacimiento, 
            semestre = :semestre, 
            correo_electronico = :correo_electronico, 
            domicilio_institucion = :domicilio_institucion, 
            historial_academico = :historial_academico, 
            inasistencias = :inasistencias, 
            horario = :horario 
            WHERE id = :id");

        // Ejecutar la consulta
        $stmt->execute([
            'numero_control' => $numero_control,
            'curp' => $curp,
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'fecha_nacimiento' => $fecha_nacimiento,
            'semestre' => $semestre,
            'correo_electronico' => $correo_electronico,
            'domicilio_institucion' => $domicilio_institucion,
            'historial_academico' => $historial_academico,
            'inasistencias' => $inasistencias,
            'horario' => $horario,
            'id' => $id
        ]);

        header("Location: ./alumnos.php"); // Redirigir después de la actualización
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Alumno</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #80deea);
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Actualizar Alumno</h1>

    <?php if (isset($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="numero_control">Número de Control:</label>
        <input type="text" name="numero_control" id="numero_control" value="<?php echo htmlspecialchars($alumno['numero_control']); ?>" required>

        <label for="curp">CURP:</label>
        <input type="text" name="curp" id="curp" value="<?php echo htmlspecialchars($alumno['curp']); ?>" required>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($alumno['nombre']); ?>" required>

        <label for="apellidos">Apellidos:</label>
        <input type="text" name="apellidos" id="apellidos" value="<?php echo htmlspecialchars($alumno['apellidos']); ?>" required>

        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php echo htmlspecialchars($alumno['fecha_nacimiento']); ?>" required>

        <label for="semestre">Semestre:</label>
        <input type="text" name="semestre" id="semestre" value="<?php echo htmlspecialchars($alumno['semestre']); ?>" required>

        <label for="correo_electronico">Correo Electrónico:</label>
        <input type="email" name="correo_electronico" id="correo_electronico" value="<?php echo htmlspecialchars($alumno['correo_electronico']); ?>" required>

        <label for="domicilio_institucion">Domicilio Institución:</label>
        <input type="text" name="domicilio_institucion" id="domicilio_institucion" value="<?php echo htmlspecialchars($alumno['domicilio_institucion']); ?>" required>

        <label for="historial_academico">Historial Académico:</label>
        <textarea name="historial_academico" id="historial_academico" required><?php echo htmlspecialchars($alumno['historial_academico']); ?></textarea>

        <label for="inasistencias">Inasistencias:</label>
        <input type="number" name="inasistencias" id="inasistencias" value="<?php echo htmlspecialchars($alumno['inasistencias']); ?>" required>

        <label for="horario">Horario:</label>
        <input type="text" name="horario" id="horario" value="<?php echo htmlspecialchars($alumno['horario']); ?>" required>

        <input type="submit" value="Actualizar">
    </form>

    <a href="./alumnos.php">Volver a la administración de alumnos</a>
</body>
</html>