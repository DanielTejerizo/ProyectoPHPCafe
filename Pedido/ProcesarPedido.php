<?php
include '../conexion.php';

session_start();

function generarIdPedido() {
    return rand(10000, 99999);
}

function obtenerIdEmpleadoAleatorio($conn) {
    $sqlEmpleados = "SELECT idEmpleado FROM empleados";
    $resultEmpleados = $conn->query($sqlEmpleados);

    if ($resultEmpleados->num_rows > 0) {
        $empleadosDisponibles = array();
        while ($rowEmpleado = $resultEmpleados->fetch_assoc()) {
            $empleadosDisponibles[] = $rowEmpleado['idEmpleado'];
        }
        return $empleadosDisponibles[array_rand($empleadosDisponibles)];
    } else {
        die("No hay empleados disponibles.");
    }
}

function obtenerIdClientePorNombre($conn, $nombreUsuario) {
    $sqlCliente = "SELECT idCliente FROM clientes WHERE NombreCli = ?";
    $stmt = $conn->prepare($sqlCliente);
    $stmt->bind_param("s", $nombreUsuario);
    $stmt->execute();
    $resultCliente = $stmt->get_result();

    if ($resultCliente->num_rows > 0) {
        $rowCliente = $resultCliente->fetch_assoc();
        return $rowCliente['idCliente'];
    } else {
        die("No se encontró el cliente con el nombre de usuario proporcionado.");
    }
}

function obtenerPrecioProducto($conn, $idProducto) {
    $sqlPrecio = "SELECT Precio FROM productos WHERE idProducto = ?";
    $stmt = $conn->prepare($sqlPrecio);
    $stmt->bind_param("i", $idProducto);
    $stmt->execute();
    $resultPrecio = $stmt->get_result();

    if ($resultPrecio->num_rows > 0) {
        $rowPrecio = $resultPrecio->fetch_assoc();
        return $rowPrecio['Precio'];
    } else {
        die("Error al obtener el precio del producto.");
    }
}

// Verificar si se ha enviado el formulario desde el paso anterior
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirmar"])) {
    $conn = conectar();

    // Obtener los datos almacenados en sesiones
    $productosSeleccionados = $_SESSION['productosSeleccionados'];
    $nombreUsuario = $_POST['nombre_usuario'];

    // Generar un ID de pedido aleatorio
    $idPedido = generarIdPedido();

    // Obtener un ID de empleado aleatorio
    $idEmpleado = obtenerIdEmpleadoAleatorio($conn);

    // Obtener el ID del cliente a partir del nombre de usuario
    $idCliente = obtenerIdClientePorNombre($conn, $nombreUsuario);

    // Iterar sobre los productos y realizar la inserción en la tabla de pedidos
    foreach ($productosSeleccionados as $idProducto => $cantidad) {
        // Obtener el precio del producto seleccionado
        $precioProducto = obtenerPrecioProducto($conn, $idProducto);

        // Calcular el total
        $total = $cantidad * $precioProducto;

        // Realizar la inserción en la tabla de pedidos
        $sqlInsertarPedido = "INSERT INTO pedidos (idPedido, idProducto, Cantidad, Total, idCliente, idEmpleado) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sqlInsertarPedido);
        $stmt->bind_param("iiiiii", $idPedido, $idProducto, $cantidad, $total, $idCliente, $idEmpleado);
        
        if ($stmt->execute() !== TRUE) {
            die("Error al registrar el pedido: " . $conn->error);
            // Puedes manejar errores aquí, por ejemplo, redirigiendo a una página de error
        }
    }

    echo "Pedido registrado correctamente. Número de Pedido: " . $idPedido;

    // Cerrar la conexión
    $conn->close();

    // Limpiar las sesiones después de completar el pedido
    unset($_SESSION['productosSeleccionados']);
} else {
    // Si no se ha enviado el formulario correctamente, redirigir al catálogo de productos
    header("Location: Catalogo.php");
    exit();
}
?>
