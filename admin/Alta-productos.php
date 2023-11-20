<?php
include('../conexion.php'); 


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {

    $id = $_POST["id"];
    $nombreProd = $_POST["nombreProd"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];
    $idCategoria = $_POST["idCategoria"];
    $idProveedor = $_POST["idProveedor"];


    $conexion = conectar();


    $secuencia = $conexion->prepare("INSERT INTO Productos (idProducto, NombreProd, Precio, Stock, IdCategoria, idProveedor) VALUES (?, ?, ?, ?, ?, ?)");


    $secuencia->bind_param('ssssid', $id, $nombreProd, $precio, $stock, $idCategoria, $idProveedor);


    if ($secuencia->execute()) {
        echo "<script>mostrarAlerta();</script>";
        echo "Producto dado de alta exitosamente";
    } else {
        echo "Error al dar de alta el producto: " . $secuencia->error;
    }


    $conexion->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Alta de Productos</title>
    <link rel="stylesheet" href="../css/Alta.css">
    <script>
        function mostrarAlerta() {
            alert("Producto dado de alta correctamente");
        }
    </script>
</head>

<body>
    <h1>Alta de Productos</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="id">idProducto:</label>
        <input type="text" name="id" id="id" required><br><br>
        <label for="nombreProd">Nombre del Producto:</label>
        <input type="text" name="nombreProd" id="nombreProd" required><br><br>
        <label for="precio">Precio:</label>
        <input type="text" name="precio" id="precio" required><br><br>
        <label for="stock">Stock:</label>
        <input type="text" name="stock" id="stock" required><br><br>
        <label for="idCategoria">Categoría:</label>
        <select name="idCategoria" id="idCategoria">
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
        <label for="idProveedor">Proveedor:</label>
        <select name="idProveedor" id="idProveedor">
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
        <button type="submit">Confirmar</button>
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


    <form action="Modif-productos.php" method="post">
        <button type="submit">Modificación</button>
    </form>

    <form action="Baja-productos.php" method="post">
        <button type="submit">Baja</button>
    </form>
</body>
</html>