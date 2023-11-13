<?php
include('conexion.php');

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $nombreUsuario = $_POST["nombreUsuario"];
    $contrasena = $_POST["contrasena"];

    $conexion = conectar();

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Consulta para obtener la contraseña almacenada
    $consulta = $conexion->prepare("SELECT idUsuario, Contrasenia, Tipo FROM Usuarios WHERE NombreUsuario = ?");
    $consulta->bind_param("s", $nombreUsuario);

    if ($consulta->execute()) {
        $consulta->store_result();

        if ($consulta->num_rows == 1) {
            $consulta->bind_result($idUsuario, $hashContrasena, $tipoUsuario);
            $consulta->fetch();

            // Verificar la contraseña
            if (password_verify($contrasena, $hashContrasena)) {
                // Inicio de sesión exitoso
                session_start();
                $_SESSION['idUsuario'] = $idUsuario;
                $_SESSION['tipoUsuario'] = $tipoUsuario;

                // Redireccionar a la página después del inicio de sesión
                header("Location: pagina_protegida.php");
                exit();
            } else {
                $mensajeError = "La contraseña es incorrecta.";
            }
        } else {
            $mensajeError = "El nombre de usuario no existe.";
        }
    } else {
        $mensajeError = "Error al realizar la consulta: " . $conexion->error;
    }

    $conexion->close();
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
        if (isset($mensajeError)) {
            echo "<p class='mensaje-error'>$mensajeError</p>";
        }
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="nombreUsuario">Nombre de Usuario:</label>
            <input type="text" name="nombreUsuario" required><br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" required><br>

            <button type="submit">Iniciar Sesión</button>
        </form>
    </div>
</body>

</html>
