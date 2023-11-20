<?php
include '../conexion.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["comprar"])) {

    $cantidades = $_POST['cantidades'];


    $productosSeleccionados = array_filter($cantidades, function ($cantidad) {
        return $cantidad > 0;
    });

    if (!empty($productosSeleccionados)) {

        session_start();
        $_SESSION['productosSeleccionados'] = $productosSeleccionados;


        header("Location: IngresarNombreUsuario.php");
        exit();
    } else {

        header("Location: Catalogo.php");
        exit();
    }
} else {

    header("Location: Catalogo.php");
    exit();
}
?>
