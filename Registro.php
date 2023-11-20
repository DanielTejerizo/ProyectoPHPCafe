<?php
include('conexion.php');

$mensajeExito = "";
$mensajeError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $idUsuario = htmlspecialchars($_POST["id"]);
    $contrasena = $_POST["contrasena"];
    $tipoUsuario = $_POST["tipo"];


    if ($tipoUsuario === "Empleado") {

        if (!preg_match('/^EM\d{3}$/', $idUsuario)) {
            $mensajeError = "El nombre de usuario para empleados debe seguir el formato 'EM' seguido de tres dígitos.";
        }
    }

    if (strlen($contrasena) < 8) {
        $mensajeError = "La contraseña debe tener al menos 8 caracteres.";
    } else {
        $hashContrasena = password_hash($contrasena, PASSWORD_DEFAULT);

        $conexion = conectar();

        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        $consulta = $conexion->prepare("INSERT INTO usuarios (NombreUsuario, Tipo, Contrasenia) VALUES (?, ?, ?)");
        $consulta->bind_param("sss", $idUsuario, $tipoUsuario, $hashContrasena);

        try {
            if ($mensajeError === "" && $consulta->execute()) {
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
        if (!empty($mensajeExito)) {
            echo "<p class='mensaje-exito'>$mensajeExito</p>";
        } elseif (!empty($mensajeError)) {
            echo "<p class='mensaje-error'>$mensajeError</p>";
        }
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="id">Nombre de Usuario:</label>
            <input type="text" name="id" required><br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" required><br>

            <label for="tipo">Tipo de Usuario:</label>
            <select name="tipo" required>
                <option value="Cliente">Cliente</option>
                <option value="Empleado">Empleado</option>
            </select><br><br>

            <button type="submit">Registrarse</button>
        </form>

        <form method="post" action="Login.php">
            <button type="submit">Iniciar sesión</button>
        </form>
    </div>
</body>

</html>
