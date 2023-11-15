<?php
include('conexion.php');


// Verifica si la acción es agregar al carrito
if (isset($_POST['action']) && $_POST['action'] == "add") {
    $idProducto = $_POST['idProducto'];

    $conexion = conectar();

    // Agrega el producto al carrito en la base de datos
    $sql = "INSERT INTO carrito (idProducto, cantidad) VALUES ('$idProducto', '1')";
    $conexion->query($sql);
}


?>
