<?php
include '../conexion.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comprar"])) {

    $idProductoSeleccionado = $_POST['idProducto'];
    $cantidad = $_POST['cantidad'];


    session_start();
    $_SESSION['idProducto'] = $idProductoSeleccionado;
    $_SESSION['cantidad'] = $cantidad;


    header("Location: IngresarNombreUsuario.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["filtrar"])) {

    $filtroCategoria = $_POST['categoria'];
    $filtroPrecioMin = $_POST['precio_min'];
    $filtroPrecioMax = $_POST['precio_max'];

    $conexion = conectar();

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }


    $sql = "SELECT idProducto, NombreProd, Precio FROM productos WHERE 1=1";

    if (!empty($filtroCategoria)) {
        $sql .= " AND IdCategoria = $filtroCategoria";
    }

    if (!empty($filtroPrecioMin)) {
        $sql .= " AND Precio >= $filtroPrecioMin";
    }

    if (!empty($filtroPrecioMax)) {
        $sql .= " AND Precio <= $filtroPrecioMax";
    }

    $result = $conexion->query($sql);

    $conexion->close();
} else {

    $conexion = conectar();

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }


    $sql = "SELECT idProducto, NombreProd, Precio FROM productos";
    $result = $conexion->query($sql);

    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
    <link rel="stylesheet" href="../css/Productos.css">
</head>

<body>
    <div class="catalogo-container">
        <h1>Catálogo de Productos</h1>


        <form method='post' action='Catalogo.php'>
            <label for='categoria'>Categoría:</label>
            <select name='categoria'>
                <option value=''>Todas las categorías</option>
                <?php
                $conexion = conectar();

                if ($conexion->connect_error) {
                    die("Conexión fallida: " . $conexion->connect_error);
                }


                $sqlCategorias = "SELECT idCategoria, NombreCat FROM categoria";
                $resultCategorias = $conexion->query($sqlCategorias);

                if ($resultCategorias->num_rows > 0) {
                    while ($rowCategoria = $resultCategorias->fetch_assoc()) {
                        echo "<option value='" . $rowCategoria['idCategoria'] . "'>" . $rowCategoria['NombreCat'] . "</option>";
                    }
                }

                $conexion->close();
                ?>
            </select><br>

            <label for='precio_min'>Precio mínimo:</label>
            <input type='number' name='precio_min' min='0' step='0.01' placeholder='0.00'>

            <label for='precio_max'>Precio máximo:</label>
            <input type='number' name='precio_max' min='0' step='0.01' placeholder='0.00'>

            <button type='submit' name='filtrar'>Filtrar</button>
        </form>

        <?php

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='producto'>";
                echo "<h3>" . $row['NombreProd'] . "</h3>";
                echo "<p>Precio: $" . $row['Precio'] . "</p>";
                echo "<form method='post' action='Catalogo.php'>";
                echo "<input type='hidden' name='idProducto' value='" . $row['idProducto'] . "'>";
                echo "<label for='cantidad'>Cantidad:</label>";
                echo "<input type='number' name='cantidad' value='1' min='1' required>";
                echo "<button type='submit' name='comprar'>Comprar</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No hay productos disponibles según los filtros seleccionados.</p>";
        }
        ?>
    </div>
</body>

</html>