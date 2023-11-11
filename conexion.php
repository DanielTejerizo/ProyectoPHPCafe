<?php
function conectar() {

    $servername = "localhost"; 
    $username = "TrabajoPHP";
    $password = "TrabajoPHP@";
    $dbname = "trabajophp";

    // Crear la conexión
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Verificar la conexión
    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    return $conn;
}

?>
