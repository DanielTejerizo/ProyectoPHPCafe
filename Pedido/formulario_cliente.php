<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Cliente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #c79d5a;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #666;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #d6b482;
            color: black;
        }
    </style>
</head>

<body>
    <?php
    include '../conexion.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["telefono"])) {
        $conn = conectar();

        $nombre = $_POST['nombre'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $nombreUsuario = $_POST['nombre_usuario'] ?? '';
        $idProducto = $_POST['idProducto'] ?? '';
        $cantidad = $_POST['cantidad'] ?? '';

        // Generar un número aleatorio
        $numeroAleatorio = rand(1000, 9999);

        // Combinar "CL" con el número aleatorio
        $idCliente = "CL" . $numeroAleatorio;

        // Verificar si el teléfono no está vacío y es numérico
        if (!empty($telefono) && is_numeric($telefono)) {
            // Insertar en la tabla clientes
            $sqlInsertarUsuario = "INSERT INTO clientes (idCliente, NombreCli, Direccion, Telefono) VALUES ('$idCliente', '$nombre', '$direccion', $telefono)";
            
            if ($conn->query($sqlInsertarUsuario) === TRUE) {
                echo "Usuario registrado correctamente. ID Cliente: " . $idCliente;
            } else {
                echo "Error al registrar el usuario: " . $conn->error;
            }
        } else {
            echo "Error: El número de teléfono debe ser un valor numérico y no puede estar vacío.";
        }

        $conn->close();
    }
    ?>

    <form action="" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required>

        <input type="submit" value="Registrar">
    </form>
</body>

</html>