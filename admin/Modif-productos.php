<?php
include('../conexion.php');


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idProducto"])) {

    $idProducto = $_POST["idProducto"];
    $nuevoNombreProd = $_POST["nuevoNombreProd"];
    $nuevoPrecio = $_POST["nuevoPrecio"];
    $nuevoStock = $_POST["nuevoStock"];
    $nuevaCategoria = $_POST["nuevaCategoria"];
    $nuevoProveedor = $_POST["nuevoProveedor"];


    $conexion = conectar();


    $consulta = $conexion->prepare("UPDATE Productos SET NombreProd = ?, Precio = ?, Stock = ?, IdCategoria = ?, idProveedor = ? WHERE idProducto = ?");


    $consulta->bind_param('sssssi', $nuevoNombreProd, $nuevoPrecio, $nuevoStock, $nuevaCategoria, $nuevoProveedor, $idProducto);


    if ($consulta->execute()) {
        echo "<script>mostrarAlertaModificacion();</script>";
        echo "Producto modificado exitosamente";
    } else {
        echo "Error al modificar el producto: " . $consulta->error;
    }


    $conexion->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Modificación de Productos</title>
    <link rel="stylesheet" href="../css/Alta.css">
    <script>
        function confirmarModificacion() {
            return confirm("¿Estás seguro de que deseas modificar este producto?");
        }

        function mostrarAlertaBaja() {
            alert("Producto dado de baja correctamente");
        }
    </script>
</head>

<body>
    <h1>Modificación de Productos</h1>


    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return confirmarModificacion()">
        <label for="idProducto">ID del Producto a modificar:</label>
        <input type="text" name="idProducto" id="idProducto" required><br><br>
        <label for="nuevoNombreProd">Nuevo Nombre del Producto:</label>
        <input type="text" name="nuevoNombreProd" id="nuevoNombreProd" required><br><br>
        <label for="nuevoPrecio">Nuevo Precio:</label>
        <input type="text" name="nuevoPrecio" id="nuevoPrecio" required><br><br>
        <label for="nuevoStock">Nuevo Stock:</label>
        <input type="text" name="nuevoStock" id="nuevoStock" required><br><br>
        <label for="nuevaCategoria">Nueva Categoría:</label>
        <select name="nuevaCategoria" id="nuevaCategoria">
            <?php

            $conexion = conectar();


            $consulta_categorias = $conexion->query("SELECT idCategoria, NombreCat FROM Categoria");


            if ($consulta_categorias->num_rows > 0) {

                while ($fila = $consulta_categorias->fetch_assoc()) {
                    echo "<option value='{$fila['idCategoria']}'>{$fila['NombreCat']}</option>";
                }
            } else {
                echo "<option value=''>No hay categorías disponibles</option>";
            }


            $conexion->close();
            ?>
        </select><br><br>
        <label for="nuevoProveedor">Nuevo Proveedor:</label>
        <select name="nuevoProveedor" id="nuevoProveedor">
            <?php

            $conexion = conectar();


            $consulta_proveedores = $conexion->query("SELECT idProveedor, NombreProv FROM Proveedores");


            if ($consulta_proveedores->num_rows > 0) {

                while ($fila = $consulta_proveedores->fetch_assoc()) {
                    echo "<option value='{$fila['idProveedor']}'>{$fila['NombreProv']}</option>";
                }
            } else {
                echo "<option value=''>No hay proveedores disponibles</option>";
            }


            $conexion->close();
            ?>
        </select><br><br>
        <button type="submit">Confirmar Modificación</button>
    </form>


    <div>
        <?php

        $conexion = conectar();


        $consulta_productos = $conexion->query("SELECT idProducto, NombreProd, Precio, Stock, IdCategoria, idProveedor FROM Productos ORDER BY idProducto");


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


        $conexion->close();
        ?>
    </div>


    <form action="Alta-productos.php" method="post">
        <button type="submit">Alta</button>
    </form>

    <form action="Baja-productos.php" method="post">
        <button type="submit">Baja</button>
    </form>
</body>

</html>
