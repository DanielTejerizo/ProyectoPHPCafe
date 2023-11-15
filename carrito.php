<?php
// Incluir el archivo de conexión
include 'conexion.php';

// Obtener la conexión
$conn = conectar();

// Consulta a la vista de carrito
$sql = "SELECT * FROM carrito";
$result = mysqli_query($conn, $sql);

// Verificar si hay resultados
if ($result) {
    // Mostrar los resultados en una tabla
    echo "<table border='1'>
            <tr>
                <th>ID Carrito</th>
                <th>ID Usuario</th>
                <th>Nombre Usuario</th>
                <th>ID Producto</th>
                <th>Nombre Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['idCarrito']}</td>
                <td>{$row['idUsuario']}</td>
                <td>{$row['NombreUsuario']}</td>
                <td>{$row['idProducto']}</td>
                <td>{$row['NombreProd']}</td>
                <td>{$row['Precio']}</td>
                <td>{$row['Cantidad']}</td>
            </tr>";
    }

    echo "</table>";
} else {
    echo "Error en la consulta: " . mysqli_error($conn);
}

// Cerrar la conexión
mysqli_close($conn);
?>
