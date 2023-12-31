<?php
include('../conexion.php'); 


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["NombreUsuario"])) {

    $NombreUsuario = $_POST["NombreUsuario"];


    $conexion = conectar();


    $consulta_existencia = $conexion->prepare("SELECT NombreUsuario, Tipo FROM usuarios WHERE NombreUsuario = ?");
    $consulta_existencia->bind_param("s", $NombreUsuario);
    $consulta_existencia->execute();
    $consulta_existencia->store_result();

    if ($consulta_existencia->num_rows > 0) {

        $secuencia = $conexion->prepare("DELETE FROM usuarios WHERE NombreUsuario = ?");
        $secuencia->bind_param("s", $NombreUsuario);


        if ($secuencia->execute()) {
            echo "<script>mostrarAlerta();</script>";
            echo "Usuario eliminado exitosamente";
        } else {
            echo "Error al eliminar el usuario: " . $secuencia->error;
        }
    } else {
        echo "No existe un usuario con ese nombre.";
    }


    $consulta_usuarios = $conexion->query("SELECT NombreUsuario, Tipo FROM usuarios");


    $conexion->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Eliminación de Usuarios</title>
    <link rel="stylesheet" href="../css/Alta.css">
    <script>
        function confirmarEliminacion() {
            return confirm("¿Estás seguro de que deseas eliminar este usuario?");
        }

        function mostrarAlerta() {
            alert("Usuario eliminado correctamente");
        }
    </script>
</head>

<body>
    <h1>Eliminación de Usuarios</h1>


    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return confirmarEliminacion();">
        <label for="NombreUsuario">Nombre de Usuario a eliminar:</label>
        <input type="text" name="NombreUsuario" id="NombreUsuario" size="20" required><br><br>
        <button type="submit">Eliminar Usuario</button>
    </form>

    <div>
        <?php

        $conexion = conectar();


        $consulta_usuarios = $conexion->query("SELECT NombreUsuario,Tipo FROM usuarios");

        if (isset($consulta_usuarios) && $consulta_usuarios->num_rows > 0) {
            echo "<h2>Lista de Usuarios</h2>";
            echo "<table border='1'>
                <tr>
                    <th>Nombre de Usuario</th>
                    <th>Tipo</th>
                </tr>";

            while ($fila = $consulta_usuarios->fetch_assoc()) {
                echo "<tr>
                    <td>{$fila['NombreUsuario']}</td>
                    <td>{$fila['Tipo']}</td>
                </tr>";
            }

            echo "</table>";
        } else {
            echo "No hay usuarios registrados.";
        }
        ?>
    </div>

</body>

</html>