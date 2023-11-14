<?php
include('conexion.php');

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $idUser = htmlspecialchars($_POST["id"]);
    $contrasena = $_POST["contrasena"];

    if (strlen($contrasena) < 8) {
        $mensajeError = "La contraseña debe tener al menos 8 caracteres.";
    } else {
        // Encriptar la contraseña
        $hashContrasena = password_hash($contrasena, PASSWORD_DEFAULT);

        $conexion = conectar();

        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        $consulta = $conexion->prepare("INSERT INTO Usuarios (idUsuario, Tipo, Contrasenia) VALUES (?, ?, ?)");
        $tipo = "Empleado"; // O el tipo que desees asignar

        $consulta->bind_param("iss", $idUser, $tipo, $hashContrasena);


        try {
            if ($consulta->execute()) {
                $mensajeExito = "Registro exitoso. ¡Bienvenido!";
            } else {
                throw new Exception("Error al registrar el usuario: " . $conexion->error);
            }
        } catch (Exception $e) {
            $mensajeError = $e->getMessage();
        }

        $conexion->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="Css/Login.css">
</head>

<body>
    <div class="login-container">
        <h1>Registro de Usuario</h1>

        <?php
        if (isset($mensajeExito)) {
            echo "<p class='mensaje-exito'>$mensajeExito</p>";
        } elseif (isset($mensajeError)) {
            echo "<p class='mensaje-error'>$mensajeError</p>";
        }
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="id">id Usuario:</label>
            <input type="text" name="id" required><br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" required><br>

            <button type="submit">Registrarse</button>
        </form>
    </div>
</body>

</html>