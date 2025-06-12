<?php
// Incluir el archivo de conexión y sesión
include '../Base/comprobar.php'; 

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirigir a la página de inicio de sesión si no hay sesión activa
    exit();
}

// Consulta para obtener los trámites realizados
$stmt = $pdo->prepare("SELECT * FROM tramites WHERE numero_control = :numero_control ORDER BY created_at DESC");
$stmt->execute(['numero_control' => $_SESSION['username']]);
$tramites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Trámites</title>
    <link rel="stylesheet" href="../css/historial.css">
</head>
<body>

    <div class="container">
        <h1>Historial de Trámites</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Plantel</th>
                    <th>Número de Control</th>
                    <th>Nombre</th>
                    <th>Carrera</th>
                    <th>Semestre</th>
                    <th>Situación Actual</th>
                    <th>Tipo de Trámite</th>
                    <th>Fecha de Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($tramites) > 0): ?>
                    <?php foreach ($tramites as $tramite): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tramite['id']); ?></td>
                            <td><?php echo htmlspecialchars($tramite['plantel']); ?></td>
                            <td><?php echo htmlspecialchars($tramite['numero_control']); ?></td>
                            <td><?php echo htmlspecialchars($tramite['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($tramite['carrera']); ?></td>
                            <td><?php echo htmlspecialchars($tramite['semestre']); ?></td>
                            <td><?php echo htmlspecialchars($tramite['situacion_actual']); ?></td>
                            <td><?php echo htmlspecialchars($tramite['tipo_tramite']); ?></td>
                            <td><?php echo htmlspecialchars($tramite['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">No hay trámites registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="back-button">
            <button><a href="index.php" style="color:white; text-decoration:none;">Volver</a></button>
        </div>
    </div>

</body>
</html>