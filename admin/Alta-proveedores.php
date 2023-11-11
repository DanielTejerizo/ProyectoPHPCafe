<?php
include('../conexion.php'); // Asegúrate de ajustar la ruta correctamente

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $persona = $_POST["persona"];
    echo $id;
    // Conectar a la base de datos
    $conexion = conectar();

    // Preparar la consulta de inserción
    $secuencia = $conexion->prepare("INSERT INTO proveedores (idProveedor, NombreProv, Direccion, Telefono, PersonaContacto) VALUES (?, ?, ?, ?, ?)");
    $secuencia->bind_param("issss", $id, $nombre, $direccion, $telefono, $persona);

    // Ejecutar la consulta
    if ($secuencia->execute()) {
        echo "<script>mostrarAlerta();</script>";
        echo "Proveedor dado de alta exitosamente";
    } else {
        echo "Error al dar de alta el proveedor: " . $secuencia->error;
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
            alert("Proveedor dado de alta correctamente");
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
        <label for="direccion">Direccion:</label>
        <input type="text" name="direccion" id="direccion" step="0.01" required><br><br>
        <label for="telefono">Telefono:</label>
        <input type="text" name="telefono" id="telefono" required><br><br>
        <label for="persona">Persona:</label>
        <input type="text" name="persona" id="persona" required><br><br>
        <button type="submit">Confirmar</button>
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

    <form action="Baja-proveedores.php" method="post">
        <button type="submit">Borrar</button>
    </form>

    <form action="Modif-proveedores.php" method="post">
        <button type="submit">Modificar</button>
    </form>
</body>

</html>