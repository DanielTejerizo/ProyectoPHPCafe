<?php
include('../conexion.php');

// Verificar si se ha enviado el formulario de modificación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idPedidoModificar"])) {
    // Obtener el ID del pedido a modificar
    $idPedidoModificar = intval($_POST["idPedidoModificar"]);

    // Conectar a la base de datos
    $conexion = conectar();

    // Consultar la información del pedido
    $consultaPedido = $conexion->prepare("SELECT idPedido, idProducto, Cantidad, Total, idCliente, idEmpleado FROM Pedidos WHERE idPedido = ?");
    $consultaPedido->bind_param('i', $idPedidoModificar);

    if ($consultaPedido->execute()) {
        $consultaPedido->store_result();

        if ($consultaPedido->num_rows == 1) {
            // Obtener los datos del pedido
            $consultaPedido->bind_result($idPedido, $idProducto, $cantidad, $total, $idCliente, $idEmpleado);
            $consultaPedido->fetch();
        } else {
            echo "ID de pedido no encontrado.";
            exit();
        }
    } else {
        echo "Error al consultar el pedido: " . $consultaPedido->error;
        exit();
    }

    // Cerrar la conexión
    $conexion->close();
} else {
    // Si no se ha enviado el formulario, mostrar un mensaje de advertencia
    echo "ID del pedido a modificar no definido.";
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Modificación de Pedidos</title>
    <link rel="stylesheet" href="../../Css/Alta.css">
</head>

<body>
    <h1>Modificación de Pedidos</h1>

    <form method="POST" action="procesar_modificacion_pedido.php">
        <!-- Campos ocultos para enviar el ID del pedido -->
        <input name="idPedidoModificar" value="<?php echo $idPedidoModificar; ?>">

        <label for="idProducto">ID del Producto:</label>
        <input type="text" name="idProducto" value="<?php echo $idProducto; ?>" required><br><br>
        
        <label for="cantidad">Cantidad:</label>
        <input type="text" name="cantidad" value="<?php echo $cantidad; ?>" required><br><br>

        <label for="total">Total:</label>
        <input type="text" name="total" value="<?php echo $total; ?>" required><br><br>

        <label for="idCliente">ID del Cliente:</label>
        <input type="text" name="idCliente" value="<?php echo $idCliente; ?>" required><br><br>

        <label for="idEmpleado">ID del Empleado:</label>
        <input type="text" name="idEmpleado" value="<?php echo $idEmpleado; ?>" required><br><br>

        <button type="submit">Confirmar Modificación</button>
    </form>
</body>

</html>
