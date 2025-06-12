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

// Borrar un alumno
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM alumnos WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header("Location: ./alumnos.php"); // Redirigir a la misma página después de borrar
    exit();
}

// Manejo de búsqueda
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Configuración de paginación
$limit = 10; // Número de registros por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Obtener todos los alumnos con paginación y búsqueda
$query = "SELECT * FROM alumnos WHERE nombre LIKE :search OR apellidos LIKE :search LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($query);
$stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contar el total de registros
$totalQuery = "SELECT COUNT(*) FROM alumnos WHERE nombre LIKE :search OR apellidos LIKE :search";
$totalStmt = $pdo->prepare($totalQuery);
$totalStmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$totalStmt->execute();
$total = $totalStmt->fetchColumn();
$totalPages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Alumnos</title>
    <link rel="stylesheet" href="../css_admin/alumnos.css">
</head>
<body>
    <header>
        <h1>Administración de Alumnos</h1>
    </header>

    <form method="GET" action="">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Buscar por nombre o apellidos">
        <input type="submit" value="Buscar">
    </form>

    <?php if (empty($alumnos)): ?>
        <p>No se encontraron alumnos.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Número de Control</th>
                    <th>CURP</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Semestre</th>
                    <th>Carrera</th>
                    <th>Situación Actual</th>
                    <th>Correo Electrónico</th>
                    <th>Domicilio Institución</th>
                    <th>Historial Académico</th>
                    <th>Inasistencias</th>
                    <th>Horario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $alumno): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($alumno['id']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['numero_control']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['curp']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['apellidos']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['fecha_nacimiento']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['semestre']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['carrera']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['situacion_actual']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['correo_electronico']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['domicilio_institucion']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['historial_academico']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['inasistencias']); ?></td>
                        <td><?php echo htmlspecialchars($alumno['horario']); ?></td>
                        <td>
                            <a href="./actualizar_alumnos.php?id=<?php echo $alumno['id']; ?>">Actualizar</a>
                            <a href="?delete=<?php echo $alumno['id']; ?>" onclick="return confirm('¿Estás seguro de que quieres borrar este alumno?');">Borrar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Anterior</a>
            <?php endif; ?>
            <span>Página <?php echo $page; ?> de <?php echo $totalPages; ?></span>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Siguiente</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <button><a href="./index_administrativo.php" style="color: white; text-decoration: none;">Volver al Menu</a></button>
</body>
</html>