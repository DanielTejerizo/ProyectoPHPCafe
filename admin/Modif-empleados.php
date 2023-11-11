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
        $consulta_modificacion = "UPDATE Empleados SET ";
        $valores = [];

        $nombreEmp = isset($_POST["nombreEmp"]) ? $_POST["nombreEmp"] : null;
        if ($nombreEmp !== null) {
            $consulta_modificacion .= "NombreEmp = ?, ";
            $valores[] = $nombreEmp;
        }

        $edad = isset($_POST["edad"]) ? $_POST["edad"] : null;
        if ($edad !== null) {
            $consulta_modificacion .= "Edad = ?, ";
            $valores[] = $edad;
        }

        $fechaContratacion = isset($_POST["fechaContratacion"]) ? $_POST["fechaContratacion"] : null;
        if ($fechaContratacion !== null) {
            $consulta_modificacion .= "FechaContratacion = ?, ";
            $valores[] = $fechaContratacion;
        }

        $numeroCuenta = isset($_POST["numeroCuenta"]) ? $_POST["numeroCuenta"] : null;
        if ($numeroCuenta !== null) {
            $consulta_modificacion .= "NumeroCuenta = ?, ";
            $valores[] = $numeroCuenta;
        }

        if (!empty($valores)) {
            $consulta_modificacion = rtrim($consulta_modificacion, ", ");
            $consulta_modificacion .= " WHERE idEmpleado = ?";
            $valores[] = $idEmpleado;

            $secuencia = $conexion->prepare($consulta_modificacion);
            $secuencia->bind_param(str_repeat("s", count($valores)), ...$valores);

            if ($secuencia->execute()) {
                echo "<script>mostrarAlerta();</script>";
                echo "Empleado modificado exitosamente";
            } else {
                echo "Error al modificar el empleado: " . $secuencia->error;
            }
        } else {
            echo "No se proporcionaron campos para modificar.";
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
    <title>Modificación de Empleados</title>
    <link rel="stylesheet" href="../css/Alta.css">
    <script>
        function confirmarModificacion() {
            return confirm("¿Estás seguro de que deseas modificar este empleado?");
        }

        function mostrarAlerta() {
            alert("Empleado modificado correctamente");
        }
    </script>
</head>

<body>
    <h1>Modificación de Empleados</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return confirmarModificacion();">
        <label for="idEmpleado">ID del Empleado a modificar:</label>
        <input type="text" name="idEmpleado" id="idEmpleado" size="10" required><br><br>
        <label for="nombreEmp">Nuevo Nombre:</label>
        <input type="text" name="nombreEmp" id="nombreEmp"><br><br>
        <label for="edad">Nueva Edad:</label>
        <input type="text" name="edad" id="edad"><br><br>
        <label for="fechaContratacion">Nueva Fecha de Contratación:</label>
        <input type="date" name="fechaContratacion" id="fechaContratacion"><br><br>
        <label for="numeroCuenta">Nuevo Número de Cuenta:</label>
        <input type="text" name="numeroCuenta" id="numeroCuenta"><br><br>
        <button type="submit">Modificar Empleado</button>
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

    <form action="Baja-empleados.php" method="post">
        <button type="submit">Baja</button>
    </form>
</body>

</html>
