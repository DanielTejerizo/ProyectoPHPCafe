<?php
function conectar() {

    $servername = "localhost"; 
    $username = "TrabajoPHP";
    $password = "TrabajoPHP@";
    $dbname = "trabajophp";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    return $conn;
}

?>
