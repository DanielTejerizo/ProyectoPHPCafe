<?php
include('../conexion.php');

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idPedido"])) {
    // Obtener el ID del pedido a modificar
    $idPedido = $_POST["idPedido"];

    // Conectar a la base de datos
    $conexion = conectar();

    // Obtener los detalles del pedido actual
    $consulta_pedido = $conexion->prepare("SELECT * FROM Pedidos WHERE idPedido = ?");
    $consulta_pedido->bind_param('i', $idPedido);
    $consulta_pedido->execute();
    $resultado_pedido = $consulta_pedido->get_result();

    // Verificar si hay resultados
    if ($resultado_pedido->num_rows > 0) {
        $pedido = $resultado_pedido->fetch_assoc();
    } else {
        echo "Pedido no encontrado.";
        exit;
    }

    // Consultar opciones para claves foráneas (Clientes y Empleados)
    $consulta_clientes = $conexion->query("SELECT idCliente, NombreCliente FROM Clientes");
    $consulta_empleados = $conexion->query("SELECT idEmpleado, NombreEmpleado FROM Empleados");

    // Cerrar la conexión
    $conexion->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Modificación de Pedidos</title>
    <link rel="stylesheet" href="../css/Alta.css">
    <script>
        function mostrarAlertaModificacion(mensaje) {
            alert(mensaje);
        }
    </script>
</head>

<body>
    <h1>Modificación de Pedidos</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <input type="hidden" name="idPedido" value="<?php echo $pedido['idPedido']; ?>">
        <label for="nuevaCantidad">Nueva Cantidad:</label>
        <input type="text" name="nuevaCantidad" id="nuevaCantidad" value="<?php echo $pedido['Cantidad']; ?>" required><br><br>
        <label for="nuevoTotal">Nuevo Total:</label>
        <input type="text" name="nuevoTotal" id="nuevoTotal" value="<?php echo $pedido['Total']; ?>" required><br><br>
        <label for="nuevoIdCliente">Nuevo Cliente:</label>
        <select name="nuevoIdCliente" id="nuevoIdCliente" required>
            <?php
            while ($cliente = $consulta_clientes->fetch_assoc()) {
                echo "<option value='{$cliente['idCliente']}'";
                if ($pedido['idCliente'] == $cliente['idCliente']) {
                    echo " selected";
                }
                echo ">{$cliente['NombreCliente']}</option>";
            }
            ?>
        </select><br><br>
        <label for="nuevoIdEmpleado">Nuevo Empleado:</label>
        <select name="nuevoIdEmpleado" id="nuevoIdEmpleado" required>
            <?php
            while ($empleado = $consulta_empleados->fetch_assoc()) {
                echo "<option value='{$empleado['idEmpleado']}'";
                if ($pedido['idEmpleado'] == $empleado['idEmpleado']) {
                    echo " selected";
                }
                echo ">{$empleado['NombreEmpleado']}</option>";
            }
            ?>
        </select><br><br>
        <button type="submit">Confirmar Modificación</button>
    </form>

    <!-- Puedes agregar una sección para mostrar detalles del pedido actual después de la modificación -->

    <div>
        <h2>Detalles del Pedido Actual</h2>
        <p>ID Pedido: <?php echo $pedido['idPedido']; ?></p>
        <p>Cantidad: <?php echo $pedido['Cantidad']; ?></p>
        <p>Total: <?php echo $pedido['Total']; ?></p>
        <p>ID Cliente: <?php echo $pedido['idCliente']; ?></p>
        <p>ID Empleado: <?php echo $pedido['idEmpleado']; ?></p>
    </div>

    <!-- Puedes agregar enlaces o botones para regresar a otras operaciones (Alta, Baja, etc.) -->

</body>

</html>
