<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="../css/Productos.css">
</head>

<body>
    <h2>Crear Nuevo Usuario</h2>

    <form method="post" action="ProcesarNuevoUsuario.php">
        <label for="nombre_usuario">Nombre de Usuario:</label>
        <input type="text" name="nombre_usuario" value="<?php echo isset($_SESSION['nombreUsuario']) ? $_SESSION['nombreUsuario'] : ''; ?>" readonly>
        <br><br>
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" required>
        <br><br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required>
        <br><br>
        <!-- Add more fields as needed for user registration -->

        <button type="submit" name="crear_usuario">Crear Usuario</button>
    </form>
</body>

</html>
