<?php
include '../conexion.php';

// Verificar si se ha enviado el formulario desde el paso anterior
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comprar"])) {
    // Obtener los datos del formulario
    $cantidades = $_POST['cantidades'];

    // Filtrar solo los productos con cantidades mayores a 0
    $productosSeleccionados = array_filter($cantidades, function ($cantidad) {
        return $cantidad > 0;
    });

    if (!empty($productosSeleccionados)) {
        // Almacenar los datos en sesiones para usarlos en el siguiente paso
        session_start();
        $_SESSION['productosSeleccionados'] = $productosSeleccionados;

        // Redirigir a la página para ingresar el nombre de usuario
        header("Location: IngresarNombreUsuario.php");
        exit();
    } else {
        // Si no se seleccionaron productos, redirigir al catálogo de productos
        header("Location: Catalogo.php");
        exit();
    }
} else {
    // Si no se ha enviado el formulario correctamente, redirigir al catálogo de productos
    header("Location: Catalogo.php");
    exit();
}
?>
