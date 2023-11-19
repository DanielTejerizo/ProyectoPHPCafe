<?php
include 'conexion.php'; // Asegúrate de incluir la ruta correcta

// Manejar el envío del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = conectar();

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener el ID del producto seleccionado
    $idProductoSeleccionado = $_POST['producto'];

    // Puedes realizar alguna acción con el ID del producto seleccionado, como agregarlo a una tabla de pedidos

    echo "Pedido realizado para el producto con ID: " . $idProductoSeleccionado;

    // Cerrar la conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Pedido</title>
</head>
<body>

<h2>Seleccionar Producto para Pedido</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php
    $conn = conectar();

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta para obtener productos disponibles
    $sql = "SELECT idProducto, NombreProd FROM vista_productos_disponibles";
    $result = $conn->query($sql);

    // Crear un menú desplegable con los productos
    echo "Selecciona un producto: ";
    echo "<select name='producto'>";
    while($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['idProducto'] . "'>" . $row['NombreProd'] . "</option>";
    }
    echo "</select>";

    // Cerrar la conexión
    $conn->close();
    ?>

    <br><br>
    <input type="submit" name="submit" value="Realizar Pedido">
</form>

</body>
</html>
