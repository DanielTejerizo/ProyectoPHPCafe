<link rel="stylesheet" href="../css/Productos.css">
<?php
include '../conexion.php';

session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirmar"])) {
    $conn = conectar();


    $idProductoSeleccionado = $_SESSION['idProducto'];
    $cantidad = $_SESSION['cantidad'];
    $nombreUsuario = $_POST['nombre_usuario'];


    $sqlPrecio = "SELECT Precio FROM productos WHERE idProducto = '$idProductoSeleccionado'";
    $resultPrecio = $conn->query($sqlPrecio);

    if ($resultPrecio->num_rows > 0) {
        $rowPrecio = $resultPrecio->fetch_assoc();
        $precioProducto = $rowPrecio['Precio'];


        $total = $cantidad * $precioProducto;


        if (isset($_SESSION['idPedido'])) {

            $sqlClienteActual = "SELECT idCliente FROM clientes WHERE NombreCli = '$nombreUsuario'";
            $resultClienteActual = $conn->query($sqlClienteActual);

            if ($resultClienteActual->num_rows > 0) {
                $rowClienteActual = $resultClienteActual->fetch_assoc();
                $idClienteActual = $rowClienteActual['idCliente'];


                $idClienteSesion = $_SESSION['idCliente'];


                if ($idClienteActual != $idClienteSesion) {

                    $idPedido = rand(10000, 99999);
                    $_SESSION['idPedido'] = $idPedido;

                    $_SESSION['idCliente'] = $idClienteActual;
                } else {

                    $idPedido = $_SESSION['idPedido'];
                }
            } else {
                echo "No se encontró el cliente con el nombre de usuario proporcionado.";
                exit();
            }
        } else {

            $idPedido = rand(10000, 99999);
            $_SESSION['idPedido'] = $idPedido;


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

        $sqlEmpleados = "SELECT idEmpleado FROM empleados";
        $resultEmpleados = $conn->query($sqlEmpleados);

        if ($resultEmpleados->num_rows > 0) {
            $empleadosDisponibles = array();
            while ($rowEmpleado = $resultEmpleados->fetch_assoc()) {
                $empleadosDisponibles[] = $rowEmpleado['idEmpleado'];
            }

            $idEmpleado = $empleadosDisponibles[array_rand($empleadosDisponibles)];


            $sqlInsertarPedido = "INSERT INTO pedidos (idPedido, idProducto, Cantidad, Total, idCliente, idEmpleado) VALUES ('$idPedido', '$idProductoSeleccionado', '$cantidad', '$total', '$idClienteActual', '$idEmpleado')";

            if ($conn->query($sqlInsertarPedido) === TRUE) {
                echo "Pedido registrado correctamente. Número de Pedido: " . $idPedido;
            } else {
                echo "Error al registrar el pedido: " . $conn->error;
            }
        } else {
            echo "No hay empleados disponibles.";
        }


        $conn->close();


        unset($_SESSION['idProducto']);
        unset($_SESSION['cantidad']);
    } else {
        echo "Error al obtener el precio del producto.";
    }
} else {

    header("Location: Catalogo.php");
    exit();
}
?>
<body>
    <a href="Catalogo.php">Compra más!!</a>
