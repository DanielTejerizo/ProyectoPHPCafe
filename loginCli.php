<?php
include('conexion.php');

// Inicializar mensaje de error
$mensajeError = "";

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idUsuario"])) {
    // Limpiar y recoger datos del formulario
    $idUsuario = limpiarDatos($_POST["idUsuario"]);
    $contrasena = limpiarDatos($_POST["contrasena"]);

    $conexion = conectar();

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Consulta para obtener la contraseña almacenada
    $consulta = $conexion->prepare("SELECT NombreUsuario, Contrasenia, Tipo FROM usuarios WHERE NombreUsuario = ?");
    $consulta->bind_param("s", $idUsuario);

    if ($consulta->execute()) {
        $consulta->store_result();

        if ($consulta->num_rows == 1) {
            $consulta->bind_result($nombreUsuario, $hashContrasena, $tipoUsuario);
            $consulta->fetch();

            // Verificar la contraseña
            if (password_verify($contrasena, $hashContrasena)) {
                // Inicio de sesión exitoso
                session_start();
                $_SESSION['nombreUsuario'] = $nombreUsuario;
                $_SESSION['tipoUsuario'] = $tipoUsuario;

                // Regenerar el ID de sesión para evitar ataques de secuestro de sesión
                session_regenerate_id(true);

                // Redireccionar a la página después del inicio de sesión
                header("Location: Admin/Controlador/Controlador.php");
                exit();
            } else {
                $mensajeError = "La contraseña es incorrecta.";
            }
        } else {
            $mensajeError = "Nombre de usuario no encontrado.";
        }
    } else {
        // Log de errores en lugar de mostrar al usuario
        error_log("Error al realizar la consulta en el inicio de sesión: " . $conexion->error);
        $mensajeError = "Error en el inicio de sesión. Por favor, inténtelo de nuevo más tarde.";
    }

    $conexion->close();
}

// Función para limpiar datos
function limpiarDatos($datos) {
    // Aplicar limpieza según sea necesario (puedes agregar más validaciones aquí)
    return htmlspecialchars(trim($datos));
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="Css/Login.css">
</head>

<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>

        <?php
        if (!empty($mensajeError)) {
            echo "<p class='mensaje-error'>$mensajeError</p>";
        }
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="idUsuario">Nombre de Usuario:</label>
            <input type="text" name="idUsuario" required><br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" required><br>

            <button type="submit">Iniciar Sesión</button>
        </form>

        <form method="post" action="registroCli.php">
            <button type="submit">¿No tienes cuenta? ¡Crea una!</button>
        </form>
    </div>
</body>

</html>