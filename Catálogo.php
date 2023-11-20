<?php
include 'conexion.php';

// Manejar el envío del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comprar"])) {
    $conn = conectar();

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener el ID del producto seleccionado, la cantidad y generar un número de pedido y empleado aleatorios
    $idProductoSeleccionado = $_POST['idProducto'];
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
    <title>Catálogo de Productos</title>
    <link rel="stylesheet" href="css/Productos.css">
</head>

<body>
    <div class="catalogo-container">
        <h1>Catálogo de Productos</h1>

        <?php

        $conexion = conectar();

        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        // Consulta para obtener productos disponibles
        $sql = "SELECT idProducto, NombreProd, Precio FROM productos";
        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='producto'>";
                echo "<h3>" . $row['NombreProd'] . "</h3>";
                echo "<p>Precio: $" . $row['Precio'] . "</p>";
                echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
                echo "<input type='hidden' name='idProducto' value='" . $row['idProducto'] . "'>";
                echo "<label for='cantidad'>Cantidad:</label>";
                echo "<input type='number' name='cantidad' value='1' min='1' required>";
                echo "<input type='hidden' name='nombre_usuario' value='UsuarioPrueba'>"; // Puedes cambiar 'UsuarioPrueba' por el nombre de usuario real o implementar la lógica para obtenerlo
                echo "<button type='submit' name='comprar'>Comprar</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No hay productos disponibles.</p>";
        }

        $conexion->close();
        ?>
    </div>
</body>

</html>
