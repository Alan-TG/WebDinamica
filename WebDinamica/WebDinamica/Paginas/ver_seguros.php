<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es personal administrativo
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'administrativo') {
    header('Location: login.php'); // Redirigir a la página de inicio de sesión si no hay sesión activa o no es administrativo
    exit();
}

// Conectar a la base de datos
$conn = new mysqli('localhost', 'root', '', 'institucion_educativa');

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consultar los PDFs almacenados
$sql = "SELECT id, username FROM seguros_facultativos"; // Asegúrate de que la consulta esté correcta según tu tabla
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de PDFs de NSS</title>
    <link rel="stylesheet" href="../css_admin/ver_seguros.css">
</head>
<body>
    <header>
        <h1>Listado de PDFs de NSS</h1>
    </header>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Mostrar cada fila de resultados
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td><a href='./ver_pdf.php?id=" . htmlspecialchars($row['id']) . "'>Ver PDF</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No se encontraron archivos PDF.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <button><a href="index_administrativo.php" style="color:green; text-decoration:none;">Volver</a></button>
    <button class="logout" onclick="window.location.href='logout.php'">Cerrar sesión</button>

</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>