<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #c79d5a;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    form {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
    }

    input[type="submit"], a {
        padding: 10px;
        margin: 5px;
        font-size: 16px;
        text-decoration: none;
        color: #fff;
        background-color: #666;
        border: 1px solid #007bff;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover, a:hover {
        background-color: #d6b482;
            color: black;
    }
</style>
<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');
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

                echo "No ha realizado nunca un pedido. ¿Quieres proporcionar tus datos para realizar un pedido?";
                echo '<form action="formulario_cliente.php" method="post">';
                echo '<input type="hidden" name="nombre_usuario" value="' . htmlspecialchars($nombreUsuario) . '">';
                echo '<input type="hidden" name="idProducto" value="' . htmlspecialchars($idProductoSeleccionado) . '">';
                echo '<input type="hidden" name="cantidad" value="' . htmlspecialchars($cantidad) . '">';
                echo '<input type="submit" value="Sí">';
                echo '</form>';
                echo '<a href="Catalogo.php">No, volver al catálogo</a>';
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

                echo "No ha realizado nunca un pedido. ¿Quieres proporcionar tus datos para realizar un pedido??";
                echo '<form action="formulario_cliente.php" method="post">';
                echo '<input type="hidden" name="nombre_usuario" value="' . htmlspecialchars($nombreUsuario) . '">';
                echo '<input type="hidden" name="idProducto" value="' . htmlspecialchars($idProductoSeleccionado) . '">';
                echo '<input type="hidden" name="cantidad" value="' . htmlspecialchars($cantidad) . '">';
                echo '<input type="submit" value="Sí">';
                echo '</form>';
                echo '<a href="Catalogo.php">No, volver al catálogo</a>';
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
