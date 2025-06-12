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

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nuevo Trámite</title>
  <link rel="stylesheet" href="../css/tramite.css">
  <script>
    function showInstructions() {
      const tipoTramite = document.getElementById('tipo_tramite');
      const instructions = document.getElementById('instructions');
      const modalidadContainer = document.getElementById('modalidad-container');

      if (tipoTramite.value === 'ARP') {
        instructions.innerHTML = 'Configuración del Trámite. Antes de solicitar este trámite, por favor asegúrate de cumplir al 100% con los requisitos estipulados para la modalidad de titulación que selecciones.';
        instructions.style.display = 'block';
        modalidadContainer.style.display = 'block';
      } else {
        instructions.style.display = 'none';
        modalidadContainer.style.display = 'none';
      }
    }
  </script>
</head>
<body>
  <div class="container">
    <h2>Nuevo Trámite</h2>
    <form action="./tramite.php" method="post">
      <label for="plantel">Plantel:</label>
      <div class="select-container">
        <select id="plantel" name="plantel" required>
          <option value="TEPOTZOTLÁN" selected>Tepotzotlán</option>
        </select>
      </div>

      <label for="numero_control">Número de control:</label>
      <input type="text" id="numero_control" name="numero_control" value="<?php echo htmlspecialchars($numero_control); ?>" required>

      <label for="nombre">Nombre:</label>
      <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>

      <label for="carrera">Carrera:</label>
      <input type="text" id="carrera" name="carrera" value="<?php echo htmlspecialchars($carrera); ?>" required>

      <label for="semestre">Semestre:</label>
      <input type="text" id="semestre" name="semestre" value="<?php echo htmlspecialchars($semestre); ?>" required>

      <label for="situacion_actual">Situación actual:</label>
      <input type="text" id="situacion_actual" name="situacion_actual" value="<?php echo htmlspecialchars($situacion_actual); ?>" required>

      <label for="tipo_tramite">Tipo de trámite:</label>
      <div class="select-container">
        <select id="tipo_tramite" name="tipo_tramite" required onchange="showInstructions()">
          <option value="">Selecciona un tipo de trámite</option>
          <option value="ARP">Actas de Recepción Profesional - ARP</option>
          <option value ="CCMP">Cambio de Carrera Mismo Plantel - CCMP</option>
          <option value="CPCD">Cambio de Plantel Carrera Diferente - CPCD</option>
          <option value="CPMC">Cambio de Plantel Misma Carrera - CPMC</option>
          <option value="CAP">Certificado Abrogado Parcial - CAP</option>
          <option value="CAT">Certificado Abrogado Total - CAT</option>
          <option value="CPE">Certificado Parcial de Estudios - CPE</option>
          <option value="CTE">Certificado Total de Estudios - CTE</option>
          <option value="I">Inscripción Online - I</option>
          <option value="R">Reinscripción Online - R</option>
        </select>
      </div>

      <div id="instructions" class="instructions"></div>

      <div id="modalidad-container" class="modalidad-container">
        <label for="modalidad">Modalidad de Titulación:</label>
        <div class="select-container">
          <select id="modalidad" name="modalidad">
            <option value="">Selecciona una modalidad</option>
            <option value="curso_especial">Curso especial de titulación</option>
            <option value="continuidad">Por continuidad en estudios superiores</option>
            <option value="promedio">Por promedio final mínimo de 9.0 puntos</option>
            <option value="examen_global">Sustentación de un examen global de las competencias profesionales</option>
          </select>
        </div>
      </div>

      <input type="submit" value="Registrar">
    </form>

    <div class="back-button">
      <button><a href="index.php">Volver</a></button>
    </div>
  </div>
</body>
</html>