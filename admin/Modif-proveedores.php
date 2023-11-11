<?php
include('../conexion.php'); // Asegúrate de ajustar la ruta correctamente

// Verificar si se ha enviado el formulario y si la clave "id" está definida
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
        // Construir la consulta de modificación dinámicamente
        $consulta_modificacion = "UPDATE proveedores SET ";
        $valores = [];

        // Nombre
        $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : null;
        if ($nombre !== null) {
            $consulta_modificacion .= "NombreProv = ?, ";
            $valores[] = $nombre;
        }

        // Dirección
        $direccion = isset($_POST["direccion"]) ? $_POST["direccion"] : null;
        if ($direccion !== null) {
            $consulta_modificacion .= "Direccion = ?, ";
            $valores[] = $direccion;
        }

        // Teléfono
        $telefono = isset($_POST["telefono"]) ? $_POST["telefono"] : null;
        if ($telefono !== null) {
            $consulta_modificacion .= "Telefono = ?, ";
            $valores[] = $telefono;
        }

        // Persona de contacto
        $contacto = isset($_POST["contacto"]) ? $_POST["contacto"] : null;
        if ($contacto !== null) {
            $consulta_modificacion .= "PersonaContacto = ?, ";
            $valores[] = $contacto;
        }

        // Eliminar la coma final si hay campos para modificar
        if (!empty($valores)) {
            $consulta_modificacion = rtrim($consulta_modificacion, ", ");
            $consulta_modificacion .= " WHERE idProveedor = ?";
            $valores[] = $id;

            // Preparar la consulta de modificación
            $secuencia = $conexion->prepare($consulta_modificacion);
            $secuencia->bind_param(str_repeat("s", count($valores)), ...$valores);

            // Ejecutar la consulta
            if ($secuencia->execute()) {
                echo "<script>mostrarAlerta();</script>";
                echo "Proveedor modificado exitosamente";
            } else {
                echo "Error al modificar el proveedor: " . $secuencia->error;
            }
        } else {
            echo "No se proporcionaron campos para modificar.";
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


<form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return confirmarModificacion();">
    <label for="id">ID del Proveedor a modificar:</label>
    <input type="text" name="id" id="id" size="10" required><br><br>
    <label for="nombre">Nuevo Nombre:</label>
    <input type="text" name="nombre" id="nombre"><br><br>
    <label for="direccion">Nueva Dirección:</label>
    <input type="text" name="direccion" id="direccion"><br><br>
    <label for="telefono">Nuevo Teléfono:</label>
    <input type="text" name="telefono" id="telefono"><br><br>
    <label for="contacto">Nueva Persona de Contacto:</label>
    <input type="text" name="contacto" id="contacto"><br><br>
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
