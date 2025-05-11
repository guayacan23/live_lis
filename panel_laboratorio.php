<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es líder de laboratorio
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== "lider laboratorio")) {
    header("Location: inicio.html"); // Redirigir si no es líder de laboratorio
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

// Obtener todos los usuarios profesionales, tecnólogos y técnicos
$sql_personal_lab = "SELECT nombre, correo, rol FROM $tabla_usuarios WHERE rol IN ('profesional en laboratorio', 'tecnologo en laboratorio', 'tecnico de laboratorio')";
$resultado_personal_lab = $conn->query($sql_personal_lab);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Laboratorio</title>
</head>
<body>
    <h1>Panel de Laboratorio</h1>
    <p>Bienvenido, Líder de Laboratorio!</p>

    <h2>Personal del Laboratorio:</h2>
    <?php
    if ($resultado_personal_lab->num_rows > 0) {
        echo "<ul>";
        while ($fila = $resultado_personal_lab->fetch_assoc()) {
            echo "<li>Nombre: " . htmlspecialchars($fila["nombre"]) . ", Correo: " . htmlspecialchars($fila["correo"]) . " (Rol: " . htmlspecialchars($fila["rol"]) . ")</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No hay personal en el laboratorio.</p>";
    }
    ?>

    <p><a href="cerrar_sesion.php">Cerrar Sesión</a></p>
</body>
</html>

<?php
$conn->close();
?>