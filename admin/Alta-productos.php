<?php
include('../conexion.php'); // Asegúrate de ajustar la ruta correctamente

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $id = $_POST["id"];
    $nombreProd = $_POST["nombreProd"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];
    $idCategoria = $_POST["idCategoria"];
    $idProveedor = $_POST["idProveedor"];

    // Conectar a la base de datos
    $conexion = conectar();

    // Preparar la consulta de inserción
$secuencia = $conexion->prepare("INSERT INTO Productos (idProducto, NombreProd, Precio, Stock, IdCategoria, idProveedor) VALUES (?, ?, ?, ?, ?, ?)");

// Corregir la línea de bind_param
$secuencia->bind_param('ssssid', $id, $nombreProd, $precio, $stock, $idCategoria, $idProveedor);

// Ejecutar la consulta
if ($secuencia->execute()) {
    echo "<script>mostrarAlerta();</script>";
    echo "Producto dado de alta exitosamente";
} else {
    echo "Error al dar de alta el producto: " . $secuencia->error;
}


    // Cerrar la conexión
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
            // Conectar a la base de datos
            $conexion = conectar();

            // Consultar las categorías disponibles
            $consulta_categorias = $conexion->query("SELECT idCategoria, NombreCat FROM Categoria");

            // Verificar si hay resultados
            if ($consulta_categorias->num_rows > 0) {
                // Mostrar opciones del desplegable
                while ($fila = $consulta_categorias->fetch_assoc()) {
                    echo "<option value='{$fila['idCategoria']}'>{$fila['NombreCat']}</option>";
                }
            } else {
                echo "<option value=''>No hay categorías disponibles</option>";
            }

            // Cerrar la conexión
            $conexion->close();
            ?>
        </select><br><br>
        <label for="idProveedor">Proveedor:</label>
        <select name="idProveedor" id="idProveedor">
            <?php
            // Conectar a la base de datos
            $conexion = conectar();

            // Consultar los proveedores disponibles
            $consulta_proveedores = $conexion->query("SELECT idProveedor, NombreProv FROM Proveedores");

            // Verificar si hay resultados
            if ($consulta_proveedores->num_rows > 0) {
                // Mostrar opciones del desplegable
                while ($fila = $consulta_proveedores->fetch_assoc()) {
                    echo "<option value='{$fila['idProveedor']}'>{$fila['NombreProv']}</option>";
                }
            } else {
                echo "<option value=''>No hay proveedores disponibles</option>";
            }

            // Cerrar la conexión
            $conexion->close();
            ?>
        </select><br><br>
        <button type="submit">Confirmar</button>
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

    <!-- Formularios para otras operaciones (Modificación, Baja, etc.) -->
    <form action="Modif-productos.php" method="post">
        <button type="submit">Modificación</button>
    </form>

    <form action="Baja-productos.php" method="post">
        <button type="submit">Baja</button>
    </form>
</body>

</html>
