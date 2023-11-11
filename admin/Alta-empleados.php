<?php
include('../conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idEmpleado"])) {
    $idEmpleado = $_POST["idEmpleado"];
    $nombreEmp = $_POST["nombreEmp"];
    $edad = $_POST["edad"];
    $fechaContratacion = $_POST["fechaContratacion"];
    $numeroCuenta = $_POST["numeroCuenta"];

    $conexion = conectar();

    $secuencia = $conexion->prepare("INSERT INTO Empleados (idEmpleado, NombreEmp, Edad, FechaContratacion, NumeroCuenta) VALUES (?, ?, ?, ?, ?)");
    $secuencia->bind_param("issss", $idEmpleado, $nombreEmp, $edad, $fechaContratacion, $numeroCuenta);

    if ($secuencia->execute()) {
        echo "<script>mostrarAlerta();</script>";
        echo "Empleado dado de alta exitosamente";
    } else {
        echo "Error al dar de alta al empleado: " . $secuencia->error;
    }

    $conexion->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Alta de Empleados</title>
    <link rel="stylesheet" href="../Css/Alta.css">
    <script>
        function mostrarAlerta() {
            alert("Empleado dado de alta correctamente");
        }
    </script>
</head>

<body>
    <h1>Alta de Empleados</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="idEmpleado">ID:</label>
        <input type="text" name="idEmpleado" id="idEmpleado" size="10" required><br><br>
        <label for="nombreEmp">Nombre:</label>
        <input type="text" name="nombreEmp" id="nombreEmp" required><br><br>
        <label for="edad">Edad:</label>
        <input type="text" name="edad" id="edad" required><br><br>
        <label for="fechaContratacion">Fecha de Contratación:</label>
        <input type="date" name="fechaContratacion" id="fechaContratacion" required><br><br>
        <label for="numeroCuenta">Número de Cuenta:</label>
        <input type="text" name="numeroCuenta" id="numeroCuenta" required><br><br>
        <button type="submit">Confirmar</button>
    </form>

    <div>
        <?php
        $conexion = conectar();

        $consulta_empleados = $conexion->query("SELECT idEmpleado, NombreEmp, Edad, FechaContratacion, NumeroCuenta FROM Empleados");

        if ($consulta_empleados->num_rows > 0) {
            echo "<h2>Lista de Empleados</h2>";
            echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Edad</th>
                    <th>Fecha de Contratación</th>
                    <th>Número de Cuenta</th>
                </tr>";

            while ($fila = $consulta_empleados->fetch_assoc()) {
                echo "<tr>
                    <td>{$fila['idEmpleado']}</td>
                    <td>{$fila['NombreEmp']}</td>
                    <td>{$fila['Edad']}</td>
                    <td>{$fila['FechaContratacion']}</td>
                    <td>{$fila['NumeroCuenta']}</td>
                </tr>";
            }

            echo "</table>";
        } else {
            echo "No hay empleados registrados.";
        }

        $conexion->close();
        ?>
    </div>

    <form action="Baja-empleados.php" method="post">
        <button type="submit">Borrar</button>
    </form>

    <form action="Modif-empleados.php" method="post">
        <button type="submit">Modificar</button>
    </form>
</body>

</html>
