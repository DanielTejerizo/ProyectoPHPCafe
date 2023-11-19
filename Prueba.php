<?php
include 'conexion.php';

// Manejar el envío del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conectar();

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener el ID del producto seleccionado, la cantidad, el ID del cliente y generar un número de pedido y empleado aleatorios
    $idProductoSeleccionado = $_POST['producto'];
    $cantidad = $_POST['cantidad'];
    $idCliente = $_POST['cliente'];
    $numeroPedido = rand(1000, 9999);
    $idPedido = rand(10000, 99999); // Nuevo idPedido aleatorio

    // Obtener aleatoriamente un ID de empleado de la tabla de usuarios
    $sqlEmpleados = "SELECT NombreUsuario FROM usuarios WHERE Tipo = 'Empleado'";
    $resultEmpleados = $conn->query($sqlEmpleados);

    if ($resultEmpleados->num_rows > 0) {
        $empleadosDisponibles = array();
        while ($rowEmpleado = $resultEmpleados->fetch_assoc()) {
            $empleadosDisponibles[] = $rowEmpleado['NombreUsuario'];
        }

        // Elegir aleatoriamente un ID de empleado
        $idEmpleado = $empleadosDisponibles[array_rand($empleadosDisponibles)];

        // Obtener el precio del producto seleccionado
        $sqlPrecio = "SELECT Precio FROM productos WHERE idProducto = '$idProductoSeleccionado'";
        $resultPrecio = $conn->query($sqlPrecio);

        if ($resultPrecio->num_rows > 0) {
            $rowPrecio = $resultPrecio->fetch_assoc();
            $precioProducto = $rowPrecio['Precio'];

            // Calcular el total
            $total = $cantidad * $precioProducto;

            // Inserción en la tabla de pedidos con idPedido aleatorio
            $sqlInsertarPedido = "INSERT INTO pedidos (idPedido, idProducto, Cantidad, Total, idCliente, idEmpleado) VALUES ('$idPedido', '$idProductoSeleccionado', '$cantidad', '$total', '$idCliente', '$idEmpleado')";


            if ($conn->query($sqlInsertarPedido) === TRUE) {
                echo "Pedido registrado correctamente. Número de Pedido: " . $idPedido;
            } else {
                echo "Error al registrar el pedido: " . $conn->error;
            }

        } else {
            echo "Error al obtener el precio del producto.";
        }

    } else {
        echo "No hay empleados disponibles.";
    }

    // Cerrar la conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/Login.css">
    <title>Realizar Pedido</title>
</head>
<body>

<h2>Seleccionar Producto para Pedido</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php
    $conn = conectar();

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta para obtener productos disponibles
    $sql = "SELECT idProducto, NombreProd FROM vista_productos_disponibles";
    $result = $conn->query($sql);

    // Crear un menú desplegable con los productos
    echo "Selecciona un producto: ";
    echo "<select name='producto'>";
    while($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['idProducto'] . "'>" . $row['NombreProd'] . "</option>";
    }
    echo "</select>";

    // Consulta para obtener clientes
    $sqlClientes = "SELECT idCliente, NombreCli FROM clientes";
    $resultClientes = $conn->query($sqlClientes);

    // Crear un menú desplegable con los clientes
    echo "<br><br>Selecciona un cliente: ";
    echo "<select name='cliente'>";
    while($rowCliente = $resultClientes->fetch_assoc()) {
        echo "<option value='" . $rowCliente['idCliente'] . "'>" . $rowCliente['NombreCli'] . "</option>";
    }
    echo "</select>";

    // Cerrar la conexión
    $conn->close();
    ?>

    <br><br>

    <!-- Agregar campo para ingresar la cantidad -->
    <label for="cantidad">Cantidad:</label>
    <input type="number" name="cantidad" required>

    <br><br>
    <input type="submit" name="submit" value="Realizar Pedido">
</form>

</body>
</html>
