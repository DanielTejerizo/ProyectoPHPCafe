<?php
include 'conexion.php';

// Manejar el envío del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conectar();

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener el ID del producto seleccionado, la cantidad y generar un número de pedido y empleado aleatorios
    $idProductoSeleccionado = $_POST['producto'];
    $cantidad = $_POST['cantidad'];
    $nombreUsuario = $_POST['nombre_usuario'];
    $numeroPedido = rand(1000, 9999);
    $idPedido = rand(10000, 99999); // Nuevo idPedido aleatorio

    // Obtener aleatoriamente un ID de empleado de la tabla de usuarios
    $sqlEmpleados = "SELECT idEmpleado FROM empleados";
    $resultEmpleados = $conn->query($sqlEmpleados);

    if ($resultEmpleados->num_rows > 0) {
        $empleadosDisponibles = array();
        while ($rowEmpleado = $resultEmpleados->fetch_assoc()) {
            $empleadosDisponibles[] = $rowEmpleado['idEmpleado'];
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
            $sqlInsertarPedido = "INSERT INTO pedidos (idPedido, idProducto, Cantidad, Total, idCliente, idEmpleado) VALUES ('$idPedido', '$idProductoSeleccionado', '$cantidad', '$total', '$nombreUsuario', '$idEmpleado')";

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
    <title>Realizar Pedido</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #c79d5a;
    }

    h2 {
        text-align: center;
    }

    form {
        max-width: 400px;
        margin: 0 auto;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 8px;
    }

    input,
    select {
        width: 100%;
        padding: 8px;
        margin-bottom: 12px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    input[type="submit"] {
        background-color: #4caf50;
        color: white;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    button {
        background-color: #666;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #d6b482;
        color: black;
    }
</style>

<body>

    <h2>Seleccionar Producto para Pedido</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['idProducto'] . "'>" . $row['NombreProd'] . "</option>";
        }
        echo "</select>";

        // Cerrar la conexión
        $conn->close();
        ?>

        <br><br>

        <!-- Agregar campo para ingresar el nombre de usuario -->
        <label for="nombre_usuario">Nombre de Usuario:</label>
        <input type="text" name="nombre_usuario" required>

        <br><br>

        <!-- Agregar campo para ingresar la cantidad -->
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" required>

        <br><br>
        <button type="submit" name="submit">Realizar pedido </button>
    </form>

</body>

</html>