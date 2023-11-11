<?php
include('../conexion.php'); // Asegúrate de ajustar la ruta correctamente

// Verificar si se ha enviado el formulario para categorías
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idCategoria"])) {
    // Obtener los valores del formulario de categorías
    $idCategoria = $_POST["idCategoria"];

    // Conectar a la base de datos
    $conexion = conectar();

    // Verificar si la categoría existe antes de eliminarla
    $consulta_existencia = $conexion->prepare("SELECT idCategoria FROM Categoria WHERE idCategoria = ?");
    $consulta_existencia->bind_param("i", $idCategoria);
    $consulta_existencia->execute();
    $consulta_existencia->store_result();

    if ($consulta_existencia->num_rows > 0) {
        // Preparar la consulta de eliminación
        $secuencia = $conexion->prepare("DELETE FROM Categoria WHERE idCategoria = ?");
        $secuencia->bind_param("i", $idCategoria);

        // Ejecutar la consulta
        if ($secuencia->execute()) {
            echo "<script>mostrarAlerta();</script>";
            echo "Categoría eliminada exitosamente";
        } else {
            echo "Error al eliminar la categoría: " . $secuencia->error;
        }
    } else {
        echo "No existe una categoría con ese ID.";
    }

    // Cerrar la conexión
    $conexion->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Eliminación de Categorías</title>
    <link rel="stylesheet" href="../css/Alta.css">
    <script>
        function confirmarEliminacion() {
            return confirm("¿Estás seguro de que deseas eliminar esta categoría?");
        }

        function mostrarAlerta() {
            alert("Categoría eliminada correctamente");
        }
    </script>
</head>

<body>
    <h1>Eliminación de Categorías</h1>

    <!-- Formulario para eliminar una categoría por su ID -->
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return confirmarEliminacion();">
        <label for="idCategoria">ID de la Categoría a eliminar:</label>
        <input type="text" name="idCategoria" id="idCategoria" size="10" required><br><br>
        <button type="submit">Eliminar Categoría</button>
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

    <form action="Alta-categoria.php" method="post">
        <button type="submit">Borrar</button>
    </form>

    <form action="Modif-categoria.php" method="post">
        <button type="submit">Modificar</button>
    </form>

</body>

</html>
