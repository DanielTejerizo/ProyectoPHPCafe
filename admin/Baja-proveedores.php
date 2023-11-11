<?php
include('../conexion.php'); // Asegúrate de ajustar la ruta correctamente

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el valor del ID del formulario
    $id = $_POST["id"];

    // Conectar a la base de datos
    $conexion = conectar();
    // Preparar la consulta de eliminación
    $secuencia = $conexion->prepare("DELETE FROM proveedores WHERE idProveedor = ?");
    $secuencia->bind_param("i", $id);

    // Ejecutar la consulta
    if ($secuencia->execute()) {
        echo "<script>mostrarAlerta();</script>";
        echo "Proveedor eliminado exitosamente";
    } else {
        echo "Error al eliminar el proveedor: " . $secuencia->error;
    }

    // Cerrar la conexión
    $conexion->close();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Eliminación de Proveedores</title>
    <link rel="stylesheet" href="../css/Alta.css">
    <script>
        function mostrarAlerta() {
            alert("Proveedor eliminado correctamente");
        }
    </script>
</head>

<body>
    <h1>Eliminación de Proveedores</h1>

    <!-- Formulario para eliminar un proveedor por su ID -->
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="id">ID del Proveedor a eliminar:</label>
        <input type="text" name="id" id="id" size="10" required><br><br>
        <button type="submit">Eliminar Proveedor</button>
    </form>

    <div>
        <?php
        // Conectar a la base de datos
        $conexion = conectar();

        // Consultar la tabla de proveedores
        $consulta_proveedores = $conexion->query("SELECT idProveedor, NombreProv, Direccion, Telefono, PersonaContacto FROM proveedores");

        // Verificar si hay resultados
        if ($consulta_proveedores->num_rows > 0) {
            echo "<h2>Lista de Proveedores</h2>";
            echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Persona de Contacto</th>
                </tr>";

            while ($fila = $consulta_proveedores->fetch_assoc()) {
                echo "<tr>
                    <td>{$fila['idProveedor']}</td>
                    <td>{$fila['NombreProv']}</td>
                    <td>{$fila['Direccion']}</td>
                    <td>{$fila['Telefono']}</td>
                    <td>{$fila['PersonaContacto']}</td>
                </tr>";
            }

            echo "</table>";
        } else {
            echo "No hay proveedores registrados.";
        }

        // Cerrar la conexión
        $conexion->close();
        ?>
    </div>
</body>

</html>
