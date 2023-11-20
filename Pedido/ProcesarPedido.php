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

        // Verificar si ya existe un ID de pedido en la sesión
        if (isset($_SESSION['idPedido'])) {
            // Obtener el ID de cliente actual
            $sqlClienteActual = "SELECT idCliente FROM clientes WHERE NombreCli = '$nombreUsuario'";
            $resultClienteActual = $conn->query($sqlClienteActual);

            if ($resultClienteActual->num_rows > 0) {
                $rowClienteActual = $resultClienteActual->fetch_assoc();
                $idClienteActual = $rowClienteActual['idCliente'];

                // Obtener el ID de cliente almacenado en la sesión
                $idClienteSesion = $_SESSION['idCliente'];

                // Verificar si el cliente actual es diferente al cliente almacenado en la sesión
                if ($idClienteActual != $idClienteSesion) {
                    // Generar un nuevo ID de pedido para el nuevo cliente
                    $idPedido = rand(10000, 99999);
                    $_SESSION['idPedido'] = $idPedido;
                    // Actualizar el ID de cliente almacenado en la sesión
                    $_SESSION['idCliente'] = $idClienteActual;
                } else {
                    // Utilizar el ID de pedido existente para el mismo cliente
                    $idPedido = $_SESSION['idPedido'];
                }
            } else {
                echo "No se encontró el cliente con el nombre de usuario proporcionado.";
                exit();
            }
        } else {
            // Si no hay ID de pedido en la sesión, generar uno nuevo
            $idPedido = rand(10000, 99999);
            $_SESSION['idPedido'] = $idPedido;

            // Obtener el ID de cliente actual
            $sqlClienteActual = "SELECT idCliente FROM clientes WHERE NombreCli = '$nombreUsuario'";
            $resultClienteActual = $conn->query($sqlClienteActual);

            if ($resultClienteActual->num_rows > 0) {
                $rowClienteActual = $resultClienteActual->fetch_assoc();
                $idClienteActual = $rowClienteActual['idCliente'];
                $_SESSION['idCliente'] = $idClienteActual;
            } else {
                echo "No se encontró el cliente con el nombre de usuario proporcionado.";
                exit();
            }
        }

        // Resto del código para obtener el ID de empleado y realizar la inserción en la tabla de pedidos
        // ...

        // Ejemplo de inserción en la tabla de pedidos (ajusta según tu base de datos)
        $sqlEmpleados = "SELECT idEmpleado FROM empleados";
        $resultEmpleados = $conn->query($sqlEmpleados);

        if ($resultEmpleados->num_rows > 0) {
            $empleadosDisponibles = array();
            while ($rowEmpleado = $resultEmpleados->fetch_assoc()) {
                $empleadosDisponibles[] = $rowEmpleado['idEmpleado'];
            }

            // Elegir aleatoriamente un ID de empleado
            $idEmpleado = $empleadosDisponibles[array_rand($empleadosDisponibles)];

            // Realizar la inserción en la tabla de pedidos
            $sqlInsertarPedido = "INSERT INTO pedidos (idPedido, idProducto, Cantidad, Total, idCliente, idEmpleado) VALUES ('$idPedido', '$idProductoSeleccionado', '$cantidad', '$total', '$idClienteActual', '$idEmpleado')";

            if ($conn->query($sqlInsertarPedido) === TRUE) {
                echo "Pedido registrado correctamente. Número de Pedido: " . $idPedido;
            } else {
                echo "Error al registrar el pedido: " . $conn->error;
            }
        } else {
            echo "No hay empleados disponibles.";
        }

        // Cerrar la conexión
        $conn->close();

        // Limpiar las sesiones después de completar el pedido
        unset($_SESSION['idProducto']);
        unset($_SESSION['cantidad']);
    } else {
        echo "Error al obtener el precio del producto.";
    }
} else {
    // Si no se ha enviado el formulario correctamente, redirigir al catálogo de productos
    header("Location: Catalogo.php");
    exit();
}
?>
<body>
    <a href="Catalogo.php">Compra más!!</a>
