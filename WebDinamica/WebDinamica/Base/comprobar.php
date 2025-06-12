<?php
// Iniciar la sesión
session_start();

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
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($role === 'alumno') {
        // Consulta para comprobar si existe el número de control y CURP
        $stmt = $pdo->prepare("SELECT * FROM alumnos WHERE numero_control = :username AND curp = :password");
        $stmt->execute(['username' => $username, 'password' => $password]);

        // Verificar si se encontró un registro
        if ($stmt->rowCount() > 0) {
            // Inicio de sesión exitoso, almacenar datos en la sesión
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'alumno'; // Almacenar el rol en la sesión

            // Redirigir al archivo correspondiente para alumnos
            header('Location: ../Paginas/index.php');
            exit();  // Detener la ejecución después de la redirección
        } else {
            // Credenciales incorrectas
            echo "Número de control o CURP incorrecto.";
        }
    } elseif ($role === 'administrativo') {
        // Consulta para comprobar si existe el número de empleado y contraseña
        $stmt = $pdo->prepare("SELECT * FROM personal_administrativo WHERE numero_empleado = :username AND password = :password");
        $stmt->execute(['username' => $username, 'password' => $password]);

        // Verificar si se encontró un registro
        if ($stmt->rowCount() > 0) {
            // Inicio de sesión exitoso, almacenar datos en la sesión
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'administrativo'; // Almacenar el rol en la sesión

            // Redirigir al archivo correspondiente para personal administrativo
            header('Location: ../Paginas/index_administrativo.php');
            exit();  // Detener la ejecución después de la redirección
        } else {
            // Credenciales incorrectas
            echo "Número de empleado o contraseña incorrectos.";
        }
    } else {
        echo "Rol no válido.";
    }
}
?>

