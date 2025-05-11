<?php

// Datos de conexión a la base de datos
$servername = "localhost"; // Cambia esto si tu servidor de base de datos es diferente
$username = "root"; // Reemplaza con tu nombre de usuario de la base de datos
$password = ""; // Reemplaza con tu contraseña de la base de datos
$dbname = "lis"; // Reemplaza con el nombre de tu base de datos
$tabla_usuarios = "usuarios"; // Reemplaza con el nombre de tu tabla de usuarios

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Verificar si se recibieron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"]; // ¡Importante! Debes hashear la contraseña antes de guardarla
    $rol = $_POST["rol"];

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($correo) || empty($contrasena) || empty($rol)) {
        echo '<script>alert("Por favor, completa todos los campos."); window.history.back();</script>';
        $conn->close();
        exit();
    }

    // Hashear la contraseña de forma segura
    $contrasena_hasheada = password_hash($contrasena, PASSWORD_DEFAULT);

    // Verificar si ya existe un usuario con este correo electrónico
    $sql_verificar = "SELECT correo FROM $tabla_usuarios WHERE correo = ?";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bind_param("s", $correo);
    $stmt_verificar->execute();
    $stmt_verificar->store_result();

    if ($stmt_verificar->num_rows > 0) {
        echo '<script>alert("Ya existe un usuario registrado con este correo electrónico."); window.history.back();</script>';
        $stmt_verificar->close();
        $conn->close();
        exit();
    }

    $stmt_verificar->close();

    // Insertar el nuevo usuario en la base de datos
    $sql_insertar = "INSERT INTO $tabla_usuarios (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)";
    $stmt_insertar = $conn->prepare($sql_insertar);
    $stmt_insertar->bind_param("ssss", $nombre, $correo, $contrasena_hasheada, $rol);

    if ($stmt_insertar->execute()) {
        echo '<script>alert("Usuario creado exitosamente."); window.location.href = "inicio.html";</script>';
        // Puedes redirigir a otra página después de la creación exitosa
        exit();
    } else {
        echo '<script>alert("Error al crear el usuario: ' . $stmt_insertar->error . '"); window.history.back();</script>';
    }

    $stmt_insertar->close();
} else {
    echo '<script>alert("No se recibieron datos del formulario de registro."); window.history.back();</script>';
}

$conn->close();

?>