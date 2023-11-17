<?php
// Incluir la clase de conexión
include('conexion.php');

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comprar_producto"])) {
    // Obtener el valor de h2 (Nombre del Producto)
    $nombreProducto = $_POST["comprar_producto"];

    // Obtener la conexión
    $conexion = conectar();

    // Consulta para obtener el id del producto
    $sql = "SELECT idProducto FROM productos WHERE NombreProd = '$nombreProducto'";
    $resultado = $conexion->query($sql);

    // Verificar si la consulta fue exitosa
    if ($resultado) {
        // Obtener el id del producto
        $fila = $resultado->fetch_assoc();
        $idProducto = $fila["idProducto"];

        // Obtener otros valores del formulario
        $cantidad = $_POST["cantidad_producto1"];
        $total = 5.99 * (float) $cantidad;
        $idCliente = 1; // Reemplaza con el id del cliente real
        $idEmpleado = 1; // Reemplaza con el id del empleado real
        $idPedido = $_POST["id_pedido"]; // Nuevo campo agregado al formulario

        // Realizar la inserción en la tabla de pedidos
        $sqlInsert = "INSERT INTO pedidos (idPedido, idProducto, Cantidad, Total, idCliente, idEmpleado) 
                      VALUES ('$idPedido', $idProducto, $cantidad, '$total', $idCliente, $idEmpleado)";

        // Manejar errores de consulta
        if ($conexion->query($sqlInsert) === TRUE) {
            echo "Pedido realizado con éxito";
        } else {
            echo "Error al realizar el pedido: " . $conexion->error;
        }

        // Cerrar la conexión
        $conexion->close();
    } else {
        echo "Error al obtener el id del producto: " . $conexion->error;
    }
} else {
    echo "No se ha enviado el formulario correctamente.";
}
?>
