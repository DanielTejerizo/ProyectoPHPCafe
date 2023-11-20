<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
    <link rel="stylesheet" href="../css/Productos.css">
</head>

<body>
    <div class="catalogo-container">
        <h1>Catálogo de Productos</h1>

        <?php
        include("../conexion.php");

        $conexion = conectar();

        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        // Consulta para obtener productos disponibles
        $sql = "SELECT idProducto, NombreProd, Precio FROM productos";
        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='producto'>";
                echo "<h3>" . $row['NombreProd'] . "</h3>";
                echo "<p>Precio: $" . $row['Precio'] . "</p>";
                echo "<form method='post' action='Pedido.php'>";
                echo "<input type='hidden' name='idProducto' value='" . $row['idProducto'] . "'>";
                echo "<label for='cantidad'>Cantidad:</label>";
                echo "<input type='number' name='cantidad' value='1' min='1' required>";
                echo "<button type='submit' name='comprar'>Comprar</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No hay productos disponibles.</p>";
        }

        $conexion->close();
        ?>
    </div>
</body>

</html>
