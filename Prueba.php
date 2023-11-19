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
    $numeroEmpleado = rand(100, 999);

    // Puedes realizar alguna acción con el ID del producto, la cantidad, el ID del cliente, el número de pedido y el número de empleado, como agregarlos a una tabla de pedidos
    echo "Pedido realizado para el producto con ID: " . $idProductoSeleccionado . ", Cantidad: " . $cantidad . ", ID del Cliente: " . $idCliente . ", Número de Pedido: " . $numeroPedido . ", Número de Empleado: " . $numeroEmpleado;

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
