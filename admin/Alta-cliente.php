<?php
include('../conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idCliente"])) {
    $idCliente = $_POST["idCliente"];
    $nombreCli = $_POST["nombreCli"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];

    $conexion = conectar();

    $secuencia = $conexion->prepare("INSERT INTO Clientes (idCliente, NombreCli, Direccion, Telefono) VALUES (?, ?, ?, ?)");
    $secuencia->bind_param("issi", $idCliente, $nombreCli, $direccion, $telefono);

    if ($secuencia->execute()) {
        echo "<script>mostrarAlerta();</script>";
        echo "Cliente dado de alta exitosamente";
    } else {
        echo "Error al dar de alta al cliente: " . $secuencia->error;
    }

    $conexion->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Alta de Clientes</title>
    <link rel="stylesheet" href="../Css/Alta.css">
    <script>
        function mostrarAlerta() {
            alert("Cliente dado de alta correctamente");
        }
    </script>
</head>

<body>
    <h1>Alta de Clientes</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="idCliente">ID:</label>
        <input type="text" name="idCliente" id="idCliente" size="10" required><br><br>
        <label for="nombreCli">Nombre:</label>
        <input type="text" name="nombreCli" id="nombreCli" required><br><br>
        <label for="direccion">Direccion:</label>
        <input type="text" name="direccion" id="direccion" required><br><br>
        <label for="telefono">Telefono:</label>
        <input type="text" name="telefono" id="telefono" required><br><br>
        <button type="submit">Confirmar</button>
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

    <form action="Baja-cliente.php" method="post">
        <button type="submit">Borrar</button>
    </form>

    <form action="Modif-cliente.php" method="post">
        <button type="submit">Modificar</button>
    </form>
</body>

</html>
