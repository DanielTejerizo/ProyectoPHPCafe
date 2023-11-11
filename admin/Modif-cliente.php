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
        $consulta_modificacion = "UPDATE Clientes SET ";
        $valores = [];

        $nombreCli = isset($_POST["nombreCli"]) ? $_POST["nombreCli"] : null;
        if ($nombreCli !== null) {
            $consulta_modificacion .= "NombreCli = ?, ";
            $valores[] = $nombreCli;
        }

        $direccion = isset($_POST["direccion"]) ? $_POST["direccion"] : null;
        if ($direccion !== null) {
            $consulta_modificacion .= "Direccion = ?, ";
            $valores[] = $direccion;
        }

        $telefono = isset($_POST["telefono"]) ? $_POST["telefono"] : null;
        if ($telefono !== null) {
            $consulta_modificacion .= "Telefono = ?, ";
            $valores[] = $telefono;
        }

        if (!empty($valores)) {
            $consulta_modificacion = rtrim($consulta_modificacion, ", ");
            $consulta_modificacion .= " WHERE idCliente = ?";
            $valores[] = $idCliente;

            $secuencia = $conexion->prepare($consulta_modificacion);
            $secuencia->bind_param(str_repeat("s", count($valores)), ...$valores);

            if ($secuencia->execute()) {
                echo "<script>mostrarAlerta();</script>";
                echo "Cliente modificado exitosamente";
            } else {
                echo "Error al modificar el cliente: " . $secuencia->error;
            }
        } else {
            echo "No se proporcionaron campos para modificar.";
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
    <title>Modificación de Clientes</title>
    <link rel="stylesheet" href="../css/Alta.css">
    <script>
        function confirmarModificacion() {
            return confirm("¿Estás seguro de que deseas modificar este cliente?");
        }

        function mostrarAlerta() {
            alert("Cliente modificado correctamente");
        }
    </script>
</head>

<body>
    <h1>Modificación de Clientes</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return confirmarModificacion();">
        <label for="idCliente">ID del Cliente a modificar:</label>
        <input type="text" name="idCliente" id="idCliente" size="10" required><br><br>
        <label for="nombreCli">Nuevo Nombre:</label>
        <input type="text" name="nombreCli" id="nombreCli"><br><br>
        <label for="direccion">Nueva Dirección:</label>
        <input type="text" name="direccion" id="direccion"><br><br>
        <label for="telefono">Nuevo Teléfono:</label>
        <input type="text" name="telefono" id="telefono"><br><br>
        <button type="submit">Modificar Cliente</button>
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

    <form action="Baja-cliente.php" method="post">
        <button type="submit">Baja</button>
    </form>
</body>

</html>
