<?php
include '../conexion.php';

// Verificar si se ha enviado el formulario desde el paso anterior
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comprar"])) {
    // Obtener los datos del producto y cantidad
    $idProductoSeleccionado = $_POST['idProducto'];
    $cantidad = $_POST['cantidad'];

    // Almacenar los datos en sesiones para usarlos en el siguiente paso
    session_start();
    $_SESSION['idProducto'] = $idProductoSeleccionado;
    $_SESSION['cantidad'] = $cantidad;

    // Redirigir a la página para ingresar el nombre de usuario
    header("Location: IngresarNombreUsuario.php");
    exit();
} else {
    // Si no se ha enviado el formulario correctamente, redirigir al catálogo de productos
    header("Location: Catalogo.php");
    exit();
}
?>
