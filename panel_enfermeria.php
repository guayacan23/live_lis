<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es líder de enfermería
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== "lider enfermeria") {
    header("Location: inicio.html"); // Redirigir si no es líder de enfermería
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

// Obtener todos los auxiliares de enfermería
$sql_auxiliares = "SELECT nombre, correo FROM $tabla_usuarios WHERE rol = 'auxiliar de enfermeria'";
$resultado_auxiliares = $conn->query($sql_auxiliares);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Enfermería</title>
</head>
<body>
    <h1>Panel de Enfermería</h1>
    <p>Bienvenido, Líder de Enfermería!</p>

    <h2>Auxiliares de Enfermería:</h2>
    <?php
    if ($resultado_auxiliares->num_rows > 0) {
        echo "<ul>";
        while ($fila = $resultado_auxiliares->fetch_assoc()) {
            echo "<li>Nombre: " . htmlspecialchars($fila["nombre"]) . ", Correo: " . htmlspecialchars($fila["correo"]) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No hay auxiliares de enfermería registrados.</p>";
    }
    ?>

    <p><a href="cerrar_sesion.php">Cerrar Sesión</a></p>
</body>
</html>

<?php
$conn->close();
?>