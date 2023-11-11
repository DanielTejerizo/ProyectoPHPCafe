<?php
include('../conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idCliente"])) {
    $idCliente = $_POST["idCliente"];

    $conexion = conectar();

    $consulta_existencia = $conexion->prepare("SELECT idCliente FROM Clientes WHERE idCliente = ?");
    $consulta_existencia->bind_param("i", $idCliente);
    $consulta_existencia->execute();
    $consulta_existencia->store_result();

    if ($consulta_existencia->num_rows > 0) {
        $secuencia = $conexion->prepare("DELETE FROM Clientes WHERE idCliente = ?");
        $secuencia->bind_param("i", $idCliente);

        if ($secuencia->execute()) {
            echo "<script>mostrarAlerta();</script>";
            echo "Cliente eliminado exitosamente";
        } else {
            echo "Error al eliminar el cliente: " . $secuencia->error;
        }
    } else {
        echo "No existe un cliente con ese ID.";
    }

    $conexion->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Eliminación de Clientes</title>
    <link rel="stylesheet" href="../css/Alta.css">
    <script>
        function confirmarEliminacion() {
            return confirm("¿Estás seguro de que deseas eliminar este cliente?");
        }

        function mostrarAlerta() {
            alert("Cliente eliminado correctamente");
        }
    </script>
</head>

<body>
    <h1>Eliminación de Clientes</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return confirmarEliminacion();">
        <label for="idCliente">ID del Cliente a eliminar:</label>
        <input type="text" name="idCliente" id="idCliente" size="10" required><br><br>
        <button type="submit">Eliminar Cliente</button>
    </form>

    <div>
        <?php
        $conexion = conectar();

        $consulta_clientes = $conexion->query("SELECT idCliente, NombreCli, Direccion, Telefono FROM Clientes");

        if ($consulta_clientes->num_rows > 0) {
            echo "<h2>Lista de Clientes</h2>";
            echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                </tr>";

            while ($fila = $consulta_clientes->fetch_assoc()) {
                echo "<tr>
                    <td>{$fila['idCliente']}</td>
                    <td>{$fila['NombreCli']}</td>
                    <td>{$fila['Direccion']}</td>
                    <td>{$fila['Telefono']}</td>
                </tr>";
            }

            echo "</table>";
        } else {
            echo "No hay clientes registrados.";
        }

        $conexion->close();
        ?>
    </div>

    <form action="Alta-cliente.php" method="post">
        <button type="submit">Alta</button>
    </form>

    <form action="Modif-cliente.php" method="post">
        <button type="submit">Modificar</button>
    </form>

</body>

</html>
