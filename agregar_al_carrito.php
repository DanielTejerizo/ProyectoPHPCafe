<?php
// agregar_al_carrito.php

// Iniciar o reanudar la sesión
session_start();

// Incluir el archivo de conexión
include 'conexion.php';

// Obtener la conexión
$conn = conectar();

// Verificar si se recibió el ID del producto
if (isset($_POST['idProducto'])) {
    // Sanitizar y obtener el ID del producto
    $idProducto = mysqli_real_escape_string($conn, $_POST['idProducto']);

    // Verificar si la sesión de carrito ya existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array(); // Inicializar la sesión del carrito si no existe
    }

    // Agregar el producto al carrito (utiliza un array asociativo para almacenar información del producto)
    $_SESSION['carrito'][] = array(
        'idProducto' => $idProducto,
        'cantidad' => 1, // Puedes ajustar esto según tus necesidades
    );

    echo "Producto agregado al carrito correctamente.";
} else {
    echo "ID del producto no recibido.";
}

// Cerrar la conexión
mysqli_close($conn);
?>
