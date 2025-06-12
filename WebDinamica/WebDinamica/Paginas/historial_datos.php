<?php
// Incluir el archivo comprobar.php que maneja la conexión y la verificación de sesión
include '../Base/comprobar.php'; 

// Verificar si el número de control está en la sesión
if (!isset($_SESSION['username'])) {
    // Redirigir a la página de inicio de sesión si no hay sesión activa
    header('Location: login.php');
    exit();
}

// Obtener el número de control del alumno desde la sesión
$numero_control = $_SESSION['username'];

// Obtener los datos del alumno
$sql = "SELECT * FROM alumnos WHERE numero_control = :numero_control";
$stmt = $pdo->prepare($sql);
$stmt->execute(['numero_control' => $numero_control]);

if ($stmt->rowCount() > 0) {
    $alumno = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Manejo de error si no se encuentra el alumno
    die("No se encontró al alumno.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Alumno</title>
    <link rel="stylesheet" href="../css/historial_datos.css">
</head>
<body>

    <div class="container">
        <h1>Información del Alumno</h1>

        <!-- Datos Personales -->
        <div class="section">
            <h2>Datos Personales</h2>
            <table>
                <tr>
                    <th>Nombre</th>
                    <td><?php echo $alumno['nombre'] . ' ' . $alumno['apellidos']; ?></td>
                </tr>
                <tr>
                    <th>Número de Control</th>
                    <td><?php echo $alumno['numero_control']; ?></td>
                </tr>
                <tr>
                    <th>CURP</th>
                    <td><?php echo $alumno['curp']; ?></td>
                </tr>
                <tr>
                    <th>Fecha de Nacimiento</th>
                    <td><?php echo date('d/m/Y', strtotime($alumno['fecha_nacimiento'])); ?></td>
                </tr>
                <tr>
                    <th>Correo Electrónico</th>
                    <td><?php echo $alumno['correo_electronico']; ?></td>
                </tr>
                <tr>
                    <th>Teléfono</th>
                    <td><?php echo $alumno['telefono'] ?? 'No disponible'; ?></td>
                </tr>
            </table>
        </div>

        <!-- Historial Académico <div class="section">
            <h2>Historial Académico</h2>
            <table>
                <thead>
                    <tr>
                        <th>Semestre</th>
                        <th>Carrera</th>
                        <th>Promedio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Aquí deberías obtener el historial académico del alumno desde la base de datos
                    // Por simplicidad, se muestra un ejemplo estático
                    for ($semestre = 1; $semestre <= $alumno['semestre']; $semestre++) {
                        echo "<tr>
                                <td>{$semestre}° Semestre</td>
                                <td>Ingeniería en Sistemas Computacionales</td>
                                <td>" . rand(7, 10) . "</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Inasistencias -->
        <div class="section">
            <h2>Inasistencias</h2>
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Materia</th>
                        <th>Justificación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Aquí deberías obtener las inasistencias del alumno desde la base de datos
                    // Por simplicidad, se muestra un ejemplo estático
                    echo "<tr>
                            <td>10/09/2024</td>
                            <td>Matemáticas</td>
                            <td>No justificada</td>
                          </tr>
                          <tr>
                            <td>20/09/2024</td>
                            <td>Programación</td>
                            <td>Justificada (Enfermedad)</td>
                          </tr>";
                    ?>
                </tbody>
            </table>
        </div>
        <!-- Botón para volver al menú -->
        <div class="back-button">
            <button><a href="./index.php" style="color:white; text-decoration:none;">Volver al Menú</a></button>
        </div>
    </div>

</body>
</html>
