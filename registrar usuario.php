<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "live_lis";
$port = 3306; 


$conn = new mysqli($servername, $username, $password, $database, $port);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];
    $rol = $_POST["rol"];

   
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

   
    $sql = "INSERT INTO usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $correo, $contrasena_hash, $rol);

    if ($stmt->execute()) {
        echo "<script>alert('Usuario registrado correctamente.'); window.location.href='inicio.html';</script>";
    } else {
        echo "<script>alert('Error al registrar el usuario: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>