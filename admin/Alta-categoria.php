<?php
include('../conexion.php'); // Asegúrate de ajustar la ruta correctamente

// Verificar si se ha enviado el formulario para categorías
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idCategoria"])) {
    // Obtener los valores del formulario de categorías
    $idCategoria = $_POST["idCategoria"];
    $nombreCat = $_POST["nombreCat"];

    // Conectar a la base de datos
    $conexion = conectar();

    // Preparar la consulta de inserción para categorías
    $secuencia_cat = $conexion->prepare("INSERT INTO Categoria (idCategoria, NombreCat) VALUES (?, ?)");
    $secuencia_cat->bind_param("is", $idCategoria, $nombreCat);

    // Ejecutar la consulta para categorías
    if ($secuencia_cat->execute()) {
        echo "<script>mostrarAlerta();</script>";
        echo "Categoría dada de alta exitosamente";
    } else {
        echo "Error al dar de alta la categoría: " . $secuencia_cat->error;
    }

    // Cerrar la conexión
    $conexion->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Alta de Categorías</title>
    <link rel="stylesheet" href="../Css/Alta.css">
    <script>
        function mostrarAlerta() {
            alert("Categoría dada de alta exitosamente");
        }
    </script>
</head>

<body>
    <h1>Alta de Categorías</h1>

    <!-- Formulario para categorías -->
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="idCategoria">ID Categoría:</label>
        <input type="text" name="idCategoria" id="idCategoria" size="10" required><br><br>
        <label for="nombreCat">Nombre:</label>
        <input type="text" name="nombreCat" id="nombreCat" required><br><br>
        <button type="submit">Confirmar</button>
    </form>

    <div>
        <?php
        // Conectar a la base de datos
        $conexion = conectar();

        // Consultar la tabla de categorías
        $consulta_categorias = $conexion->query("SELECT idCategoria, NombreCat FROM Categoria");

        // Verificar si hay resultados
        if ($consulta_categorias->num_rows > 0) {
            echo "<h2>Lista de Categorías</h2>";
            echo "<table border='1'>
                <tr>
                    <th>ID Categoría</th>
                    <th>Nombre</th>
                </tr>";

            while ($fila = $consulta_categorias->fetch_assoc()) {
                echo "<tr>
                    <td>{$fila['idCategoria']}</td>
                    <td>{$fila['NombreCat']}</td>
                </tr>";
            }

            echo "</table>";
        } else {
            echo "No hay categorías registradas.";
        }

        // Cerrar la conexión
        $conexion->close();
        ?>
    </div>

    <form action="Baja-categoria.php" method="post">
        <button type="submit">Borrar</button>
    </form>

    <form action="Modif-categoria.php" method="post">
        <button type="submit">Modificar</button>
    </form>

</body>

</html>
