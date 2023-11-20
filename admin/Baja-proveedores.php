<?php
include('../conexion.php');


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {

    $id = $_POST["id"];


    $conexion = conectar();

  
    $consulta_existencia = $conexion->prepare("SELECT idProveedor FROM proveedores WHERE idProveedor = ?");
    $consulta_existencia->bind_param("i", $id);
    $consulta_existencia->execute();
    $consulta_existencia->store_result();

    if ($consulta_existencia->num_rows > 0) {

        $secuencia = $conexion->prepare("DELETE FROM proveedores WHERE idProveedor = ?");
        $secuencia->bind_param("i", $id);


        if ($secuencia->execute()) {
            echo "<script>mostrarAlerta();</script>";
            echo "Proveedor eliminado exitosamente";
        } else {
            echo "Error al eliminar el proveedor: " . $secuencia->error;
        }
    } else {
        echo "No existe un proveedor con ese ID.";
    }


    $conexion->close();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Eliminación de Proveedores</title>
    <link rel="stylesheet" href="../css/Alta.css">
    <script>
        function confirmarEliminacion() {
            return confirm("¿Estás seguro de que deseas eliminar este proveedor?");
        }

        function mostrarAlerta() {
            alert("Proveedor eliminado correctamente");
        }
    </script>
</head>

<body>
    <h1>Eliminación de Proveedores</h1>


    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return confirmarEliminacion();">
        <label for="id">ID del Proveedor a eliminar:</label>
        <input type="text" name="id" id="id" size="10" required><br><br>
        <button type="submit">Eliminar Proveedor</button>
    </form>

    <div>
        <?php

        $conexion = conectar();


        $consulta_proveedores = $conexion->query("SELECT idProveedor, NombreProv, Direccion, Telefono, PersonaContacto FROM proveedores");


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


        $conexion->close();
        ?>
    </div>

    <form action="Alta-proveedores.php" method="post">
        <button type="submit">Alta</button>
    </form>

    <form action="Modif-proveedores.php" method="post">
        <button type="submit">Modificar</button>
    </form>

</body>

</html>
