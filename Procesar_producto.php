<?php
include('conexion.php');

$conexion = conectar();

// Verifica la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

if (isset($_POST['comprar'])) {
    $idProducto = $_POST['comprar'];
    $cantidad = 1;
    $total = 5.99;
    $idCliente = 1;
    $idEmpleado = 1;

    // Utiliza sentencias preparadas para prevenir inyección de SQL
    $sql = "INSERT INTO pedidos (idProducto, Cantidad, Total, idCliente, idEmpleado) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    // Vincula los parámetros
    $stmt->bind_param("iiidi", $idProducto, $cantidad, $total, $idCliente, $idEmpleado);

    if ($stmt->execute()) {
        echo "¡Pedido realizado exitosamente!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cierra la sentencia preparada
    $stmt->close();
}

// Cierra la conexión
$conexion->close();
?>

