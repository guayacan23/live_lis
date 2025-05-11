<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "live_lis";
$port = 3306; // Especifica el puerto

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database, $port);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];

    // Preparar la consulta SQL para buscar el usuario por correo
    $sql = "SELECT id, contrasena FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($contrasena, $row["contrasena"])) {
            echo "<script>alert('Inicio de sesión exitoso.'); window.location.href='pagina_principal.html';</script>";
            // Aquí podrías iniciar una sesión (usando $_SESSION) para mantener al usuario logueado
        } else {
            echo "<script>alert('Contraseña incorrecta.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No se encontró ningún usuario con ese correo.'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>