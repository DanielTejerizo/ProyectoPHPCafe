<?php
require_once 'conexion.php';

function obtenerIdProducto($nombreProducto) {
    $conn = conectar();

    // Escapar el nombre del producto para prevenir inyecciones SQL
    $nombreProducto = mysqli_real_escape_string($conn, $nombreProducto);

    // Consulta para obtener el ID del producto
    $sql = "SELECT idProducto FROM productos WHERE NombreProd = '$nombreProducto'";
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

function realizarPedido($idPedido, $nombreProducto, $cantidad, $idCliente, $idEmpleado) {
    $conn = conectar();

    // Obtener el ID del producto
    $idProducto = obtenerIdProducto($nombreProducto);

    // Verificar si el producto existe
    if ($idProducto === null) {
        echo "El producto '$nombreProducto' no existe. Introduce un nombre de producto válido.";
        return;
    }

    // Verificar si el cliente existe antes de realizar el pedido
    if (!clienteExiste($idCliente)) {
        echo "El ID de cliente no existe. Introduce un ID válido.";
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
    $nombreProducto = $_POST["id_producto"]; // Asegúrate de tener este campo en tu formulario
    $cantidad = $_POST["cantidad_producto1"];
    $idCliente = $_POST["id_cliente"];
    $idEmpleado = $_POST["id_empleado"];

    realizarPedido($idPedido, $nombreProducto, $cantidad, $idCliente, $idEmpleado);
}
?>
