<?php
include('../conexion.php'); // Asegúrate de ajustar la ruta correctamente

// Verificar si se ha enviado el formulario y si la clave "id" está definida
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    // Obtener el valor del ID del formulario
    $id = $_POST["id"];

    // Conectar a la base de datos
    $conexion = conectar();

    // Verificar si el proveedor existe antes de modificarlo
    $consulta_existencia = $conexion->prepare("SELECT idProveedor FROM proveedores WHERE idProveedor = ?");
    $consulta_existencia->bind_param("i", $id);
    $consulta_existencia->execute();
    $consulta_existencia->store_result();

    if ($consulta_existencia->num_rows > 0) {
        // Obtener los valores del formulario
        $nombre = $_POST["nombre"];
        $direccion = $_POST["direccion"];
        $telefono = $_POST["telefono"];
        $contacto = $_POST["contacto"];

        // Preparar la consulta de modificación
        $secuencia = $conexion->prepare("UPDATE proveedores SET NombreProv = ?, Direccion = ?, Telefono = ?, PersonaContacto = ? WHERE idProveedor = ?");
        $secuencia->bind_param("ssssi", $nombre, $direccion, $telefono, $contacto, $id);

        // Ejecutar la consulta
        if ($secuencia->execute()) {
            echo "<script>mostrarAlerta();</script>";
            echo "Proveedor modificado exitosamente";
        } else {
            echo "Error al modificar el proveedor: " . $secuencia->error;
        }
    } else {
        echo "No existe un proveedor con ese ID.";
    }

    // Cerrar la conexión
    $conexion->close();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Modificación de Proveedores</title>
    <link rel="stylesheet" href="../css/Alta.css">
    <script>
        function confirmarModificacion() {
            return confirm("¿Estás seguro de que deseas modificar este proveedor?");
        }

        function mostrarAlerta() {
            alert("Proveedor modificado correctamente");
        }
    </script>
</head>

<body>
    <h1>Modificación de Proveedores</h1>

    <!-- Formulario para modificar un proveedor por su ID -->
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return confirmarModificacion();">
        <label for="id">ID del Proveedor a modificar:</label>
        <input type="text" name="id" id="id" size="10" required><br><br>
        <label for="nombre">Nuevo Nombre:</label>
        <input type="text" name="nombre" id="nombre" required><br><br>
        <label for="direccion">Nueva Dirección:</label>
        <input type="text" name="direccion" id="direccion" required><br><br>
        <label for="telefono">Nuevo Teléfono:</label>
        <input type="text" name="telefono" id="telefono" required><br><br>
        <label for="contacto">Nueva Persona de Contacto:</label>
        <input type="text" name="contacto" id="contacto" required><br><br>
        <button type="submit">Modificar Proveedor</button>
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
