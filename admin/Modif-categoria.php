<?php
include('../conexion.php'); // Asegúrate de ajustar la ruta correctamente

// Verificar si se ha enviado el formulario y si la clave "idCategoria" está definida
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idCategoria"])) {
    // Obtener el valor del ID de la categoría del formulario
    $idCategoria = $_POST["idCategoria"];

    // Conectar a la base de datos
    $conexion = conectar();

    // Verificar si la categoría existe antes de modificarla
    $consulta_existencia = $conexion->prepare("SELECT idCategoria FROM Categoria WHERE idCategoria = ?");
    $consulta_existencia->bind_param("i", $idCategoria);
    $consulta_existencia->execute();
    $consulta_existencia->store_result();

    if ($consulta_existencia->num_rows > 0) {
        // Construir la consulta de modificación dinámicamente
        $consulta_modificacion = "UPDATE Categoria SET ";
        $valores = [];

        // Nombre de la categoría
        $nombreCat = isset($_POST["nombreCat"]) ? $_POST["nombreCat"] : null;
        if ($nombreCat !== null) {
            $consulta_modificacion .= "NombreCat = ? ";
            $valores[] = $nombreCat;
        }

        // Eliminar la coma final si hay campos para modificar
        if (!empty($valores)) {
            $consulta_modificacion .= "WHERE idCategoria = ?";
            $valores[] = $idCategoria;

            // Preparar la consulta de modificación
            $secuencia = $conexion->prepare($consulta_modificacion);
            $secuencia->bind_param(str_repeat("s", count($valores)), ...$valores);

            // Ejecutar la consulta
            if ($secuencia->execute()) {
                echo "<script>mostrarAlerta();</script>";
                echo "Categoría modificada exitosamente";
            } else {
                echo "Error al modificar la categoría: " . $secuencia->error;
            }
        } else {
            echo "No se proporcionaron campos para modificar.";
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
    <title>Modificación de Categorías</title>
    <link rel="stylesheet" href="../css/Alta.css">
    <script>
        function confirmarModificacion() {
            return confirm("¿Estás seguro de que deseas modificar esta categoría?");
        }

        function mostrarAlerta() {
            alert("Categoría modificada correctamente");
        }
    </script>
</head>

<body>
    <h1>Modificación de Categorías</h1>

    <!-- Formulario para modificar una categoría por su ID -->
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return confirmarModificacion();">
        <label for="idCategoria">ID de la Categoría a modificar:</label>
        <input type="text" name="idCategoria" id="idCategoria" size="10" required><br><br>
        <label for="nombreCat">Nuevo Nombre de la Categoría:</label>
        <input type="text" name="nombreCat" id="nombreCat"><br><br>
        <button type="submit">Modificar Categoría</button>
    </form>

    <div>
        <?php
        // Conectar a la base de datos
        $conexion = conectar();

        // Consultar la tabla de categorías ordenadas por ID
        $consulta_categorias = $conexion->query("SELECT idCategoria, NombreCat FROM Categoria ORDER BY idCategoria");

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

    <form action="Alta-categoria.php" method="post">
        <button type="submit">Modificar</button>
    </form>

</body>

</html>
