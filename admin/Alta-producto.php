<?php
include('../conexion.php'); // Asegúrate de ajustar la ruta correctamente

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];
    $categoria = $_POST["categoria"];
    $proveedor = $_POST["proveedor"];

    // Conectar a la base de datos
    $conexion = conectar();

    // Preparar la consulta de inserción
    $stmt = $conexion->prepare("INSERT INTO nombre_de_la_tabla (id, nombre, precio, stock, categoria, proveedor) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdiss", $id, $nombre, $precio, $stock, $categoria, $proveedor);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>mostrarAlerta();</script>";
        echo "Producto dado de alta exitosamente";
    } else {
        echo "Error al dar de alta el producto: " . $stmt->error;
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
            alert("Producto dado de alta");
        }
    </script>
</head>
<body>
    <h1>Alta de Productos</h1>
    
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <label for="id">ID:</label>
        <input type="text" name="id" id="id" size="10" required><br><br>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required><br><br>
        <label for="precio">Precio:</label>
        <input type="number" name="precio" id="precio" step="0.01" required><br><br>
        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" required><br><br>
        <label for="categoria">Categoría:</label>
        <input type="text" name="categoria" id="categoria" required><br><br>
        <label for="proveedor">Proveedor:</label>
        <input type="text" name="proveedor" id="proveedor" required><br><br>
        <button type="submit">Confirmar</button>
    </form>
</body>
</html>

