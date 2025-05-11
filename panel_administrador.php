<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== "Administrador") {
    header("Location: inicio.html"); // Redirigir si no es administrador
    exit();
}

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lis";
$tabla_usuarios = "usuarios";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Obtener todos los usuarios administradores
$sql_admin = "SELECT nombre, correo FROM $tabla_usuarios WHERE rol = 'Administrador'";
$resultado_admin = $conn->query($sql_admin);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
</head>
<body>
    <h1>Panel de Administrador</h1>
    <p>Bienvenido, Administrador!</p>

    <h2>Usuarios Administradores:</h2>
    <?php
    if ($resultado_admin->num_rows > 0) {
        echo "<ul>";
        while ($fila = $resultado_admin->fetch_assoc()) {
            echo "<li>Nombre: " . htmlspecialchars($fila["nombre"]) . ", Correo: " . htmlspecialchars($fila["correo"]) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No hay otros usuarios administradores.</p>";
    }
    ?>

    <p><a href="cerrar_sesion.php">Cerrar Sesión</a></p>
</body>
</html>

<?php
$conn->close();
?>