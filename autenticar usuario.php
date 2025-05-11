<?php
session_start(); // ¡Asegúrate de iniciar la sesión al principio del archivo!

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "harold";
$password = "";
$dbname = "lis";
$tabla_usuarios = "usuarios";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];

    if (empty($correo) || empty($contrasena)) {
        echo '<script>alert("Por favor, ingresa tu correo electrónico y contraseña."); window.history.back();</script>';
        $conn->close();
        exit();
    }

    $sql_buscar = "SELECT id, contrasena, rol FROM $tabla_usuarios WHERE correo = ?";
    $stmt_buscar = $conn->prepare($sql_buscar);
    $stmt_buscar->bind_param("s", $correo);
    $stmt_buscar->execute();
    $stmt_buscar->store_result();

    if ($stmt_buscar->num_rows == 1) {
        $stmt_buscar->bind_result($user_id, $contrasena_hash_db, $rol_usuario);
        $stmt_buscar->fetch();

        if (password_verify($contrasena, $contrasena_hash_db)) {
            // Contraseña correcta, guardar el rol en la sesión
            $_SESSION['rol'] = $rol_usuario;
            $_SESSION['usuario_id'] = $user_id; // Opcional: guardar el ID del usuario

            
            if ($rol_usuario == "Administrador") {
                header("Location: panel_administrador.php");
            } elseif ($rol_usuario == "lider laboratorio"){
                header("Location: panel_laboratorio.php");
            } elseif ($rol_usuario == "lider enfermeria") {
                header("Location: panel_enfermeria.php");
            } else {
                // Rol no reconocido, redirigir a una página por defecto
                header("Location: inicio.html");
            }
            exit();
        } else {
            echo '<script>alert("La contraseña no coincide."); window.history.back();</script>';
        }
    } else {
        echo '<script>alert("No se encontró ningún usuario con ese correo electrónico."); window.history.back();</script>';
    }

    $stmt_buscar->close();
} else {
    echo '<script>alert("No se recibieron datos del formulario de inicio de sesión."); window.history.back();</script>';
}

$conn->close();

?>