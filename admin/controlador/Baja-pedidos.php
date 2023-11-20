<?php
include('../../conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" ) {

    if (isset($_POST["idPedidoBaja"])) {

        $idPedidoBaja = intval($_POST["idPedidoBaja"]);


        $conexion = conectar();


        $secuenciaBaja = $conexion->prepare("DELETE FROM Pedidos WHERE idPedido = ?");


        $secuenciaBaja->bind_param('i', $idPedidoBaja);


        if ($secuenciaBaja->execute()) {
            echo "Pedido dado de baja exitosamente";
        } else {
            echo "Error al dar de baja el pedido: " . $secuenciaBaja->error;
        }


        $conexion->close();
    } else {
        echo "ID del pedido no definido.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Baja de Pedidos</title>
    <link rel="stylesheet" href="../../Css/Alta.css">
</head>

<body>
    <h1>Baja de Pedidos</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="idPedidoBaja">ID del Pedido a dar de baja:</label>
        <input type="text" name="idPedidoBaja" id="idPedidoBaja" required><br><br>
        <button type="submit">Confirmar Baja</button>
    </form>

    <div>
        <?php

        $conexion = conectar();


        $consultaPedidos = $conexion->query("SELECT idPedido, idProducto, Cantidad, Total, idCliente, idEmpleado FROM Pedidos ORDER BY idPedido");


        if ($consultaPedidos->num_rows > 0) {
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

            while ($fila = $consultaPedidos->fetch_assoc()) {
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

        $conexion->close();
        ?>
    </div>


    <form action="../Alta-pedidos.php" method="post">
        <button type="submit">Alta</button>
    </form>

    <form action="Modif-pedidos.php" method="post">
        <button type="submit">Modificación</button>
    </form>
</body>
</html>
