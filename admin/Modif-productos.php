<?php
include('../conexion.php');

// Verificar si se ha enviado el formulario y si la clave "idProducto" está definida
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idProducto"])) {
    // Obtener el valor del ID del formulario
    $idProducto = $_POST["idProducto"];

    // Conectar a la base de datos
    $conexion = conectar();

    // Verificar si el producto existe antes de modificarlo
    $consulta_existencia = $conexion->prepare("SELECT idProducto, NombreProd, Precio, Stock, IdCategoria, idProveedor FROM Productos WHERE idProducto = ?");
    $consulta_existencia->bind_param("i", $idProducto);
    $consulta_existencia->execute();
    $consulta_existencia->store_result();

    if ($consulta_existencia->num_rows > 0) {
        // Obtener los valores existentes del producto
        $consulta_existencia->bind_result($idProducto, $nombreProd, $precio, $stock, $idCategoria, $idProveedor);
        $consulta_existencia->fetch();

        // Construir la consulta de modificación dinámicamente
        $consulta_modificacion = "UPDATE Productos SET ";
        $valores = [];

        // Nombre del Producto
        $nombreProdNuevo = isset($_POST["nombreProd"]) ? $_POST["nombreProd"] : $nombreProd;
        $consulta_modificacion .= "NombreProd = ?, ";
        $valores[] = $nombreProdNuevo;

        // Precio
        $precioNuevo = isset($_POST["precio"]) ? $_POST["precio"] : $precio;
        $consulta_modificacion .= "Precio = ?, ";
        $valores[] = $precioNuevo;

        // Stock
        $stockNuevo = isset($_POST["stock"]) ? $_POST["stock"] : $stock;
        $consulta_modificacion .= "Stock = ?, ";
        $valores[] = $stockNuevo;

        // Categoría
        $idCategoriaNuevo = isset($_POST["idCategoria"]) ? $_POST["idCategoria"] : $idCategoria;
        $consulta_modificacion .= "IdCategoria = ?, ";
        $valores[] = $idCategoriaNuevo;

        // Proveedor
        $idProveedorNuevo = isset($_POST["idProveedor"]) ? $_POST["idProveedor"] : $idProveedor;
        $consulta_modificacion .= "idProveedor = ?, ";
        $valores[] = $idProveedorNuevo;

        // Eliminar la coma final si hay campos para modificar
        if (!empty($valores)) {
            $consulta_modificacion = rtrim($consulta_modificacion, ", ");
            $consulta_modificacion .= " WHERE idProducto = ?";
            $valores[] = $idProducto;

            // Preparar la consulta de modificación
            $secuencia = $conexion->prepare($consulta_modificacion);
            $secuencia->bind_param(str_repeat("s", count($valores)), ...$valores);

            // Ejecutar la consulta
            if ($secuencia->execute()) {
                echo "<script>mostrarAlerta();</script>";
                echo "Producto modificado exitosamente";
            } else {
                echo "Error al modificar el producto: " . $secuencia->error;
            }
        } else {
            echo "No se proporcionaron campos para modificar.";
        }
    } else {
        echo "No existe un producto con ese ID.";
    }

    // Cerrar la conexión
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

        function mostrarAlerta() {
            alert("Producto modificado correctamente");
        }
    </script>
</head>

<body>
    <h1>Modificación de Productos</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return confirmarModificacion();">
        <label for="idProducto">ID del Producto a modificar:</label>
        <input type="text" name="idProducto" id="idProducto" size="10" required><br><br>
        <label for="nombreProd">Nuevo Nombre del Producto:</label>
        <input type="text" name="nombreProd" id="nombreProd"><br><br>
        <label for="precio">Nuevo Precio:</label>
        <input type="text" name="precio" id="precio"><br><br>
        <label for="stock">Nuevo Stock:</label>
        <input type="text" name="stock" id="stock"><br><br>
        <label for="idCategoria">Nueva Categoría:</label>
        <select name="idCategoria" id="idCategoria">
            <!-- Aquí debes cargar dinámicamente las opciones desde la base de datos -->
            <!-- Puedes usar una consulta SQL para obtener las categorías y luego un bucle para crear las opciones -->
            <!-- Ejemplo: SELECT idCategoria, NombreCat FROM Categoria -->
            <!-- Luego, en un bucle while o foreach, recorres los resultados y creas las opciones -->
            <!-- Ejemplo de bucle: while ($fila = $resultado->fetch_assoc()) { echo "<option value='{$fila['idCategoria']}'>{$fila['NombreCat']}</option>"; } -->
        </select><br><br>
        <label for="idProveedor">Nuevo Proveedor:</label>
        <select name="idProveedor" id="idProveedor">
            <!-- Similar al desplegable de categorías, debes cargar las opciones desde la base de datos -->
            <!-- Ejemplo: SELECT idProveedor, NombreProv FROM Proveedores -->
        </select><br><br>
        <button type="submit">Modificar Producto</button>
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

    <!-- Formularios para otras operaciones (Alta, Baja, etc.) -->
    <form action="Alta-productos.php" method="post">
        <button type="submit">Alta</button>
    </form>

    <form action="Baja-productos.php" method="post">
        <button type="submit">Baja</button>
    </form>
</body>

</html>
