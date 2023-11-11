<?php
include('../conexion.php'); // Asegúrate de ajustar la ruta correctamente

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idProducto"])) {
    // Obtener el ID del producto a dar de baja
    $idProducto = $_POST["idProducto"];

    // Conectar a la base de datos
    $conexion = conectar();

    // Preparar la consulta de baja
    $secuencia = $conexion->prepare("DELETE FROM Productos WHERE idProducto = ?");

    // Corregir la línea de bind_param
    $secuencia->bind_param('s', $idProducto);

    // Ejecutar la consulta de baja
    if ($secuencia->execute()) {
        echo "<script>mostrarAlertaBaja();</script>";
        echo "Producto dado de baja exitosamente";
    } else {
        echo "Error al dar de baja el producto: " . $secuencia->error;
    }

    // Cerrar la conexión
    $conexion->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Baja de Productos</title>
    <link rel="stylesheet" href="../css/Alta.css">
    <script>
        function confirmarEliminacion() {
            return confirm("¿Estás seguro de que deseas eliminar este producto?");
        }

        function mostrarAlertaBaja() {
            alert("Producto dado de baja correctamente");
        }
    </script>
</head>

<body>
    <h1>Baja de Productos</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return confirmarEliminacion()">
        <label for="idProducto">ID del Producto a dar de baja:</label>
        <input type="text" name="idProducto" id="idProducto" required><br><br>
        <button type="submit">Confirmar Baja</button>
    </form>

    <div>
        <?php
        // Conectar a la base de datos
        $conexion = conectar();

        // Consultar la tabla de productos ordenados por ID
        $consulta_productos = $conexion->query("SELECT idProducto, NombreProd, Precio, Stock, IdCategoria, idProveedor FROM Productos ORDER BY idProducto");

        // Verificar si hay resultados
        if ($consulta_productos->num_rows > 0) {
            echo "<h2>Lista de Productos</h2>";
            echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Proveedor</th>
                </tr>";

            while ($fila = $consulta_productos->fetch_assoc()) {
                echo "<tr>
                    <td>{$fila['idProducto']}</td>
                    <td>{$fila['NombreProd']}</td>
                    <td>{$fila['Precio']}</td>
                    <td>{$fila['Stock']}</td>
                    <td>{$fila['IdCategoria']}</td>
                    <td>{$fila['idProveedor']}</td>
                </tr>";
            }

            echo "</table>";
        } else {
            echo "No hay productos registrados.";
        }

        // Cerrar la conexión
        $conexion->close();
        ?>
    </div>

    <!-- Formularios para otras operaciones (Modificación, Alta, etc.) -->
    <form action="Modif-productos.php" method="post">
        <button type="submit">Modificación</button>
    </form>

    <form action="Alta-productos.php" method="post">
        <button type="submit">Alta</button>
    </form>

</body>

</html>
