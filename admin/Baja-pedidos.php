<?php
include('../conexion.php');

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID del pedido a dar de baja
    $idPedido = $_POST["idPedido"];

    // Conectar a la base de datos
    $conexion = conectar();

    // Preparar la consulta de baja
    $consulta = $conexion->prepare("DELETE FROM Pedidos WHERE idPedido = ?");

    // Corregir la línea de bind_param
    $consulta->bind_param('i', $idPedido);

    // Ejecutar la consulta de baja
    if ($consulta->execute()) {
        echo "<script>mostrarAlertaBaja('Pedido dado de baja correctamente');</script>";
    } else {
        echo "Error al dar de baja el pedido: " . $consulta->error;
    }

    // Cerrar la conexión
    $conexion->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Baja de Pedidos</title>
    <link rel="stylesheet" href="../css/Alta.css">
    <script>
        function mostrarAlertaBaja(mensaje) {
            alert(mensaje);
        }
    </script>
</head>

<body>
    <h1>Baja de Pedidos</h1>

    <!-- Formulario para la baja de pedidos -->
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="idPedido">ID del Pedido a dar de baja:</label>
        <input type="text" name="idPedido" id="idPedido" required><br><br>
        <button type="submit">Confirmar Baja</button>
    </form>

    <!-- Lista de pedidos para referencia -->
    <div>
        <?php
        // Conectar a la base de datos
        $conexion = conectar();

        // Consultar la tabla de pedidos ordenados por ID
        $consulta_pedidos = $conexion->query("SELECT idPedido, idProducto, Cantidad, Total, idCliente, idEmpleado FROM Pedidos ORDER BY idPedido");

        // Verificar si hay resultados
        if ($consulta_pedidos->num_rows > 0) {
            echo "<h2>Lista de Pedidos</h2>";
            echo "<table border='1'>
                <tr>
                    <th>ID Pedido</th>
                    <th>ID Producto</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>ID Cliente</th>
                    <th>ID Empleado</th>
                </tr>";

            while ($fila = $consulta_pedidos->fetch_assoc()) {
                echo "<tr>
                    <td>{$fila['idPedido']}</td>
                    <td>{$fila['idProducto']}</td>
                    <td>{$fila['Cantidad']}</td>
                    <td>{$fila['Total']}</td>
                    <td>{$fila['idCliente']}</td>
                    <td>{$fila['idEmpleado']}</td>
                </tr>";
            }

            echo "</table>";
        } else {
            echo "No hay pedidos registrados.";
        }

        // Cerrar la conexión
        $conexion->close();
        ?>
    </div>

    <!-- Formularios para otras operaciones (Alta, Modificación, etc.) -->
    <form action="Alta-pedidos.php" method="post">
        <button type="submit">Alta</button>
    </form>

    <form action="Modif-pedidos.php" method="post">
        <button type="submit">Modificación</button>
    </form>
</body>

</html>
