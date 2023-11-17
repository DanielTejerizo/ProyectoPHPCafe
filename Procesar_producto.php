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

        // Ahora, puedes insertar en la tabla de pedidos
        // (Asegúrate de tener el idCliente e idEmpleado adecuados)
        $cantidad = $_POST["cantidad_producto1"];
        $total = 5.99 * $cantidad; // Precio del producto multiplicado por la cantidad
        $idCliente = 1; // Reemplaza con el id del cliente real
        $idEmpleado = 1; // Reemplaza con el id del empleado real

        // Realizar la inserción en la tabla de pedidos
        $sqlInsert = "INSERT INTO pedidos (idPedido, idProducto, Cantidad, Total, idCliente, idEmpleado) 
              VALUES (NULL, $idProducto, $cantidad, $total, $idCliente, $idEmpleado)";

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
