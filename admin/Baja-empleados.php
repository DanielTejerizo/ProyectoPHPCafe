<?php
include('../conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idEmpleado"])) {
    $idEmpleado = $_POST["idEmpleado"];

    $conexion = conectar();

    $consulta_existencia = $conexion->prepare("SELECT idEmpleado FROM Empleados WHERE idEmpleado = ?");
    $consulta_existencia->bind_param("i", $idEmpleado);
    $consulta_existencia->execute();
    $consulta_existencia->store_result();

    if ($consulta_existencia->num_rows > 0) {
        $secuencia = $conexion->prepare("DELETE FROM Empleados WHERE idEmpleado = ?");
        $secuencia->bind_param("i", $idEmpleado);

        if ($secuencia->execute()) {
            echo "<script>mostrarAlerta();</script>";
            echo "Empleado eliminado exitosamente";
        } else {
            echo "Error al eliminar el empleado: " . $secuencia->error;
        }
    } else {
        echo "No existe un empleado con ese ID.";
    }

    $conexion->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Eliminación de Empleados</title>
    <link rel="stylesheet" href="../css/Alta.css">
    <script>
        function confirmarEliminacion() {
            return confirm("¿Estás seguro de que deseas eliminar este empleado?");
        }

        function mostrarAlerta() {
            alert("Empleado eliminado correctamente");
        }
    </script>
</head>

<body>
    <h1>Eliminación de Empleados</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return confirmarEliminacion();">
        <label for="idEmpleado">ID del Empleado a eliminar:</label>
        <input type="text" name="idEmpleado" id="idEmpleado" size="10" required><br><br>
        <button type="submit">Eliminar Empleado</button>
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

    <form action="Alta-empleados.php" method="post">
        <button type="submit">Alta</button>
    </form>

    <form action="Modif-empleados.php" method="post">
        <button type="submit">Modificar</button>
    </form>
</body>

</html>
