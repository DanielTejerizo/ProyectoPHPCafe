<?php
include('../conexion.php');

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idPedido"])) {
    // Obtener los valores del formulario
    $idPedido = $_POST["idPedido"];
    $idProducto = $_POST["idProducto"];
    $cantidad = $_POST["cantidad"];
    $total = $_POST["total"];
    $idCliente = $_POST["idCliente"];
    $idEmpleado = $_POST["idEmpleado"];

    // Conectar a la base de datos
    $conexion = conectar();

    // Verificar la existencia del cliente antes de insertar el pedido
    $verificarCliente = $conexion->prepare("SELECT idCliente FROM Clientes WHERE idCliente = ?");
    $verificarCliente->bind_param("i", $idCliente);
    $verificarCliente->execute();
    $verificarCliente->store_result();

    if ($verificarCliente->num_rows == 0) {
        echo "Error: El cliente con ID $idCliente no existe.";
    } else {
        // Preparar la consulta de inserción
        $secuencia = $conexion->prepare("INSERT INTO Pedidos (idPedido, idProducto, Cantidad, Total, idCliente, idEmpleado) VALUES (?, ?, ?, ?, ?, ?)");

        // Corregir la línea de bind_param
        $secuencia->bind_param('iiisii', $idPedido, $idProducto, $cantidad, $total, $idCliente, $idEmpleado);

        // Ejecutar la consulta
        if ($secuencia->execute()) {
            echo "<script>mostrarAlerta();</script>";
            echo "Pedido dado de alta exitosamente";
        } else {
            echo "Error al dar de alta el pedido: " . $secuencia->error;
        }
    }

    // Cerrar la conexión
    $conexion->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Alta de Pedidos</title>
    <link rel="stylesheet" href="../css/Alta.css">
    <script>
        function mostrarAlerta() {
            alert("Pedido dado de alta correctamente");
        }
    </script>
</head>

<body>
    <h1>Alta de Pedidos</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="idPedido">ID del Pedido:</label>
        <input type="text" name="idPedido" id="idPedido" required><br><br>
        <label for="idProducto">ID del Producto:</label>
        <select name="idProducto" id="idProducto">
            <?php
            // Conectar a la base de datos
            $conexion = conectar();

            // Consultar los productos disponibles
            $consulta_productos = $conexion->query("SELECT idProducto, NombreProd FROM Productos");

            // Verificar si hay resultados
            if ($consulta_productos->num_rows > 0) {
                // Mostrar opciones del desplegable
                while ($fila = $consulta_productos->fetch_assoc()) {
                    echo "<option value='{$fila['idProducto']}'>{$fila['NombreProd']}</option>";
                }
            } else {
                echo "<option value=''>No hay productos disponibles</option>";
            }

            // Cerrar la conexión
            $conexion->close();
            ?>
        </select><br><br>
        <label for="cantidad">Cantidad:</label>
        <input type="text" name="cantidad" id="cantidad" required><br><br>
        <label for="total">Total:</label>
        <input type="text" name="total" id="total" required><br><br>
        <label for="idCliente">ID del Cliente:</label>
        <select name="idCliente" id="idCliente">
            <?php
            // Conectar a la base de datos
            $conexion = conectar();

            // Consultar los clientes disponibles
            $consulta_clientes = $conexion->query("SELECT idCliente, NombreCli FROM Clientes");

            // Verificar si hay resultados
            if ($consulta_clientes->num_rows > 0) {
                // Mostrar opciones del desplegable
                while ($fila = $consulta_clientes->fetch_assoc()) {
                    echo "<option value='{$fila['idCliente']}'>{$fila['NombreCli']}</option>";
                }
            } else {
                echo "<option value=''>No hay clientes disponibles</option>";
            }

            // Cerrar la conexión
            $conexion->close();
            ?>
        </select><br><br>
        <label for="idEmpleado">ID del Empleado:</label>
        <select name="idEmpleado" id="idEmpleado">
            <?php
            // Conectar a la base de datos
            $conexion = conectar();

            // Consultar los empleados disponibles
            $consulta_empleados = $conexion->query("SELECT idEmpleado, NombreEmp FROM Empleados");

            // Verificar si hay resultados
            if ($consulta_empleados->num_rows > 0) {
                // Mostrar opciones del desplegable
                while ($fila = $consulta_empleados->fetch_assoc()) {
                    echo "<option value='{$fila['idEmpleado']}'>{$fila['NombreEmp']}</option>";
                }
            } else {
                echo "<option value=''>No hay empleados disponibles</option>";
            }

            // Cerrar la conexión
            $conexion->close();
            ?>
        </select><br><br>
        <button type="submit">Confirmar</button>
    </form>

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

    <!-- Formularios para otras operaciones (Modificación, Baja, etc.) -->
    <form action="Modif-pedidos.php" method="post">
        <button type="submit">Modificación</button>
    </form>

    <form action="Baja-pedidos.php" method="post">
        <button type="submit">Baja</button>
    </form>
</body>
</html>
