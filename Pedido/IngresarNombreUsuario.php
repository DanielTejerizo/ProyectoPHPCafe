<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ingresar Nombre de Usuario</title>
    <link rel="stylesheet" href="../css/Productos.css">
</head>

<body>
    <h2>Ingrese su Nombre de Usuario</h2>

    <form method="post" action="ProcesarPedido.php">
        <label for="nombre_usuario">Nombre de Usuario:</label>
        <input type="text" name="nombre_usuario" required>
        <br><br>
        <button type="submit" name="confirmar">Confirmar Pedido</button>
    </form>
</body>

</html>
