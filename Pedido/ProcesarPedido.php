<link rel="stylesheet" href="../css/Productos.css">
<?php
include '../conexion.php';

session_start();

// Verificar si se ha enviado el formulario desde el paso anterior
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirmar"])) {
    $conn = conectar();

    // Obtener los datos almacenados en sesiones
    $idProductoSeleccionado = $_SESSION['idProducto'];
    $cantidad = $_SESSION['cantidad'];
    $nombreUsuario = $_POST['nombre_usuario'];

    // Obtener el precio del producto seleccionado
    $sqlPrecio = "SELECT Precio FROM productos WHERE idProducto = '$idProductoSeleccionado'";
    $resultPrecio = $conn->query($sqlPrecio);

    if ($resultPrecio->num_rows > 0) {
        $rowPrecio = $resultPrecio->fetch_assoc();
        $precioProducto = $rowPrecio['Precio'];

        // Calcular el total
        $total = $cantidad * $precioProducto;

        // Generar un ID de pedido aleatorio
        $idPedido = rand(10000, 99999);

        // Obtener un ID de empleado aleatorio de la tabla empleados
        $sqlEmpleados = "SELECT idEmpleado FROM empleados";
        $resultEmpleados = $conn->query($sqlEmpleados);

        if ($resultEmpleados->num_rows > 0) {
            $empleadosDisponibles = array();
            while ($rowEmpleado = $resultEmpleados->fetch_assoc()) {
                $empleadosDisponibles[] = $rowEmpleado['idEmpleado'];
            }

            // Elegir aleatoriamente un ID de empleado
            $idEmpleado = $empleadosDisponibles[array_rand($empleadosDisponibles)];

            // Obtener el ID del cliente a partir del nombre de usuario
            $sqlCliente = "SELECT idCliente FROM clientes WHERE NombreCli = '$nombreUsuario'";
            $resultCliente = $conn->query($sqlCliente);

            if ($resultCliente->num_rows > 0) {
                $rowCliente = $resultCliente->fetch_assoc();
                $idCliente = $rowCliente['idCliente'];

                // Realizar la inserción en la tabla de pedidos
                // (Agrega aquí tu lógica para obtener más información del empleado si es necesario)

                // Ejemplo de inserción en la tabla de pedidos (ajusta según tu base de datos)
                $sqlInsertarPedido = "INSERT INTO pedidos (idPedido, idProducto, Cantidad, Total, idCliente, idEmpleado) VALUES ('$idPedido', '$idProductoSeleccionado', '$cantidad', '$total', '$idCliente', '$idEmpleado')";

                if ($conn->query($sqlInsertarPedido) === TRUE) {
                    echo "Pedido registrado correctamente. Número de Pedido: " . $idPedido;
                } else {
                    echo "Error al registrar el pedido: " . $conn->error;
                }
            } else {
                echo "No se encontró el cliente con el nombre de usuario proporcionado.";
            }
        } else {
            echo "No hay empleados disponibles.";
        }
    } else {
        echo "Error al obtener el precio del producto.";
    }

    // Cerrar la conexión
    $conn->close();

    // Limpiar las sesiones después de completar el pedido
    unset($_SESSION['idProducto']);
    unset($_SESSION['cantidad']);
} else {
    // Si no se ha enviado el formulario correctamente, redirigir al catálogo de productos
    header("Location: Catalogo.php");
    exit();
}
?>
<body>
    <a href="Catalogo.php">Compra más!!</a>
