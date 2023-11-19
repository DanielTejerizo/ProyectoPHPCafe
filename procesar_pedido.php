<?php
require_once 'conexion.php';

function obtenerIdProducto($idProducto) {
    $conn = conectar();

    // Escapar el ID del producto para prevenir inyecciones SQL
    $idProducto = mysqli_real_escape_string($conn, $idProducto);

    // Consulta para obtener el ID del producto
    $sql = "SELECT idProducto FROM productos WHERE idProducto = '$idProducto'";
    $result = mysqli_query($conn, $sql);

    // Verificar si se encontró el producto
    if ($row = mysqli_fetch_assoc($result)) {
        $idProducto = $row['idProducto'];
    } else {
        $idProducto = null; // El producto no fue encontrado
    }

    // Cerrar la conexión
    mysqli_close($conn);

    return $idProducto;
}

function clienteExiste($idCliente) {
    $conn = conectar();

    // Verificar si el cliente existe en la base de datos
    $sql = "SELECT * FROM clientes WHERE idCliente = '$idCliente'";
    $result = mysqli_query($conn, $sql);

    // Si hay al menos una fila, el cliente existe
    $existe = mysqli_num_rows($result) > 0;

    // Cerrar la conexión
    mysqli_close($conn);

    return $existe;
}

function obtenerIdEmpleadoAleatorio() {
    $conn = conectar();

    // Consulta para obtener un ID de empleado aleatorio
    $sql = "SELECT idEmpleado FROM empleados ORDER BY RAND() LIMIT 1";
    $result = mysqli_query($conn, $sql);

    // Verificar si se encontró un empleado
    if ($row = mysqli_fetch_assoc($result)) {
        $idEmpleado = $row['idEmpleado'];
    } else {
        $idEmpleado = null; // No se encontró ningún empleado
    }

    // Cerrar la conexión
    mysqli_close($conn);

    return $idEmpleado;
}

function realizarPedido($idPedido, $idProducto, $cantidad, $idCliente) {
    $conn = conectar();

    // Verificar si el producto existe
    if (!obtenerIdProducto($idProducto)) {
        echo "El producto con ID '$idProducto' no existe. Introduce un ID de producto válido.";
        return;
    }

    // Verificar si el cliente existe antes de realizar el pedido
    if (!clienteExiste($idCliente)) {
        echo "El ID de cliente no existe. Introduce un ID válido.";
        return;
    }

    // Obtener un ID de empleado aleatorio
    $idEmpleado = obtenerIdEmpleadoAleatorio();

    // Verificar si se encontró un empleado
    if ($idEmpleado === null) {
        echo "No se encontró ningún empleado disponible.";
        return;
    }

    // Insertar el pedido en la base de datos
    $sql = "INSERT INTO pedidos (idPedido, idProducto, Cantidad, idCliente, idEmpleado) 
            VALUES ('$idPedido', '$idProducto', '$cantidad', '$idCliente', '$idEmpleado')";

    if (mysqli_query($conn, $sql)) {
        echo "Pedido realizado con éxito";
    } else {
        echo "Error al realizar el pedido: " . mysqli_error($conn);
    }

    // Cerrar la conexión
    mysqli_close($conn);
}

// Manejo del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idPedido = $_POST["id_pedido"];
    $idProducto = $_POST["id_producto"]; // Asegúrate de tener este campo en tu formulario
    $cantidad = $_POST["cantidad_producto1"];
    $idCliente = $_POST["id_cliente"];

    realizarPedido($idPedido, $idProducto, $cantidad, $idCliente);
}
?>
